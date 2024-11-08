/*
  Blink - Primeiro experimento do ARDUINO.
 
 */

// Pino 13 tem um  LED conectado na placa do ARDUINO.
// 
int led = 13;

// Rotina setup roda apenas uma vez:
void setup() {                

  pinMode(13, OUTPUT);  // inicializa pino digital como saida.   
}

// Rotina loop roda o tempo todo:
void loop() {
  digitalWrite(led, HIGH);   // Acende o LED 
  delay(100);               // Espera por 1 segundo.
  digitalWrite(led, LOW);    // Apaga o LED
  delay(100);               // Espera por 1 segundo.
}


