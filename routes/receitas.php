<?php

use App\Controllers\ReceitaController;

$receitaController = new ReceitaController();

if ($rota === 'receita/listar' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $receitaController->listar();
    exit;
}
if ($rota === 'receita/criar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = json_decode(file_get_contents('php://input'), true);
    $receitaController->criar($dados);
    exit;
}
if ($rota === 'receita/buscar' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $receitaController->buscar($id);
    } else {
        echo json_encode(["erro" => "⚠️ ID da receita não fornecido!"]);
    }
    exit;
}
if ($rota === 'receita/atualizar' && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $dados = json_decode(file_get_contents('php://input'), true);
    $id = $_GET['id'] ?? null;
    if ($id) {
        $receitaController->atualizar($id, $dados);
    } else {
        echo json_encode(["erro" => "⚠️ ID da receita não fornecido!"]);
    }
    exit;
}
if ($rota === 'receita/deletar' && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $receitaController->deletar($id);
    } else {
        echo json_encode(["erro" => "⚠️ ID da receita não fornecido!"]);
    }
    exit;
}

?>