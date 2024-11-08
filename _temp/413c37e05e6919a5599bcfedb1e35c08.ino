/*****************************************************
* Experimento 11 - Gerador de Caracteres             *
*                                                    *
* Experimento usa o DISPLAY de CARACTERES LCD-1602B  *
//****************************************************
*/
#include <LiquidCrystal.h>  
/* LiquidCrystal display with:
*  rs on pin 6
*  rw on pin 7
*  enable on pin 8
*  d4, d5, d6, d7 on pins 9, 10, 11, 12
*/
LiquidCrystal lcd(6, 7, 8, 9, 10, 11, 12);

void setup()
{
 Serial.begin(9600); 
}

void loop()
{
  lcd.clear();   // Reseta o Disply
  delay(500);
  lcd.begin (16, 2); // Define o tamanho do DISPLAY 
  lcd.print("ESCOLA de OUTONO");
  delay(3000);
  lcd.setCursor(0,1); // Escreve na linha (Linha 2)
  lcd.print(" N C E - U F R J");
  delay(3000);
  lcd.setCursor(0,1); // Escreve na linha (Linha 2)
  lcd.print(" M A I O - 2014 ");
  delay(3000);
  lcd.clear();
  lcd.setCursor(0,0); // Escreve na linha (Linha 1)
  lcd.print("* A L U N O S *");
  delay(1000);
  lcd.setCursor(3,1); // Escreve: Linha 2 Coluna 4
  lcd.print("BEM-VINDOS");
  delay(4000);
}
