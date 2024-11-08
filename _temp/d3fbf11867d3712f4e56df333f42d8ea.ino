//*****************************************************
// S E R V O gira modulos de 45 graus                 *
// By S.Brandao                                       *
// Em: 21/07//2014 -                                  *
// Experiencia com o  Servo   -  Grupo Miscelanea     *
//*****************************************************
#include <Servo.h>

Servo Meu_servo;

// Saídas não utilizadas neste experimento da cat: MISCELANEA
int saida_6 = 6;
int saida_8 = 8;
int LED_VM = 9;
int LED_VD = 10;
int LED_AZ = 11;
// **********************************************************

void setup() 
{ 
  Meu_servo.attach(7);      // Servo está conectado no I/O(7)
  

  
  // Resete das saidas NAO utilizadas neste experimento
 pinMode(saida_6,OUTPUT);  // Pino_6 MOTOR
 pinMode(saida_8,OUTPUT);  // Pino_8 RELE
 pinMode(LED_VM, OUTPUT);  // Pino_9
 pinMode(LED_VD, OUTPUT);  // Pino_10 
 pinMode(LED_AZ, OUTPUT);  // Pino_11
} 

void loop() 
{ 
  
  Meu_servo.write(0);
  delay(5000);  // tempo para o SERVO se posicionar.
  
  
// Posiciona o SERVO a 45 GRAUS da origem.
  Meu_servo.write(45);
  delay(5000);
  
  
// Posiciona o SERVO a 90 GRAUS da origem.
  Meu_servo.write(120);
  delay(5000);
 
  
// Posiciona o SERVO a 135 GRAUS da origem.
  Meu_servo.write(135);
  delay(5000);
  
// Posiciona o SERVO a 180 GRAUS da origem.
  Meu_servo.write(150);
  delay(5000);
  
// Posiciona o SERVO na origem.
  Meu_servo.write(0);
  delay(5000);
  

}