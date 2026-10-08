<?php
use App\Controllers\MedicamentoController;

// Instancia o controlador
$medicamentoController = new MedicamentoController();

// Listar Medicamentos
if ($rota === 'medicamento/listar' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    //header('Content-Type: application/json');
    try 
    {
        $medicamentos = $medicamentoController->listar();
        exit;
    }
    catch (Exception $e) 
    {
        http_response_code(500);
        json_encode(["erro" => "❌ Erro ao listar medicamentos."]);
    }
    
}

// Listar Medicamentos
if ($rota === 'medicamento/listarJSON' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    //header('Content-Type: application/json');
    try 
    {
        $medicamentos = $medicamentoController->listarJSON();
    }
    catch (Exception $e) 
    {
        http_response_code(500);
        json_encode(["erro" => "❌ Erro ao listar medicamentos."]);
    }
    
}

// Criar Medicamento
if ($rota === 'medicamento/criar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = json_decode(file_get_contents('php://input'), true);
    $medicamentoController->criar($dados);
    exit;
}

// Remove Duplicadas
if ($rota === 'medicamento/remover-duplicatas' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $medicamentoController->removerDuplicatas();
    exit;
}

// Atualizar Medicamento
if ($rota === 'medicamento/atualizar' && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $dados = json_decode(file_get_contents('php://input'), true);
    $id = $_GET['id'] ?? null;
    if ($id) {
        $medicamentoController->atualizar($id, $dados);
    } else {
        echo json_encode(["erro" => "⚠️ ID do medicamento não fornecido!"]);
    }
    exit;
}

// Deletar Medicamento
if ($rota === 'medicamento/deletar' && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $medicamentoController->deletar($id);
    } else {
        echo json_encode(["erro" => "⚠️ ID do medicamento não fornecido!"]);
    }
    exit;
}

// Buscar Medicamento por ID
if ($rota === 'medicamento/buscar' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $medicamentoController->buscar($id);
    } else {
        echo json_encode(["erro" => "⚠️ ID do medicamento não fornecido!"]);
    }
    exit;
}
?>