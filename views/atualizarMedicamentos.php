<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\MedicamentoController;

$medicamentoController = new MedicamentoController();

// Obtém o ID do medicamento
$id = $_GET['id'] ?? null;
if (!$id) {
    die("⚠️ Medicamento não encontrado.");
}

// Processa o POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dadosAtualizados = [
        'nome' => $_POST['nome'],
        'tipo_id' => (int) $_POST['tipo_id'],
        'doses_por_caixa' => (int) $_POST['doses_por_caixa'],
        'preco_por_caixa' => str_replace(',', '.', $_POST['preco_por_caixa'])
    ];

    $resultado = $medicamentoController->atualizar($id, $dadosAtualizados);
    $resultadoArray = json_decode($resultado, true);

    if (isset($resultadoArray['mensagem'])) {
        $_SESSION['sucesso'] = $resultadoArray['mensagem'];
        header("Location: ?rota=listarMedicamentos&id=" . $id);
        exit;
    } else {
        $erro = $resultadoArray['erro'] ?? "Erro ao atualizar medicamento.";
    }
}

// Busca dados atualizados
$resultadoBusca = $medicamentoController->buscar($id);
$medicamento = json_decode($resultadoBusca, true);

if (!$medicamento) {
    die("⚠️ Medicamento não encontrado.");
}

if (isset($_SESSION['sucesso'])) {
    $sucesso = $_SESSION['sucesso'];
    unset($_SESSION['sucesso']);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Medicamento</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
</head>
<body class="container mt-4">
    <h2 class="text-center">✏️ Editar Medicamento</h2>

    <?php if (isset($sucesso)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div>
    <?php endif; ?>

    <?php if (isset($erro)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Medicamento</label>
            <input type="text" id="nome" name="nome" class="form-control" 
                   value="<?= htmlspecialchars($medicamento['nome'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="tipo_id" class="form-label">Tipo</label>
            <select id="tipo_id" name="tipo_id" class="form-control" required>
                <option value="">Selecione um tipo</option>
                <!-- opções via JS -->
            </select>
        </div>

        <div class="mb-3">
            <label for="doses_por_caixa" class="form-label">Doses por Caixa</label>
            <input type="number" id="doses_por_caixa" name="doses_por_caixa" class="form-control" 
                   value="<?= htmlspecialchars($medicamento['doses_por_caixa'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="preco_por_caixa" class="form-label">Preço por Caixa</label>
            <input type="text" id="preco_por_caixa" name="preco_por_caixa" class="form-control" 
                   value="<?= htmlspecialchars(number_format($medicamento['preco_por_caixa'] ?? 0, 2, ',', '.')) ?>" required>
        </div>

        <div class="d-flex justify-content-between">
            <a href="?rota=listarMedicamentos" class="btn btn-outline-dark">⬅️ Voltar</a>
            <div>
                <button type="submit" class="btn btn-primary">💾 Atualizar</button>
                <a href="?rota=dashboard" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </form>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        fetch("http://hospital-web.local/?rota=tipo-medicamento/listarJSON")
        .then(response => {
            if (!response.ok) throw new Error(`Erro HTTP! Status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            const select = document.getElementById("tipo_id");
            const tipoSelecionado = <?= (int)$medicamento['tipo_id'] ?>;

            data.forEach(tipo => {
                const option = document.createElement("option");
                option.value = tipo.id;
                option.textContent = tipo.nome;

                // ✅ Seleciona automaticamente o tipo atual do medicamento
                if (tipo.id == tipoSelecionado) {
                    option.selected = true;
                }

                select.appendChild(option);
            });
        })
        .catch(error => {
            console.error("Erro ao carregar tipos de medicamentos:", error);
        });
    });
    </script>
</body>
</html>
