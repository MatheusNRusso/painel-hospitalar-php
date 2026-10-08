<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\ReceitaController;

$receitaController = new ReceitaController();

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    $_SESSION['erro'] = "⚠️ ID da receita inválido ou não informado.";
    header("Location: ?rota=listarReceitas");
    exit;
}

try {
    $resultado = $receitaController->deletar($id);
    $resposta = json_decode($resultado, true);

    if (isset($resposta['success']) && $resposta['success']) {
        $_SESSION['sucesso'] = $resposta['mensagem'] ?? "✅ Receita deletada com sucesso.";
    } else {
        $_SESSION['erro'] = $resposta['mensagem'] ?? "❌ Erro ao deletar receita.";
    }
} catch (Exception $e) {
    $_SESSION['erro'] = "❌ Erro inesperado: " . $e->getMessage();
}

header("Location: ?rota=listarReceitas");
exit;
