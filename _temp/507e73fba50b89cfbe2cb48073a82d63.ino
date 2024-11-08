//*****************************************************
// GERADOR de MENSAGENS com Display 2x16              *
// By S.Brand�o                                       *
// 25/07/2014 - 3792 bytes                            *
// Esta experiencia usa o DISPLAY de CARACTERES       *
//*****************************************************

#include <LiquidCrystal.h>

// LiquidCrystal display with:
// rs on pin 6
// rw on pin 7
// enable on pin 8
// d4, d5, d6, d7 on pins 9, 10, 11, 12
LiquidCrystal lcd(6, 7, 8, 9, 10, 11, 12);
int brightness = 255;

// Decodificador do MUX.******************
  int LED_VM0 = 2; 
  int LED_VM1 = 4;  
//****************************************  

void setup()
{
 // Decodificador do MUX.**********************************************
  pinMode(LED_VM0,OUTPUT);  // Pino_2 - Decodificador do Conjunto bit_0
  pinMode(LED_VM1,OUTPUT);  // Pino_4 - Decodificador do Conjunto bit_1
  // Habilita MUX - 02 - Display 7 Segmentos
  digitalWrite(LED_VM0, LOW);   // LED VM0 
  digitalWrite(LED_VM1, LOW);   // LED VM1 
 //******************************************************************** 
 Serial.begin(9600); 

}

void loop()
{
  // Print a message to the LCD.
  lcd.clear();
  delay(500);
  lcd.begin (16, 2); // Define o tamanho do DISPLAY 
  lcd.print("* NCE * LABVAD *");
  delay(1500);
  lcd.setCursor(0,1); // Escreve na Linha 1
  lcd.print("Proj:  UCAnaCUCA");
 brightness = 100;
  delay(1500);
  lcd.setCursor(0,1); // Escreve na  linha 1
  lcd.print("* AGOSTO__2014 *");
  delay(1500);
  lcd.setCursor(0,0); // Escreve na linha 0
  lcd.print("*  VISITANTES  *");
  delay(1500);
  lcd.setCursor(0,1); // Escreve na linha 1
  lcd.print("Sejam Bem-vindos");
  delay(1500);
}
