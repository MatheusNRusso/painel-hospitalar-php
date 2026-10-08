<?php
namespace App\Config;

require_once __DIR__ . '/../../config/bootstrap.php';

use PDO;
use PDOException;
class Database 
{
    private static $pdo = null; 

    private function __construct() {} 

    public static function getConnection()
    {
        if (self::$pdo === null) 
        {
            try {
                // Verifica se as variáveis já foram carregadas
                if (!isset($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_CHARSET'])) 
                {
                    throw new \Exception("As variáveis de ambiente não estão carregadas. Verifique se bootstrap.php foi incluído corretamente.");
                }
                

                $host = $_ENV['DB_HOST'];
                $dbname = $_ENV['DB_NAME'];
                $username = $_ENV['DB_USER'];
                $password = $_ENV['DB_PASS'];
                $charset = $_ENV['DB_CHARSET'] ?? 'utf8';

                $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

                self::$pdo = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_TIMEOUT => 5, 
                ]);
            } 
            catch (PDOException $e) 
            {
                error_log("Erro de conexão com o banco de dados: " . $e->getMessage());
                throw new \Exception("Erro ao conectar ao banco de dados. Consulte os logs para mais detalhes.");
            }
        }
        return self::$pdo;
    }
}
