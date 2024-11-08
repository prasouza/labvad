//*****************************************************
// S E Q U E N C I A L  de LEDs c/ Parada por bot�o   *
// By S.Brand�o                                       *
// 09/01/2012 - 1398 bytes                            *
// Esta experiencia usa o SEQUENCIALde LEDs.          *
// * Projeto 5 do Livro ARDUINO  - Alterado : 09/12/14                        *
//*****************************************************

byte ledPin[] = {  
  6,7,8,9,10,11,12,13}; // Array dos Leds

int  ledDelay(200);          // Intervalo enter as altera��es (mseg)
int  sentido = 1;    
int  LED_atual = 0;
int  botao = 3;
int  buzzer = 5;
unsigned long changeTime;

// Decodificador do MUX.**************************************
int LED_VM0 = 2; 
int LED_VM1 = 4; 
//************************************************************

void setup()
{
  // Decodificador do MUX.*********************************************
  pinMode(LED_VM0,OUTPUT);  // Pino_3 - Decodificador do Conjunto bit_0
  pinMode(LED_VM1,OUTPUT);  // Pino_5 - Decodificador do Conjunto bit_1
  // Habilita MUX - 01 - Conjunto de LEDs - LED0 a LED7
  digitalWrite(LED_VM0, 1);  // LED VM0 
  digitalWrite(LED_VM1, 1);   // LED VM1
  // ******************************************************************

  for (int x=0; x<8; x++)  //Define todos os pinos com sa�da
  {
    pinMode(ledPin[x], OUTPUT);
  }
  changeTime = millis();
  pinMode(botao, INPUT);
  pinMode(buzzer, OUTPUT);
}
void loop() 
{   
  if ((millis() - changeTime) > ledDelay) 
  { 
    changeLED();
    changeTime = millis();
    int state = digitalRead(botao);
    if ((state == HIGH) && (millis() >1000)) 
    {
      digitalWrite(buzzer, HIGH);
      delay(3000);
      digitalWrite(buzzer, LOW);
    }
  }  
}

void changeLED()
{
  for (int x=0; x<8; x++) // Apaga todos os LEDs
  { 
    digitalWrite(ledPin[x], LOW);
  }
  digitalWrite(ledPin[LED_atual], HIGH); // Acende o Led atual
  LED_atual += sentido; // incrementa de acordo com a dura��o
  // ALTERA a dire��o qundo chega ao final.
  if (LED_atual == 7) {
    sentido = -1;
  }
  if (LED_atual == 0) {
    sentido = 1;
  }
}      
// THE END*****************************************************

