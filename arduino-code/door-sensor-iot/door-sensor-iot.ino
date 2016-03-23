#include <Button.h>
#include <ESP8266WiFi.h>
#include <ESP8266WiFiMulti.h>
#include <ESP8266HTTPClient.h>

ESP8266WiFiMulti WiFiMulti;

const char* ssid = "SSID";
const char* pass = "PASSWORD";
String url = "LocationOfServer";

int pushButton = 0;
int pushButtonTwo = 2;
Button myBtn(pushButton, true, true, 20);
Button myBtnTwo(pushButtonTwo, true, true, 20);

void setup() {
  Serial.begin(115200);
  delay(4000);
  WiFiMulti.addAP(ssid, pass);
  Serial.print("Wait for WiFi... ");
  Serial.println("WiFi connected");
  
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP()); // just so you know

  delay(1000);
  pinMode(pushButton, INPUT);
  Serial.println("Go");
}

void sendInfo(String area, String state) {
  if ((WiFiMulti.run() == WL_CONNECTED)) {
    String fullUrl = url + "?area=" + area + "&state=" + state;
    Serial.print("Send to server: ");
    Serial.println(fullUrl);
    HTTPClient http;
    http.begin(fullUrl);
    int httpCode = http.GET();
    if (httpCode != -1) {
      Serial.println("data sent to server");
    } else {
      Serial.println("fail");
      Serial.println(httpCode);
    }
  }
}

void loop() {
  myBtn.read();
  myBtnTwo.read();
  if (myBtn.wasPressed()) {
    Serial.println("front press");
    sendInfo("Front", "Open");
  }
  if (myBtn.wasReleased()) {
    Serial.println("front Released");
    sendInfo("Front", "Closed");
  }
  if (myBtnTwo.wasPressed()) {
    Serial.println("Garage press");
    sendInfo("Garage", "Open");
  }
  if (myBtnTwo.wasReleased()) {
    Serial.println("Garage Released");
    sendInfo("Garage", "Closed");
  }
  delay(100);
}

