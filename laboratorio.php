<?php
$acaoLog = '';
require_once 'app.ado/TConnection.class.php';
require_once 'app.include/verificasessao.inc.php';

$msg = '';
function trataRetorno($msg) {
    $msg = implode(' ', $msg);
    if (stripos($msg, '*** ERROR') > 0) {
        pclose(popen("start /B abuild zerar.pde", "r"));
		$detalheErro = substr($msg, 0, stripos($msg, 'C:\Users'));
        return 'Erro ao compilar! Reveja seu arquivo Arduino!<br><b>Detalhe:</b> ' . $detalheErro;
    } else {
        return 'Compilado com sucesso! <br>Em alguns segundos seu Experimento será executado...';
    }
}

function isAjax(){
	return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
}

function placaDefault() {
    if (disponbilidade() == 0) {
        pclose(popen("start /B abuild zerar.pde", "r"));
    }
}

function disponbilidade() {
    try {
        $conn      = TConnection::open();
        $sql       = $conn->prepare("SELECT 
                                        agendamentos.fk_pessoa 
                                       FROM 
                                        agendamentos
                                       WHERE 
                                        (dt_agendamento = CURRENT_DATE())
                                        AND (CURRENT_TIME() BETWEEN hora_inicio AND hora_fim) ");
        $sql->execute();
        $resultado = $sql->fetchObject();
        //usuário agendado
        if ((isset($resultado->fk_pessoa)) && ($resultado->fk_pessoa == $_SESSION['id_usuario'])) {
            return 1;
        }
        //outro usuário agendado
        else if ((isset($resultado->fk_pessoa)) && ($resultado->fk_pessoa > 0)) {
            return 2;
        }
        //tempo livre
        else {
            return 0;
        }
    } catch (Exception $e) {
        return FALSE;
    }
}

//Verificando se tem a permissão de executar (se esta no horário)
$permissaoExecutar = disponbilidade();

function getFormEditar($telaId, $telaNomeCodigo, $telaCodigo, $acao) {
    placaDefault();

    $desabilita = ($acao == 'editar') ? ' disabled ' : '';

    return '<form id="formCodigo" name="formCodigo" method="post" action="laboratorio.php?acao=gravar">
                <input type="hidden" name="idCodigo" id="idCodigo" value="' . $telaId . '">
                <input type="text" maxlength="70" id="txtNomeCodigo" ' . $desabilita . ' name="txtNomeCodigo" value="' . $telaNomeCodigo . '" placeholder="Informe o nome do seu arquivo">
                <textarea id="txtCodigo" name="txtCodigo" class="abc" placeholder="Codifique aqui...">' . $telaCodigo . '</textarea>
                <!--<pre id="editorCodigo">' . $telaCodigo . '</pre>-->                
            </form>';
}

//Rotina de gravacao
if ((isset($_GET['acao'])) && ($_GET['acao'] == 'gravar')) {
    try {
        $conn          = TConnection::open();
        $iIdCodigo     = isset($_POST['idCodigo']) ? $_POST['idCodigo'] : 0;
        $iIdCodigo     = empty($iIdCodigo) ? 0 : $iIdCodigo;
        $txtNomeCodigo = isset($_POST['txtNomeCodigo']) ? $_POST['txtNomeCodigo'] : '';
        $txtCodigo     = isset($_POST['txtCodigo']) ? $_POST['txtCodigo'] : '';
        if ((is_numeric($iIdCodigo)) && ($iIdCodigo == 0)) {

            //verificando se existe algum código na conta do usuário com o mesmo nome;
            $sql       = $conn->prepare("SELECT COUNT(id) AS total FROM experimentos 
                                            WHERE ((fk_pessoa = :fk_pessoa) AND (nome = :nome)) ");
            $sql->bindParam(':fk_pessoa', $_SESSION['id_usuario']);
            $sql->bindParam(':nome', $txtNomeCodigo);
            $sql->execute();
            $resultado = $sql->fetchColumn();
            if ($resultado > 0) {
                $rJson   = array("id" => $iIdCodigo,
                                    "msg" => 'Nome de arquivo já está em uso por você!<br>Escolha um outro nome para continuar.',
                                    "erro" => 1);
                $meuJson = json_encode($rJson);
                echo $meuJson;
                exit;
            }

            $sql = $conn->prepare("INSERT INTO experimentos (
                                        nome,
                                        codigo,
                                        dt_envio,
                                        publico,
                                        fk_pessoa
                                    )
                                    VALUES (
                                        :nome,
                                        :codigo,
                                        NOW(),
                                        'N',
                                        :fk_pessoa
                                    ) ");
            $sql->bindParam(':nome', $txtNomeCodigo);
            $sql->bindParam(':codigo', $txtCodigo);
            $sql->bindParam(':fk_pessoa', $_SESSION['id_usuario']);
            $sql->execute();
            $iIdCodigo = $conn->lastInsertId();
            $rJson     = array('id' => $iIdCodigo,
                                'nome' => $txtNomeCodigo,
                                'msg' => 'Arquivo gravado com sucesso!',
                                'erro' => 0);
        } 
        else if ((is_numeric($iIdCodigo)) && ($iIdCodigo > 0)) {

            //Verificando se o nome já esta me uso
            $sql       = $conn->prepare("SELECT COUNT(id) AS total FROM experimentos 
                                            WHERE (fk_pessoa = :fk_pessoa) AND (nome = :nome) AND (id <> :id) ");
            $sql->bindParam(':fk_pessoa', $_SESSION['id_usuario']);
            $sql->bindParam(':nome', $txtNomeCodigo);
            $sql->bindParam(':id', $iIdCodigo);
            $sql->execute();
            $resultado = $sql->fetchColumn();
            if ($resultado > 0) {
                $rJson   = array("id" => $iIdCodigo,
                                    "msg" => 'Nome de arquivo já em uso por você!!<br>Escolha um outro nome para continuar.',
                                    "erro" => 1);
                $meuJson = json_encode($rJson);
                echo $meuJson;
                exit;
            }

            $sql   = $conn->prepare("UPDATE experimentos SET
                                        nome = :nome,
                                        codigo = :codigo
                                     WHERE
                                        (fk_pessoa = :fk_pessoa)
                                        AND (id = :id)
                                        AND (id NOT BETWEEN 1 AND 14) 
                                     LIMIT 1 ");
            $sql->bindParam(':id', $iIdCodigo);
            $sql->bindParam(':nome', $txtNomeCodigo);
            $sql->bindParam(':codigo', trim($txtCodigo));
            $sql->bindParam(':fk_pessoa', $_SESSION['id_usuario']);
            $sql->execute();
            $rJson = array("id" => $iIdCodigo,
                "nome" => $txtNomeCodigo,
                "msg" => 'Arquivo atualizado com sucesso!',
                "erro" => 0);
            
            $acaoLog = 'Gravando a alteração no experimento ' . $txtNomeCodigo;
            gravaLog($acaoLog);
        } 
        else {
            $rJson = array("id" => $iIdCodigo,
                "msg" => 'Dados não informado corretamente!!',
                "erro" => 1);
        }
    } 
    catch (Exception $ex) {
        $rJson = array("id" => 0,
                       "msg" => $ex->getMessage(),
                       "erro" => 2);
    }

    $meuJson = json_encode($rJson);
    echo $meuJson;
    exit;
}
//Executando o codigo
else if ((isset($_GET['acao'])) && ($_GET['acao'] == 'executar')) {

    try {
        $iIdCodigo      = isset($_POST['idCodigo']) ? $_POST['idCodigo'] : 0;
        $txtNomeCodigo  = isset($_POST['txtNomeCodigo']) ? $_POST['txtNomeCodigo'] : '';
        $txtCodigo      = isset($_POST['txtCodigo']) ? $_POST['txtCodigo'] : '';
        $tipoCodigo     = isset($_GET['tipo']) ? $_GET['tipo'] : '';
        $telaCodigo     = trim($txtCodigo);
        $telaId         = $iIdCodigo;
        $telaNomeCodigo = $txtNomeCodigo;
        $r              = '';

        if ($telaCodigo != '') {

            switch ($tipoCodigo) {
                case 'leds':
                    $acaoLog = 'Executando o experimento em LED';
                    gravaLog($acaoLog);
                    
                    $base     = 'void setup()';
                    $posicao1 = stripos($telaCodigo, $base);
                    $blocoA   = substr($telaCodigo, 0, $posicao1);

                    $posicao2 = stripos($telaCodigo, '{', $posicao1);
                    $blocoB   = substr($telaCodigo, $posicao1, $posicao2 - $posicao1 + 1);
                    $blocoC   = substr($telaCodigo, $posicao2 + 1);

                    $declaracaoParaBlocoA = "//inicio Decodificador do MUX \n" .
                            "int  PIN_MUX_0 = 2; \n" .
                            "int PIN_MUX_1 = 4; \n" .
                            "//fim Decodificador do MUX \n";
                    $blocoA_alterado      = $blocoA . $declaracaoParaBlocoA;

                    $declaracaoParaBlocoB = "//Habilita MUX - 01 - Conjunto de LEDs - LED0 a LED7 \n" .
                            "pinMode(13, OUTPUT); \n" .
                            "pinMode(12, OUTPUT); \n" .
                            "pinMode(11, OUTPUT); \n" .
                            "pinMode(10, OUTPUT); \n" .
                            "pinMode(9, OUTPUT); \n" .
                            "pinMode(8, OUTPUT); \n" .
                            "pinMode(7, OUTPUT); \n" .
                            "pinMode(6, OUTPUT); \n" .
                            "//Decodificador do MUX.********************************************* \n" .
                            "pinMode( PIN_MUX_0,OUTPUT);  // Pino_2 - Decodificador do Conjunto bit_0 \n" .
                            "pinMode(PIN_MUX_1,OUTPUT);  // Pino_4 - Decodificador do Conjunto bit_1 \n" .
                            "digitalWrite(13, LOW); \n" .
                            "digitalWrite(12, LOW); \n" .
                            "digitalWrite(11, LOW); \n" .
                            "digitalWrite(10, LOW); \n" .
                            "digitalWrite(9, LOW); \n" .
                            "digitalWrite(8, LOW); \n" .
                            "digitalWrite(7, LOW); \n" .
                            "digitalWrite(6, LOW); \n" .
                            "digitalWrite(PIN_MUX_0, HIGH);   // MUX_0 \n" .
                            "digitalWrite(PIN_MUX_1, HIGH);    // MUX_1 \n";
                    $blocoB_alterado      = $blocoB . $declaracaoParaBlocoB;

                    $codigoMontado = $blocoA_alterado . $blocoB_alterado . $blocoC;
                    break;


                case 'display_caracteres':
                    $acaoLog = 'Executando o experimento em Display de Caracteres';
                    gravaLog($acaoLog);
                    
                    $cabecalho = "#include \"WProgram.h\"  \n" .
                            "extern \"C\" void __cxa_pure_virtual() \n" .
                            "{  \n" .
                            " cli(); \n " .
                            " for (;;);  \n" .
                            "}  \n ";
                    $base      = 'void setup()';
                    $posicao1  = stripos($telaCodigo, $base);
                    $blocoA    = substr($telaCodigo, 0, $posicao1);

                    $posicao2 = stripos($telaCodigo, '{', $posicao1);
                    $blocoB   = substr($telaCodigo, $posicao1, $posicao2 - $posicao1 + 1);
                    $blocoC   = substr($telaCodigo, $posicao2 + 1);

                    $declaracaoParaBlocoA = "//inicio Decodificador do MUX \n" .
                            "int PIN_MUX_0 = 2; \n" .
                            "int PIN_MUX_1 = 4; \n" .
                            "//fim Decodificador do MUX \n";
                    $blocoA_alterado      = $blocoA . $declaracaoParaBlocoA;

                    $declaracaoParaBlocoB = "//Decodificador do MUX.********************************************* \n" .
                            "pinMode(PIN_MUX_0,OUTPUT);  // Pino_2 - Decodificador do Conjunto bit_0 \n" .
                            "pinMode(PIN_MUX_1,OUTPUT);  // Pino_4 - Decodificador do Conjunto bit_1 \n" .
                            "//Habilita MUX - 00 - Display de Caracteres \n" .
                            "digitalWrite(PIN_MUX_0, LOW);   // MUX_0 \n" .
                            "digitalWrite(PIN_MUX_1, LOW);    // MUX_1 \n";
                    $blocoB_alterado      = $blocoB . $declaracaoParaBlocoB;

                    $codigoMontado = $cabecalho . $blocoA_alterado . $blocoB_alterado . $blocoC;
                    break;

                case 'display_7':
                    $acaoLog = 'Executando o experimento em Display de 7 segmentos';
                    gravaLog($acaoLog);
                    
                    $base     = 'void setup()';
                    $posicao1 = stripos($telaCodigo, $base);
                    $blocoA   = substr($telaCodigo, 0, $posicao1);

                    $posicao2 = stripos($telaCodigo, '{', $posicao1);
                    $blocoB   = substr($telaCodigo, $posicao1, $posicao2 - $posicao1 + 1);
                    $blocoC   = substr($telaCodigo, $posicao2 + 1);

                    $declaracaoParaBlocoA = "//inicio Decodificador do MUX \n" .
                            "int PIN_MUX_0 = 2; \n" .
                            "int PIN_MUX_1 = 4; \n" .
                            "//fim Decodificador do MUX \n";
                    $blocoA_alterado      = $blocoA . $declaracaoParaBlocoA;

                    $declaracaoParaBlocoB = "//Decodificador do MUX.********************************************* \n" .
                            "pinMode(PIN_MUX_0,OUTPUT);  // Pino_2 - Decodificador do Conjunto bit_0 \n" .
                            "pinMode(PIN_MUX_1,OUTPUT);  // Pino_4 - Decodificador do Conjunto bit_1 \n" .
                            "//Habilita MUX 7 Segmentos \n" .
                            "digitalWrite(PIN_MUX_0, HIGH);   // MUX_0 \n" .
                            "digitalWrite(PIN_MUX_1, LOW);    // MUX_1 \n";
                    $blocoB_alterado      = $blocoB . $declaracaoParaBlocoB;

                    $codigoMontado = $blocoA_alterado . $blocoB_alterado . $blocoC;
                    break;

                case 'ledrgd':
                    $acaoLog = 'Executando o experimento em LED RGB';
                    gravaLog($acaoLog);
                    
                    $base     = 'void setup()';
                    $posicao1 = stripos($telaCodigo, $base);
                    $blocoA   = substr($telaCodigo, 0, $posicao1);

                    $posicao2 = stripos($telaCodigo, '{', $posicao1);
                    $blocoB   = substr($telaCodigo, $posicao1, $posicao2 - $posicao1 + 1);
                    $blocoC   = substr($telaCodigo, $posicao2 + 1);

                    $declaracaoParaBlocoA = "//inicio Decodificador do MUX \n" .
                            "int PIN_MUX_0 = 2; \n" .
                            "int PIN_MUX_1 = 4; \n" .
							"int saida_6 = 6;  \n".
							"int saida_7 = 7;  \n". 
                            "int saida_8 = 8; \n".
                            "//fim Decodificador do MUX \n";
                    $blocoA_alterado      = $blocoA . $declaracaoParaBlocoA;

                    $declaracaoParaBlocoB = "//Decodificador do MUX.********************************************* \n" .
                            "pinMode(PIN_MUX_0,OUTPUT);  // Pino_2 - Decodificador do Conjunto bit_0 \n" .
                            "pinMode(PIN_MUX_1,OUTPUT);  // Pino_4 - Decodificador do Conjunto bit_1 \n" .
                            "//Habilita MUX LED RGB. Servo e Relé \n" .
                            "digitalWrite(PIN_MUX_0, LOW);   // MUX_0 \n" .
                            "digitalWrite(PIN_MUX_1, HIGH);    // MUX_1\n" .
                            "pinMode(6,OUTPUT); \n " . 
                            "pinMode(7,OUTPUT);  \n" .  
                            "pinMode(8, OUTPUT);  \n"; 
                    $blocoB_alterado      = $blocoB . $declaracaoParaBlocoB;

                    $codigoMontado = $blocoA_alterado . $blocoB_alterado . $blocoC;
                    break;

                case 'servo':
                    $acaoLog = 'Executando o experimento em Servo';
                    gravaLog($acaoLog);
                    
                    $base     = 'void setup()';
                    $posicao1 = stripos($telaCodigo, $base);
                    $blocoA   = substr($telaCodigo, 0, $posicao1);

                    $posicao2 = stripos($telaCodigo, '{', $posicao1);
                    $blocoB   = substr($telaCodigo, $posicao1, $posicao2 - $posicao1 + 1);
                    $blocoC   = substr($telaCodigo, $posicao2 + 1);

                    $declaracaoParaBlocoA = "//inicio Decodificador do MUX \n" .
                            "int PIN_MUX_0 = 2; \n" .
                            "int PIN_MUX_1 = 4; \n" .
                            "//fim Decodificador do MUX \n".
							"int saida_6 = 6; \n" .
							"int saida_8 = 8; \n" .
							"int LED_VM = 9; \n" .
							"int LED_VD = 10; \n" .
							"int LED_AZ = 11; \n" ;
                    $blocoA_alterado      = $blocoA . $declaracaoParaBlocoA;

                    $declaracaoParaBlocoB = "//Decodificador do MUX.********************************************* \n" .
                            "pinMode(PIN_MUX_0,OUTPUT);  // Pino_2 - Decodificador do Conjunto bit_0 \n" .
                            "pinMode(PIN_MUX_1,OUTPUT);  // Pino_4 - Decodificador do Conjunto bit_1 \n" .
                            "//Habilita MUX LED RGB. Servo e Relé \n" .
                            "digitalWrite(PIN_MUX_0, LOW);   // MUX_0 \n" .
                            "digitalWrite(PIN_MUX_1, HIGH);    // MUX_1\n" .
                            "pinMode(6,OUTPUT); \n" . 
                            "pinMode(8,OUTPUT);  \n" . 
                            "pinMode(9, OUTPUT);  \n" . 
                            "pinMode(10, OUTPUT);  \n" .  
                            " pinMode(11, OUTPUT); \n";  
                    $blocoB_alterado      = $blocoB . $declaracaoParaBlocoB;

                    $codigoMontado = $blocoA_alterado . $blocoB_alterado . $blocoC;
                    break;

                       case 'motordc':
                    $acaoLog = 'Executando o experimento em Motor DC';
                    gravaLog($acaoLog);
                    
                    $base     = 'void setup()';
                    $posicao1 = stripos($telaCodigo, $base);
                    $blocoA   = substr($telaCodigo, 0, $posicao1);

                    $posicao2 = stripos($telaCodigo, '{', $posicao1);
                    $blocoB   = substr($telaCodigo, $posicao1, $posicao2 - $posicao1 + 1);
                    $blocoC   = substr($telaCodigo, $posicao2 + 1);

                    $declaracaoParaBlocoA = "//inicio Decodificador do MUX \n" .
                            "int PIN_MUX_0 = 2; \n" .
                            "int PIN_MUX_1 = 4; \n" .
                            "//fim Decodificador do MUX \n" .
							"int saida_7 = 7; \n" .
							"int saida_8 = 8; \n" .
							"int LED_VM = 9; \n" .
							"int LED_VD = 10; \n" .
							"int LED_AZ = 11; \n" ;
                    $blocoA_alterado      = $blocoA . $declaracaoParaBlocoA;

                    $declaracaoParaBlocoB = "//Decodificador do MUX.********************************************* \n" .
                            "pinMode(PIN_MUX_0,OUTPUT);  // Pino_2 - Decodificador do Conjunto bit_0 \n" .
                            "pinMode(PIN_MUX_1,OUTPUT);  // Pino_4 - Decodificador do Conjunto bit_1 \n" .
                            "//Habilita MUX LED RGB. Servo e Relé \n" .
                            "digitalWrite(PIN_MUX_0, LOW);   // MUX_0 \n" .
                            "digitalWrite(PIN_MUX_1, HIGH);    // MUX_1\n" .
                            "pinMode(7,OUTPUT);  \n" . 
                            "pinMode(8,OUTPUT); \n" . 
                            "pinMode(9, OUTPUT);  \n" . 
                            "pinMode(10, OUTPUT);  \n" . 
                            "pinMode(11, OUTPUT); \n" ;  
                    $blocoB_alterado      = $blocoB . $declaracaoParaBlocoB;

                    $codigoMontado = $blocoA_alterado . $blocoB_alterado . $blocoC;
                    break;
					
               case 'rele':
                    $acaoLog = 'Executando o experimento em Relé';
                    gravaLog($acaoLog);
                    
                    $base     = 'void setup()';
                    $posicao1 = stripos($telaCodigo, $base);
                    $blocoA   = substr($telaCodigo, 0, $posicao1);

                    $posicao2 = stripos($telaCodigo, '{', $posicao1);
                    $blocoB   = substr($telaCodigo, $posicao1, $posicao2 - $posicao1 + 1);
                    $blocoC   = substr($telaCodigo, $posicao2 + 1);

                    $declaracaoParaBlocoA = "//inicio Decodificador do MUX \n" .
                            "int PIN_MUX_0 = 2; \n" .
                            "int PIN_MUX_1 = 4; \n" .
                            "//fim Decodificador do MUX \n" .
							"int saida_6 = 6; \n" .
							"int saida_7 = 7; \n" .
							"int LED_VM = 9; \n" .
							"int LED_VD = 10; \n" .
							"int LED_AZ = 11; \n" ;
                    $blocoA_alterado      = $blocoA . $declaracaoParaBlocoA;

                    $declaracaoParaBlocoB = "//Decodificador do MUX.********************************************* \n" .
                            "pinMode(PIN_MUX_0,OUTPUT);  // Pino_2 - Decodificador do Conjunto bit_0 \n" .
                            "pinMode(PIN_MUX_1,OUTPUT);  // Pino_4 - Decodificador do Conjunto bit_1 \n" .
                            "//Habilita MUX LED RGB. Servo e Relé \n" .
                            "digitalWrite(PIN_MUX_0, LOW);   // MUX_0 \n" .
                            "digitalWrite(PIN_MUX_1, HIGH);    // MUX_1\n" .
                            "pinMode(6,OUTPUT); \n" . 
                            "pinMode(7,OUTPUT);  \n" . 
                            "pinMode(9, OUTPUT);  \n" . 
                            "pinMode(10, OUTPUT);  \n" . 
                            "pinMode(11, OUTPUT); \n";  
                    $blocoB_alterado      = $blocoB . $declaracaoParaBlocoB;

                    $codigoMontado = $blocoA_alterado . $blocoB_alterado . $blocoC;
                    break;
					case 'misc':
                    $acaoLog = 'Executando o experimento em Relé';
                    gravaLog($acaoLog);
                    
                    $base     = 'void setup()';
                    $posicao1 = stripos($telaCodigo, $base);
                    $blocoA   = substr($telaCodigo, 0, $posicao1);

                    $posicao2 = stripos($telaCodigo, '{', $posicao1);
                    $blocoB   = substr($telaCodigo, $posicao1, $posicao2 - $posicao1 + 1);
                    $blocoC   = substr($telaCodigo, $posicao2 + 1);

                    $declaracaoParaBlocoA = "//inicio Decodificador do MUX \n" .
                            "int PIN_MUX_0 = 2; \n" .
                            "int PIN_MUX_1 = 4; \n" .
                            "//fim Decodificador do MUX \n" ;
							
                    $blocoA_alterado      = $blocoA . $declaracaoParaBlocoA;

                    $declaracaoParaBlocoB = "//Decodificador do MUX.********************************************* \n" .
                            "pinMode(PIN_MUX_0,OUTPUT);  // Pino_2 - Decodificador do Conjunto bit_0 \n" .
                            "pinMode(PIN_MUX_1,OUTPUT);  // Pino_4 - Decodificador do Conjunto bit_1 \n" .
                            "//Habilita MUX LED RGB. Servo e Relé \n" .
                            "digitalWrite(PIN_MUX_0, LOW);   // MUX_0 \n" .
                            "digitalWrite(PIN_MUX_1, HIGH);    // MUX_1\n" ;
                           
                    $blocoB_alterado      = $blocoB . $declaracaoParaBlocoB;

                    $codigoMontado = $blocoA_alterado . $blocoB_alterado . $blocoC;
                    break;
                default:
                    $base     = 'void setup()';
                    $posicao1 = stripos($telaCodigo, $base);
                    $blocoA   = substr($telaCodigo, 0, $posicao1);

                    $posicao2 = stripos($telaCodigo, '{', $posicao1);
                    $blocoB   = substr($telaCodigo, $posicao1, $posicao2 - $posicao1 + 1);
                    $blocoC   = substr($telaCodigo, $posicao2 + 1);

                    $declaracaoParaBlocoA = "//inicio Decodificador do MUX \n" .
                            "int PIN_MUX_0 = 2; \n" .
                            "int PIN_MUX_1 = 4; \n" .
                            "//fim Decodificador do MUX \n";
                    $blocoA_alterado      = $blocoA . $declaracaoParaBlocoA;

                    $declaracaoParaBlocoB = "//Decodificador do MUX.********************************************* \n" .
                            "pinMode(PIN_MUX_0,OUTPUT);  // Pino_2 - Decodificador do Conjunto bit_0 \n" .
                            "pinMode(PIN_MUX_1,OUTPUT);  // Pino_4 - Decodificador do Conjunto bit_1 \n" .
                            "//Habilita MUX  \n" .
                           "digitalWrite(PIN_MUX_0, LOW);   // MUX_0 \n" .
                           "digitalWrite(PIN_MUX_1, HIGH);    // MUX_1\n" ;
                    $blocoB_alterado      = $blocoB . $declaracaoParaBlocoB;

                    $codigoMontado = $blocoA_alterado . $blocoB_alterado . $blocoC;
                    break;

					case 'rele':
                    $acaoLog = 'Executando o experimento em Relé';
                    gravaLog($acaoLog);
                    
                    $base     = 'void setup()';
                    $posicao1 = stripos($telaCodigo, $base);
                    $blocoA   = substr($telaCodigo, 0, $posicao1);

                    $posicao2 = stripos($telaCodigo, '{', $posicao1);
                    $blocoB   = substr($telaCodigo, $posicao1, $posicao2 - $posicao1 + 1);
                    $blocoC   = substr($telaCodigo, $posicao2 + 1);

                    $declaracaoParaBlocoA = "//inicio Decodificador do MUX \n" .
                            "int PIN_MUX_0 = 2; \n" .
                            "int PIN_MUX_1 = 4; \n" .
                            "//fim Decodificador do MUX \n" .
							"int saida_6 = 6; \n" .
							"int saida_7 = 7; \n" .
							"int LED_VM = 9; \n" .
							"int LED_VD = 10; \n" .
							"int LED_AZ = 11; \n" ;
                    $blocoA_alterado      = $blocoA . $declaracaoParaBlocoA;

                    $declaracaoParaBlocoB = "//Decodificador do MUX.********************************************* \n" .
                            "pinMode(PIN_MUX_0,OUTPUT);  // Pino_2 - Decodificador do Conjunto bit_0 \n" .
                            "pinMode(PIN_MUX_1,OUTPUT);  // Pino_4 - Decodificador do Conjunto bit_1 \n" .
                            "//Habilita MUX LED RGB. Servo e Relé \n" .
                            "digitalWrite(PIN_MUX_0, LOW);   // MUX_0 \n" .
                            "digitalWrite(PIN_MUX_1, HIGH);    // MUX_1\n" .
                            "pinMode(6,OUTPUT); \n" . 
                            "pinMode(7,OUTPUT);  \n" . 
                            "pinMode(9, OUTPUT);  \n" . 
                            "pinMode(10, OUTPUT);  \n" . 
                            "pinMode(11, OUTPUT); \n";  
                    $blocoB_alterado      = $blocoB . $declaracaoParaBlocoB;

                    $codigoMontado = $blocoA_alterado . $blocoB_alterado . $blocoC;
                    break;
            }

            $arquivo = fopen('dados.pde', 'w+');
            fwrite($arquivo, $codigoMontado);
            fclose($arquivo);

            //deixar somente no servidor
            exec('abuild dados.pde', $r);
            $r     = trataRetorno($r);
            $rJson = array("id" => $iIdCodigo,
                "msg" => 'Compilado!',
                "retorno" => $r,
                "erro" => 0);
        } 
        else {
            $rJson = array("id" => 0,
                            "msg" => 'Não existe arquivo Arduino para ser compilado!',
                            "retorno" => $r,
                            "erro" => 1);
        }
    } 
    catch (Exception $e) {
        exec('abuild zerar.pde', $r);
        $rJson = array("id" => 0,
                        "msg" => $ex->getMessage(),
                        "retorno" => $r,
                        "erro" => 2);
    }

    $meuJson = json_encode($rJson);
    echo $meuJson;
    exit;
}
//Retornar a listagem dos experimentos (chamado por ajax)
else if ((isset($_GET['acao'])) && ($_GET['acao'] == 'listagem')) {
    try {
        $conn      = TConnection::open();
        $sql       = $conn->prepare("SELECT
                                        experimentos.id,
                                        experimentos.nome,
                                        experimentos.codigo
                                    FROM
                                        experimentos
                                    WHERE
                                        (experimentos.fk_pessoa = :fk_pessoa) 
                                    ORDER BY
                                        experimentos.nome ");
        $sql->bindParam(':fk_pessoa', $_SESSION['id_usuario']);
        $sql->execute();
        $TGrid     = '';
        while ($resultado = $sql->fetchObject()) {
            $TGrid .= "<li class=\"list-group-item\">"
                    . "<a href=\"laboratorio.php?id={$resultado->id}&acao=editar\" class=\"lkDiretoCodigo\">{$resultado->nome}</a>"
                    . "<span class=\"lkExclusaoCodigo\"><a href=\"laboratorio.php?acao=exclusao&id={$resultado->id}\" title=\"Excluir o arquivo {$resultado->nome}\">&nbsp;</a></span>"
                    . "</li>";

        }
    } 
    catch (Exception $e) {
        echo $e->getMessage();
    }

    echo $TGrid;
    exit;
} 
else if ((isset($_GET['acao'])) && ($_GET['acao'] == 'exclusao')) {
    $iId = isset($_POST['idCodigo']) ? $_POST['idCodigo'] : 0;
    
    if ($iId == 0) {
		$iId = isset($_GET['id']) ? $_GET['id'] : 0;
		if ($iId == 0) {
			 header("Location: laboratorio.php?erro=id{$iId}");
		}
		//debug
		//echo "<pre>"; print_r($_POST); echo "</pre>";
		//echo "<pre>"; print_r($_GET); echo "</pre>";
		//exit;   
    }

    try {
        $conn        = TConnection::open();
        
        $sql         = $conn->prepare("SELECT nome FROM experimentos WHERE (id = :id) AND (fk_pessoa = :fk_pessoa) LIMIT 1 ");
        $sql->bindParam(':fk_pessoa', $_SESSION['id_usuario']);
        $sql->bindParam(':id', $iId);
        $sql->execute();
        if ($sql->rowCount() > 0) {
            $resultado = $sql->fetchObject();
            $acaoLog = 'Exclusão do experimento ' . $resultado->nome;
            gravaLog($acaoLog);
        }        
        
        $sql         = $conn->prepare("DELETE FROM experimentos WHERE (id = :id) AND (fk_pessoa = :fk_pessoa) LIMIT 1 ");
        $sql->bindParam(':fk_pessoa', $_SESSION['id_usuario']);
        $sql->bindParam(':id', $iId);
        $sql->execute();
        $telaRetorno = 'Arquivo excluído com sucesso!';

        placaDefault();
    } 
    catch (Exception $e) {
//        echo $e->getMessage();
        $telaRetorno = 'Erro ao excluir o arquivo!';
    }
	
	if (isAjax()) {
		echo $telaRetorno;
		exit;
	}
	else {
		header("Location: laboratorio.php?acao=novo");
		exit;
	}
} 
else if ((isset($_GET['acao'])) && ($_GET['acao'] == 'upload-arquivo')) {
    try {
    
        $permitidos = array('.ino');

        $nome_arquivo    = $_FILES['arquivo-codigo']['name'];
        $tamanho_arquivo = $_FILES['arquivo-codigo']['size'];

        $ext = strtolower(strrchr($nome_arquivo, "."));

        if (in_array($ext, $permitidos)) {
            $tamanho = round($tamanho_arquivo / 1024); //converte o tamanho para KB

            if ($tamanho < 2024) { //se imagem for até 2MB envia
                $nome_atual = md5(uniqid(time())) . $ext; //nome do arquivo
                $tmp        = $_FILES['arquivo-codigo']['tmp_name']; //caminho temporário da imagem

                if (move_uploaded_file($tmp, '_temp/' . $nome_atual)) {
                    $conteudoArquivo = fopen('_temp/' . $nome_atual, 'r');
                    $linha           = '';
                    while (!feof($conteudoArquivo)) {
                        $linha .= fgets($conteudoArquivo, 4069);
                    }
                    fclose($conteudoArquivo);
                    //echo $linha;

                    if ($linha != '') {

                        $conn         = TConnection::open();
                        $somente_nome = explode(".", $nome_arquivo);

                        //Verificando se já existe um código com esse nome
                        $sql       = $conn->prepare("SELECT COUNT(id) AS total FROM experimentos 
                                            WHERE ((fk_pessoa = :fk_pessoa) AND (nome = :nome)) ");
                        $sql->bindParam("fk_pessoa", $_SESSION['id_usuario']);
                        $sql->bindParam("nome", $somente_nome[0]);
                        $sql->execute();
                        $resultado = $sql->fetchObject();
                        if ((isset($resultado->total)) && ($resultado->total > 0)) {
                            $rJson = array("id" => 0,
                                            "msg" => 'Já existe um arquivo com esse nome (' . $somente_nome[0] . ')!',
                                            "nome" => '',
                                            "codigo" => '',
                                            "erro" => 1);
                        } 
                        else {
                            $sql     = $conn->prepare("INSERT INTO experimentos (
                                                        nome,
                                                        codigo,
                                                        dt_envio,
                                                        publico,
                                                        fk_pessoa
                                                      )
                                                      VALUES (
                                                        :nome,
                                                        :codigo,
                                                        NOW(),
                                                        'N',
                                                        :fk_pessoa
                                                     ) ");
                            $sql->bindParam(':nome', $somente_nome[0]);
                            $sql->bindParam(':codigo', $linha);
                            $sql->bindParam(':fk_pessoa', $_SESSION['id_usuario']);
                            $sql->execute();
                            $iIdNovo = $conn->lastInsertId();

                            $rJson = array("id" => $iIdNovo,
                                            "msg" => 'Arquivo enviado com sucesso!',
                                            "nome" => $somente_nome[0],
                                            "codigo" => $linha,
                                            "erro" => 0);
                        }
                    } 
                    else {
                        $rJson = array("id" => 0,
                                        "msg" => 'Arquivo não enviado!',
                                        "nome" => '',
                                        "codigo" => '',
                                        "erro" => 1);
                    }
                } 
                else {
                    $rJson = array("id" => 0,
                                    "msg" => 'Falha ao enviar o arquivo!',
                                    "nome" => '',
                                    "codigo" => '',
                                    "erro" => 2);
                }
            } 
            else {
                $rJson = array("id" => 0,
                                "msg" => 'O arquivo deve ter no máximo 2MB!',
                                "nome" => '',
                                "codigo" => '',
                                "erro" => 3);
            }
        } 
        else {
            $rJson = array("id" => 0,
                            "msg" => 'O arquivo deve ser do Arduino.',
                            "nome" => '',
                            "codigo" => '',
                            "erro" => 4);
        }

        $meuJson = json_encode($rJson);
        echo $meuJson;
        placaDefault();
        exit;
    }
    catch (Exception $e) {
        $rJson = array("id" => 0,
                            "msg" => 'Erro -> ' . $e->getMessage(),
                            "nome" => '',
                            "codigo" => '',
                            "erro" => 4);   
        
            $meuJson = json_encode($rJson);
        echo $meuJson;
        placaDefault();
        exit;
    }
} 
else if ((isset($_GET['acao'])) && ($_GET['acao'] == 'editar') && (isset($_GET['metodo'])) && ($_GET['metodo'] == 'ajax')) {

    //Montando grid de codigo
    try {
        $telaCodigo     = '';
        $iId            = 0;
        $telaId         = 0;
        $telaNomeCodigo = '';
        if ((isset($_GET['id'])) && (is_numeric($_GET['id']))) {
            $iId = $_GET['id'];
        }
        
        //se o ID for de 1 a 14 pode liberar para aparecer independente do FK_PESSOA, pois são os códigos de exemplo
        $sFiltro = " AND (experimentos.fk_pessoa = {$_SESSION['id_usuario']}) ";
        if ($iId > 0 && $iId < 15) {
            $sFiltro = '';
        }     
        
        $acao = (isset($_GET['acao'])) ? $_GET['acao'] : '';

        $conn      = TConnection::open();
        $sql       = $conn->prepare("SELECT
                                        experimentos.id,
                                        experimentos.nome,
                                        experimentos.codigo
                                    FROM
                                        experimentos
                                    WHERE
                                        (experimentos.id = :id) {$sFiltro}                   
                                    LIMIT 1 ");                                        
        $sql->bindParam(':id', $iId);
        $sql->execute();
        $resultado = $sql->fetchObject();
        if (isset($resultado->id)) {
            $telaCodigo     = trim($resultado->codigo);
            $telaId         = $resultado->id;
            $telaNomeCodigo = $resultado->nome;
            
            $acaoLog = 'Editando o experimento ' . $telaNomeCodigo;
            gravaLog($acaoLog);

            $rJson = array("codigo" => $telaCodigo,
                            "nome" => $telaNomeCodigo,
                            "id" => $telaId,
                            "erro" => 1);
        } 
        else {
            $rJson = array("codigo" => $telaCodigo,
                            "nome" => $telaNomeCodigo,
                            "id" => $telaId,
                            "erro" => 2);
        }

        echo json_encode($rJson);
    } 
    catch (Exception $e) {
        $rJson = array("codigo" => $telaCodigo,
                        "nome" => $telaNomeCodigo,
                        "id" => $telaId,
                        "erro" => 3);

        echo json_encode($rJson);
    }

    exit;
}

$cabecalho = file('app.include/header.inc.php');

$conteudo = implode('', $cabecalho);
$conteudo = str_replace('{TITULO}', 'Laboratório', $conteudo);
echo $conteudo;

//Montando grid de codigo
try {
    $TGrid          = '';
    $telaCodigo     = '';
    $iId            = 0;
    $telaId         = 0;
    $telaNomeCodigo = '';
    if ((isset($_GET['id'])) && (is_numeric($_GET['id']))) {
        $iId = $_GET['id'];
    }

    $acao = (isset($_GET['acao'])) ? $_GET['acao'] : '';

    $conn      = TConnection::open();
    $sql       = $conn->prepare("SELECT
                                    experimentos.id,
                                    experimentos.nome,
                                    experimentos.codigo
                                FROM
                                    experimentos
                                WHERE
                                    (experimentos.fk_pessoa = :fk_pessoa) 
                                    OR (experimentos.id > 0 AND experimentos.id < 15)
                                ORDER BY
                                    experimentos.nome ");
    $sql->bindParam(':fk_pessoa', $_SESSION['id_usuario']);
    $sql->execute();
    while ($resultado = $sql->fetchObject()) {
        
        //Não exibir na listagem os codigo de exmplo
        if ($resultado->id > 14) {
			$TGrid .= "<li class=\"list-group-item\">"
                    . "<a href=\"laboratorio.php?id={$resultado->id}&acao=editar\" class=\"lkDiretoCodigo\">{$resultado->nome}</a>"
                    . "<span class=\"lkExclusaoCodigo\"><a href=\"laboratorio.php?acao=exclusao&id={$resultado->id}\" title=\"Excluir o arquivo {$resultado->nome}\">&nbsp;</a></span>"
                    . "</li>";
        }
        
        if ($acao == 'novo') {
            $telaCodigo = '';
            placaDefault();
        } 
        else if ($iId == $resultado->id) {
            $telaCodigo     = trim($resultado->codigo);
            $telaId         = $resultado->id;
            $telaNomeCodigo = $resultado->nome;
        }
    }
    
    if ($acao == 'novo') {
        $acaoLog = 'Criando novo experimento';
        gravaLog($acaoLog);
    }
} 
catch (Exception $e) {
    
}
//fim Montando grid de codigo
?>

<?php echo $msg; ?>

<?php
$sLink = '';
if ($permissaoExecutar == 0) {
    $sCor = '<a href="agendamentos.php" title="Agende um horário para executar seus Experimentos!"></a>';
} 
else {
    $sCor = '<a href="agendamentos.php" title="Veja quem está agendado!"></a>';
}
?>
<h1><?php echo $sCor; ?>Experimentos</h1> 
<div class="row">
    <div class="col-md-12">

        <div id="video" class="col-md-8 .col-xs-8 panel">
            <video width="95%" height="95%" controls="" autoplay=""> <source src="http://146.164.3.24:8080/stream64p.ogg" type="video/ogg"></video>
        </div>
        <div id="lista-codigo"  class="col-md-4 .col-xs-4">
            <div class="panel panel-default">
                <div class="panel-heading">Meus Arquivos Arduino</div>
                <div class="panel-body box-codigo">

                    <ul class="list-group listagem-codigo">
                        <?php echo $TGrid; ?>
                    </ul>

                </div>
            </div>
        </div>

    </div>

    <div class="col-md-12 clearfix">
        <div class="panel panel-default box-visualiza-codigo clearfix">
            <div class="panel-heading clearfix" id="menuAcao">
                <ul class="nav nav-pills">
                    <li><a href="laboratorio.php?acao=novo" id="novo-codigo" class="btAcao"  title="Criar novo arquivo Arduino">Novo Arquivo</a></li>
                    <li><a href="#" id="codigo-exemplo" class="btAcao" title="Exemplos" data-toggle="dropdown">Exemplos<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="laboratorio.php?id=1&acao=editar" title="Clique para abrir" class="salvar-codigo-como" id="codigo-exemplo-led">Exemplo LED</a></li>
                            <li><a href="laboratorio.php?id=3&acao=editar" title="Clique para abrir"class="salvar-codigo-como" id="codigo-exemplo-display-caracteres">Exemplo Display LCD</a></li>                         
                            <li><a href="laboratorio.php?id=5&acao=editar" title="Clique para abrir"class="salvar-codigo-como" id="codigo-exemplo-display-7-segmentos">Exemplo Display de 7 Segmentos</a></li>                         
                            <li><a href="laboratorio.php?id=7&acao=editar" title="Clique para abrir"class="salvar-codigo-como" id="codigo-exemplo-led-rgb">Exemplo LED RGB</a></li>                         
                            <li><a href="laboratorio.php?id=9&acao=editar" title="Clique para abrir"class="salvar-codigo-como" id="codigo-exemplo-servo">Exemplo Servo Motor</a></li>                         
                            <li><a href="laboratorio.php?id=11&acao=editar" title="Clique para abrir"class="salvar-codigo-como" id="codigo-exemplo-motordc">Exemplo Motor DC</a></li>                         
                            <li><a href="laboratorio.php?id=13&acao=editar" title="Clique para abrir"class="salvar-codigo-como" id="codigo-exemplo-rele"> Exemplo Relé</a></li>                                                     
                            
                        </ul>   
                    </li>
                    <li><a href="#" id="salvar-codigo" class="btAcao" title="Salvar arquivos no LabVad" data-toggle="dropdown">Salvar<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#" id="salvar-codigo-como" class="salvar-codigo-como" title="Salvar arquivos no LabVad">Salvar</a></li>
                            <li><a href="#" id="salvar-codigo-escolher"class="salvar-codigo-como" title="Salvar arquivos no LabVad">Salvar como</a></li>                         
                        </ul>   
                    </li>
                    <?php if ($permissaoExecutar === 1) { ?>	
                    <li><a href="#" id="executar-codigo" class="btAcao dropdown-toggle" title="Escolha a categoria de dispositivo para Compilar e Executar seu Experimento!" data-toggle="dropdown">Executar<span class="caret"></span></a>						
                        <ul class="dropdown-menu">
							<li><div class="well" title="Escolha a categoria de dispositivo para Compilar e Executar seu Experimento!">Escolha um Dispositivo</div>
							<li><a href="laboratorio.php?acao=executar&tipo=leds" title="Clique para Executar" class="executar-codigo-como">Executar para LED</a></li>
                            <li><a href="laboratorio.php?acao=executar&tipo=display_caracteres" title="Clique para Executar" class="executar-codigo-como">Executar para Display LCD</a></li>
                            <li><a href="laboratorio.php?acao=executar&tipo=display_7" title="Clique para Executar" class="executar-codigo-como">Executar para Display de 7 Segmentos</a></li>
                            <li><a href="laboratorio.php?acao=executar&tipo=ledrgd" title="Clique para Executar" class="executar-codigo-como">Executar para LED RGB</a></li>
                            <li><a href="laboratorio.php?acao=executar&tipo=servo" title="Clique para Executar" class="executar-codigo-como">Executar para Servo Motor</a></li>
                            <li><a href="laboratorio.php?acao=executar&tipo=motordc" title="Clique para Executar" class="executar-codigo-como">Executar para Motor DC</a></li>
                            <li><a href="laboratorio.php?acao=executar&tipo=rele" title="Clique para Executar" class="executar-codigo-como">Executar para Relé</a></li>
							<li><a href="laboratorio.php?acao=executar&tipo=misc" title="Clique para Executar" class="executar-codigo-como">Executar para Miscelânea</a></li>
                        </ul>
                    </li>
                        <?php } else { ?>
                    <li><a href="agendamentos.php" id="executar-codigo" class="btAcao" title="Agende um horário para executar seus Experimentos">Agende um horário</a></li>
                        <?php } ?>
                    <li><a href="#" id="enviar-codigo-arduino" class="btAcao file-inputs"  title="Enviar arquivos do seu computador para Meus Arquivos Arduino">Upload Arquivo</a>
                        <form name="formUploadCodigo" id="formUploadCodigo" method="post" enctype="multipart/form-data" action="laboratorio.php?acao=upload-arquivo">
                            <input type="file" name="arquivo-codigo" id="arquivo-codigo">
                        </form>
                    </li>
                    <li><a href="laboratorio_download.php?acao=download&id=<?php echo $telaId; ?>" target="_blank" id="dowload-codigo" class="btAcao" title="Baixe arquivos para seu computador">Download Arquivo</a></li>
                    <!--<li><a href="laboratorio.php?acao=exclusao&id=<?php echo $telaId; ?>" id="excluir-codigo" class="btAcao" title="Exclusão do código">Excluir</a></li>-->
                </ul>

            </div>
                <?php
                if (($acao == 'novo') || ($acao == 'editar')) {
                    echo getFormEditar($telaId, $telaNomeCodigo, $telaCodigo, $acao);
                } else {
                    echo "<pre class=\"brush: cpp;\">{$telaCodigo}</pre>";
                }
                ?>
        </div>

    </div>
</div>
</div>

<div class="modal fade boxAlert">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Atenção!</h4>
            </div>
            <div class="modal-body">
                <p id="mensagem-local"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade boxPedido">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Atenção!</h4>
            </div>
            <div class="modal-body">
                <p id="mensagem-local">Informe o novo nome do arquivo: <input type="text" name="txtRenomear" id="txtRenomear" maxlength="70"></p>
            </div>
            <div class="modal-footer">
                <button type="button" id="btRenomear" class="btn btn-primary" data-dismiss="modal">Gravar</button>
                <button type="button" id="btRenomearFechar" class="btn btn-danger" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade boxPergunta">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Atenção!</h4>
            </div>
            <div class="modal-body">
                <p id="mensagem-local"></p>
            </div>
            <div class="modal-footer">
                <button type="button" id="btExclusaoConfirmar" class="btn btn-primary" data-dismiss="modal">Sim</button>
                <button type="button" id="btExclusaoFechar" class="btn btn-danger" data-dismiss="modal">Não</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade boxPerguntaConfirmacao">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Atenção!</h4>
            </div>
            <div class="modal-body">
                <p id="mensagem-local">Deseja realmente descartar a alteração no arquivo?</p>
            </div>  
            <div class="modal-footer">
                <button type="button" id="btConfirmaPergunta" class="btn btn-primary" data-dismiss="modal">Sim</button>
                <button type="button" id="btCancelaPergunta" class="btn btn-danger" data-dismiss="modal">Não</button>
            </div>	
        </div>
    </div>
</div>

<div id="msg-carregando">Carregando...</div>

<script>
    $(document).ready(function () {
        var editor = ace.edit("txtCodigo");
        editor.setTheme("ace/theme/crimson_editor");
        editor.getSession().setMode("ace/mode/c_cpp");
        editor.resize();
        editor.getSession().setUseWrapMode(false);
        
        var acaoPedida = "", urlChamada = "", idCodigoExclusao = 0, nomeCodigoExclusao = "", caminhoExclusao = "";        

        localStorage.setItem("comparaCodigo", localStorage.getItem("codigo"));

        function carregaLista() {
            $("ul.listagem-codigo").load('laboratorio.php?acao=listagem', function() {
				$("span.lkExclusaoCodigo").on('click',  'a', function (event) {
		
					myUrl            = $(this).attr('href');
					parametros       = myUrl.split("?")[1];
					caminhoExclusao  = myUrl;
					idCodigoExclusao = parametros.split("=")[2];

					if ((idCodigoExclusao === 0) || (idCodigoExclusao === "")) {
						$(".boxAlert #mensagem-local").html('Selecione o código para realizar a exclusão!');
						$('.boxAlert').modal('show');
						return false;
					}
					else {
						$(".boxPergunta #mensagem-local").html('Deseja realmente excluir esse código?');
						$(".boxPergunta").modal('show');
					}
					
					return false;
				});
			});
        }

        function limpaAreaCodigo() {
            $("#txtNomeCodigo, #idCodigo, #txtCodigo").val('');
            editor.setValue('');
        }

        function aguarde(exibir) {
            if (exibir)
                $("#msg-carregando").show();
            else
                $("#msg-carregando").hide(5000);
        }

        $('#salvar-codigo-escolher').on('click', function () {
            $(".boxPedido").modal('show');
            $("#txtRenomear").val($('#txtNomeCodigo').val());
            $("#btRenomearFechar").on('click', function () {
                $(".boxPedido").modal('hide');
                return false;
            });

            var nome = '';
            $("#btRenomear").on('click', function () {
                nome = $("#txtRenomear").val();
                $("#idCodigo").val(0);
                if (nome === '') {
                    return false;
                }
                else {
                    $('#txtNomeCodigo').val(nome);
                    $("#salvar-codigo-como").click();
                }
            });
        });

        $('#salvar-codigo-como, #salvar-codigo-copia').click(function () {
            caminho   = $('#formCodigo').attr('action');
            mIdCodigo = $('#idCodigo').val();
            mCodigo   = editor.getValue(); //$('#txtCodigo').val();
            mNomeCodigo = $('#txtNomeCodigo').val();

            if (mNomeCodigo === '') {
                $("#mensagem-local").html('Informe pelo menos o nome do seu arquivo!');
                $('.boxAlert').modal('show');
                return false;
            }

            if ($(this).attr('id') === 'salvar-codigo-copia') {
                mIdCodigo = 0;
                mNomeCodigo += ' Copia';
            }

            $.post(caminho,
                {
                    idCodigo: mIdCodigo,
                    txtCodigo: mCodigo,
                    txtNomeCodigo: mNomeCodigo
                },
                function (retorno) {
                    if (retorno.erro === 0) {
                        $("#idCodigo").val(retorno.id);
                        $('#txtNomeCodigo').val(retorno.nome);

                        $("#mensagem-local").html(retorno.msg);
                        $('.boxAlert').modal('show');

                        localStorage.setItem("alterado", "n");
                        localStorage.setItem("comparaCodigo", editor.getValue()); 

						//evitar que o bind se perca
						//if (mIdCodigo > 0) {
						carregaLista();
						//}
						//else {
						//	document.location.href = "laboratorio.php?id=" + retorno.id + "&acao=editar";
						//}
                    }
                    else if (retorno.erro === 1) {
                        $("#mensagem-local").html(retorno.msg);
                        $('.boxAlert').modal('show');
                        carregaLista();
                    }
                },
                'json'
            );

            return false;
        });

        $('.executar-codigo-como').click(function () {
            caminho     = $(this).attr('href');
            mIdCodigo   = $('#idCodigo').val();
            mCodigo     = editor.getValue(); //$('#txtCodigo').val();
            mNomeCodigo = $('#txtNomeCodigo').val();

            $.post(caminho,
                {
                    idCodigo: mIdCodigo,
                    txtCodigo: mCodigo,
                    txtNomeCodigo: mNomeCodigo
                },
                function (retorno) {
                    if (retorno.erro === 0) {
                        $("#mensagem-local").html(retorno.retorno);
                        $('.boxAlert').modal('show');
                    }
                    else {
                        $("#mensagem-local").html(retorno.msg);
                        $('.boxAlert').modal('show');
                    }
                },
                'json'
            );

            return false;
        });
		
		carregaLista();
   
        $(".boxPergunta #btExclusaoFechar").on('click', function () {
            $(".boxPergunta").modal('hide');
        });

        $(".boxPergunta #btExclusaoConfirmar").on('click', function () {
            caminho     = caminhoExclusao; //$("#excluir-codigo").attr('href');
            mIdCodigo   = idCodigoExclusao; //$('#idCodigo').val();
            //mNomeCodigo = $('#txtNomeCodigo').val();

            $.post(caminho,
                {
                    idCodigo: mIdCodigo//,txtNomeCodigo: mNomeCodigo
                },
                function (retorno) {
                    $(".boxPergunta").modal('hide');
                    $(".boxAlert #mensagem-local").html(retorno);
                    $('.boxAlert').modal('show');

					if (mIdCodigo == $('#idCodigo').val()) {
						localStorage.setItem("nomeCodigo", "");
						localStorage.setItem("codigo", "");
						localStorage.setItem("idCodigo", "");
						localStorage.setItem("comparaCodigo", "");
						localStorage.setItem("alterado", "n");
						document.location.href = 'laboratorio.php?acao=novo';
					}
					else {
						
						document.location.reload();						
					}
                    //carregaLista();
                    //limpaAreaCodigo();
                }
            );

            return false;
        });

        $('#enviar-codigo-arduino').on('click', function () {
            acaoPedida = "enviar-codigo";
            if (verificaSalvarCodigo()) {
                localStorage.setItem("nomeCodigo", "");
                localStorage.setItem("codigo", "");
                $('#arquivo-codigo').click();
            }
            
            return false;
        });

        $('#arquivo-codigo').on('change', function () {
            $('#formUploadCodigo').ajaxForm({
                dataType: 'json',
                success: function (retorno) {
                    $('#idCodigo').val(retorno.id);
                    $("#txtNomeCodigo").val(retorno.nome);
                    editor.setValue(retorno.codigo); //$("#txtCodigo").val(retorno.codigo);

                    $("#dowload-codigo").attr('href', 'laboratorio_download.php?acao=download&id=' + retorno.id);
                    $("#exclusao-codigo").attr('href', 'laboratorio.php?acao=exclusao&id=' + retorno.id);

                    $("#mensagem-local").html(retorno.msg);
                    $('.boxAlert').modal('show');

                    carregaLista();
                }
            }).submit();
        });

        $('#dowload-codigo').on('click', function () {
            var urlDownload = $(this).attr('href');
            var parametros  = urlDownload.split('?')[1];
            var id          = parametros.split('&')[1].split('=')[1];
            if (parseInt(id) === 0) {
                $(".boxAlert #mensagem-local").html('Selecione o arquivo para realizar o download!');
                $('.boxAlert').modal('show');
                return false;
            }
        });

        //retornando o código selecionado
        $('#lista-codigo').on('click', ' a.lkDiretoCodigo', function () {
           acaoPedida = "abre-codigo";
		   urlChamada = $(this).attr('href') + '&metodo=ajax';
            if (verificaSalvarCodigo()) {
                localStorage.setItem("nomeCodigo", "");
                localStorage.setItem("codigo", "");
                localStorage.setItem("alterado", "");
                $("#btConfirmaPergunta").click();
            }
            
            return false;
        });

        var nav = $('#menuAcao');
        $(window).scroll(function () {
            if ($(this).scrollTop() > 550) {
                nav.addClass("menuFixo");
                verificaMenu();
            }
            else {
                nav.removeClass("menuFixo");
            }
        });

        verificaMenu();
        window.onresize = verificaMenu;

        function verificaMenu() {
            var tela = $("#formCodigo").width();
            if (tela < 1000) {
                $("div.menuFixo").width(tela - 30);
            }
            else if (tela > 1000) {
                $("div.menuFixo").width(1061);
            }
        }
        
        $("#btConfirmaPergunta").on("click", function() {
            localStorage.setItem("nomeCodigo", "");
            localStorage.setItem("codigo", "");
            
            if (acaoPedida === "novo") {    
                localStorage.setItem("nomeCodigo", "");
                localStorage.setItem("codigo", "");
                localStorage.setItem("idCodigo", "");
                localStorage.setItem("comparaCodigo", "");
                localStorage.setItem("alterado", "n");
                $(".boxEsp").modal('show');    
                document.location.href = $("#novo-codigo").attr('href');
            }
            else if (acaoPedida === "enviar-codigo") {
                localStorage.setItem("nomeCodigo", "");
                localStorage.setItem("codigo", "");
                localStorage.setItem("alterado", "");
                $('#arquivo-codigo').click();
            }
            else if (acaoPedida === "logout") {
                document.location.href = $("#logout").attr('href');
            }
            else if (acaoPedida === "abre-codigo") {
                var url = urlChamada; //$(this).attr('href') + '&metodo=ajax';
                aguarde(true);
                $.post(
                    url,
                    function (retorno) {
                        if (retorno.erro === 1) {
                            $("#idCodigo").val(retorno.id);
                            $("#txtNomeCodigo").val(retorno.nome).attr('disabled', 'disabled');
                            editor.setValue(retorno.codigo); //$("#txtCodigo").val(retorno.codigo);
							editor.clearSelection();

                            localStorage.setItem("alterado", "n");
                            localStorage.setItem("comparaCodigo", retorno.codigo);

                            $("#dowload-codigo").attr('href', 'laboratorio_download.php?acao=download&id=' + retorno.id);
                            $("#exclusao-codigo").attr('href', 'laboratorio.php?acao=exclusao&id=' + retorno.id);
                        }
                    },
                    'json'
                )
                .done(function () {

                })
                .fail(function () {

                })
                .always(function () {
                    aguarde(false);
                });
            }
        });
        
        /*
         * False = pergunta
         * True  = sem pergunta
         */
        function verificaSalvarCodigo() {
            var id       = $("#idCodigo").val();
            var nome     = $("#txtNomeCodigo").val();
            var codigo   = editor.getValue(); //$("#txtCodigo").val();
            var alterado = localStorage.getItem("alterado");
            var retorno  = true;
            if ((acaoPedida === "novo") || (acaoPedida === "enviar-codigo") || (acaoPedida === "logout") || (acaoPedida === "abre-codigo")) {         
                if ((alterado === "s") || ((id === "") && ((nome !== "") || (codigo !== "")))) {
                    $(".boxPerguntaConfirmacao .mensagem-local").html('Deseja realmente descartar as alterações no arquivo?');
                    $(".boxPerguntaConfirmacao").modal('show');
                    retorno = false;
                }  
                else {
                    retorno = true;
                }
            }            
            
            return retorno;
        }

        $("#novo-codigo").on("click", function () {
            acaoPedida = "novo";
            if (verificaSalvarCodigo()) {
                localStorage.setItem("nomeCodigo", "");
                localStorage.setItem("codigo", "");
                localStorage.setItem("idCodigo", "");
                localStorage.setItem("comparaCodigo", "");
                localStorage.setItem("alterado", "n");
                $(".boxEsp").modal('show');
            }
            else {                
                return false;
            }
        });

        $("#logout").on("click", function () {
            acaoPedida = "logout";
            if (verificaSalvarCodigo()) {
                $(".boxLogout").modal('show');
                return false;
            }
        });
        
        $("#btLogoutSim").on("click", function () {
            document.location.href = $("#logout").attr('href');
        });

        var lkSaida = "";

        $("#btConfirmarSaida").on("click", function () {
            if (lkSaida !== '') {
                document.location.href = lkSaida;
            }
        });

        if (editor.getValue() === "") {
            $("#txtNomeCodigo").val(localStorage.getItem("nomeCodigo"));
            editor.setValue(localStorage.getItem("codigo")); //$("#txtCodigo").val(localStorage.getItem("codigo"));
            $("#idCodigo").val(localStorage.getItem("idCodigo"));
            if ($("#idCodigo").val() > 0) {
                $("#txtNomeCodigo").attr('disabled', 'disabled');
            }
        }
        
        editor.getSession().on('change', function(e) {
            gravarTemp();
            localStorage.setItem("alterado", "s");
        });

        $("#menu-navegacao-horizontal a").on("click", function () {
            lkSaida = $(this).attr("href");
            gravarTemp();
            return true;
        });

        function gravarTemp() {
            var nomeCodigo = $("#txtNomeCodigo").val();
            var codigo     = editor.getValue(); //$("#txtCodigo").val();
            var idCodigo   = $("#idCodigo").val();
            localStorage.setItem("nomeCodigo", nomeCodigo);
            localStorage.setItem("codigo", codigo);
            localStorage.setItem("idCodigo", idCodigo);
        }
		
		$('#txtNomeCodigo').on('keypress', function (e) {
			var code = null;
			code = (e.keyCode ? e.keyCode : e.which);                
			return (code == 13) ? false : true;
	   });
	   
	   editor.clearSelection();
	   
	   localStorage.setItem("tour_current_step", 5);
	   
	   /*localStorage.setItem("tour_end", false);
	   
	   var tour2 = new Tour({
		  steps: [
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
		tour2.init();

		// Start the tour
		tour2.start();*/
    });
	
	//tratando o voltar
	$(document).unbind('keydown').bind('keydown', function (event) {
		var doPrevent = false;
		if (event.keyCode === 8) {
			var d = event.srcElement || event.target;
			if ((d.tagName.toUpperCase() === 'INPUT' && (d.type.toUpperCase() === 'TEXT' || d.type.toUpperCase() === 'PASSWORD' || d.type.toUpperCase() === 'FILE')) 
				 || d.tagName.toUpperCase() === 'TEXTAREA') {
				doPrevent = d.readOnly || d.disabled;
			}
			else {
				doPrevent = true;
			}
		}

		if (doPrevent) {
			event.preventDefault();
		}
	});
	//fim tratando o voltar
</script>

<style>
.ace_editor { height: 510px}
</style>

<script src="js/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>

<?php require_once 'app.include/footer.inc.php'; ?>