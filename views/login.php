<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\LoginController;

$loginController = new LoginController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'];

    if ($loginController->login($email, $senha)) {
        header("Location: ?rota=dashboard");
        exit;
    } else {
        $_SESSION['erro'] = "E-mail ou senha inválidos!";
        header("Location: ?rota=login");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login - MYIASYS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>
    <style>
        body {
            background: linear-gradient(to right, #f8f9fa, #e9ecef);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            max-width: 400px;
            margin: auto;
            position: relative;
        }
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header-bar {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin-bottom: 1rem;
        }
        .arc-logo {
            height: 80px;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .arc-text {
            font-size: 40px;
            font-weight: bold;
            color: #0d6efd;
        }
        .subtitle {
            font-size: 18px;
            margin-top: -0.5rem;
            font-weight: bold;
            color: #0d6efd;
        }
        .input-group-text {
            background-color: transparent;
            border-left: none;
        }
        .form-text a {
            text-decoration: none;
        }
        .form-text a:hover {
            text-decoration: underline;
        }
        .form-control:focus {
            box-shadow: none;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="container login-container">

        <!-- Cabeçalho com título e subtítulo -->
        <div class="header-bar">
            <div class="arc-logo">
                <span class="arc-text">MYAISYS</span>
            </div>
        </div>

        <!-- Card de login -->
        <div class="card p-4">
            <?php if (isset($_SESSION['erro'])): ?>
                <div class="alert alert-danger text-center">
                    <?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <div class="input-group">
                        <input type="email" id="email" name="email" class="form-control" placeholder="E-mail / nome de usuário" required>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="input-group">
                        <input type="password" id="senha" name="senha" class="form-control" placeholder="Senha" required>
                        <span class="input-group-text"><i class="fas fa-eye" id="toggleSenha" style="cursor:pointer;"></i></span>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Entrar</button>
                </div>
            </form>

            <div class="mt-4 text-center form-text">
                <a href="#">Esqueceu a senha?</a><br>
                <a href="#">Não tem conta?</a><br>
                <a href="#" class="text-decoration-underline">Abra sua conta</a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('toggleSenha').addEventListener('click', function () {
            const senha = document.getElementById('senha');
            const icone = this;
            if (senha.type === 'password') {
                senha.type = 'text';
                icone.classList.remove('fa-eye');
                icone.classList.add('fa-eye-slash');
            } else {
                senha.type = 'password';
                icone.classList.remove('fa-eye-slash');
                icone.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>
