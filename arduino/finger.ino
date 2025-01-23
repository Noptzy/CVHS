#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>

#define ledPin1 D0
#define ledPin2 D1
#define ledPin3 D2
#define ledPin4 D3

#define TRIG_PIN D5
#define ECHO_PIN D6

const char *ssid = "namawifi";
const char *password = "passwordwifi";

ESP8266WebServer server(80);

bool dapurLedState = false;
bool tamuLedState = false;
bool makanLedState = false;

long duration;
int distance;

unsigned long lastDistanceUpdate = 0;

void setup() {
    Serial.begin(115200);

    pinMode(ledPin1, OUTPUT);
    pinMode(ledPin2, OUTPUT);
    pinMode(ledPin3, OUTPUT);
    pinMode(ledPin4, OUTPUT); // LED tambahan untuk jarak

    digitalWrite(ledPin1, LOW);
    digitalWrite(ledPin2, LOW);
    digitalWrite(ledPin3, LOW);
    digitalWrite(ledPin4, LOW); // Matikan LED tambahan di awal

    pinMode(TRIG_PIN, OUTPUT);
    pinMode(ECHO_PIN, INPUT);

    WiFi.begin(ssid, password);
    Serial.print("Connecting to WiFi...");

    while (WiFi.status() != WL_CONNECTED) {
        delay(500);
        Serial.print(".");
    }

    Serial.println("\nWiFi Connected!");
    Serial.println("IP Address: ");
    Serial.println(WiFi.localIP());

    // Endpoint untuk LED individu
    server.on("/dapur", HTTP_GET, []() { sendLedState(dapurLedState); });
    server.on("/dapur", HTTP_POST, []() { toggleLed(dapurLedState, ledPin1); });

    server.on("/tamu", HTTP_GET, []() { sendLedState(tamuLedState); });
    server.on("/tamu", HTTP_POST, []() { toggleLed(tamuLedState, ledPin2); });

    server.on("/makan", HTTP_GET, []() { sendLedState(makanLedState); });
    server.on("/makan", HTTP_POST, []() { toggleLed(makanLedState, ledPin3); });

    // Endpoint untuk semua lampu
    server.on("/off_all", HTTP_POST, []() { turnOffAllLeds(); });
    server.on("/on_all", HTTP_POST, []() { turnOnAllLeds(); });

    // Endpoint untuk jarak
    server.on("/distance", HTTP_GET, []() {
        server.sendHeader("Access-Control-Allow-Origin", "*");
        server.send(200, "text/plain", String(distance));
    });

    server.begin();
    Serial.println("HTTP server started");
}

void loop() {
    server.handleClient();

    // Update jarak setiap 1 detik
    if (millis() - lastDistanceUpdate > 1000) {
        measureDistance();
        lastDistanceUpdate = millis();
    }
}

void measureDistance() {
    digitalWrite(TRIG_PIN, LOW);
    delayMicroseconds(2);
    digitalWrite(TRIG_PIN, HIGH);
    delayMicroseconds(10);
    digitalWrite(TRIG_PIN, LOW);

    duration = pulseIn(ECHO_PIN, HIGH);
    distance = duration * 0.034 / 2;

    // Logika untuk menyalakan LED berdasarkan jarak
    if (distance > 0 && distance < 10) {
        digitalWrite(ledPin4, HIGH); // Nyalakan LED jika jarak < 10 cm
        Serial.println("Object detected: LED D3 ON");
    } else {
        digitalWrite(ledPin4, LOW); // Matikan LED jika jarak >= 10 cm
        Serial.println("No object detected: LED D3 OFF");
    }
}

void sendLedState(bool ledState) {
    server.sendHeader("Access-Control-Allow-Origin", "*");
    server.send(200, "text/plain", ledState ? "ON" : "OFF");
}

void toggleLed(bool &ledState, int pin) {
    ledState = !ledState;
    digitalWrite(pin, ledState ? HIGH : LOW);

    server.sendHeader("Access-Control-Allow-Origin", "*");
    server.send(200, "text/plain", ledState ? "ON" : "OFF");
}

void turnOffAllLeds() {
    dapurLedState = false;
    tamuLedState = false;
    makanLedState = false;

    digitalWrite(ledPin1, LOW);
    digitalWrite(ledPin2, LOW);
    digitalWrite(ledPin3, LOW);

    server.sendHeader("Access-Control-Allow-Origin", "*");
    server.send(200, "text/plain", "ALL OFF");
}

void turnOnAllLeds() {
    dapurLedState = true;
    tamuLedState = true;
    makanLedState = true;

    digitalWrite(ledPin1, HIGH);
    digitalWrite(ledPin2, HIGH);
    digitalWrite(ledPin3, HIGH);

    server.sendHeader("Access-Control-Allow-Origin", "*");
    server.send(200, "text/plain", "ALL ON");
}
