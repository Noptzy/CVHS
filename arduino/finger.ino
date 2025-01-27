#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>

// Pin LED
#define ledPin1 D0
#define ledPin2 D1
#define ledPin3 D2
#define ledPin4 D3

// Ultrasonic sensor
#define TRIG_PIN D5
#define ECHO_PIN D6

const char *ssid = "namawifikamu";
const char *password = "passwordwifimu";

ESP8266WebServer server(80);

// Status LED
bool rumahLedState = false;
bool tamanSatuLedState = false;
bool tamanDuaLedState = false;

long duration;
int distance;

unsigned long lastDistanceUpdate = 0;

void setup() {
  Serial.begin(115200);

  // Setup pin LED
  pinMode(ledPin1, OUTPUT);
  pinMode(ledPin2, OUTPUT);
  pinMode(ledPin3, OUTPUT);
  pinMode(ledPin4, OUTPUT);

  digitalWrite(ledPin1, LOW);
  digitalWrite(ledPin2, LOW);
  digitalWrite(ledPin3, LOW);
  digitalWrite(ledPin4, LOW);

  // Setup ultrasonic sensor
  pinMode(TRIG_PIN, OUTPUT);
  pinMode(ECHO_PIN, INPUT);

  // Connect WiFi
  WiFi.begin(ssid, password);
  Serial.print("Connecting to WiFi...");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nWiFi Connected!");
  Serial.print("IP Address: ");
  Serial.println(WiFi.localIP());

  // Endpoint untuk LED
  server.on("/rumah", HTTP_GET, []() {
    sendLedState(rumahLedState);
  });
  server.on("/rumah", HTTP_POST, []() {
    toggleLed(rumahLedState, ledPin1);
  });

  server.on("/tamanSatu", HTTP_GET, []() {
    sendLedState(tamanSatuLedState);
  });
  server.on("/tamanSatu", HTTP_POST, []() {
    toggleLed(tamanSatuLedState, ledPin2);
  });

  server.on("/tamanDua", HTTP_GET, []() {
    sendLedState(tamanDuaLedState);
  });
  server.on("/tamanDua", HTTP_POST, []() {
    toggleLed(tamanDuaLedState, ledPin3);
  });

  server.on("/off_all", HTTP_POST, []() {
    turnOffAllLeds();
  });

  server.on("/on_all", HTTP_POST, []() {
    turnOnAllLeds();
  });

  // Endpoint untuk jarak
  server.on("/distance", HTTP_GET, []() {
    server.sendHeader("Access-Control-Allow-Origin", "*");
    server.send(200, "text/plain", String(distance));
  });

  // Penanganan endpoint tidak ditemukan
  server.onNotFound([]() {
    server.sendHeader("Access-Control-Allow-Origin", "*");
    server.send(404, "text/plain", "Not Found");
  });

  server.begin();
  Serial.println("HTTP server started");
}

void loop() {
  server.handleClient();

  // Update jarak setiap 1 detik
  if (millis() - lastDistanceUpdate > 3000) {
    measureDistance();
    lastDistanceUpdate = millis();
  }
}

// Fungsi untuk mengukur jarak dengan ultrasonic
void measureDistance() {
  digitalWrite(TRIG_PIN, LOW);
  delayMicroseconds(2);
  digitalWrite(TRIG_PIN, HIGH);
  delayMicroseconds(10);
  digitalWrite(TRIG_PIN, LOW);

  duration = pulseIn(ECHO_PIN, HIGH);
  distance = duration * 0.034 / 2;

  if (distance > 0 && distance < 10) {
    digitalWrite(ledPin4, HIGH);
    Serial.println("Object Terdeteksi : Lampu Menyala");
  } else {
    digitalWrite(ledPin4, LOW);
    Serial.println("Object Tidak Terdeteksi : Lampu Mati");
  }

  // Log jarak ke serial
  Serial.print("Distance: ");
  Serial.print(distance);
  Serial.println(" cm");
}

// Mengirim status LED
void sendLedState(bool ledState) {
  server.sendHeader("Access-Control-Allow-Origin", "*");
  server.send(200, "text/plain", ledState ? "ON" : "OFF");
}

// Mengubah status LED
void toggleLed(bool &ledState, int pin) {
  ledState = !ledState;
  digitalWrite(pin, ledState ? HIGH : LOW);

  server.sendHeader("Access-Control-Allow-Origin", "*");
  server.send(200, "text/plain", ledState ? "ON" : "OFF");
}

void turnOffAllLeds() {
  rumahLedState = false;
  tamanSatuLedState = false;
  tamanDuaLedState = false;

  digitalWrite(ledPin1, LOW);
  digitalWrite(ledPin2, LOW);
  digitalWrite(ledPin3, LOW);

  server.sendHeader("Access-Control-Allow-Origin", "*");
  server.send(200, "text/plain", "ALL OFF");
}

void turnOnAllLeds() {
  rumahLedState = false;
  tamanSatuLedState = false;
  tamanDuaLedState = false;

  digitalWrite(ledPin1, HIGH);
  digitalWrite(ledPin2, HIGH);
  digitalWrite(ledPin3, HIGH);

  server.sendHeader("Access-Control-Allow-Origin", "*");
  server.send(200, "text/plain", "ALL ON");
}
