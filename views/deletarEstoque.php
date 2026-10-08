<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\EstoqueController;

$estoqueController = new EstoqueController();

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    $_SESSION['erro'] = "⚠️ ID do item de estoque inválido ou não informado.";
    header("Location: ?rota=listarEstoque");
    exit;
}

try {
    $resultado = $estoqueController->deletarItem($id);
    $resposta = json_decode($resultado, true);

    if (isset($resposta['success']) && $resposta['success']) {
        $_SESSION['sucesso'] = $resposta['mensagem'] ?? "✅ Item de estoque deletado com sucesso.";
    } else {
        $_SESSION['erro'] = $resposta['mensagem'] ?? "❌ Erro ao deletar item do estoque.";
    }
} catch (Exception $e) {
    $_SESSION['erro'] = "❌ Erro inesperado: " . $e->getMessage();
}

header("Location: ?rota=listarEstoque");
exit;
// Fim do código