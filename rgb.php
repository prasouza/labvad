<?php
$acaoLog = 'Help - LED RGB';
require_once 'app.ado/TConnection.class.php';
require_once 'app.include/verificasessao.inc.php';
$cabecalho = file('app.include/header.inc.php');

$conteudo = implode('', $cabecalho);
$conteudo = str_replace('{TITULO}', 'Laboratório', $conteudo);
echo $conteudo;
?>

<title>LED RGB</title>

<body>
    <blockquote>
        <h1>LED RGB<img src="img/LED-RGB.png" width="233" height="175"></h1>
        <h1>O que é? O que faz?                    </h1>
<p><strong>Um LED  RGB incorpora três LEDs juntos, um vermelho (RED), um verde (GREEN) e um azul (BLUE)</strong>. Podemos  codificar as mais diversas cores que as combinações de vermelho, verde e azul  podem nos oferecer.</blockquote>
    <blockquote>
        <h1>Como funciona no LabVad?</h1>
        <p>Como podemos ver na figura abaixo, o LED RGB está conectado aos pinos 9, 10 e 11.</p>


        <p><img src="img/rgb.png" width="540" height="340"></p>
    </blockquote>
    <blockquote>
        <h1>Veja um exemplo:</h1>
        <p><img src="img/dica.png" alt="" width="32" height="32">Dica: copie e cole o código na aba Experimentos!</p>
        <p>//*****************************************************<br>
            // A R C O  I R I S  WEB - Geracao de Cores           *<br>
            // By S.Brandao                                       *<br>
            // 25/10/2012 - 1750 bytes                            *<br>
            // Esta experiencia usa o LED RGB                     *<br>
            //*****************************************************</p>
        <p>int timer  = 5000;<br>
            int LED_VM = 9;<br>
            int LED_VD = 10;<br>
            int LED_AZ = 11;</p>
        <p>&nbsp;</p>
        <p>void setup()<br>
            {   // Obs: Todos os LEDs tem que estarem conectados em PINOS PWM <br>
            pinMode(LED_VM, OUTPUT);  // Pino_9<br>
            pinMode(LED_VD, OUTPUT);  // Pino_10 <br>
            pinMode(LED_AZ, OUTPUT);  // Pino_11<br>
            }<br>
            void loop()</p>
        <p>{ <br>
            // Cor 1 - APAGADO<br>
            analogWrite(LED_VD, 0);   // LED<br>
            analogWrite(LED_AZ, 0);   // LED <br>
            analogWrite(LED_VM, 0);   // LED VM<br>
            delay(timer);<br>
            // Cor 2 - VERMELHO<br>
            analogWrite(LED_VM, 255);   // LED VM <br>
            analogWrite(LED_AZ, 0);   // LED AZ<br>
            analogWrite(LED_VD, 0);   // LED VD<br>
            delay(timer);<br>
            // Cor 3 - VERDE<br>
            analogWrite(LED_VM, 0);   // LED VM <br>
            analogWrite(LED_AZ, 0);     // LED AZ<br>
            analogWrite(LED_VD, 255);   // LED VD<br>
            delay(timer);<br>
            // Cor 4 - AZUL<br>
            analogWrite(LED_VM, 0);   // LED VM <br>
            analogWrite(LED_AZ, 255);     // LED AZ<br>
            analogWrite(LED_VD, 0);   // LED VD<br>
            delay(timer);<br>
            // Cor 5 - Amarelo<br>
            analogWrite(LED_VD, 255);   // LED VD <br>
            analogWrite(LED_VM, 255);   // LED VM <br>
            analogWrite(LED_AZ, 0);     // LED AZ<br>
            delay(timer);<br>
            // Cor 6 - Magenta<br>
            analogWrite(LED_VM, 255);   // LED VM <br>
            analogWrite(LED_AZ, 255);   // LED AZ<br>
            analogWrite(LED_VD, 0);   // LED VD<br>
            delay(timer);<br>
            // Cor 7 - Ciano<br>
            analogWrite(LED_VM, 0);   // LED VM <br>
            analogWrite(LED_AZ, 255);   // LED AZ<br>
            analogWrite(LED_VD, 255);   // LED VD<br>
            delay(timer);<br>
            // Cor 8 - BRANCO<br>
            analogWrite(LED_VM, 255);  // LED VM <br>
            analogWrite(LED_AZ, 255);   // LED AZ<br>
            analogWrite(LED_VD, 255);   // LED VD<br>
            delay(timer);<br>
            } <br>
            //_________________________________________________________<br>
            // THE END</p>
    </blockquote>
    <blockquote>
        <p><span class="animated">| <a href="labvad.php">Introdução</a> | <a href="servo.php">Próxima Lição</a> | <a href="img/LabVad_Guia.pdf">Baixar Guia do LabVad</a> |</span></p>
    </blockquote>

    <?php require_once 'app.include/footer.inc.php'; ?>
