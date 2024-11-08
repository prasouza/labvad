/* Experimento _ Motor Dc com velocidade controlada
 * By S.Brandao 
 * Em: 25/07/2014  - 
 */


int Motor_DC = 6;  // Seleciona o pino de saída PWM para o motor
int Valor = 125;   // Valor  de 0 a 45 (parado) de 50(vel.min.) a 255(vel. max)

void setup() {
  
  pinMode(Motor_DC, OUTPUT); // configura a porta do motor como saída
  
// Resete das saidas NAO utilizadas neste experimento
 pinMode(saida_7,OUTPUT);  // Pino_7 SERVO
 pinMode(saida_8,OUTPUT);  // Pino_8 RELE
 pinMode(LED_VM, OUTPUT);  // Pino_9
 pinMode(LED_VD, OUTPUT);  // Pino_10 
 pinMode(LED_AZ, OUTPUT);  // Pino_11
}

void loop() {
  
  analogWrite(Motor_DC, Valor); // Aciona o Motor
}
