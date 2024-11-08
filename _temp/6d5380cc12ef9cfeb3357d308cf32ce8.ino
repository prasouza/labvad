//*****************************************************
// S E R V O gira modulos de 45 graus *
// By S.Brandao *
// Em: 21/07//2014 - *
// Experiencia com o Servo *
//*****************************************************
#include <Servo.h>

Servo Meu_servo;

void setup() 
{ 
Meu_servo.attach(7); // Servo está conectado no I/O(7)
}

void loop() 
{ 

Meu_servo.write(0);
delay(5000); // tempo para o SERVO se posicionar.


// Posiciona o SERVO a 45 GRAUS da origem.
Meu_servo.write(45);
delay(5000);


// Posiciona o SERVO a 90 GRAUS da origem.
Meu_servo.write(90);
delay(5000);


// Posiciona o SERVO a 135 GRAUS da origem.
Meu_servo.write(135);
delay(5000);

// Posiciona o SERVO a 180 GRAUS da origem.
Meu_servo.write(180);
delay(5000);

// Posiciona o SERVO na origem.
Meu_servo.write(0);
delay(5000);
}