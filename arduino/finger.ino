  #include <ESP8266WiFi.h>
  #include <ESP8266WebServer.h>

  const char* ssid = "wongirengjambuaten136";
  const char* password = "ngawurcik";

  ESP8266WebServer server(80);

  const int redLED = D2;
  const int yellowLED = D1;
  const int greenLED = D3;

  void setup() {
    Serial.begin(115200);

    WiFi.begin(ssid, password);
    while (WiFi.status() != WL_CONNECTED) {
      delay(500);
      Serial.print(".");
    }
    Serial.println("");
    Serial.println("WiFi connected");
    Serial.println(WiFi.localIP());

    pinMode(redLED, OUTPUT);
    pinMode(yellowLED, OUTPUT);
    pinMode(greenLED, OUTPUT);
    digitalWrite(redLED, LOW);
    digitalWrite(yellowLED, LOW);
    digitalWrite(greenLED, LOW);

    server.on("/leds", HTTP_POST, []() {
      String state = server.arg("state");
      Serial.println("Received state: " + state);

      if (state == "red") {
        digitalWrite(redLED, HIGH);
        digitalWrite(yellowLED, LOW);
        digitalWrite(greenLED, LOW);
      } else if (state == "yellow") {
        digitalWrite(redLED, LOW);
        digitalWrite(yellowLED, HIGH);
        digitalWrite(greenLED, LOW);
      } else if (state == "green") {
        digitalWrite(redLED, LOW);
        digitalWrite(yellowLED, LOW);
        digitalWrite(greenLED, HIGH);
      } else if (state == "all") {
        digitalWrite(redLED, HIGH);
        digitalWrite(yellowLED, HIGH);
        digitalWrite(greenLED, HIGH);
      } else if (state == "off") {
        digitalWrite(redLED, LOW);
        digitalWrite(yellowLED, LOW);
        digitalWrite(greenLED, LOW);
      }

      server.send(200, "text/plain", "LEDs updated");
    });
    server.begin();
  }

  void loop() {
    server.handleClient();
  }
