#include <Arduino.h>
#include <SPI.h>
#include <Wire.h>
#include <Adafruit_GFX.h>
#include <Adafruit_SSD1306.h>
#include <EthernetLarge.h>   // https://github.com/MicSG-dev/EthernetLarge
#include <SSLClient.h>       // https://github.com/OPEnSLab-OSU/SSLClient
#include "certificatesSSL.h" // local file | Use the Generator at https://openslab-osu.github.io/bearssl-certificate-utility/ informing the sites you will access

const int SCREEN_WIDTH = 128;
const int SCREEN_HEIGHT = 64;

const int SCREEN_ADDRESS = 0x3C;

byte macAddressEthernet[] = {0x02, 0xAD, 0xF3, 0x82, 0x4D, 0xAD};
char serverHub[] = "sistemas.micsg.com.br";                           // host do website do HUB Updates OTA IoT
char query[] = "GET /sistemas-web/hub-updates-ota-iot/api/iot/teste"; // https://sistemas.micsg.com.br/sistemas-web/hub-updates-ota-iot/api/iot/teste

// Define o IP estático, máscara, DNS e endereço de gateway a ser usado se o DHCP não conseguir atribui-los
IPAddress ipEthernet(192, 168, 0, 85);
IPAddress dnsEthernet(255, 255, 255, 0);
IPAddress gatewayEthernet(1, 1, 1, 1);
IPAddress mascaraEthernet(192, 168, 0, 1);

// Pino analógico para obter dados semi-aleatórios para SSL a partir da leitura da flutuação de tensão aleatória (ruído)
const int rand_pin = A0; // (GPIO26)

// Inicializa a biblioteca cliente SSL inserindo os objetos: EthernetClient, certificado x.509 e o pino analógico gerador de semi-aleatórios
EthernetClient base_client;
SSLClient client(base_client, TAs, (size_t)TAs_NUM, rand_pin);

Adafruit_SSD1306 display(SCREEN_WIDTH, SCREEN_HEIGHT, &Wire, -1);

bool setupCore1Concluido = false;
bool esperandoResponse;

// Variables to measure the speed
unsigned long beginMicros, endMicros;
unsigned long byteCount = 0;
bool printWebData = true; // set to false for better speed measurement

enum modosDeInicializacaoEthernet
{
  MODO_IP_ESTATICO,
  MODO_IP_DHCP
};

bool beginEthernetComModo(modosDeInicializacaoEthernet modo)
{
  switch (modo)
  {
  case MODO_IP_ESTATICO:
    Ethernet.begin(macAddressEthernet, ipEthernet, dnsEthernet, gatewayEthernet, mascaraEthernet);
    return true;
  case MODO_IP_DHCP:
    return Ethernet.begin(macAddressEthernet, "Device 2");
  default:
    return false;
  }
}

void definirModoIpEthernet()
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
  }
}

void verificacaoHardwareEthernet()
{
  bool corrigidoAlgumErro = false;
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

    do
    {
      delay(1000);
      beginEthernetComModo(MODO_IP_ESTATICO);
    } while (Ethernet.hardwareStatus() == EthernetNoHardware);
    delay(3000);
    corrigidoAlgumErro = true;
  }
  Serial.println(Ethernet.linkStatus());
  if (Ethernet.linkStatus() == LinkOFF)
  {
    Serial.println("Ethernet cable is not connected.");

    display.clearDisplay();
    display.setCursor(0, 16);
    display.setTextSize(2);
    display.setTextColor(SSD1306_WHITE);
    display.println("Conecte o cabo de");
    display.print("rede");
    display.display();
    while (Ethernet.linkStatus() == LinkOFF)
    {
      delay(1000);
      Serial.println(Ethernet.linkStatus());
    }

    corrigidoAlgumErro = true;
  }

  if (corrigidoAlgumErro)
  {
    definirModoIpEthernet();
  }
}

void setup()
{
  Serial.begin(115200);

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
  display.print("   2");
  display.display();

  // Atribui o pino CS (seleção) do módulo W5500 como GPIO17
  Ethernet.init(17);

  // Inicialização rápida para verificação de cabo e hardware íntegros
  beginEthernetComModo(MODO_IP_ESTATICO);
  delay(1000);
  verificacaoHardwareEthernet();

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

void loop()
{

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

void loop1()
{
  Serial.println("xyz");
  delay(500);
  verificacaoHardwareEthernet();
  if (client.connect(serverHub, 443))
  {
    Serial.print("connected to ");
    // Make a HTTP request:
    client.print(query);
    client.println(" HTTP/1.1");
    client.print("Host: ");
    client.println(serverHub);
    client.println("Connection: close");
    client.println();
  }
  else
  {
    // if you didn't get a connection to the server:
    Serial.println("connection failed");
  }

  esperandoResponse = true;

  while (esperandoResponse)
  {

    int len = client.available();
    if (len > 0)
    {
      byte buffer[80];
      if (len > 80)
        len = 80;
      client.read(buffer, len);
      if (printWebData)
      {
        Serial.write(buffer, len); // show in the serial monitor (slows some boards)
      }
      byteCount = byteCount + len;
    }

    // if the server's disconnected, stop the client:
    if (!client.connected())
    {
      endMicros = micros();
      Serial.println();
      Serial.println("disconnecting.");
      client.stop();
      Serial.print("Received ");
      Serial.print(byteCount);
      Serial.print(" bytes in ");
      float seconds = (float)(endMicros - beginMicros) / 1000000.0;
      Serial.print(seconds, 4);
      float rate = (float)byteCount / seconds / 1000.0;
      Serial.print(", rate = ");
      Serial.print(rate);
      Serial.print(" kbytes/second");
      Serial.println();

      delay(3000);
      esperandoResponse = false;
    }
  }
}