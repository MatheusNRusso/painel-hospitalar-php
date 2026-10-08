<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\LeitoController;

$leitoController = new LeitoController();

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    $_SESSION['erro'] = "⚠️ ID do leito inválido ou não informado.";
    header("Location: ?rota=listarLeito");
    exit;
}

try {
    $resultado = $leitoController->deletar($id);
    $resposta = json_decode($resultado, true);

    if (isset($resposta['success']) && $resposta['success']) {
        $_SESSION['sucesso'] = $resposta['mensagem'] ?? "✅ Leito deletado com sucesso.";
    } else {
        $_SESSION['erro'] = $resposta['mensagem'] ?? "❌ Erro ao deletar leito.";
    }
} catch (Exception $e) {
    $_SESSION['erro'] = "❌ Erro inesperado: " . $e->getMessage();
}

header("Location: ?rota=listarLeito");
exit;
