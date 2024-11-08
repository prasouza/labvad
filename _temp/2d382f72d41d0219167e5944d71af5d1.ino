/* Este exemplo de código é de domínio publico
O nosso LED 12 é o último LED do canto esquerdo da tela do LabVad.
Vamos declarar este LED */
int led = 12;

// No função setup escrevemos parte do código que será executado uma vez:
void setup() { 
// Inicializando LED como saída.
pinMode(led, OUTPUT); 
}

// O loop rodará parte do código até que o mesmo seja interrompido ou zerado.
void loop() {
digitalWrite(led, HIGH); //Acende o LED 
delay(1000); // Espera um segundo. Para esperar meio segundo o valor atribuido seria 500
digitalWrite(led, LOW); // Apaga o LED
delay(1000); // Espera um segundo
}