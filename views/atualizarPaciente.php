<?php
// atualizarPaciente.php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\PacienteController;

$controller = new PacienteController();

// Obtém o ID do paciente a ser atualizado
$id = $_GET['id'] ?? null;  
if (!$id) 
{
    die("Paciente não encontrado.");
}
// Se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'nome' => $_POST['nome'] ?? '',
        'cpf' => $_POST['cpf'] ?? '',
        'data_nascimento' => $_POST['data_nascimento'] ?? '',
        'peso' => $_POST['peso'] ?? '',
    ];

    $resultado = json_decode($controller->atualizar($id, $dados), true);

    if (isset($resultado['mensagem'])) 
    {
        $_SESSION['sucesso'] = $resultado['mensagem'];
        header("Location: ?rota=listarPacientes&id=" . $id);
        exit;
    } else {
        $erro = $resultado['erro'] ?? "Erro ao atualizar paciente.";
    }
} 
$paciente = json_decode($controller->buscarPorId($id), true);
if (!$paciente || !isset($paciente['id'])) 
{
    header("Location: ?rota=listarPacientes");
}
// Exibe mensagem de sucesso se existir
if (isset($_SESSION['sucesso'])) {
    $sucesso = $_SESSION['sucesso'];
    unset($_SESSION['sucesso']); // Limpa para não reaparecer
}
// Exibe mensagem de erro se existir
if (isset($_SESSION['erro'])) {
    $erro = $_SESSION['erro'];
    unset($_SESSION['erro']); // Limpa para não reaparecer
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Atualizar Paciente</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2 class="text-center">✏️ Atualizar Paciente</h2>


    <?php if (isset($sucesso)): ?>
        <div class="alert alert-danger"> <?= htmlspecialchars($sucesso) ?> </div>
    <?php endif; ?>

    <?php if (isset($erro)): ?>
        <div class="alert alert-danger"> <?= htmlspecialchars($erro) ?> </div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($paciente['nome']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="cpf" class="form-label">CPF:</label>
            <input type="text" name="cpf" class="form-control" value="<?= htmlspecialchars($paciente['cpf']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="data_nascimento" class="form-label">Data de Nascimento:</label>
            <input type="date" name="data_nascimento" class="form-control" value="<?= htmlspecialchars($paciente['data_nascimento']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="peso" class="form-label">Peso (kg):</label>
            <input type="number" step="0.01" name="peso" class="form-control" value="<?= htmlspecialchars($paciente['peso']) ?>" required>
        </div>

        <div class="d-flex justify-content-between">
            <a href="?rota=listarPacientes" class="btn btn-outline-dark">⬅️ Voltar</a>
            <div>
                <button type="submit" class="btn btn-primary">💾 Atualizar</button>
                <a href="?rota=listarPacientes" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </form>
</body>
</html>
