<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\MedicoController;

$medicoController = new MedicoController();

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    $_SESSION['erro'] = "⚠️ ID do médico inválido ou não informado.";
    header("Location: ?rota=listarMedicos");
    exit;
}

try {
    $resultado = $medicoController->deletar($id);
    $resposta = json_decode($resultado, true);

    if (isset($resposta['success']) && $resposta['success']) {
        $_SESSION['sucesso'] = $resposta['mensagem'] ?? "✅ Médico deletado com sucesso.";
    } else {
        $_SESSION['erro'] = $resposta['mensagem'] ?? "❌ Erro ao deletar médico.";
    }
} catch (Exception $e) {
    $_SESSION['erro'] = "❌ Erro inesperado: " . $e->getMessage();
}

header("Location: ?rota=listarMedicos");
exit;
