<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\MedicamentoController;

$medicamentoController = new MedicamentoController();

// Paginação
$paginaAtual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$limite = 10; // Número de registros por página
$offset = ($paginaAtual - 1) * $limite;

// Dados paginados
$medicamentos = json_decode($medicamentoController->listarComTipoPaginado($limite, $offset), true);
$total = $medicamentoController->contarTotalMedicamentos();
$totalPaginas = ceil($total / $limite);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Medicamentos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="container mt-4">

    <h2 class="text-center mb-4">💊 Lista de Medicamentos</h2>

    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Operação realizada com sucesso!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between mb-3">
        <a href="?rota=dashboard" class="btn btn-secondary">
            <i class="bi-arrow-left-circle"></i> Voltar 
        </a>
        <a href="?rota=cadastroMedicamento" class="btn btn-success btn-lg">
            <i class="bi bi-plus-circle"></i> Cadastrar Novo Medicamento
        </a>
    </div>

    <table class="table table-striped table-hover">
        <thead class="table-primary">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Doses/Caixa</th>
                <th>Preço/Caixa</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>

            <?php if (!empty($medicamentos)): ?>
                <?php usort($medicamentos, fn($a, $b) => strcasecmp($a['nome'], $b['nome'])); ?>
                <?php foreach ($medicamentos as $medicamento): ?>
                    <tr>
                        <td><?= htmlspecialchars($medicamento['id']) ?></td>
                        <td><?= htmlspecialchars($medicamento['nome']) ?></td>
                        <td>
                            <span class="badge bg-info text-dark"><?= htmlspecialchars($medicamento['tipo_nome'] ?? 'Tipo não informado') ?></span>
                        </td>
                        <td><?= htmlspecialchars($medicamento['doses_por_caixa']) ?></td>
                        <td>R$ <?= number_format($medicamento['preco_por_caixa'], 2, ',', '.') ?></td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                    ⚙️ Ações
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="?rota=atualizarMedicamentos&id=<?= $medicamento['id'] ?>">✏️ Editar</a></li>
                                    <li><a class="dropdown-item text-danger btn-deletar" data-id="<?= $medicamento['id'] ?>" href="#">🗑️ Deletar</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">Nenhum medicamento encontrado.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if ($totalPaginas > 1): ?>
        <nav aria-label="Navegação de páginas">
            <ul class="pagination justify-content-center mt-4">
                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <li class="page-item <?= $i == $paginaAtual ? 'active' : '' ?>">
                        <a class="page-link" href="?rota=listarMedicamentos&pagina=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
            <p class="text-center">Página <?= $paginaAtual ?> de <?= $totalPaginas ?></p>
        </nav>
    <?php endif; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".btn-deletar").forEach(botao => {
            botao.addEventListener("click", function (event) {
                event.preventDefault();
                let medicamentoId = this.getAttribute("data-id");
                if (confirm("Tem certeza que deseja excluir este medicamento?")) {
                    window.location.href = `?rota=deletarMedicamento&id=${medicamentoId}`;
                }
            });
        });
    });
    </script>
</body>
</html>
