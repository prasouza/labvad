//*****************************************************
// S E M A F A R O (2Ruas)                            *
// By S.Brandao                                       *
// 25/07/2014 - 1522 bytes                            *
// Esta experiencia usa o SEQUENCIALde LEDs.          *
//*****************************************************

int LED_VM = 6;
int LED_AM = 7;
int LED_VD = 8;
int LED_AZ = 9;
int LED_VM2 = 10;
int LED_AM2 = 11;
int LED_VD2 = 12;
int LED_AZ2 = 13;

int Timer5 = 5000;

// Decodificador do MUX.******************
int LED_VM0 = 2; 
int LED_VM1 = 4;  
//**************************************** 

void setup() 
{ 
  pinMode(LED_VM, OUTPUT);  // LED VM 
  pinMode(LED_AM, OUTPUT);  // LED AM  
  pinMode(LED_VD, OUTPUT);  // LED VD 
  pinMode(LED_AZ, OUTPUT);  // LED AZ
  pinMode(LED_VM2, OUTPUT);  // LED VM2
  pinMode(LED_AM2, OUTPUT);  // LED AM2
  pinMode(LED_VD2, OUTPUT);  // LED VD2 
  pinMode(LED_AZ2, OUTPUT);  // LED AZ2 
 
  
 
 
} 

void loop() 
{
  // Inicializa o Semafaro
 
  // Acende a LUZ VERDE por 5 segundos
  
  digitalWrite(LED_VD,HIGH);
  digitalWrite(LED_VM2,HIGH);
  delay(Timer5);

  // LUZ AMARELA  pisca 10x300 = 3seg  
  for (int x=0; x<10; x++)
  {
    digitalWrite(LED_AM, HIGH);
    delay(150);
    digitalWrite(LED_AM, LOW);
    delay(150);
  }
  digitalWrite(LED_VM, HIGH);
  digitalWrite(LED_VD2, HIGH);
  digitalWrite(LED_VM2, LOW);
  digitalWrite(LED_VD, LOW);
  delay(Timer5);
  
  // LUZ AMARELA2  pisca 10x300 = 3seg  
  for (int x=0; x<10; x++)
  {
    digitalWrite(LED_AM2, HIGH);
    delay(150);
    digitalWrite(LED_AM2, LOW);
    delay(150);
  }
    digitalWrite(LED_VM2, HIGH);
    digitalWrite(LED_VD, HIGH);
    digitalWrite(LED_VD2, LOW);
    digitalWrite(LED_VM,  LOW);
    delay(Timer5);
   
    // RESETE GERAL

    digitalWrite(LED_VD, LOW);
    digitalWrite(LED_AM, LOW);
    digitalWrite(LED_VM, LOW);
    digitalWrite(LED_VD2, LOW);
    digitalWrite(LED_AM2, LOW);
    digitalWrite(LED_VM2, LOW);
    delay(150);
  }            
  // F I M