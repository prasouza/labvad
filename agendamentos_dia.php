<?php
session_start();
require_once 'app.ado/TConnection.class.php';
$acaoLog = 'Visualizando o agendamento do dia ';

if (isset($_GET['data'])) {
    $dtSelecionadaLog = filter_input(INPUT_GET, 'data', FILTER_SANITIZE_STRING);
    $dtSelecionadaLog = new DateTime($dtSelecionadaLog, new DateTimeZone('America/Sao_Paulo'));    
    $acaoLog .= $dtSelecionadaLog->format('d/m/Y');
    
    //Caso tenha ID é exclusão
    $iId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    if ($iId > 0) {
        try {
            $conn      = TConnection::open();
            $sql = $conn->prepare("SELECT hora_inicio FROM agendamentos WHERE id = :id AND fk_pessoa = :fk_pessoa LIMIT 1 ");
            $sql->bindParam(":id", $iId);
            $sql->bindParam(":fk_pessoa", $_SESSION['id_usuario']);
            $sql->execute();
            $resultado = $sql->fetchObject();
            if ($sql->rowCount() > 0) {
                $acaoLog = 'Excluíndo o agendamento de ' . $resultado->hora_inicio . ' na data ' . $dtSelecionadaLog->format('d/m/Y');
            }
        }
        catch (Exception $e) {
            
        }
    }
}
if (isset($_GET['data_hora'])) {
    $dtSelecionadaLog = filter_input(INPUT_GET, 'data_hora', FILTER_SANITIZE_STRING);
    $dtSelecionadaLog = new DateTime($dtSelecionadaLog, new DateTimeZone('America/Sao_Paulo'));
    $acaoLog = 'Agendado em ' . $dtSelecionadaLog->format('d/m/Y') . ' às ' .  $dtSelecionadaLog->format('H:i:s');
}

require_once 'app.include/verificasessao.inc.php';
require_once 'app.classe/TData.class.php';

$dataAgendamento = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d');
$iId             = isset($_GET['id']) ? $_GET['id'] : FALSE;

if ((isset($_SESSION['msg'])) && (!empty($_SESSION['msg']))) {
    $telaMensagem    = $_SESSION['msg'];
    $_SESSION['msg'] = FALSE;
}

if (isset($_GET['data_hora'])) {
    $dataAgendar     = $_GET['data_hora'];
    $dataHora        = new DateTime($dataAgendar, new DateTimeZone('America/Sao_Paulo'));
    $horaSelecionada = $dataHora->format('H:i:s');
    $horaCalculo     = $dataHora->format('H') + 1;
    $horaFim         = $dataHora->format($horaCalculo . ':i:s');
    $dataSelecionada = $dataHora->format('Y-m-d');
    $dataAgendamento = $dataSelecionada;

    try {
        $conn      = TConnection::open();
        //Verificando se o horário esta disponivel
        $sql       = $conn->prepare("SELECT COUNT(id) AS total FROM agendamentos WHERE (dt_agendamento = :dt_agendamento) AND (hora_inicio = :hora_inicio) ");
        $sql->bindParam(':dt_agendamento', $dataSelecionada);
        $sql->bindParam(':hora_inicio', $horaSelecionada);
        $sql->execute();
        $resultado = $sql->fetchObject();
        if ($resultado->total > 0) {
            $telaMensagem = "Esse horário já foi agendado por outro usuário!";
        } else {

            $sql             = $conn->prepare("INSERT INTO agendamentos (
                                        fk_laboratorio,
                                        fk_pessoa,
                                        dt_agendamento,
                                        hora_inicio, 
                                        hora_fim,
                                        dt_cadastro
                                    ) 
                                    VALUES (
                                        :fk_laboratorio,
                                        :fk_pessoa,
                                        :dt_agendamento,
                                        :hora_inicio,
                                        :hora_fim,
                                        CURRENT_DATE()  
                                    ) ");
            $idLaboratorio   = 1;
            $sql->bindParam(":fk_laboratorio", $idLaboratorio);
            $sql->bindParam(":fk_pessoa", $_SESSION['id_usuario']);
            $sql->bindParam(":dt_agendamento", $dataSelecionada);
            $sql->bindParam(":hora_inicio", $horaSelecionada);
            $sql->bindParam(":hora_fim", $horaFim);
            $sql->execute();
            $_SESSION['msg'] = 'Agendamento marcado com sucesso!';
            header("Location: agendamentos_dia.php?data={$dataSelecionada}");
            exit;
        }
    } 
    catch (Exception $e) {
        echo $e->getMessage();
    }
}

if ($dataAgendamento) {

    try {
        $_SESSION['dataSelecionadaAgenda'] = $dataAgendamento;

        $conn = TConnection::open();
        //Exclusão de agendamento
        if ($iId > 0) {
            $sql = $conn->prepare("DELETE FROM agendamentos WHERE id = :id AND fk_pessoa = :fk_pessoa LIMIT 1 ");
            $sql->bindParam(":id", $iId);
            $sql->bindParam(":fk_pessoa", $_SESSION['id_usuario']);
            $sql->execute();
        }

        $sql        = $conn->prepare("SELECT 
                                agendamentos.id,
                                agendamentos.hora_inicio, 
                                agendamentos.hora_fim,
                                pessoas.nome,
                                fk_pessoa
                               FROM 
                                agendamentos
                               LEFT OUTER JOIN pessoas ON (pessoas.id = agendamentos.fk_pessoa)
                               WHERE 
                                agendamentos.dt_agendamento = :dt_agendamento 
                               ORDER BY 
                                agendamentos.hora_inicio ");
        $sql->bindParam(":dt_agendamento", $dataAgendamento);
        $sql->execute();
        $vRetorno   = '';
        $sSeparador = '';
        //formato padrão start: '2014-08-16T16:00:00'
        while ($resultado  = $sql->fetchObject()) {
            $iId = 0;
            if ($resultado->fk_pessoa == $_SESSION['id_usuario']) {
                $iId = $resultado->id;
            }

            $vRetorno .= $sSeparador .
                    "{
                            title: '{$resultado->nome}',
                            id: {$iId},    
                            start: '{$dataAgendamento}T{$resultado->hora_inicio}',
                            end: '{$dataAgendamento}T{$resultado->hora_fim}'                            
                          }";
            $sSeparador = ',';
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

$cabecalho = file('app.include/header.inc.php');

$conteudo = implode('', $cabecalho);
$conteudo = str_replace('{TITULO}', 'Laboratório', $conteudo);
echo $conteudo;
?>


<h1>Agendamentos</h1>

<div class="well">Clique no horário desejado para fazer seu agendamento. Para cancelar um agendamento faça 
    o mesmo processo. Os horários com a cor clara estão vagos e os horários com a cor azul estão agendados.</div>

<div class="row">
    <div id="calendar"></div>
</div>


<div class="modal fade boxAlert">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Atenção!</h4>
            </div>
            <div class="modal-body">
                <p id="mensagem-local"><?php echo $telaMensagem; ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade boxDialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Atenção!</h4>
            </div>
            <div class="modal-body">
                <p id="mensagem-local">Deseja realmente excluir esse agendamento?</p>
            </div>
            <div class="modal-footer">
                <button type="button" id="btConfirmaExclusao" class="btn btn-primary">Sim</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Não</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        var currentLangCode = 'pt-br';
        var idexclusao = 0;

        function dataAtualFormatada() {
            var data = new Date();
            var dia = data.getDate();
            if (dia.toString().length == 1)
                dia = "0" + dia;
            var mes = data.getMonth() + 1;
            if (mes.toString().length == 1)
                mes = "0" + mes;
            var ano = data.getFullYear();

            var hora = data.getHours();
            if (hora.toString().length == 1)
                hora = "0" + hora;
            var minuto = data.getMinutes();
            if (minuto.toString().length == 1)
                minuto = "0" + minuto;
            var segundo = data.getSeconds();
            if (segundo.toString().length == 1)
                segundo = "0" + segundo;

            return ano + "-" + mes + "-" + dia + "T" + hora + ":00:00";
        }

        $('#calendar').fullCalendar({
            header: {
                left: '', //'today',
                center: 'title',
                right: '' //'agendaDay'
            },
            defaultView: 'agendaDay',
            defaultDate: '<?php echo $dataAgendamento; ?>',
            selectable: true,
            selectHelper: true,
            lang: 'pt-br',
            slotDuration: '00:60:00',
            dayClick: function (date, jsEvent, view) {
                selecionada = date.format();
                atual = dataAtualFormatada();
                if ((selecionada == atual) || (selecionada > atual))
                    document.location.href = "agendamentos_dia.php?data_hora=" + date.format();
            },
            eventClick: function (calEvent, jsEvent, view) {
                idexclusao = calEvent.id;
                if (idexclusao > 0) {
                    $(".boxDialog").modal('show');
                }
            },
            editable: false,
            eventLimit: true, // allow "more" link when too many events
            events: [
                    <?php echo $vRetorno; ?>
            ]
        });

        $("#btConfirmaExclusao").on("click", function () {
            document.location.href = 'agendamentos_dia.php?data=<?php echo $dataAgendamento; ?>&id=' + idexclusao;
            $(".boxDialog").modal('hide');
        });

        <?php
        if ((isset($telaMensagem)) && ($telaMensagem != '')) {
            echo "$('.boxAlert').modal('show');";
        }
        ?>
    });
</script>

<style>

    body {
        margin: 40px 10px;
        padding: 0;
        font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
        font-size: 14px;
    }

    #calendar {
        max-width: 900px;
        margin: 0 auto;
    }


</style>

<?php require_once 'app.include/footer.inc.php'; ?>