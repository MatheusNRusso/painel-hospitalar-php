<?php
//require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\LoginController;

$loginController = new LoginController();
$loginController->logout();
?>
