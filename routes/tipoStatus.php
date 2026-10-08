<?php

use App\Controllers\TipoStatusController;

$tipoStatusController = new TipoStatusController();

// Listar TipoStatus
if ($rota === 'tipoStatus/listar' && $_SERVER['REQUEST_METHOD'] === 'GET') 
{
    $tipoStatusController->listarTodos();
    exit;
}

// Criar TipoStatus
if ($rota === 'tipoStatus/criar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = json_decode(file_get_contents('php://input'), true);
    $tipoStatusController->criar($dados);
    exit;
}

// Atualizar TipoStatus
if ($rota === 'tipoStatus/atualizar' && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $dados = json_decode(file_get_contents('php://input'), true);
    $id = $_GET['id'] ?? null;
    if ($id) {
        $tipoStatusController->atualizar($id, $dados);
    } else {
        echo json_encode(["erro" => "⚠️ ID do tipoStatus não fornecido!"]);
    }
    exit;
}

// Deletar TipoStatus
if ($rota === 'tipoStatus/deletar' && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $tipoStatusController->deletar($id);
    } else {
        echo json_encode(["erro" => "⚠️ ID do tipoStatus não fornecido!"]);
    }
    exit;
}

?>