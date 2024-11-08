#include <WProgram.h>
/********************************************************
 * // DADO ELETR�NICO com Display HS5101 B              *
 * // By S.Brandao                                      *
 * // 03/09/2012 - 2128 bytes                           *
 * // Esta experiencia usa o Display de 7 Segmentos     *
 ********************************************************
*/

int timer = 200;      // Tempo = .1 segundo.
int timer1 = 1000;    // Tempo = 1 segundos na parada.
int timer2 = 3000;    // Tempo = 3 segundos na parada.
int BOTAO  = 3;       // Botao de Parada.
int BUZZER = 5;       // Buzzer     
int Seg_F = 11;       // Segmento F.
int Seg_G = 12;       // Segmento G.
int Seg_E = 10;       // Segmento E.
int Seg_D = 9;        // Segmento D.
int Seg_A = 6;        // Segmento A.
int Seg_B = 7;        // Segmento B.
int Seg_C = 8;        // Segmento C.
int PD    = 13;       // Ponto Decimal.
int val=0;
int x = 0;
// Decodificador do MUX.
int LED_VM0 = 2; 
int LED_VM1 = 4;  

void setup()
{
  pinMode(Seg_F,OUTPUT);  // Segmento F.
  pinMode(Seg_G,OUTPUT);  // Segmento G.
  pinMode(Seg_E,OUTPUT);  // Segmento E.
  pinMode(Seg_D,OUTPUT);  // Segmento D.
  pinMode(Seg_A,OUTPUT);  // Segmento A.
  pinMode(Seg_B,OUTPUT);  // Segmento B.  
  pinMode(Seg_C,OUTPUT);  // Segmento C.
  pinMode(PD,OUTPUT);  // Ponto Decimal.
  pinMode(BOTAO,INPUT); //Bot�o de Parada
  pinMode(BUZZER,OUTPUT);     //Aciona BUZZER
  // Decodificador do MUX.*********************************************
  pinMode(LED_VM0,OUTPUT);  // Pino_2 - Decodificador do Conjunto bit_0
  pinMode(LED_VM1,OUTPUT);  // Pino_4 - Decodificador do Conjunto bit_1
  // Habilita MUX - 02 - Display 7 Segmentos
  digitalWrite(LED_VM0, HIGH );   // LED VM0 
  digitalWrite(LED_VM1, LOW);    // LED VM1

}
void loop()

{   
  // M�DULO DADO ELETR�NICO
  // D�GITO 1
  digitalWrite(Seg_F,HIGH);         // Segmento F OFF
  digitalWrite(Seg_G,HIGH);         // Segmento G OFF
  digitalWrite(Seg_E,HIGH);         // Segmento E OFF
  digitalWrite(Seg_D,HIGH);         // Segmento D OFF
  digitalWrite(Seg_A,HIGH);         // Segmento A OFF
  digitalWrite(Seg_B,LOW);         // Segmento B ON
  digitalWrite(Seg_C,LOW);         // Segmento C ON
  delay(timer);
  // ----------------   testa se bot�o foi apertado
  val = digitalRead(BOTAO);  // read input value
  if (val == HIGH) {     // check if the input is HIGH
    digitalWrite( BUZZER,HIGH);
    delay(timer1);       // Para por 5 segundos
    digitalWrite( BUZZER,LOW);
  }
  // -------------------------------------------------                      

  // D�GITO 2
  digitalWrite(Seg_F,HIGH);         // Segmento F OFF
  digitalWrite(Seg_G,LOW);         // Segmento G ON
  digitalWrite(Seg_E,LOW);         // Segmento E ON
  digitalWrite(Seg_D,LOW);         // Segmento D ON
  digitalWrite( Seg_A,LOW);         // Segmento A ON
  digitalWrite( Seg_B,LOW);         // Segmento B ON
  digitalWrite( Seg_C,HIGH);         // Segmento C OFF
  delay(timer);
  // ----------------   testa se bot�o foi apertado
  val = digitalRead(BOTAO);  // read input value
  if (val == HIGH) {     // check if the input is HIGH
    digitalWrite( BUZZER,HIGH);
    delay(timer2);       // Para por 5 segundos
    digitalWrite( BUZZER,LOW);
  }
  // ------------------------------------------------- 

  // D�GITO 3
  digitalWrite(Seg_F,HIGH);         // Segmento F OFF
  digitalWrite(Seg_G,LOW);         // Segmento G ON
  digitalWrite(Seg_E,HIGH);         // Segmento E OFF
  digitalWrite(Seg_D,LOW);         // Segmento D ON
  digitalWrite( Seg_A,LOW);         // Segmento A ON
  digitalWrite( Seg_B,LOW);         // Segmento B ON
  digitalWrite( Seg_C,LOW);         // Segmento C ON
  delay(timer);
  // ----------------   testa se bot�o foi apertado
  val = digitalRead(BOTAO);  // read input value
  if (val == HIGH) {     // check if the input is HIGH
    digitalWrite( BUZZER,HIGH);
    delay(timer1);       // Para por 5 segundos
    digitalWrite( BUZZER,LOW);
  }
  // ------------------------------------------------- 

  // D�GITO 4
  digitalWrite(Seg_F,LOW);         // Segmento F ON
  digitalWrite(Seg_G,LOW);         // Segmento G ON
  digitalWrite(Seg_E,HIGH);          // Segmento E OFF
  digitalWrite(Seg_D,HIGH);          // Segmento D OFF
  digitalWrite( Seg_A,HIGH);          // Segmento A OFF
  digitalWrite( Seg_B,LOW);          // Segmento B ON
  digitalWrite( Seg_C,LOW);         // Segmento C ON
  delay(timer);
  // ----------------   testa se bot�o foi apertado
  val = digitalRead(BOTAO);  // read input value
  if (val == HIGH) {     // check if the input is HIGH
    digitalWrite( BUZZER,HIGH);
    delay(timer2);       // Para por 5 segundos
    digitalWrite( BUZZER,LOW);
  }
  // ------------------------------------------------- 

  // D�GITO 5
  digitalWrite(Seg_F,LOW);         // Segmento F ON
  digitalWrite(Seg_G,LOW);         // Segmento G ON
  digitalWrite(Seg_E,HIGH);          // Segmento E OFF
  digitalWrite(Seg_D,LOW);         // Segmento D ON
  digitalWrite( Seg_A,LOW);         // Segmento A ON
  digitalWrite( Seg_B,HIGH);          // Segmento B OFF
  digitalWrite( Seg_C,LOW);         // Segmento C ON
  delay(timer);
  // ----------------   testa se bot�o foi apertado
  val = digitalRead(BOTAO);  // read input value
  if (val == HIGH) {     // check if the input is HIGH
    digitalWrite( BUZZER,HIGH);
    delay(timer1);       // Para por 1 segundo
    digitalWrite( BUZZER,LOW);
  }
  // ------------------------------------------------- 

  // D�GITO 6
  digitalWrite(Seg_F,LOW);         // Segmento F ON
  digitalWrite(Seg_G,LOW);         // Segmento G ON
  digitalWrite(Seg_E,LOW);         // Segmento E ON
  digitalWrite(Seg_D,LOW);         // Segmento D ON
  digitalWrite(Seg_A,HIGH);         // Segmento A ON
  digitalWrite(Seg_B,HIGH);          // Segmento B OFF
  digitalWrite(Seg_C,LOW);         // Segmento C ON
  delay(timer);
  // ----------------   testa se bot�o foi apertado
  val = digitalRead(BOTAO);  // read input value
  if (val == HIGH) {     // check if the input is HIGH
    digitalWrite( BUZZER,HIGH);
    delay(timer2);       // Para por 3 segundos
    digitalWrite( BUZZER,LOW);
  }
  // ------------------------------------------------- 

  digitalWrite(PD,LOW);         // Ponto Decimal ON
  delay(timer);
  digitalWrite(PD,HIGH);          // Ponto Decimal OFF 
  delay(timer);

}  // THE END