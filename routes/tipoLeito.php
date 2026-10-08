<?php

use App\Controllers\TipoLeitoController;

$tipoLeitoController = new TipoLeitoController();

// Listar Tipo Leito
if ($rota === 'tipoLeito/listar' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $tipoLeitoController->listarTodos();
}

// Criar Tipo Leito
if ($rota === 'tipoLeito/criar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = json_decode(file_get_contents('php://input'), true);
    $tipoLeitoController->criar($dados);
    exit;
}

// Atualizar Tipo leito
if ($rota === 'tipoLeito/atualizar' && $_SERVER['REQUEST_METHOD'] === 'PUT') 
{
    $dados = json_decode(file_get_contents('php://input'), true);
    $id = $_GET['id'] ?? null;
    if ($id) 
    {
        $tipoLeitoController->atualizar($id, $dados);
    }
     else 
    {
        echo json_encode(["erro" => "⚠️ ID do Tipo leito não fornecido!"]);
    }
    exit;
}

// Deletar Tipo leito   
if ($rota === 'tipoLeito/deletar' && $_SERVER['REQUEST_METHOD'] === 'DELETE') 
{
    $id = $_GET['id'] ?? null;
    if ($id) 
    {
        $tipoLeitoController->deletar($id);
    } 
    else 
    {
        echo json_encode(["erro" => "⚠️ ID do tipo leito não fornecido!"]);
    }
    exit;
}
?>