<?php
namespace App\Controllers;

use App\Models\Login;

class LoginController
{
    private $loginModel;

    public function __construct()
    {
        $this->loginModel = new Login();
        $this->iniciarSessao(); // Garante que a sessão será iniciada sempre que o controlador for instanciado
    }

    /**
     * Inicia a sessão se ainda não estiver iniciada
     */
    private function iniciarSessao()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Realiza o login do usuário
     */
    public function login($email, $senha)
    {
        $usuario = $this->loginModel->autenticar($email, $senha);

        if ($usuario) {
            // Armazena as informações do usuário na sessão
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];
            $_SESSION['usuario_ativo'] = $usuario['ativo'];

            return true;
        }
        return false;
    }

    /**
     * Verifica se o usuário está logado
     * Se não estiver, redireciona para a página de login
     */
    public function verificarLogin()
    {
        if (!$this->estaLogado()) {
            header("Location: ?rota=login");
            exit;
        }
    }

    /**
     * Verifica se há um usuário logado
     * @return bool
     */
    public function estaLogado()
    {
        return isset($_SESSION['usuario_id']);
    }

    /**
     * Realiza o logout do usuário, destruindo a sessão
     */
    public function logout()
    {
        session_unset(); // Remove todos os dados da sessão
        session_destroy(); // Destroi a sessão

        header("Location: ?rota=login"); // Redireciona para a página de login
        exit;
    }
    public function isRoot()
    {
        return isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'root';
    }


    public function registrarUsuario($nome, $email, $senha, $tipo)
    {
        if (!$this->isRoot()) 
        {
            return false; // Somente o root pode cadastrar novos usuários
        }

        if ($this->loginModel->emailExiste($email)) {
            return false; // E-mail já cadastrado
        }

        $senhaHash = password_hash($senha, PASSWORD_BCRYPT);
        return $this->loginModel->criarUsuario($nome, $email, $senhaHash, $tipo);
    }

}
?>
