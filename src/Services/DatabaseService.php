<?php

namespace App\Services;

use PDO;
use PDOException;
use Exception;
use App\Config\Database;

class DatabaseService
{
    private $pdo;

    public function __construct()
    {
        // Conectar ao banco de dados usando as variáveis de ambiente
        $this->pdo = Database::getConnection();
       
    }

    /**
     * Cria o banco de dados e as tabelas necessárias.
     */
    public function criarBancoETabelas()
    {
        try {
            // Criar tabela de medicamentos (exemplo)
            $sql = "
                CREATE TABLE IF NOT EXISTS medicamentos (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    nome VARCHAR(255) NOT NULL,
                    tipo_id INT NOT NULL,
                    doses_por_caixa INT NOT NULL,
                    preco_por_caixa DECIMAL(10, 2) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ";

            $this->pdo->exec($sql);
        } catch (PDOException $e) {
            throw new Exception("Erro ao criar banco de dados e tabelas: " . $e->getMessage());
        }
    }

    /**
     * Verifica se a tabela de medicamentos já existe.
     */
    public function verificarTabelaMedicamentos()
    {
        try {
            $tables = $this->pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
            return in_array('medicamentos', $tables);
        } catch (PDOException $e) {
            throw new Exception("Erro ao verificar tabelas: " . $e->getMessage());
        }
    }
}