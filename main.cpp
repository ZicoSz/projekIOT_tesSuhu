#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <DHT.h>

#define DHTPIN D6
#define DHTTYPE DHT11
#define BUZZER_PIN D7

const char* ssid = "Xiaomi 14T Pro";
const char* password = "behkasusmen";

String server = "http://10.82.67.123/suhu_iot/";

DHT dht(DHTPIN, DHTTYPE);
LiquidCrystal_I2C lcd(0x27, 16, 2);

void setup() {

  Serial.begin(115200);

  lcd.init();
  lcd.backlight();

  lcd.clear();
  lcd.setCursor(0,0);
  lcd.print("Connecting...");
  lcd.setCursor(0,1);
  lcd.print("WiFi");

  WiFi.begin(ssid,password);

  while(WiFi.status()!=WL_CONNECTED){
    delay(1000);
    Serial.print("Status = ");
    Serial.println(WiFi.status());
  }

  Serial.println();
  Serial.println("========================");
  Serial.println("WIFI CONNECTED!");

  Serial.print("SSID : ");
  Serial.println(ssid);

  Serial.print("IP ESP8266 : ");
  Serial.println(WiFi.localIP());

  Serial.print("Gateway : ");
  Serial.println(WiFi.gatewayIP());

  Serial.print("RSSI : ");
  Serial.println(WiFi.RSSI());

  lcd.clear();
  lcd.setCursor(0,0);
  lcd.print("WiFi Connected");

  lcd.setCursor(0,1);
  lcd.print(WiFi.localIP());

  delay(2000);

  dht.begin();

  pinMode(BUZZER_PIN, OUTPUT);
  digitalWrite(BUZZER_PIN, LOW);

}

void loop(){

  if(WiFi.status()==WL_CONNECTED){

    WiFiClient client;

    HTTPClient httpStatus;

    String urlStatus = server + "status.php";
    String urlMute   = server + "mute_status.php";

    Serial.println();
    Serial.println("========================");
    Serial.println("Mengambil status...");

    httpStatus.begin(client, urlStatus);

    int code = httpStatus.GET();

    Serial.print("HTTP Code : ");
    Serial.println(code);

    if(code==200){

      String status = httpStatus.getString();
      status.trim();

      Serial.print("Status : ");
      Serial.println(status);

      // =========================
      // Ambil status mute
      // =========================

      HTTPClient httpMute;

      httpMute.begin(client, urlMute);

      int codeMute = httpMute.GET();

      String mute = "0";

      if(codeMute==200){

        mute = httpMute.getString();
        mute.trim();

      }

      httpMute.end();

      Serial.print("Mute : ");
      Serial.println(mute);

      // =========================
      // Baca suhu
      // =========================

      float suhu = dht.readTemperature();

      if(isnan(suhu)){

        Serial.println("DHT ERROR");

        lcd.clear();
        lcd.setCursor(0,0);
        lcd.print("Sensor Error");

        delay(3000);

        httpStatus.end();

        return;

      }

      Serial.print("Suhu : ");
      Serial.println(suhu);

      lcd.clear();

      lcd.setCursor(0,0);
      lcd.print("Temp:");
      lcd.print(suhu);
      lcd.print((char)223);
      lcd.print("C");

      // =========================
      // START
      // =========================

      if(status=="1"){

        lcd.setCursor(0,1);
        lcd.print("START");

        if(suhu >= 30){
          delay(200);

          if(mute=="0"){

            digitalWrite(BUZZER_PIN, HIGH);

          }else{

            digitalWrite(BUZZER_PIN, LOW);

          }

        }else{

          digitalWrite(BUZZER_PIN, LOW);

          // Reset mute otomatis
          HTTPClient httpReset;

          String urlReset = server + "unmute.php";

          httpReset.begin(client, urlReset);
          httpReset.GET();
          httpReset.end();

        }

        // =========================
        // Kirim data
        // =========================

        Serial.println("Mengirim data...");

        HTTPClient http;

        String url =
        server +
        "simpan.php?suhu=" +
        String(suhu);

        Serial.println(url);

        http.begin(client, url);

        int httpCode = http.GET();

        Serial.print("Upload Code : ");
        Serial.println(httpCode);

        if(httpCode==200){

          Serial.println("Data berhasil dikirim");

        }else{

          Serial.println("Upload gagal");

        }

        http.end();

      }

      // =========================
      // STOP
      // =========================

      else{

        digitalWrite(BUZZER_PIN, LOW);

        lcd.setCursor(0,1);
        lcd.print("STOP");

        Serial.println("Pengiriman OFF");

      }

    }else{

      Serial.println("Tidak bisa akses status.php");

      digitalWrite(BUZZER_PIN, LOW);

      lcd.clear();
      lcd.setCursor(0,0);
      lcd.print("Server Error");

      lcd.setCursor(0,1);
      lcd.print(code);

    }

    httpStatus.end();

  }else{

    Serial.println("WiFi Terputus");

    digitalWrite(BUZZER_PIN, LOW);

    lcd.clear();
    lcd.setCursor(0,0);
    lcd.print("WiFi Lost");

  }

  delay(5000);

}