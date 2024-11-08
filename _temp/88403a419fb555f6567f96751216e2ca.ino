//*****************************************************
// A R C O  I R I S  WEB - Geração de Cores           *
// By S.Brand�o                                       *
// 25/10/2012 - 1750 bytes                            *
// Esta experiencia usa o LED RGB - Grupo Miscel�nea  *
//*****************************************************

int timer  = 5000;
int LED_VM = 9;
int LED_VD = 10;
int LED_AZ = 11;
// Saídas não utilizadas neste experimento
int saida_6 = 6; // MOTOR
int saida_7 = 7; // SERVO
int saida_8 = 8; // RELE

// Decodificador do MUX.
// int LED_VM0 = 2; 
// int LED_VM1 = 4;

void setup()
{   // Obs: Todos os LEDs tem que estarem conectados em PINOS PWM 
  pinMode(LED_VM, OUTPUT);  // Pino_9
  pinMode(LED_VD, OUTPUT);  // Pino_10 
  pinMode(LED_AZ, OUTPUT);  // Pino_11
  //********************************************************************
  // pinMode(LED_VM0,OUTPUT);  // Pino_2 - Decodificador do Conjunto bit_0
  //  pinMode(LED_VM1,OUTPUT);  // Pino_4 - Decodificador do Conjunto bit_1
  // digitalWrite(LED_VM0, HIGH);   // LED VM0 
  // digitalWrite(LED_VM1, HIGH);   // LED VM1
  //******************************************************************** 
// Resete das saidas NAO utilizadas neste experimento
 pinMode(saida_6 , OUTPUT);   // Pino_6 MOTOR
 pinMode(saida_7 , OUTPUT);   // Pino_7 SERVO
 pinMode(saida_8 , OUTPUT);   // Pino_8 RELE
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
