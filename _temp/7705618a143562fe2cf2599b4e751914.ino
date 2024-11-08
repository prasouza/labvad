//************************************************
// Exp_9b_Motor_Servo                            *
// Servo desloca angulos predeterminados         *
// Experiencia usa o Micro-Servo 9g da Power Pro *
//************************************************
#include <Servo.h>

Servo Meu_servo;

void setup() 
{ 
  Meu_servo.attach(9); // Servo está conectado no pino I/O(9)
} 

void loop() 
{ 
  Meu_servo.write(0);
  delay(1000);  // tempo para o SERVO se posicionar.

  // Posiciona o SERVO a 30 GRAUS da origem.
  Meu_servo.write(30);
  delay(3000);

  // Posiciona o SERVO a 45 GRAUS da origem.
  Meu_servo.write(45);
  delay(3000);

  // Posiciona o SERVO a 90 GRAUS da origem.
  Meu_servo.write(90);
  delay(3000);

  // Posiciona o SERVO a 90 GRAUS da origem.
  Meu_servo.write(180);
  delay(3000);
  
  // Posiciona o SERVO na origem.
  Meu_servo.write(0);
  delay(5000);
} 
// F I M - Servo retorna a origem e para 5 segundos

