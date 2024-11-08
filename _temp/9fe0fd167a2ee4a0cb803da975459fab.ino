//*****************************************************
// A R C O  I R I S  WEB - Geracao de Cores           *
// By S.Brandao                                       *
// 25/10/2012 - 1750 bytes                            *
// Esta experiencia usa o LED RGB                     *
//*****************************************************

int timer  = 5000;
int LED_VM = 9;
int LED_VD = 10;
int LED_AZ = 11;



void setup()
{   // Obs: Todos os LEDs tem que estarem conectados em PINOS PWM 
  pinMode(LED_VM, OUTPUT);  // Pino_9
  pinMode(LED_VD, OUTPUT);  // Pino_10 
  pinMode(LED_AZ, OUTPUT);  // Pino_11
}
void loop()

{ 
  // Cor 1 - APAGADO
  analogWrite(LED_VD, 0);   // LED
  analogWrite(LED_AZ, 0);   // LED 
  analogWrite(LED_VM, 0);   // LED VM
  delay(timer);
  // Cor 2 - VERMELHO
  analogWrite(LED_VM, 255);   // LED VM 
  analogWrite(LED_AZ, 0);   // LED AZ
  analogWrite(LED_VD, 0);   // LED VD
  delay(timer);
  // Cor 3 - VERDE
  analogWrite(LED_VM, 0);   // LED VM 
  analogWrite(LED_AZ, 0);     // LED AZ
  analogWrite(LED_VD, 255);   // LED VD
  delay(timer);
  // Cor 4 - AZUL
  analogWrite(LED_VM, 0);   // LED VM 
  analogWrite(LED_AZ, 255);     // LED AZ
  analogWrite(LED_VD, 0);   // LED VD
  delay(timer);
  // Cor 5 - Amarelo
  analogWrite(LED_VD, 255);   // LED VD 
  analogWrite(LED_VM, 255);   // LED VM 
  analogWrite(LED_AZ, 0);     // LED AZ
  delay(timer);
  // Cor 6 - Magenta
  analogWrite(LED_VM, 255);   // LED VM 
  analogWrite(LED_AZ, 255);   // LED AZ
  analogWrite(LED_VD, 0);   // LED VD
  delay(timer);
  // Cor 7 - Ciano
  analogWrite(LED_VM, 0);   // LED VM 
  analogWrite(LED_AZ, 255);   // LED AZ
  analogWrite(LED_VD, 255);   // LED VD
  delay(timer);
  // Cor 8 - BRANCO
  analogWrite(LED_VM, 255);  // LED VM 
  analogWrite(LED_AZ, 255);   // LED AZ
  analogWrite(LED_VD, 255);   // LED VD
  delay(timer);
}  
//_________________________________________________________
// THE END
