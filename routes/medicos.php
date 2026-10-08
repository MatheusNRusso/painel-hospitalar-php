<?php

use App\Controllers\MedicoController;

// Instancia o controlador
$medicoController = new MedicoController();

// Listar Medicos
if ($rota === 'medico/listar' && $_SERVER['REQUEST_METHOD'] === 'GET') 
{
    $medicoController->listarTodos();
    exit;
}

// Criar Medico
if ($rota === 'medico/criar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = json_decode(file_get_contents('php://input'), true);
    $medicoController->criar($dados);
    exit;
}

// Atualizar Medico
if ($rota === 'medico/atualizar' && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $dados = json_decode(file_get_contents('php://input'), true);
    $id = $_GET['id'] ?? null;
    if ($id) {
        $medicoController->atualizar($id, $dados);
    } else {
        echo json_encode(["erro" => "⚠️ ID do medico não fornecido!"]);
    }
    exit;
}

// Deletar Medico
if ($rota === 'medico/deletar' && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $medicoController->deletar($id);
    } else {
        echo json_encode(["erro" => "⚠️ ID do medico não fornecido!"]);
    }
    exit;
}

// Buscar Medico por ID
if ($rota === 'medico/buscar' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $medicoController->buscarPorId($id);
        
    } else {
        echo json_encode(["erro" => "⚠️ ID do medico não fornecido!"]);
    }
    exit;
}

// Buscar Medico por CRM
if ($rota === 'medico/buscar-crm' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $crm = $_GET['crm'] ?? null;
    if ($crm) {
        $medico = $medicoController->buscarPorCRM($crm);
        echo json_encode($medico);
    } else {
        echo json_encode(["erro" => "⚠️ CRM do medico não fornecido!"]);
    }
    exit;
}

// Buscar Medicos por Especialidade
if ($rota === 'medico/buscar-especialidade' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $especialidade = $_GET['especialidade'] ?? null;
    if ($especialidade) {
        $medicos = $medicoController->buscarPorEspecialidade($especialidade);
        echo json_encode($medicos);
    } else {
        echo json_encode(["erro" => "⚠️ Especialidade do medico não fornecida!"]);
    }
    exit;
}

// Buscar Medicos por Nome
if ($rota === 'medico/buscar-nome' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $nome = $_GET['nome'] ?? null;
    if ($nome) {
        $medicos = $medicoController->buscarPorNome($nome);
        echo json_encode($medicos);
    } else {
        echo json_encode(["erro" => "⚠️ Nome do medico não fornecido!"]);
    }
    exit;
}



?>