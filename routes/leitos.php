<?php

use App\Controllers\LeitoController;

$leitoController = new LeitoController();

// Listar Consultas
if ($rota === 'leito/listarConsulta' && $_SERVER['REQUEST_METHOD'] === 'GET') 
{
    $leitoController->listarConsulta();
    exit;
}

// Listar Leitos
if ($rota === 'leito/listar' && $_SERVER['REQUEST_METHOD'] === 'GET') 
{
    $leitoController->listar();
    exit;
}


// Listar Leitos
if ($rota === 'leito/listarLeito' && $_SERVER['REQUEST_METHOD'] === 'GET') 
{
    $leitoController->listarLeito();
    exit;
}

// Listar Leitos
if ($rota === 'leito/listarTodos' && $_SERVER['REQUEST_METHOD'] === 'GET') 
{
    $leitoController->listarTodos();
    exit;
}

// Criar Leito
if ($rota === 'leito/criar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = json_decode(file_get_contents('php://input'), true);
    $leitoController->criar($dados);
    exit;
}

// Atualizar Leito
if ($rota === 'leito/atualizar' && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $dados = json_decode(file_get_contents('php://input'), true);
    $id = $_GET['id'] ?? null;
    if ($id) {
        $leitoController->atualizar($id, $dados);
    } else {
        echo json_encode(["erro" => "⚠️ ID do leito não fornecido!"]);
    }
    exit;
}

// Deletar Leito
if ($rota === 'leito/deletar' && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $leitoController->deletar($id);
    } else {
        echo json_encode(["erro" => "⚠️ ID do leito não fornecido!"]);
    }
    exit;
}

// Buscar Leito por ID
if ($rota === 'leito/buscar' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $leito = $leitoController->buscar($id);
        echo json_encode($leito);
    } else {
        echo json_encode(["erro" => "⚠️ ID do leito não fornecido!"]);
    }
    exit;
}

?>