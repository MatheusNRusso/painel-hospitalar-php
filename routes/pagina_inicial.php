<?php
use App\Controllers\LoginController;

// Verifica se o usuário está autenticado antes de exibir o Dashboard
$loginController = new LoginController();
$loginController->verificarLogin();

// Carrega a View do Dashboard
require_once __DIR__ . '/../public/pagina_inicial.php';

?>
