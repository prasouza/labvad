
int timer = 4000;      // Tempo = 4 segundos.
   
int Seg_F =  7;       // Segmento F.
int Seg_G =  6;       // Segmento G.
int Seg_E = 13;       // Segmento E.
int Seg_D = 12;        // Segmento D.
int Seg_A =  8;        // Segmento A.
int Seg_B =  9;        // Segmento B.
int Seg_C = 11;        // Segmento C.
int PD    = 10;       // Ponto Decimal.

// Decodificador do MUX.
int LED_VM0 = 2; 
int LED_VM1 = 4;  

void setup()
{
// Decodificador do MUX.*********************************************
  pinMode(LED_VM0,OUTPUT);  // Pino_2 - Decodificador do Conjunto bit_0
  pinMode(LED_VM1,OUTPUT);  // Pino_4 - Decodificador do Conjunto bit_1
  // Habilita MUX - 02 - Display 7 Segmentos
  digitalWrite(LED_VM0, HIGH );   // LED VM0 
  digitalWrite(LED_VM1, LOW);    // LED VM1

  pinMode(Seg_F,OUTPUT);  // Segmento F.
  pinMode(Seg_G,OUTPUT);  // Segmento G.
  pinMode(Seg_E,OUTPUT);  // Segmento E.
  pinMode(Seg_D,OUTPUT);  // Segmento D.
  pinMode(Seg_A,OUTPUT);  // Segmento A.
  pinMode(Seg_B,OUTPUT);  // Segmento B.  
  pinMode(Seg_C,OUTPUT);  // Segmento C.
  pinMode(PD,OUTPUT);     // Ponto Decimal.
}
void loop()

{   
  // DIGITO 1
  digitalWrite(Seg_F,HIGH);         // Segmento F OFF
  digitalWrite(Seg_G,HIGH);         // Segmento G OFF
  digitalWrite(Seg_E,HIGH);         // Segmento E OFF
  digitalWrite(Seg_D,HIGH);         // Segmento D OFF
  digitalWrite(Seg_A,HIGH);         // Segmento A OFF
  digitalWrite(Seg_B,LOW);          // Segmento B ON
  digitalWrite(Seg_C,LOW);          // Segmento C ON
  digitalWrite(PD,HIGH);            //PONTO DEC. OFF
  delay(timer); 
  
  // -------------------------------------------------                      

  // DIGITO 2
  digitalWrite(Seg_F,HIGH);        // Segmento F OFF
  digitalWrite(Seg_G,LOW);         // Segmento G ON
  digitalWrite(Seg_E,LOW);         // Segmento E ON
  digitalWrite(Seg_D,LOW);         // Segmento D ON
  digitalWrite(Seg_A,LOW);         // Segmento A ON
  digitalWrite(Seg_B,LOW);         // Segmento B ON
  digitalWrite(Seg_C,HIGH);        // Segmento C OFF
  digitalWrite(PD,LOW);            // PONTO DEC. ON
  delay(timer);

 // DIGITO 3
  digitalWrite(Seg_F,HIGH);        // Segmento F OFF
  digitalWrite(Seg_G,LOW);         // Segmento G ON
  digitalWrite(Seg_E,HIGH);        // Segmento E OFF
  digitalWrite(Seg_D,LOW);         // Segmento D ON
  digitalWrite(Seg_A,LOW);         // Segmento A ON
  digitalWrite(Seg_B,LOW);         // Segmento B ON
  digitalWrite(Seg_C,LOW);         // Segmento C OFF
  digitalWrite(PD,LOW);            // PONTO DEC. ON
  delay(timer);

// DIGITO 4
  digitalWrite(Seg_F,LOW);        // Segmento F ON
  digitalWrite(Seg_G,LOW);        // Segmento G ON
  digitalWrite(Seg_E,HIGH);       // Segmento E OFF
  digitalWrite(Seg_D,HIGH);       // Segmento D OFF
  digitalWrite(Seg_A,HIGH);       // Segmento A OFF
  digitalWrite(Seg_B,LOW);        // Segmento B ON
  digitalWrite(Seg_C,LOW);        // Segmento C ON
  digitalWrite(PD,LOW);           // PONTO DEC. ON
  delay(timer);
  
}  // THE END
