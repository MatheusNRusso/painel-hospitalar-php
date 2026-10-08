<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Garante o autoload do Composer

use Dotenv\Dotenv;

// Carregar as variáveis de ambiente do arquivo .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();  

?>
