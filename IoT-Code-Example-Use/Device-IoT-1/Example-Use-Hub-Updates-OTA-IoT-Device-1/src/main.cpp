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
  else
  {
    Serial.println("Iniciado display");
  }

  display.display();

  delay(1000); // Pause for 2 seconds

  // Clear the buffer
  display.clearDisplay();
  display.setCursor(15,16);
  display.setTextSize(5);
  display.setTextColor(SSD1306_WHITE);
  display.print("0.1");
  display.display();
}

void loop()
{
  delay(10);
}