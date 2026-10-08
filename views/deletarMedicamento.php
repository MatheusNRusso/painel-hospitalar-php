<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\MedicamentoController;

// Sessão iniciada no controller, como informado
$medicamentoController = new MedicamentoController();

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    $_SESSION['erro'] = "⚠️ ID do medicamento inválido ou não informado.";
    header("Location: ?rota=listarMedicamentos");
    exit;
}

try {
    $resultado = $medicamentoController->deletar($id);
    $resposta = json_decode($resultado, true);

    if (isset($resposta['success']) && $resposta['success']) {
        $_SESSION['sucesso'] = $resposta['mensagem'] ?? "✅ Medicamento deletado com sucesso.";
    } else {
        $_SESSION['erro'] = $resposta['mensagem'] ?? "❌ Erro ao deletar medicamento.";
    }
} catch (Exception $e) {
    $_SESSION['erro'] = "❌ Erro inesperado: " . $e->getMessage();
}

header("Location: ?rota=listarMedicamentos");
exit;
