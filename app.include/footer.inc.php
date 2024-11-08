<div class="modal fade boxLogout">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Atenção!</h4>
            </div>
            <div class="modal-body">
                <p id="mensagem-local">Tem certeza que deseja sair do LabVad?</p>
            </div>
            <div class="modal-footer">
                <button type="button" id="btLogoutSim" class="btn btn-primary" data-dismiss="modal">Sim</button>
                <button type="button" id="btLogoutNao" class="btn btn-danger" data-dismiss="modal">Não</button>
            </div>
        </div>
    </div>
</div>


</div>

<div class="modal fade boxEsp">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Atenção!</h4>
            </div>
            <div class="modal-body">
                <p id="mensagem-local">Preparando o ambiente de Experimentos...</p>
            </div>    
        </div>
    </div>
</div>

<script src="js/jquery.form.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
    var seguranca = false;
    $(document).ready(function () {        
        $("#logout").on("click", function () {
            var localAtual = document.location.href;
            if (document.location.href.indexOf('laboratorio.php') < 0) {
                $(".boxLogout").modal('show');                
            }
            return false;
        });
        $("#btLogoutSim").on("click", function () {
            seguranca = true;
            document.location.href = $("#logout").attr('href');
        });
        $("#btLogoutNao").on("click", function () {
            
        });

        $("#lkExperimento").on("click", function () {
            $(".boxEsp").modal('show');
        });
		

		var tour = new Tour({
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
		  },
		  {
			element: "#codigo-exemplo",
			title: "Exemplos",
			content: "Temos exemplos para cada tipo de Experimento. Escolha um deles e clique no botão Executar",
			placement: "top"
		  },
		  {
			element: "#executar-codigo",
			title: "Executar",
			content: "No botão Executar você deve escolher a categoria do Experimento. Depois seu programa será compilado executado ",
			placement: "top"
		  }
		  
		]});

		// Initialize the tour
		tour.init();

		// Start the tour
		tour.start();
    });
    
    window.onbeforeunload = function () {
        if (seguranca) {
            var codigo1, codigo2;
            codigo1   = localStorage.getItem("codigo");
            codigo2   = localStorage.getItem("comparaCodigo");
            alterado  = localStorage.getItem("alterado");
            seguranca = false;
            if ((codigo1 !== codigo2) || (alterado === "s")) {
                return 'Se você fechar essa janela irá perder todas as informações que não foram salvas!';
            }
        }
    }
</script>	

</body>
</html>
