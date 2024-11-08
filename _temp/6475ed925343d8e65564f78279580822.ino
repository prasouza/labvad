
int ledPin1 = 10; // LED conectado ao pino digital 10
int ledPin2 = 11; // LED conectado ao pino digital 11
int ledPin3 = 12;
// O método setup() roda uma vez, quando o programa inicia
void setup() {                
  pinMode(ledPin1, OUTPUT);  // Inicializa o pino digital como uma saída     
  pinMode(ledPin2, OUTPUT); // Inicializa o pino digital como uma saída  
  pinMode(ledPin3, OUTPUT);  
}

// O método loop() roda repetidamente, enquanto houver alimentação na placa
void loop() {
  digitalWrite(ledPin1, HIGH);  // acende o LED conectado ao pino digital 10
  // neste instante somente o ledPin1 está aceso
  delay(100);                  // espera por um segundo
  digitalWrite(ledPin1, LOW);   // apaga o LED conectado ao pino digital 10
  // neste instante os dois leds estão apagados
  delay(100);                  // espera por um segundo
  digitalWrite(ledPin2, HIGH);  // acende o LED conectado ao pino digital 11
  // neste instante somente o ledPin2 está aceso
  delay(100);                  // espera por um segundo
  digitalWrite(ledPin2, LOW);   // apaga o LED conectado ao pino digital 11
  // neste instante os dois leds estão apagados
  delay(100);                  // espera por um segundo
   digitalWrite(ledPin3, LOW);  // acende o LED conectado ao pino digital 11
  // neste instante somente o ledPin2 está aceso
  delay(100);                  // espera por um segundo
  digitalWrite(ledPin3, LOW);   // apaga o LED conectado ao pino digital 11
  // neste instante os dois leds estão apagados
  delay(100);         
}

