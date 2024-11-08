//Curso de Outono - Experimento 7
//Testando o DISPLAY_7_Segmentos + Ponto Decimal PD

int SEG_A = 13;
int SEG_B = 12;
int SEG_C = 11;
int SEG_D = 10;
int SEG_E = 9;
int SEG_F = 8;
int SEG_G = 7;
int SEG_PD = 6; //Ponto Decimal


void setup()
{
  pinMode(SEG_A,OUTPUT);
  pinMode(SEG_B,OUTPUT);
  pinMode(SEG_C,OUTPUT);
  pinMode(SEG_D,OUTPUT);
  pinMode(SEG_E,OUTPUT);
  pinMode(SEG_F,OUTPUT);
  pinMode(SEG_G,OUTPUT);
  pinMode(SEG_PD,OUTPUT);  
}

void loop()
{
  digitalWrite(SEG_A,HIGH);
  delay(500);
  digitalWrite(SEG_A,LOW);
  digitalWrite(SEG_B,HIGH);
  delay(500);
  digitalWrite(SEG_B,LOW);
  digitalWrite(SEG_C,HIGH);
  delay(500);
  digitalWrite(SEG_C,LOW);
  digitalWrite(SEG_D,HIGH);
  delay(500);
  digitalWrite(SEG_D,LOW);
  digitalWrite(SEG_E,HIGH);
  delay(500);
  digitalWrite(SEG_E,LOW);
  digitalWrite(SEG_F,HIGH);
  delay(500);
  digitalWrite(SEG_F,LOW);
  digitalWrite(SEG_G,HIGH);
  delay(500);
  digitalWrite(SEG_G,LOW);
  digitalWrite(SEG_PD,HIGH);
  delay(500);
  digitalWrite(SEG_PD,LOW);
  
  delay(1000);
}  
  
