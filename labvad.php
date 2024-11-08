<?php
$acaoLog = 'Acesso ao sistema';
require_once 'app.ado/TConnection.class.php';      
require_once 'app.include/verificasessao.inc.php';
$cabecalho = file('app.include/header.inc.php');

$conteudo = implode('', $cabecalho);
$conteudo = str_replace('{TITULO}', 'Laboratório', $conteudo);
echo $conteudo;
?>

<title>Introdução</title>

<body>
    <blockquote>
        <h1>Introdução</h1>
        <h2>Bem vindo ao Laboratório de Atividades Didáticas  para Robótica Educativa, baseado no projeto de hardware livre Arduino. O LabVad é de acesso livre, multiplataforma e não necessita  de extensões ou plugins para ser executado no navegador WEB (browser) do seu computador.</h2>
    </blockquote>
    <blockquote>
        <h1>Como utilizar o LabVad?</h1>
        <h2>Basta agendar um horário para executar seus experimentos. Preparamos algumas lições para você aproveitar ao máximo os recursos do LabVad.	Visite	nossa	seção	de Ajuda para entender o funcionamento dos dispositivos eletromecânicos e eletroeletrônicos do LabVad.</h2>
    </blockquote>
       <div class="footer-meta">
        <div class="container">
            <div class="row">
            </div>
        </div>
    </div>
	
	<script>
		/*var tour = new Tour({
		  steps: [
		  {
			element: "#lkPadraoAgendamento",
			title: "Agendamento",
			content: "Agende um horário para executar seus Experimentos",
			placement: "bottom"
		  },
		  {
			element: "#lkPadraoExperimento",
			title: "Experimentos",
			content: "Veja a transmissão dos seus Experimentos ao vivo",
			placement: "bottom"
		  },
		  {
			element: "#lkPadraoAjuda",
			title: "Ajuda",
			content: "Aprenda a programar Arduino, no LabVad, clicando em Ajuda",
			placement: "bottom"
		  },
		  {
			element: "#lkPadraoConfiguracao",
			title: "Configurações",
			content: "Em Configurações, altere seu perfil e senha",
			placement: "bottom"
		  },
		  {
			element: "#conteudo",
			title: "Navegação",
			content: "Navegue por este tutorial para explorar o ambiente do LabVad",
			placement: "top"
		  }
		  
		]});

		// Initialize the tour
		tour.init();

		// Start the tour
		tour.start();*/
	</script>
	
    <?php require_once 'app.include/footer.inc.php'; ?>