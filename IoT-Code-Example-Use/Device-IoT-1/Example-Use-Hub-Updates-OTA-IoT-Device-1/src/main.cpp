#include <Arduino.h>

#include <Wire.h>
#include <Adafruit_GFX.h>
#include <Adafruit_SSD1306.h>

const int SCREEN_WIDTH = 128;
const int SCREEN_HEIGHT = 64;

const int SCREEN_ADDRESS = 0x3C;

Adafruit_SSD1306 display(SCREEN_WIDTH, SCREEN_HEIGHT, &Wire, -1);

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
  delay(1000);
  display.clearDisplay();

  // Diminuição de Contraste e Brilho na tela para prevenção de efeito Burn-In (por ser tela OLED)
  display.ssd1306_command(SSD1306_SETCONTRAST);
  display.ssd1306_command(1);
  display.ssd1306_command(SSD1306_SETVCOMDETECT);
  display.ssd1306_command(0x00);
  display.display();
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