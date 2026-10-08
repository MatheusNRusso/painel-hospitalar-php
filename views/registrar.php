<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\LoginController;

$loginController = new LoginController();
$loginController->verificarLogin();

// Apenas o root pode acessar esta página
if (!$loginController->isRoot()) {
    $_SESSION['erro'] = "Você não tem permissão para acessar esta página.";
    header("Location: ?rota=dashboard");
    exit;
}

// Processa o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = htmlspecialchars($_POST['nome']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'];
    $tipo = $_POST['tipo']; //'usuario' ou 'admin'
    //$ativo = $_POST['ativo'];

    $resultado = $loginController->registrarUsuario($nome, $email, $senha, $tipo);

    if ($resultado) {
        echo $_SESSION['sucesso'] = "Usuário cadastrado com sucesso!";
        header("Location: ?rota=login");
        exit;
    } else {
        $_SESSION['erro'] = "Erro ao cadastrar usuário. Tente novamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="container">
        <div class="card p-4 shadow-lg" style="max-width: 450px; margin: auto;">
            <h2 class="text-center">Cadastro de Usuário</h2>

            <?php if (isset($_SESSION['erro'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']) ?></div>
                <?php unset($_SESSION['erro']); ?>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome Completo</label>
                    <input type="text" id="nome" name="nome" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" id="senha" name="senha" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo de Usuário</label>
                    <select id="tipo" name="tipo" class="form-select" required>
                        <option value="usuario">Usuário</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
            </form>

            <div class="text-center mt-3">
                <a href="?rota=login">Voltar ao Login</a>
            </div>
        </div>
    </div>
</body>
</html>
