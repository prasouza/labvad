<?php
/*
 * classe TConnection
 * gerencia conexões com bancos de dados através de arquivos de configuração.
 */
final class TConnection
{
    /*
     * método __construct()
     * não existirão instâncias de TConnection, por isto estamos marcando-o como private
     */
    private function __construct() {}
    
    //print_r(PDO::getAvailableDrivers());
    
    /*
     * método open()
     * recebe o nome do banco de dados e instancia o objeto PDO correspondente
     */
    public static function open()
    {
       
        // lê as informações contidas no arquivo
        $user = isset($db['user']) ? $db['user'] : 'root';
        $pass = isset($db['pass']) ? $db['pass'] : '';
        $name = isset($db['name']) ? $db['name'] : 'labvad';
        $host = isset($db['host']) ? $db['host'] : 'localhost';
        $type = isset($db['type']) ? $db['type'] : 'mysql';
        $port = isset($db['port']) ? $db['port'] : 3306;
        
        // descobre qual o tipo (driver) de banco de dados a ser utilizado
        switch ($type)
        {
            case 'pgsql':
                $port = $port ? $port : '5432';
                $conn = new PDO("pgsql:dbname={$name}; user={$user}; password={$pass};
                        host=$host;port={$port}");
                break;
            case 'mysql':
                $port = $port ? $port : '3306';
                $conn = new PDO("mysql:host={$host};port={$port};dbname={$name}", $user, $pass);
                break;
            case 'sqlite':
                $conn = new PDO("sqlite:{$name}");
                break;
            case 'ibase':
                $conn = new PDO("firebird:dbname={$name}", $user, $pass);
                break;
            case 'oci8':
                $conn = new PDO("oci:dbname={$name}", $user, $pass);
                break;
            case 'mssql':
                $conn = new PDO("mssql:host={$host},1433;dbname={$name}", $user, $pass);
                break;
        }
        // define para que o PDO lance exceções na ocorrência de erros
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->exec("SET NAMES 'utf8'");
        $conn->exec('SET character_set_connection=utf8');
        $conn->exec('SET character_set_client=utf8');
        $conn->exec('SET character_set_results=utf8');
        // retorna o objeto instanciado.
        return $conn;
    }
}
?>
