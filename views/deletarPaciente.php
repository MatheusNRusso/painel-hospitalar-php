<?php
// deletarPaciente.php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\PacienteController;

$pacienteController = new PacienteController();

// Obtém o ID do medicamento a ser deletado
$id = $_GET['id'] ?? null;

if (!$id) {
    $_SESSION['erro'] = "⚠️ ID do paciente não especificado.";
    header("Location: ?rota=listarPacientes");
    exit;
}

try 
{
    // Chama o método para deletar o paciente
    $resultado = $pacienteController->deletar($id);
    $resultadoArray = json_decode($resultado, true);

    if (isset($resultadoArray['mensagem'])) 
    {
        $_SESSION['sucesso'] = $resultadoArray['mensagem'];
    }
    else 
    {
        $_SESSION['erro'] = $resultadoArray['erro'] ?? "Erro desconhecido ao deletar paciente.";
    }
} 
catch (Exception $e) 
{
    $_SESSION['erro'] = "❌ Erro ao deletar paciente: " . $e->getMessage();
}
// Redireciona de volta para a lista de pacientes
header("Location: ?rota=listarPacientes");
exit;


