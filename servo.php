<?php
$acaoLog = 'Help - Servo Motor';
require_once 'app.ado/TConnection.class.php';
require_once 'app.include/verificasessao.inc.php';
$cabecalho = file('app.include/header.inc.php');

$conteudo = implode('', $cabecalho);
$conteudo = str_replace('{TITULO}', 'Laboratório', $conteudo);
echo $conteudo;
?>

<title>Servo Motor</title>

<body>
    <blockquote>
        <h1>Micro Servo Motor<img src="img/servomotor.png" width="246" height="175"></h1>
        <h1>O que é? O que faz?                    </h1>
        <p><strong>É uma máquina eletromecânica que apresenta movimento proporcional a um comando. </strong>Geralmente, recebem um sinal de controle, verificam a  posição atual e atuam no sistema indo para a posição desejada<strong>.</strong>    
</blockquote>
    <blockquote>
        <h1>Como funciona no LabVad?</h1>
        <p>Como podemos ver na figura abaixo, o servo motor do LabVad está conectado na entrada 7. </p>


        <p><img src="img/servo.png" width="540" height="340"></p>
    </blockquote>
    <blockquote>
        <p>O movimento do servo motor do LabVad vai de 0º a 180º. Para auxiliar a programação temos a imagem abaixo no LabVad.</p>
        <h2><img src="img/angulo.png" width="171" height="175"></h2>
        <h1>Veja um exemplo:</h1>
      <p><img src="img/dica.png" alt="" width="32" height="32">Dica: copie e cole o código na aba Experimentos!</p>
        <p>//*****************************************************<br>
            // S E R V O gira modulos de 45 graus                 *<br>
            // By S.Brandao                                       *<br>
            // Em: 21/07//2014 -                                  *<br>
            // Experiencia com o  Servo        *<br>
            //*****************************************************<br>
        #include &lt;Servo.h&gt;</p>
        <p>Servo Meu_servo;</p>
        <p>void setup() <br>
            { <br>
            Meu_servo.attach(7);      // Servo está conectado no I/O(7)<br>
            } </p>
        <p>void loop() <br>
            { <br>
            <br>
            Meu_servo.write(0);<br>
            delay(5000);  // tempo para o SERVO se posicionar.<br>
            <br>
            <br>
            // Posiciona o SERVO a 45 GRAUS da origem.<br>
            Meu_servo.write(45);<br>
            delay(5000);<br>
            <br>
            <br>
            // Posiciona o SERVO a 90 GRAUS da origem.<br>
            Meu_servo.write(90);<br>
            delay(5000);<br>
            <br>
            <br>
            // Posiciona o SERVO a 135 GRAUS da origem.<br>
            Meu_servo.write(135);<br>
            delay(5000);<br>
            <br>
            // Posiciona o SERVO a 180 GRAUS da origem.<br>
            Meu_servo.write(180);<br>
            delay(5000);<br>
            <br>
            // Posiciona o SERVO na origem.<br>
            Meu_servo.write(0);<br>
            delay(5000);<br>
            } </p>
    </blockquote><blockquote>
        <p><span class="animated">| <a href="labvad.php">Introdução</a> | <a href="rele.php">Próxima Lição</a> | <a href="img/LabVad_Guia.pdf">Baixar Guia do LabVad</a> |</span></p>
    </blockquote>

<?php require_once 'app.include/footer.inc.php'; ?>