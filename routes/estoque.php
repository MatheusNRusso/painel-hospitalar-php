<?php

use App\Controllers\EstoqueController;

// Instancia o controlador
$estoqueController = new EstoqueController();

if ($rota === 'estoque/listar' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $estoqueController->listarItens();
    exit;
}

// Adicionar Item ao Estoque
if ($rota === 'estoque/adicionarItem' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json'); // Define o cabeçalho JSON
    try {
        $dados = json_decode(file_get_contents('php://input'), true);
        error_log("Dados recebidos: " . print_r($dados, true)); // Log dos dados recebidos
        $resultado = $estoqueController->adicionarItem($dados);
        //echo json_encode(["mensagem" => $resultado]);
    } catch (Exception $e) {
        http_response_code(500); // Define o código de status HTTP para erro
        echo json_encode(["erro" => "❌ Erro ao adicionar item ao estoque: " . $e->getMessage()]);
    }
    exit;
}

if ($rota === 'estoque/atualizarQuantidade' && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $dados = json_decode(file_get_contents('php://input'), true);
    $id = $_GET['id'] ?? null;
    if ($id) {
        $estoqueController->atualizarQuantidade($id, $dados);
    } else {
        echo json_encode(["erro" => "⚠️ ID do item ou nova quantidade não fornecidos!"]);
    }
    exit;
}

if ($rota === 'estoque/deletarItem' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $estoqueController->deletarItem($id);
    } else {
        echo json_encode(["erro" => "⚠️ ID do item não fornecido!"]);
    }
    exit;
}

if ($rota === 'estoque/gerar-relatorio' && $_SERVER['REQUEST_METHOD'] === 'GET') 
{
    header("Content-Type: application/json"); // Retorna JSON corretamente
    $estoqueController->gerarRelatorio();
    exit;
}

if ($rota == 'estoque/executar-relatorio' && $_SERVER['REQUEST_METHOD'] == 'GET')
{
    $relatorio = $estoqueController->executarScriptPython();
    echo "<pre>$relatorio</pre>";
    exit;

}




?>
