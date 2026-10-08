<?php

use App\Controllers\EspecialidadeController;

$especialidadeController = new EspecialidadeController();

// Listar Especialidades
if ($rota === 'especialidade/listar' && $_SERVER['REQUEST_METHOD'] === 'GET') 
{
    $especialidadeController->listarTodos();
    exit;
}

// Criar Especialidade
if ($rota === 'especialidade/criar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = json_decode(file_get_contents('php://input'), true);
    $especialidadeController->criar($dados);
    exit;
}

// Atualizar Especialidade
if ($rota === 'especialidade/atualizar' && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $dados = json_decode(file_get_contents('php://input'), true);
    $id = $_GET['id'] ?? null;
    if ($id) {
        $especialidadeController->atualizar($id, $dados);
    } else {
        echo json_encode(["erro" => "⚠️ ID da especialidade não fornecido!"]);
    }
    exit;
}

// Deletar Especialidade    
if ($rota === 'especialidade/deletar' && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $especialidadeController->deletar($id);
    } else {
        echo json_encode(["erro" => "⚠️ ID da especialidade não fornecido!"]);
    }
    exit;
}



?>