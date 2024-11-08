/*  Acende o led quando o ambiente fica escuro */

int ldrPin = A0; // Seleciona o pino de entrada para o potentiômetro
int ledPin = 9;  // Seleciona o pino de saída para o motor
int val    = 0;  // Variável para guardar o valor da entrada analógica

void setup() {
  pinMode(ldrPin, INPUT);  // configura a porta do potenciômetro como entrada
  pinMode(ledPin, OUTPUT); // configura a porta do motor como saída
  // abre a porta serial, define a taxa de transferência de dados a 9600 bps
  Serial.begin(9600);
}

void loop() {
  val = analogRead(ldrPin); // Lê o valor proveniente da entrada analógica
  Serial.println(val);      // escreve o valor no monitor serial
  // teste para o acionamento do led
  if (val < 100) {            
    digitalWrite(ledPin, HIGH);
  }else {
    digitalWrite(ledPin, LOW);
  }
}
