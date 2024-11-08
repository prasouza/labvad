//*****************************************************
// S E R V O gira modulos de 45 graus                 *
// By S.Brandao                                       *
// Em: 21/07//2014 -                                  *
// Experiencia com o  Servo   -  Grupo Miscel�nea     *
//*****************************************************
#include <Servo.h>

Servo Meu_servo;
int LED_VM = 9;
int LED_AZ = 10;
int LED_VD = 11;

// Decodificador do MUX.**************************************
int LED_VM0 = 2; 
int LED_VM1 = 4; 
//************************************************************
// Saídas não utilizadas neste experimento da cat: MISCELANEA
int saida_6 = 6;
int saida_8 = 8;

// **********************************************************

void setup() 
{ 
  Meu_servo.attach(7);      // Servo está conectado no I/O(7)
  pinMode(LED_VM, OUTPUT);  // Pino_9
  pinMode(LED_VD, OUTPUT);  // Pino_10 
  pinMode(LED_AZ, OUTPUT);  // Pino_11

  // Decodificador do MUX.*********************************************
  pinMode(LED_VM0,OUTPUT);  // Pino_3 - Decodificador do Conjunto bit_0
  pinMode(LED_VM1,OUTPUT);  // Pino_5 - Decodificador do Conjunto bit_1
  // Habilita MUX - 01 - Conjunto de LEDs - LED0 a LED7
  digitalWrite(LED_VM0, HIGH);   // LED VM0 
  digitalWrite(LED_VM1, HIGH);   // LED VM1
  // ****************************************************************** 

  // Resete das saidas NAO utilizadas neste experimento
  pinMode(saida_6,OUTPUT);  // Pino_6 MOTOR
  pinMode(saida_8,OUTPUT);  // Pino_8 RELE

} 

void loop() 
{ 

  Meu_servo.write(0);
  delay(5000);  // tempo para o SERVO se posicionar.


  // Posiciona o SERVO a 30 GRAUS da origem.
  Meu_servo.write(30);
  digitalWrite(LED_VM, 0); 
  delay(5000);


  // Posiciona o SERVO a 90 GRAUS da origem.
  Meu_servo.write(90);
  digitalWrite(LED_VM, 1);
  digitalWrite(LED_VD, 0); // Acende o Led VD
  delay(5000);


  // Posiciona o SERVO a 150 GRAUS da origem.
  Meu_servo.write(150);
  digitalWrite(LED_VD, 1);
  digitalWrite(LED_AZ, 0); // Acende o Led AZ
  delay(5000);

  // Posiciona o SERVO a 180 GRAUS da origem.
  Meu_servo.write(180);
  digitalWrite(LED_VM, 1); 
  digitalWrite(LED_VD, 1);
  digitalWrite(LED_AZ, 1);
  delay(5000);

  // Posiciona o SERVO na origem.
  Meu_servo.write(0);
  delay(5000);



} 




