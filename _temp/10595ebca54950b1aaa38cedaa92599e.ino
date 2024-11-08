
int LED_VD2 = 12;
int LED_AM2 = 11;
int LED_VM2 = 10;

// Decodificador do MUX.******************
int LED_VM0 = 2; 
int LED_VM1 = 4;  
//**************************************** 

// O método setup() roda uma vez, quando o programa inicia
void setup() 
{                
  pinMode(LED_VD2, OUTPUT);  // Inicializa o pino digital como uma saída     
  pinMode(LED_AM2, OUTPUT); // Inicializa o pino digital como uma saída
  pinMode(LED_VM2, OUTPUT);
  // Decodificador do MUX.**********************************************
  pinMode(LED_VM0,OUTPUT);  // Pino_2 - Decodificador do Conjunto bit_0
  pinMode(LED_VM1,OUTPUT);  // Pino_4 - Decodificador do Conjunto bit_1
  // Habilita MUX - 02 - Display 7 Segmentos
  digitalWrite(LED_VM0, LOW);   // LED VM0 
  digitalWrite(LED_VM1, HIGH);   // LED VM1 
  //********************************************************************

}

void loop() {
  digitalWrite(LED_VD2, HIGH);  // acende o LED conectado ao pino digital 10
  // neste instante somente o ledPin1 está aceso
  delay(1000);                  // espera por um segundo
  digitalWrite(LED_VD2, LOW);   // apaga o LED conectado ao pino digital 10
  // neste instante os dois leds estão apagados
  delay(1000);                  // espera por um segundo
  digitalWrite(LED_AM2, HIGH);  // acende o LED conectado ao pino digital 11
  // neste instante somente o ledPin2 está aceso
  delay(1000);                  // espera por um segundo
  digitalWrite(LED_AM2, LOW);   // apaga o LED conectado ao pino digital 11
  // neste instante os dois leds estão apagados
  delay(1000);                  // espera por um segundo
  digitalWrite(LED_VM2, HIGH);  // acende o LED conectado ao pino digital 12
  // neste instante somente o ledPin2 está aceso
  delay(1000);                  // espera por um segundo
  digitalWrite(LED_VM2, LOW);   // apaga o LED conectado ao pino digital 12
  delay(1000);
}
