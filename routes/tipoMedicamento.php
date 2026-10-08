<?php

use App\Controllers\TipoMedicamentoController;

$tipoMedicamentoController = new TipoMedicamentoController();

if ($rota === 'tipo-medicamento/listar' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $tipoMedicamentoController->listar();
    exit;
}


if ($rota === 'tipo-medicamento/listarJSON' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $tipoMedicamentoController->listarJSON();
    exit;
}

if ($rota === 'tipo-medicamento/criar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = json_decode(file_get_contents('php://input'), true);
    $tipoMedicamentoController->criar($dados);
    exit;
}

if ($rota === 'tipo-medicamento/atualizar' && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $dados = json_decode(file_get_contents('php://input'), true);
    $id = $_GET['id'] ?? null;
    if ($id) {
        $tipoMedicamentoController->atualizar($id, $dados);
    } else {
        echo json_encode(["erro" => "⚠️ ID do tipo de medicamento não fornecido!"]);
    }
    exit;
}
if ($rota === 'tipo-medicamento/deletar' && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $tipoMedicamentoController->deletar($id);
    } else {
        echo json_encode(["erro" => "⚠️ ID do tipo de medicamento não fornecido!"]);
    }
    exit;
}
//echo json_encode(["erro" => "⚠️ Rota não encontrada: $rota"]);

?>