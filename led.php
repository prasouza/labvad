<?php
$acaoLog = 'Help - LEDs';
require_once 'app.ado/TConnection.class.php';
require_once 'app.include/verificasessao.inc.php';
$cabecalho = file('app.include/header.inc.php');

$conteudo = implode('', $cabecalho);
$conteudo = str_replace('{TITULO}', 'Laboratório', $conteudo);
echo $conteudo;
?>

<title>LED</title>

<body>
    <blockquote>
        <h1>LED <img src="img/led.jpg" width="350" height="175"></h1>
        <h1>O que é? O que faz?                    </h1>
<p><strong>LED</strong> (<strong>L</strong>ight <strong>E</strong>mitting <strong>D</strong>iode). Emite uma luz quando uma pequena corrente o aciona.</blockquote>
    <blockquote>
        <h1>Como funciona no LabVad?</h1>


        <p>Os LEDs estão conectados no LabVad dos pinos 6 ao 13, como na figura abaixo:</p>
        <p><img src="img/led.png" width="540" height="340"></p>
    </blockquote>
    <blockquote>
        <h1>Veja um exemplo:</h1>
        <p><img src="img/dica.png" alt="" width="32" height="32">Dica: copie e cole o código na aba Experimentos!</p>
        <p>/*<br>
            Piscar LED<br>
            Acende o LED por um segundo e depois o apaga por um segundo também.<br>
            <br>
            Este exemplo de código é de domínio publico<br>
            */<br>
            <br>
            // O nosso LED 13 é o último LED do canto esquerdo da tela do LabVad.<br>
            // Vamos declarar este LED:<br>
            int ledBranco = 13;</p>
        <p>// No função setup escrevemos parte do código que será executado uma vez:<br>
            void setup() { <br>
            // Inicializando LED como saída.<br>
            pinMode(ledBranco, OUTPUT); <br>
            }</p>
        <p>// O loop rodará parte do código até que o mesmo seja interrompido ou zerado.<br>
            void loop() {<br>
            digitalWrite(ledBranco, HIGH);   //Acende o LED <br>
            delay(1000);               // Espera um segundo. Para esperar meio segundo o valor atribuido seria 500<br>
            digitalWrite(ledBranco, LOW);    // Apaga o LED<br>
            delay(1000);               // Espera um segundo<br>
            }</p>
    </blockquote>
    <blockquote>
        <p><span class="animated"> | <a href="labvad.php">Introdução</a> | <a href="caracteres.php">Próxima Lição</a> | <a href="img/LabVad_Guia.pdf">Baixar Guia do LabVad</a> |</span></p>
    </blockquote>

    <?php require_once 'app.include/footer.inc.php'; ?>
