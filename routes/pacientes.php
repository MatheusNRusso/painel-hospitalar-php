<?php
use App\Controllers\PacienteController;

// Instancia o controlador
$pacienteController = new PacienteController();

// Listar Pacientes
if ($rota === 'paciente/listar' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $pacienteController->listar();
    exit;
}

// Criar Paciente
if ($rota === 'paciente/criar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = json_decode(file_get_contents('php://input'), true);
    $pacienteController->criar($dados);
    exit;
}

// Atualizar Paciente
if ($rota === 'paciente/atualizar' && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $dados = json_decode(file_get_contents('php://input'), true);
    $id = $_GET['id'] ?? null;
    if ($id) {
        $pacienteController->atualizar($id, $dados);
    } else {
        echo json_encode(["erro" => "⚠️ ID do paciente não fornecido!"]);
    }
    exit;
}

// Deletar Paciente
if ($rota === 'paciente/deletar' && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $pacienteController->deletar($id);
    } else {
        echo json_encode(["erro" => "⚠️ ID do paciente não fornecido!"]);
    }
    exit;
}

// Buscar Paciente por Nome ou CPF
if ($rota === 'paciente/buscarPorNomeOuCpf' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $termo = $_GET['termo'] ?? null;
    if ($termo) {
        $pacienteController->buscarPorNomeOuCpf($termo);
    } else {
        echo json_encode(["erro" => "⚠️ Nenhum termo de busca informado."]);
    }
    exit;
}



?>