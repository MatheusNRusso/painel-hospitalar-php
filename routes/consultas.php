<?php

use App\Controllers\ConsultaController;

$consultaController = new ConsultaController();

// Listar todas as consultas
if ($rota === 'consulta/listarConsulta' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $consultaController->listarConsulta();
    exit;
}

// Listar todas as consultas
if ($rota === 'consulta/listar' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $consultaController->listarTodos();
    exit;
}

// Criar consulta
if ($rota === 'consulta/criar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = json_decode(file_get_contents('php://input'), true);
    $consultaController->criar($dados);
    exit;
}

// Atualizar consulta
if ($rota === 'consulta/atualizar' && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $dados = json_decode(file_get_contents('php://input'), true);
    $id = $_GET['id'] ?? null;
    if ($id) {
        $consultaController->atualizar($id, $dados);
    } else {
        echo json_encode(["erro" => "⚠️ ID da consulta não fornecido!"]);
    }
    exit;
}

// Deletar consulta
if ($rota === 'consulta/deletar' && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $consultaController->deletar($id);
    } else {
        echo json_encode(["erro" => "⚠️ ID da consulta não fornecido!"]);
    }
    exit;
}

// Buscar consulta por ID
if ($rota === 'consulta/buscarPorPaciente' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $consultaController->buscarPorPaciente($id);
    } else {
        echo json_encode(["erro" => "⚠️ ID da consulta não fornecido!"]);
    }
    exit;
}
?>