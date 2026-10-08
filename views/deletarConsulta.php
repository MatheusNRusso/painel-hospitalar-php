<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\ConsultaController;

$consultaController = new ConsultaController();

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    $_SESSION['erro'] = "⚠️ ID da consulta inválido ou não informado.";
    header("Location: ?rota=listarConsultas");
    exit;
}

try {
    $resultado = $consultaController->deletar($id);
    $resposta = json_decode($resultado, true);

    if (isset($resposta['success']) && $resposta['success']) {
        $_SESSION['sucesso'] = $resposta['mensagem'] ?? "✅ Consulta deletada com sucesso.";
    } else {
        $_SESSION['erro'] = $resposta['mensagem'] ?? "❌ Erro ao deletar consulta.";
    }
} catch (Exception $e) {
    $_SESSION['erro'] = "❌ Erro inesperado: " . $e->getMessage();
}

header("Location: ?rota=listarConsultas");
exit;
