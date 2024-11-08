<?php set_time_limit(20); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="img/favicon.ico">

    <title>LabVad - {TITULO}</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/labvab.css" rel="stylesheet">
    
    <script src="js/jquery.min.js"></script>
    <script src="js/json.js"></script>
    <script src="js/bootstrap.file-input.js"></script>
    
    <link href='css/calendario/fullcalendar.css' rel='stylesheet' />
    <link href='css/calendario/fullcalendar.print.css' rel='stylesheet' media='print' />
    <script src='js/calendario/lib/moment.min.js'></script>
    <script src='js/calendario/fullcalendar.min.js'></script>
    <script src='js/calendario/lang-all.js'></script>
	
	
<link rel=" stylesheet" type="text/css" href="js/bootstrap-tour.min.css">
<script src="js/bootstrap-tour.min.js"></script>

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation" id="menu-navegacao-horizontal">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Navegação</span>
            <span class="icon-bar">Experimentos</span>
            <span class="icon-bar">Ajuda</span>
            <span class="icon-bar">Configurações</span>
            <span class="icon-bar">Sair</span>
          </button>
          <a href="labvad.php" title="Introdução ao LabVad" class="navbar-brand">LabVad</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active" id="lkPadraoAgendamento"><a href="agendamentos.php" title="Agende um horário para executar seus Experimentos">Agendamento</a></li>
            <li id="lkPadraoExperimento"><a href="laboratorio.php?acao=novo" title="Veja a transmissão dos seus Experimentos ao vivo" id="lkExperimento">Experimentos</a></li>
            <li id="lkPadraoAjuda"><a href="ajuda.php" title="Ajuda - Aprenda a programar no LabVad">Ajuda</a></li>
            <li id="lkPadraoConfiguracao"><a href="usuarios.php" title="Configurações do usuário e senha">Configurações</a></li>
            <li id="lkPadraoSair"><a href="logout.php" title="Sair do LabVad" id="logout">Sair</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
	

    <div class="container" id="conteudo">

      <div class="starter-template">