<?php
// deletarTipo.php Script para deletar o tipo de Medicamento
require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\TipoMedicamentoController;

// Cria uma instância do controller
$tipoMedicamentoController = new TipoMedicamentoController();

// Obtém o ID do tipo a deletado
$id = $_GET['id'] ?? null;

// Confirme se o ID do tipo foi carregado corretamante
if (!$id)
{
    $_SESSION['erro'] = "ID do tipo de medicamento não especificado.";
    header("Location: ?rota=listarTipos");
    exit;
}

try 
{
    // Chama o método para deletar o tipo
    $resultado = $tipoMedicamentoController->deletar($id);
    $resultadoTipo = json_decode($resultado, true);

    if (isset($resultadoTipo['mensagem']))
    {
        $_SESSION['sucesso'] = $resultadoTipo['mensagem'];
    }
    else
    {
        $_SESSION = $resultadoTipo['erro'] ?? 'Erro desconhecido a deletar tipo medicamento.';
    }
}
catch (Exception $e)
{
    $_SESSION['erro'] = "Erro ao deletar o tipo de medicamento";
}

// Redireciona para voltar a lista de tipos de medicamentos
header("Location: ?rota=listarTipos ");
?>