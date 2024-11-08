<?php
$acaoLog = 'Ajuda';
require_once 'app.ado/TConnection.class.php';
require_once 'app.include/verificasessao.inc.php';
$cabecalho = file('app.include/header.inc.php');
$conteudo  = implode('', $cabecalho);
$conteudo  = str_replace('{TITULO}', 'Laboratório', $conteudo);
echo $conteudo;
?>

<title>Ajuda</title>

<body>
            <h1>Ajuda</h1>
<div class="well">O LabVad possui, em seu hardware, diversos dispositivos de robótica. 
						Para programá-lo basta entender como os dispositivos estão conectados no Hardware. 
						</br>Veja nas seções abaixo, como cada dispositivo funciona, ou baixe o guia rápido do LabVad.         <a href="img/LabVad_Guia.pdf" title="Baixe o Guia do LabVad"><img src="img/pdf.png" width="32" height="32"></a></div>
<div class="blog-content">
    <div class="caption">
                        <h4>Programação do Sequencial de LED </h4>
                        <p><a href="led.php" title="Programação do do Sequencial de LED"><em>Leia mais</em></a></p>
  </div>
</div>
                <div class="overlay-wrapper">
                    <a href="led.php"><img src="img/led.png" alt="Image" title="Programação do do Sequencial de LED" class="overlay-image" data-overlaytext="LEDs" /></a>
                    <div class="overlay"></div>
                </div>
                <div class="blog-content">
                    <div class="caption">
                        <h6>&nbsp;</h6>
                        <h4>Programação do Display de LCD</h4>
                        <p><a href="caracteres.php" title="Programação do Display de LCD"><em>Leia mais</em></a></p>
                    </div>
                </div>
                <div class="overlay-wrapper">
                    <a href="caracteres.php"><img src="img/carac.png" alt="Image" title="Programação do Display de LCD" class="overlay-image" data-overlaytext="Display Caracteres" /></a>
                    <div class="overlay"></div>
                </div>
                <div class="blog-content">
                    <div class="caption">
                        <h6>&nbsp;</h6>
                        <h4>Programação do Display de 7 segmentos</h4>
                        <p><a href="sete.php" title="Programação do Display de 7 segmentos"><em>Leia mais</em></a></p>
                    </div>
                </div>
                <div class="overlay-wrapper">
                    <a href="sete.php"><img src="img/7.png" alt="Image" title="Programação do Display de 7 segmentos" class="overlay-image" data-overlaytext="7 Segmentos" /></a>
                    <div class="overlay"></div>
                </div>
                <div class="blog-content">
                    <div class="caption">
                        <p>&nbsp;</p>
                        <h4>Programação do LED RGB</h4>
                        <p><a href="rgb.php" title="Programação do LED RGB" ><em>Leia mais</em></p>
                        <h6>&nbsp;</h6>
                    </div>
                </div>
                <div class="overlay-wrapper">
                    <a href="rgb.php"><img src="img/rgb.png" alt="Image" title="Programação do LED RGB" class="overlay-image" data-overlaytext="RGB" /></a>
                    <div class="overlay"></div>
                </div>
                <div class="blog-content">
                    <div class="caption">
                        <h6>&nbsp;</h6>
                        <h4>Programação do Servo Motor</h4>
                        <p><a href="servo.php" title="Programação do Servo Motor"><em>Leia mais</em></a></p>
                    </div>
                </div>
                <div class="overlay-wrapper">
                    <a href="servo.php"><img src="img/servo.png" alt="Image" title="Programação do Servo Motor" class="overlay-image" data-overlaytext="Servo Motor" /></a>
                    <div class="overlay"></div>
                </div>
                <div class="blog-content">
                    <div class="caption">
                        <h6>&nbsp;</h6>
                        <h4>Programação do Relé e Motor DC</h4>
                        <p><a href="rele.php" title="Programação do Relé e Motor DC"><em>Leia mais</em></a></p>
                    </div>
                </div>
                <div class="overlay-wrapper">
                    <a href="rele.php"><img src="img/rele.png" alt="Image" title="Programação do Relé e Motor DC" class="overlay-image" data-overlaytext="RELÉ" /></a>
                    <div class="overlay"></div>
                </div>
           
<h5>&nbsp;</h5>
<div class="footer-meta">
  <div class="container">
          <div class="row">
            <h4><span class="animated">| <a href="labvad.php">Introdução</a> | <a href="led.php">Primeira Lição</a> | <a href="img/LabVad_Guia.pdf"> Baixar Guia do LabVad</a> |</span></h4>
              </div>
              </div>
              </div>
            </div>
        </div>
    </div>
 </div>
  
<?php require_once 'app.include/footer.inc.php'; ?>