//*****************************************************
// S E R V O acionando um LDR                         *
// By S.Brand�o                                       *
// 24/07//2014 -  4842 bytes                          *
// Experiencia do Servo com LDR   - Grupo Miscel�nea  *
//*****************************************************
#include <Servo.h>
Servo Meu_servo;

int RELE = 8;
int LDR = A0;
// Decodificador do MUX.**************************************
int LED_VM0 = 2; 
int LED_VM1 = 4; 
//************************************************************
// Saídas não utilizadas neste experimento da cat: MISCELANEA
int saida_6 = 6;
int saida_8 = 8;
int LED_VM = 9;
int LED_VD = 10;
int LED_AZ = 11;
// **********************************************************

void setup() 
{ 
  Meu_servo.attach(7); // Servo está conectado no I/O(7)
  Serial.begin(9600);
  pinMode(RELE,OUTPUT);

  // Decodificador do MUX.*********************************************
  pinMode(LED_VM0,OUTPUT);  // Pino_2 - Decodificador do Conjunto bit_0
  pinMode(LED_VM1,OUTPUT);  // Pino_4 - Decodificador do Conjunto bit_1
  // Habilita MUX - 01 - Conjunto de LEDs - LED0 a LED7
  digitalWrite(LED_VM0, HIGH);   // LED VM0 
  digitalWrite(LED_VM1, HIGH);   // LED VM1
  // ******************************************************************
   // Resete das saidas NAO utilizadas neste experimento
 pinMode(saida_6,OUTPUT);  // Pino_6 MOTOR
 pinMode(saida_8,OUTPUT);  // Pino_8 RELE
 pinMode(LED_VM, OUTPUT);  // Pino_9
 pinMode(LED_VD, OUTPUT);  // Pino_10 
 pinMode(LED_AZ, OUTPUT);  // Pino_11
} 

void loop() 
{ 
  int Val = 0;
  Meu_servo.write(0);
  delay(1000);  // tempo para o SERVO se posicionar.
  Val = analogRead(LDR);
  // Teste do LFDR
  Serial.print("Servo em ZERO Graus - LDR =");
  Serial.println(Val);
  
  if (Val < 400 )
    digitalWrite(RELE, 1);
  else
    digitalWrite(RELE, 0);
  delay(5000); 
  
// Posiciona o SERVO a 45 GRAUS da origem.
  Meu_servo.write(45);
  delay(500);
  Val = analogRead(LDR);
  Serial.print("Servo em 45 Graus - LDR =");
  Serial.println(Val);
  if (Val < 400 )
    digitalWrite(RELE, 1);
  else
    digitalWrite(RELE, 0);
  delay(5000);
  
// Posiciona o SERVO a 90 GRAUS da origem.
  Meu_servo.write(90);
  delay(1000);
  Val = analogRead(LDR);
  Serial.print("Servo em 90 Graus - LDR =");
  Serial.println(Val);
    if (Val < 400 ){
       digitalWrite(RELE, 1);
       Serial.print("DENTRO do IF Val<400  =");
       Serial.println(Val);
       delay(10000); // Aciona por 10 segundos
  }
  else
    digitalWrite(RELE, 0);
  delay(5000);
  
// Posiciona o SERVO a 135 GRAUS da origem.
  Meu_servo.write(135);
  delay(500);
  Val = analogRead(LDR);
  Serial.print("Estou sobre 135 Graus. Valor = ");
  Serial.println(Val);
  if (Val < 400 )
    digitalWrite(RELE, 1);
  else
    digitalWrite(RELE, 0);
  delay(5000);
  
// Posiciona o SERVO na origem.
  Meu_servo.write(180);
  delay(500);
  Val = analogRead(LDR);
  Serial.print("Estou em 180 Graus. Valor = ");
  Serial.println(Val);
  if (Val < 400 )
    digitalWrite(RELE, 1);
  else
    digitalWrite(RELE, 0);
  delay(5000);

} 

