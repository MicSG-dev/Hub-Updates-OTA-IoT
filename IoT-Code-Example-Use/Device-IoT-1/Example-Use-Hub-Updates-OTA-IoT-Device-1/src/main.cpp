#include <Arduino.h> // native library
#include <SPI.h> // native library
#include <Wire.h> // native library
#include <Adafruit_GFX.h> // auto download com library Adafruit_SSD1306 (https://github.com/adafruit/Adafruit_SSD1306)
#include <Adafruit_SSD1306.h> // https://github.com/adafruit/Adafruit_SSD1306
#include <EthernetLarge.h>   // https://github.com/MicSG-dev/EthernetLarge
#include <SSLClient.h>       // https://github.com/OPEnSLab-OSU/SSLClient
#include "certificatesSSL.h" // local file | Use the Generator at https://openslab-osu.github.io/bearssl-certificate-utility/ informing the sites you will access
#include <FS.h> // native library
#include <LittleFS.h> // native library
#include <Updater.h> // native library

const int SCREEN_WIDTH = 128;
const int SCREEN_HEIGHT = 64;

const int SCREEN_ADDRESS = 0x3C;

byte macAddressEthernet[] = {0x02, 0xAD, 0xF3, 0x82, 0x4D, 0xED};
char serverHub[] = "sistemas.micsg.com.br";                           // host do website do HUB Updates OTA IoT
char query[] = "GET /sistemas-web/hub-updates-ota-iot/api/iot/readme.txt"; // TESTE DE ARQUIVO GRANDE. Use o Postman para formatação adequada: https://sistemas.micsg.com.br/sistemas-web/hub-updates-ota-iot/api/iot/readme.txt

// Define o IP estático, máscara, DNS e endereço de gateway a ser usado se o DHCP não conseguir atribui-los
IPAddress ipEthernet(192, 168, 0, 199);
IPAddress mascaraEthernet(255, 255, 255, 0);
IPAddress dnsEthernet(1, 1, 1, 1);
IPAddress gatewayEthernet(192, 168, 0, 1);

// Pino analógico para obter dados semi-aleatórios para SSL a partir da leitura da flutuação de tensão aleatória (ruído)
const int rand_pin = A0; // (GPIO26)

// Inicializa a biblioteca cliente SSL inserindo os objetos: EthernetClient, certificado x.509 e o pino analógico gerador de semi-aleatórios
EthernetClient base_client;
SSLClient client(base_client, TAs, (size_t)TAs_NUM, rand_pin, 1, SSLClient::SSL_NONE);

Adafruit_SSD1306 display(SCREEN_WIDTH, SCREEN_HEIGHT, &Wire, -1);

bool setupCore1Concluido = false;
bool esperandoResponse;

// Variables to measure the speed
unsigned long beginMicros, endMicros;
unsigned long byteCount = 0;
bool printWebData = true; // set to false for better speed measurement

// Number of milliseconds to wait without receiving any data before we give up
const int kNetworkTimeout = 30 * 1000;
// Number of milliseconds to wait if no data is available before trying again
const int kNetworkDelay = 1000;

enum modosDeInicializacaoEthernet
{
  MODO_IP_ESTATICO,
  MODO_IP_DHCP
};

String getBodyOfResponseHttp(String);
float calcularTaxaTransferencia(unsigned long, unsigned long);

bool beginEthernetComModo(modosDeInicializacaoEthernet modo)
{
  switch (modo)
  {
  case MODO_IP_ESTATICO:
    Ethernet.begin(macAddressEthernet, ipEthernet, dnsEthernet, gatewayEthernet, mascaraEthernet);
    return true;
  case MODO_IP_DHCP:
    SPI.endTransaction();
    return Ethernet.begin(macAddressEthernet, 2000, 2000);
  default:
    return false;
  }
}

void verificacaoHardwareEthernet()
{
  if (Ethernet.hardwareStatus() == EthernetNoHardware)
  {
    Serial.println("Ethernet module was not found.  Sorry, can't run without hardware. :(");

    display.clearDisplay();
    display.setTextSize(2);
    display.setCursor(0, 0);
    display.setTextColor(SSD1306_WHITE);
    display.print("Ethernet");
    display.setCursor(0, 16);
    display.println("Hardware");
    display.println("defeituso");
    display.setTextSize(1);
    display.println("Conserte isso e");
    display.print("reinicie a placa");
    display.display();
    delay(10000);
    rp2040.reboot();
  }

  if (Ethernet.linkStatus() == LinkOFF)
  {
    Serial.println("Ethernet cable is not connected.");

    display.clearDisplay();
    display.setTextSize(2);
    display.setTextColor(SSD1306_WHITE);
    display.setCursor(0, 0);
    display.println("Sem rede");
    display.setCursor(0, 16);
    display.println("Verifique cabo de");
    display.print("rede");
    display.display();
    while (Ethernet.linkStatus() == LinkOFF)
    {
      delay(1000);
    }
  }
}

void setup()
{
  Serial.begin(115200);
  delay(2000);

  Wire.setSDA(12);
  Wire.setSCL(13);
  Wire.begin();

  Serial.println("Iniciado Firmware");
  if (!display.begin(SSD1306_SWITCHCAPVCC, SCREEN_ADDRESS))
  {
    Serial.println(F("SSD1306 allocation failed"));
    for (;;)
      ; // Don't proceed, loop forever
  }

  if (!LittleFS.begin())
    Serial.println(F("File system mount failed"));
  else
    Serial.println(F("File system mount successful"));

  display.clearDisplay();

  // Diminuição de Contraste e Brilho na tela para prevenção de efeito Burn-In (por ser tela OLED)
  display.ssd1306_command(SSD1306_SETCONTRAST);
  display.ssd1306_command(1);
  display.ssd1306_command(SSD1306_SETVCOMDETECT);
  display.ssd1306_command(0x00);
  display.display();

  display.clearDisplay();
  display.setCursor(0, 16);
  display.setTextSize(3);
  display.setTextColor(SSD1306_WHITE);
  display.println("DEVICE");
  display.print("   1");
  display.display();

  // Atribui o pino CS (seleção) do módulo W5500 como GPIO17
  Ethernet.init(17);
  bool hardwareEthernetOk = false;
  bool configuracaoEthernetOk = false;

  do
  {
    if (beginEthernetComModo(MODO_IP_DHCP))
    { // Dynamic IP setup

      Serial.println("IP definido por DHCP");

      display.clearDisplay();
      display.setCursor(15, 16);
      display.setTextSize(2);
      display.setTextColor(SSD1306_WHITE);
      display.print("DHCP ok");
      display.display();
      delay(1500);
      configuracaoEthernetOk = true;
    }
    else if (hardwareEthernetOk == false)
    {
      verificacaoHardwareEthernet();
      hardwareEthernetOk = true;
    }
    else
    {
      Serial.println("Falha na atribuicao de IP pelo DHCP. Definindo IP estatico");

      display.clearDisplay();
      display.setCursor(15, 16);
      display.setTextSize(2);
      display.setTextColor(SSD1306_WHITE);
      display.println("IP");
      display.print("estatico");
      display.display();
      delay(1500);

      beginEthernetComModo(MODO_IP_ESTATICO);
      Serial.println("STATIC OK!");
      configuracaoEthernetOk = true;
    }
  } while (!configuracaoEthernetOk);

  Serial.print("Local IP : ");
  Serial.println(Ethernet.localIP());
  Serial.print("Subnet Mask : ");
  Serial.println(Ethernet.subnetMask());
  Serial.print("Gateway IP : ");
  Serial.println(Ethernet.gatewayIP());
  Serial.print("DNS Server : ");
  Serial.println(Ethernet.dnsServerIP());

  Serial.println("Ethernet Successfully Initialized");

  display.clearDisplay();
  display.setCursor(0, 16);
  display.setTextSize(2);
  display.setTextColor(SSD1306_WHITE);
  display.println("    IP");
  display.print(Ethernet.localIP()[0]);
  display.print(".");
  display.print(Ethernet.localIP()[1]);
  display.println(".");
  display.print(Ethernet.localIP()[2]);
  display.print(".");
  display.print(Ethernet.localIP()[3]);
  display.display();
  delay(2000);

  setupCore1Concluido = true;
}

void setup1()
{
  while (setupCore1Concluido == false)
  {
    delay(100);
  }
}
bool deveExecutarLeituraFile = false;
void loop()
{
  if (deveExecutarLeituraFile)
  {
    File f = LittleFS.open("/teste.txt", "r");
    if (!f || f.isDirectory())
    {
      Serial.println("- failed to open file for reading");
      return;
    }

    Serial.println("- read from file:");
    while (f.available())
    {
      Serial.write(f.read());
    }
    f.close();
    deveExecutarLeituraFile= false;
  }
  static int i = 0;
  display.clearDisplay();
  display.setCursor(15, 16);
  display.setTextSize(5);
  display.setTextColor(SSD1306_WHITE);
  display.print("0.");
  display.print(i);
  display.display();
  delay(1000);
  i++;
  if (i >= 10)
    i = 0;
}
unsigned long timer;
void loop1()
{

  verificacaoHardwareEthernet();

  Serial.println("Iniciando Requisicao: ");
  timer = micros();
  File f;
  if (client.connect(serverHub, 443))
  {

    Serial.print("connected to ");
    Serial.println(serverHub);
    // Make a HTTP request:
    client.print(query);
    client.println(" HTTP/1.1");
    client.print("Host: ");
    client.println(serverHub);
    client.println("Connection: close");
    client.println();
    client.flush();
  }
  else
  {
    // if you didn't get a connection to the server:
    Serial.println("connection failed");
  }

  esperandoResponse = true;

  String response;
  String lineResponse = "";
  bool currentLineIsBlank = true;
  bool jaChegouNoBody = false;
  bool eliminarLinhaHead = false;
  f = LittleFS.open("/teste.txt", "w");
  while (esperandoResponse)
  {
    int len = client.available();
    if (len != 0)
    {
      char c = client.read();
      byteCount++;

      if (c == '\n' && currentLineIsBlank && jaChegouNoBody == false) // Se o caractere atual for um '\n' e a linha atual estiver ainda vazia e ainda não estiver no cabeçalho (head) http, ...
      {
        jaChegouNoBody = true;
        eliminarLinhaHead = true; // variavel para controle de eliminação da última linha vazia do head http
      }

      if (c == '\n') // se o caractere atual for um '\n', chegou ao final da linha.
      {
        if (!jaChegouNoBody) // se ainda não chegou no body http, ...
        {
          Serial.print("HEAD: ");
          Serial.println(lineResponse);
        }
        else
        {
          if (eliminarLinhaHead) // controla se deve eliminar a ultima linha vazia do header http
          {
            eliminarLinhaHead = false;
            Serial.print("HEAD: ");
          }
          else
          {
            Serial.print("BODY: ");
            f.println(lineResponse);
          }
          Serial.println(lineResponse);
        }

        lineResponse = "";         // Limpa a variável para a próxima linha
        currentLineIsBlank = true; // variacvel sinaliza que chegou ao final da linha
      }
      else if (c != '\r')
      { // Se não for um retorno de carro, a linha não está em branco (qualquer outro caractere será adicionado)

        currentLineIsBlank = false;
        lineResponse += c; // Adiciona o caractere à linha atual
      }
    }

    // if the server's disconnected, stop the client:
    if (!client.connected())
    {
      if (client.getWriteError() == 2)
      {
        Serial.println("Rede sem conexao com a Internet");
      }
      else
      {
        unsigned long segundosGastos = (micros() - timer) / 1000000;
        Serial.print("Tempo gasto foi de ");
        Serial.print(segundosGastos);
        Serial.println(" segundos");
        Serial.print("Response: ");
        Serial.print((response));

        Serial.println();

        Serial.println("disconnecting.");
        client.stop();
        Serial.print("Recebidos ");
        Serial.print(byteCount);
        Serial.print(" bytes em ");
        Serial.print(segundosGastos);
        float taxa = calcularTaxaTransferencia(byteCount, segundosGastos);
        Serial.print(" segundos, sendo a taxa de transferencia de ");
        Serial.print(taxa, 10);
        Serial.print(" kbps");
        Serial.println('\n');
        byteCount = 0;
      }
      esperandoResponse = false;
    }
  }
  f.close();
  deveExecutarLeituraFile = true;
  while (1)
    ;
}

float calcularTaxaTransferencia(unsigned long bytes, unsigned long seconds)
{
  return (bytes * 8.0) / (seconds * 1000);
}

String getBodyOfResponseHttp(String response)
{
  int bodyIndex = response.indexOf("\r\n\r\n") + 4;
  Serial.println();
  Serial.println();
  Serial.println();
  Serial.print("response.length(): ");
  Serial.println(response.length());
  Serial.print("bodyIndex: ");
  Serial.println(bodyIndex);
  if (bodyIndex != -1)
  {
    return response.substring(bodyIndex);
  }
  else
  {
    return ""; // Retorna uma string vazia se não encontrar o corpo da resposta
  }
}