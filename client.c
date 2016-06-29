// nrf24_client

#include <SPI.h>
#include <RH_NRF24.h>
#include<stdlib.h>
#include <OneWire.h>
#include <Wire.h>
#include <Adafruit_BMP085.h>
#include <dht11.h>
#include <Wire.h>
#include <BH1750.h>
vvv
BH1750 lightMeter;
 

// sluzi pre ANEMOMETER
#define AN 2                  // príjem z anemometru
const float pi = 3.14159265;  // číslo pí
int period = 1000;            // Measurement period (miliseconds)
unsigned int Sample = 0;      // Sample number
unsigned int counter = 0;     // B/W counter for sensor 
unsigned int RPM = 0;         // Revolutions per minute
int anemo = 91;               // konstanta pre výpočet rychlosti vetra
float speedwind = 0;           // rychlost vetra (m/s)
float vitr = 0;                // rychlost vetra km/h
// sluzi pre ANEMOMETER


// Singleton instance of the radio driver

RH_NRF24 nrf24(9, 10); // For RFM73 on Anarduino Mini

// PIN DS18B20
OneWire  ds(3);  // on pin 10 (a 4.7K resistor is necessary)
dht11 DHT;
 
Adafruit_BMP085 bmp;
// PIN BMP085
#define DHT11_PIN 8
// PIN BH1750
#define sensorPin 0 //A0 Pin
 
int sensorValue = 0; 
float celsius = 0;
int msg[1];
String vlhkost, teplota_dht11, teplota_ds18b20, tlak, vyska, skutocna_vyska, svietivost;
String speedwindstring;
String vitrstring;

void setup() 
{
  Serial.begin(9600);
  while (!Serial) 
    ; // wait for serial port to connect. Needed for Leonardo only
  if (!nrf24.init())
    Serial.println("init failed");
  // Defaults after init are 2.402 GHz (channel 2), 2Mbps, 0dBm
  if (!nrf24.setChannel(1))
    Serial.println("setChannel failed");
  if (!nrf24.setRF(RH_NRF24::DataRate2Mbps, RH_NRF24::TransmitPower0dBm))
    Serial.println("setRF failed");
      if (!bmp.begin())
  {
    Serial.println("Nie odnaleziono czujnika BMP085 / BMP180");
    while (1) {}
  }
  pinMode(2, INPUT);          // pin anemometru vstup
  digitalWrite(2, HIGH);      // pull up na pinu anemometru
  lightMeter.begin();
}


void loop()
{

  windvelocity();
  RPMcalc();
  WindSpeed();
  vitr = speedwind * 3,6;

  speedwindstring = String(speedwind);
  vitrstring = String(vitr);
  Serial.println(vitrstring);
  int chk;
  chk = DHT.read(DHT11_PIN);    // READ DATA

 // DISPLAT DATA
  Serial.print("Vlhkost:");
  
  Serial.println(DHT.humidity,1);
  vlhkost = DHT.humidity;
  Serial.print("Teplota:");
  Serial.println(DHT.temperature,1);
 
  byte i;
  byte present = 0;
  byte type_s;
  byte data[12];
  byte addr[8];
  float celsius;


  if ( !ds.search(addr)) {
    ds.reset_search();
    delay(250);
    //return;
  }
  // potrebne pre DALLAS DS18b20
  type_s = 0;
  ds.reset();
  ds.select(addr);
  ds.write(0x44, 1);        // start conversion, with parasite power on at the end
  present = ds.reset();
  ds.select(addr);    
  ds.write(0xBE);         // Read Scratchpad

  for ( i = 0; i < 9; i++) {           // we need 9 bytes
    data[i] = ds.read();
  }
  // Convert the data to actual temperature
  // because the result is a 16 bit signed integer, it should
  // be stored to an "int16_t" type, which is always 16 bits
  // even when compiled on a 32 bit processor.
  int16_t raw = (data[1] << 8) | data[0];
  if (type_s) {
    raw = raw << 3; // 9 bit resolution default
    if (data[7] == 0x10) {
      // "count remain" gives full 12 bit resolution
      raw = (raw & 0xFFF0) + 12 - data[6];
    }
  } else {
    byte cfg = (data[4] & 0x60);
    // at lower res, the low bits are undefined, so let's zero them
    if (cfg == 0x00) raw = raw & ~7;  // 9 bit resolution, 93.75 ms
    else if (cfg == 0x20) raw = raw & ~3; // 10 bit res, 187.5 ms
    else if (cfg == 0x40) raw = raw & ~1; // 11 bit res, 375 ms
    //// default is 12 bit resolution, 750 ms conversion time
  }
  
  celsius = (float)raw / 16.0;
  Serial.print("Temperature = ");
  Serial.print(celsius);
  teplota_ds18b20 = celsius;
  Serial.println(" Celsius, ");

  Serial.print("Teplota = ");
  Serial.print(bmp.readTemperature());
  teplota_dht11 = bmp.readTemperature();
  Serial.println(" *C");
  
  Serial.print("Tlak = ");
  Serial.print(bmp.readPressure());
  tlak = bmp.readPressure();
  Serial.println(" Pa");
  
  // p0 = 1013.25 millibar = 101325 Pascal
  Serial.print("vyska = ");
  vyska = bmp.readAltitude();
  Serial.print(bmp.readAltitude());
  Serial.println(" metrov");
  
  // Jesli znamy aktualne cisnienie przy poziomie morza,
  // mozemy dokladniej wyliczyc wysokosc, padajac je jako parametr
  Serial.print("Skutocna vyska = ");
  Serial.print(bmp.readAltitude(102520));
  skutocna_vyska = bmp.readAltitude(102520);
  Serial.println(" metrov");
  Serial.println("Sending to nrf24_server");


  // svietivost 
 uint16_t lux = lightMeter.readLightLevel();
 Serial.println("svetlo je:");
 Serial.println(lux);
  //svietivost
 //String theMessage1 = vlhkost+"|"+teplota_ds18b20+"|"+tlak+"|"+teplota_dht11+"|"+speedwindstring;
  String theMessage1 = vlhkost+"|"+teplota_ds18b20+"|"+tlak+"|"+lux+"|"+speedwindstring+"|"+teplota_dht11;

  const char* theMessage = theMessage1.c_str();
  // Send a message to nrf24_server
  uint8_t data1[] = "Hello word today tomorow";
  //nrf24.send((uint8_t*)theMessage, sizeof(theMessage1));
  nrf24.send((uint8_t*)theMessage, sizeof(data1));
  
  nrf24.waitPacketSent();
  // Now wait for a reply
  uint8_t buf[RH_NRF24_MAX_MESSAGE_LEN];
  uint8_t len = sizeof(buf);

  if (nrf24.waitAvailableTimeout(500))
  { 
    // Should be a reply message for us now   
    if (nrf24.recv(buf, &len))
    {
      Serial.print("got reply: ");
      Serial.println((char*)buf);
    }
    else
    {
      Serial.println("recv failed");
    }
  }
  else
  {
    Serial.println("No reply, is nrf24_server running?");
  }
  delay(400);
}

//////////////////Meranie rychlosti vetra/////////////////


void windvelocity(){
  speedwind = 0;
  counter = 0;  
  attachInterrupt(0, addcount, CHANGE);
  unsigned long millis();                     
  long startTime = millis();
  while(millis() < startTime + period) {
  }
  detachInterrupt(0);
}

void RPMcalc(){
  RPM=((counter/2)*60)/(period/1000);  // Calculate revolutions per minute (RPM)
}

void WindSpeed()
{
  speedwind = ((2 * pi * anemo * RPM)/60) / 1000;  // Calculate wind speed on m/s
}

void addcount()
{
   counter++;
}

/////////////////KONIEC MERANIA RYCHLOSTI /////////////////


