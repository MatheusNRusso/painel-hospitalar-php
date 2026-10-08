<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php'; // Certifique-se que este caminho está correto

use App\Controllers\HuggingFaceController;

$controller = new HuggingFaceController();
echo "<pre>";
echo $controller->gerarRelatorio();
echo "</pre>";
