<?php
namespace App\Models;

use App\Config\Database;
use PDO;
use PDOException;

class Login
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    /**
     * Autentica um usuário verificando e-mail e senha
     */
    public function autenticar($email, $senha)
    {
        try {
            $stmt = $this->conn->prepare("SELECT id, nome, senha, tipo, ativo FROM usuarios WHERE email = :email LIMIT 1");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($usuario && password_verify($senha, $usuario['senha'])) {
                unset($usuario['senha']); // Remove a senha do retorno por segurança
                return $usuario;
            }

            return false;
        } catch (PDOException $e) {
            error_log("Erro ao autenticar usuário: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica se o e-mail já está cadastrado no sistema
     */
    public function emailExiste($email)
    {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM usuarios WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Erro ao verificar e-mail existente: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cria um novo usuário no banco de dados
     */
    public function criarUsuario($nome, $email, $senha, $tipo = 'usuario')
    {
        try 
        {
            $stmt = $this->conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo, ativo) VALUES (:nome, :email, :senha, :tipo, 1)");
            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':senha', $senha, PDO::PARAM_STR);
            $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
            return $stmt->execute();
        } 
        catch (PDOException $e) 
        {
            error_log("Erro ao criar usuário: " . $e->getMessage());
            return false;
        }
    }
}
?>
