<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\TipoMedicamentoController;

$tipoMedicamentoController = new TipoMedicamentoController();

// Obtém o ID do tipo de medicamento
$id = $_GET['id'] ?? null;
if (!$id) {
    die("⚠️ Tipo de medicamento não encontrado.");
}

// Processamento do POST deve vir ANTES de buscar os dados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dadosAtualizados = [
        'nome' => $_POST['nome']
    ];

    $resultado = $tipoMedicamentoController->atualizar($id, $dadosAtualizados);
    $resultadoArray = json_decode($resultado, true);

    if (isset($resultadoArray['mensagem'])) {
        $_SESSION['sucesso'] = $resultadoArray['mensagem'];
        header("Location: ?rota=listarTipos&id=" . $id);
        exit;
    } else {
        $erro = $resultadoArray['erro'] ?? "Erro ao atualizar tipo de medicamento.";
    }
}

// Busca os dados ATUALIZADOS (após possível POST)
$resultadoBusca = $tipoMedicamentoController->buscar($id);
$tipoMedicamento = json_decode($resultadoBusca, true);

if (!$tipoMedicamento) {
    die("⚠️ Tipo de medicamento não encontrado.");
}

// Exibe mensagem de sucesso se existir
if (isset($_SESSION['sucesso'])) {
    $sucesso = $_SESSION['sucesso'];
    unset($_SESSION['sucesso']); // Limpa para não reaparecer
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tipo de Medicamento</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Evita cache da página -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
</head>
<body class="container mt-4">
    <h2 class="text-center">✏️ Editar Tipo de Medicamento</h2>
    <a href="?rota=listarTiposMedicamentos" class="btn btn-secondary mb-3">🔙 Voltar</a>

    <?php if (isset($sucesso)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div>
    <?php endif; ?>

    <?php if (isset($erro)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Tipo de Medicamento</label>
            <input type="text" id="nome" name="nome" class="form-control" 
                   value="<?= htmlspecialchars($tipoMedicamento['nome'] ?? '') ?>" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">💾 Salvar Alterações</button>
    </form>
</body>
</html>
