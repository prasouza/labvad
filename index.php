<?php
session_start();
$tela = '';
$acao = filter_input(INPUT_GET, 'acao', FILTER_SANITIZE_STRING);
if (empty($acao)) {
    $acao = filter_input(INPUT_POST, 'acao', FILTER_SANITIZE_STRING);
}
if ($acao == 'logar') {
    $txtEmail = isset($_POST['txtEmail']) ? $_POST['txtEmail'] : '';
    $txtSenha = isset($_POST['txtSenha']) ? $_POST['txtSenha'] : '';
    if (($txtEmail != '') && ($txtSenha != '')) {
        require_once 'app.ado/TConnection.class.php';
        $conn      = TConnection::open();
        $sql       = $conn->prepare("SELECT 
                                        pessoas.id, 
                                        pessoas.nome, 
                                        pessoas.email, 
                                        pessoas.tipo,
                                        pessoas.senha,
                                        pessoas.recuperar_senha 
                                     FROM 
                                        pessoas 
                                     WHERE
                                        (pessoas.email = :email)
                                     LIMIT 1 ");
        $sql->bindParam(':email', $txtEmail);
        //$sql->bindParam(':senha', $txtSenha);
        $sql->execute();
        $resultado = $sql->fetchObject();
        if ($resultado) {
            if (($resultado->email == $txtEmail) && (($resultado->senha == MD5($txtSenha)) || ($resultado->recuperar_senha == MD5($txtSenha)))) {

                $_SESSION['id_usuario']   = $resultado->id;
                $_SESSION['nome_usuario'] = $resultado->nome;
                $_SESSION['tipo']         = $resultado->tipo;
                $_SESSION['id_sessao']    = rand(0, 2000) . '00' . session_id();

                header('Location: labvad.php');
                exit;
            } 
            else {
                $tela = "Senha não confere!";
            }
        } 
        else {
            $tela = "Usuário não encontrado!";
        }
    }
}
else if ($acao == "cadastro") {
    $txtCadastroNome   = filter_input(INPUT_POST, 'txtCadastroNome', FILTER_SANITIZE_STRING);
    $txtCadastroEscola = filter_input(INPUT_POST, 'txtCadastroEscola', FILTER_SANITIZE_STRING);
    $txtCadastroEmail  = filter_input(INPUT_POST, 'txtCadastroEmail', FILTER_SANITIZE_STRING);
    if ((! empty($txtCadastroNome)) && (! empty($txtCadastroEscola)) && (! empty($txtCadastroEmail))) {
        try {
            require_once 'app.ado/TConnection.class.php';
            $conn = TConnection::open();
                        
            $novaSenha = rand(11998844, 78963214565);
            
            $sql  = $conn->prepare("SELECT pessoas.email FROM pessoas WHERE (pessoas.email = :email) LIMIT 1 ");
            $sql->bindParam(':email', $txtCadastroEmail);
            $sql->execute();		
            $resultado = $sql->fetchAll();	
            if (count($resultado) > 0) {
                $tela = "E-mail já utilizado! Para recuperar sua senha utilize o link Esqueceu a Senha?";
            }
            else {                

                $sql  = $conn->prepare("INSERT INTO pessoas (
                                            id, 
                                            nome,
                                            email,
                                            dt_cadastro,
                                            ativo,
                                            tipo,
                                            senha,
                                            escola
                                        )
                                        VALUES (
                                            NULL, 
                                            :nome,
                                            :email,
                                            NOW(),
                                            'S',
                                            'u',
                                            MD5(:senha),
                                            :escola
                                        ) ");
                $sql->bindParam(':nome', $txtCadastroNome);
                $sql->bindParam(':email', $txtCadastroEmail);
                $sql->bindParam(':senha', $novaSenha);
                $sql->bindParam(':escola', $txtCadastroEscola);
                $sql->execute();
                $iId = $conn->lastInsertId();
                if ($iId > 0) {
                    $conteudo = http_build_query(array(
                            'email' => $txtCadastroEmail,
                            'senha' => $novaSenha,
                            'escola' => $txtCadastroEscola,
                            'nome' => $txtCadastroNome
                     ));

                    $context = stream_context_create(array(
                            'http' => array(
                                    'method'  => 'POST',
                                    'content' => $conteudo
                            )
                    ));

                    $resultado = @file_get_contents('http://labvad.com/enviarusuario.php', null, $context, -1, 40000);
					$tela      = "Cadastro efetuado com sucesso. Verifique sua senha no e-mail cadastrado! Se não receber o e-mail em sua caixa de entrada, por favor, não deixe de verificar em seu Lixo Eletrônico ou Spam. Ele pode estar lá!";
                }
            }
        }
        catch (Exception $e) {
            $tela = "Cadastro não realizado! Tente novamente em instantes!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
        <title>LabVad</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="set/js/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" href="set/js/bootstrap/css/bootstrap-responsive.min.css" />
        <link rel="stylesheet" href="set/css/font-awesome/css/font-awesome.min.css" />
        <link rel="stylesheet" href="set/css/style.css" />
        <link href='http://fonts.googleapis.com/css?family=Economica:400,700,700italic' rel='stylesheet' type='text/css'>

    </head>
    <body>

        <!-- Home -->
        <div id="page1" class="page bgcolor center">
            <div class="container">
                <div class="row">
                    <div class="span12">
                        <div class="caption"> 

                            <?php
                            if (!empty($tela)) {
                                echo '<div class="alert alert-danger">' . $tela . '</div>';
                            }
                            ?>
                            <h1><img src="set/img/ufrj.gif"></h1>
                            <h1>Laboratório Virtual de Atividades Didáticas com Robótica - LabVad </h1>
                            <h1>
                                <p>Login
                                <div style="width: 570px">
                                    <form class="form-horizontal" role="form" name="formLogin" id="formLogin" method="post" action="?acao=logar">
                                        </p>
                                        </p>
                                        <div class="form-group">
                                            <label for="txtEmail" class="col-sm-2 control-label">E-mail:</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" name="txtEmail" id="txtEmail" placeholder="E-mail">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="txtSenha" class="col-sm-2 control-label">Senha:</label>
                                            <div class="col-sm-10">
                                                <input type="password" class="form-control" name="txtSenha" id="txtSenha" placeholder="Senha">

                                            </div>
                                            <button class="btn-theme pull-left">Acessar</button>
                                            <a id="lkRecuperar" href="#" title="Redefinir senha" style="font: 13px Arial; color: #FFF; padding-left: 20px">Esqueceu a Senha?</a><a id="lkCadastrarNovo" href="#page5" title="Cadastro de novo usuário" style="font: 13px Arial; color: #FFF; padding-left: 20px">Cadastro</a><a id="lkSaibaMais" href="#page3" title=" Sobre o Labvad" style="font: 13px Arial; color: #FFF; padding-left: 20px">LabVad</a>
                                            </h1>
                                        </div>
                                    </form>    
                                </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- /Home-->

            <!-- LabVad-->
            <div id="page3" class="page center">
                <div class="container">
                    <div class="row">
                        <div class="span12">
                            <h1 class=""><span class="center">Siga três passos para usar o LabVad</span></h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="span4">
                            <div class="well well-large pricing">
                                <hr>
                                <h2>Faça seu cadastro</h2>
                                <div class="roundimg"><img src="set/img/cadastro.png" alt="" />
                                    <hr>
                                </div>
                            </div>
                        </div>

                        <div class="span4">
                            <div class="well well-large pricing">
                                <hr>
                                <h2>Agende suas Aulas</h2>
                                <div class="roundimg"><img src="set/img/agenda.png" alt="" />
                                    <hr>
                                </div>
                            </div>
                        </div>

                        <div class="span4">
                            <div class="well well-large pricing">
                                <hr>
                                <h2>Execute Experimentos</h2>
                                <div class="roundimg"><img src="set/img/aula.png" alt="" /></div>
                                <hr>
                            </div></div>
                    </div>

                    <div class="row vspace50">
                        <div class="span12">
                           <a id="lkCadastrarNovo" href="#page5" title="Cadastro de novo usuário"/a> <h3 class="center">Solicite seu cadastro no formulário abaixo</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div id="window2" class="window">
                <div class="container">
                    <div class="row">
                        <div class="span12">
                        </div>
                    </div>
                </div>
            </div>
            <!-- /LabVad-->
            <!-- Cadastro -->
            <div id="page5" class="page">
                <div class="container">
                    <div class="row">
                        <div class="span12 center">
                            <h3><span><i class="icon-envelope-alt"></i>&nbsp;</span>Cadastro</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="span8 center">
                            <form name="formCadastro" method="post" id="formLogin" formCadastro="post" action="?acao=cadastro">
                                <div class="controls">
                                    <input id="ContactName" name="txtCadastroNome" type="text" placeholder="Nome" class="span8" required />
                                </div>
                                <div class="controls">
                                    <input id="ContactEscola" name="txtCadastroEscola" type="text" placeholder="Escola ou Empresa" class="span8" required />
                                </div>
                                <div class="controls">
                                    <input id="ContactEmail" name="txtCadastroEmail" type="text" placeholder="E-mail" class="span8" required />
                                </div>

                                <button class="btn-theme pull-left">Enviar</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <!-- /Cadastro -->

            <footer>
                <a href=http://www.nce.ufrj.br/ginape/paginas/index.html target="_blank" >&copy;&nbsp;Copyright 2014/2015 - GINAPE - NCE/UFRJ<br /><br /></a>
                <a href="https://www.facebook.com/ProjetoUcaNaCuca" target="_blank"><img src="set/img/socials/32/Facebook.png" alt="" /></a>
                <a href="#"><img src="set/img/socials/32/Twitter.png" alt="" /></a>
                <a href="#"><img src="set/img/socials/32/Linkedin.png" alt="" /></a>
                <a href="#"><img src="set/img/socials/32/Pinterest.png" alt="" /></a>
                <a href="#"><img src="set/img/socials/32/Google+.png" alt="" /></a>
                <a href="https://www.youtube.com/user/projetoucanacuca" target="_blank""<img src="set/img/socials/32/Youtube.png" alt="" /></a>
            </footer>

            <a id="scrollToTop"><i class="icon-caret-up"></i></a>


            <script src="set/js/jquery-1.10.0.min.js"></script>
            <script src="set/js/bootstrap/js/bootstrap.min.js"></script>
            <script src="set/js/script.js"></script>
            <script>
                $(document).ready(function () {
                    $("#lkRecuperar").on("click", function () {
                        var meuemail = $("#txtEmail").val();
                        if (meuemail === "") {
                            alert("Informe o email!");
                            $("#txtEmail").focus();
                        }
                        else {
                            $.post('recuperar_senha.php',
                                    {
                                        email: meuemail
                                    },
                            function (retorno) {
                                alert(retorno)
                            }
                            );
                        }
                    });

                    localStorage.setItem("nomeCodigo", "");
                    localStorage.setItem("codigo", "");
                    localStorage.setItem("comparaCodigo", "");
                    localStorage.setItem("alterado", "");
                    localStorage.setItem("idCodigo", "");
                });
            </script>
    </body>
</html>