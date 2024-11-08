/* Experimento _ RELE - Acionando uma carga com tempo programado
 * By S.Brandao 
 * Em: 25/07/2014  - 
 */


int RELE = 8;        // Seleciona o pino da saida 8 
int Timer = 15000;   // Tempo de acionamento e desligamento

// Saídas não utilizadas neste experimento da cat: MISCELANEA
int saida_6 = 6;
int saida_7 = 7;
int LED_VM = 9;
int LED_VD = 10;
int LED_AZ = 11;
// **********************************************************
// Decodificador do MUX.**************************************
int LED_VM0 = 2; 
int LED_VM1 = 4; 
//************************************************************

void setup() {
  
  pinMode(RELE, OUTPUT); // configura a porta do RELE como saida.
  
   // Decodificador do MUX.*********************************************
  pinMode(LED_VM0,OUTPUT);  // Pino_3 - Decodificador do Conjunto bit_0
  pinMode(LED_VM1,OUTPUT);  // Pino_5 - Decodificador do Conjunto bit_1
  // Habilita MUX - 01 - Conjunto de LEDs - LED0 a LED7
  digitalWrite(LED_VM0, HIGH);   // LED VM0 
  digitalWrite(LED_VM1, HIGH);   // LED VM1
  // ****************************************************************** 
  
  // Resete das saidas NAO utilizadas neste experimento
 pinMode(saida_6,OUTPUT);  // Pino_6 MOTOR
 pinMode(saida_7,OUTPUT);  // Pino_7 SERVO
 pinMode(LED_VM, OUTPUT);  // Pino_9
 pinMode(LED_VD, OUTPUT);  // Pino_10 
 pinMode(LED_AZ, OUTPUT);  // Pino_11
}

void loop() {
  
  digitalWrite(RELE, LOW); 
  delay(Timer);
  digitalWrite(RELE, HIGH); // Aciona o RELE por um tempo pre-definido
  delay(Timer);
// F I M 
}
