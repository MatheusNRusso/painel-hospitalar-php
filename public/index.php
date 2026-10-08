<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Carregar dependências
require_once __DIR__ . '/../vendor/autoload.php';

// Carregar o controlador de login
require_once __DIR__ . '/../src/Controllers/LoginController.php';

// Configuração do banco de dados
require_once __DIR__ . '/../src/Config/Database.php';

// Configuração do Roteamento
require_once __DIR__ . '/../src/Core/Router.php';

// Se o usuário estiver autenticado, carrega a página inicial
//require_once __DIR__ . '/pagina_inicial.php';

?>
