<?php

use App\Controllers\FilialHospitalController;

$filialHospitalController = new FilialHospitalController();

// Listar FilialHospital
if ($rota === 'filialHospital/listar' && $_SERVER['REQUEST_METHOD'] === 'GET') 
{
    $filialHospitalController->listarTodos();
    exit;
    
}

// Criar FilialHospital
if ($rota === 'filialHospital/criar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = json_decode(file_get_contents('php://input'), true);
    $filialHospitalController->criar($dados);
    exit;
}

// Atualizar FilialHospital
if ($rota === 'filialHospital/atualizar' && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $dados = json_decode(file_get_contents('php://input'), true);
    $id = $_GET['id'] ?? null;
    if ($id) {
        $filialHospitalController->atualizar($id, $dados);
    } else {
        echo json_encode(["erro" => "⚠️ ID do filialHospital não fornecido!"]);
    }
    exit;
}

// Deletar FilialHospital
if ($rota === 'filialHospital/deletar' && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $filialHospitalController->deletar($id);
    } else {
        echo json_encode(["erro" => "⚠️ ID do filialHospital não fornecido!"]);
    }
    exit;
}

// Buscar FilialHospital por ID
if ($rota === 'filialHospital/buscar' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $filialHospitalController->buscarPorId($id);
    } else {
        echo json_encode(["erro" => "⚠️ ID do filialHospital não fornecido!"]);
    }
    exit;
}

?>