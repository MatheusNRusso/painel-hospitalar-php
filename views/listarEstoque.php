<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\EstoqueController;

$estoqueController = new EstoqueController();

// Listagem de itens do estoque com paginação
$itemEstoque = json_decode($estoqueController->listarEstoque(), true);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Itens do Estoque</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .table-responsive {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .quantidade-baixa {
            background-color: #fff3cd;
            font-weight: bold;
        }
        .quantidade-critica {
            background-color: #f8d7da;
            font-weight: bold;
        }
        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .btn-actions {
            min-width: 100px;
        }
    </style>
</head>
<body class="container mt-4">

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0 text-center"><i class="bi bi-box-seam"></i> Estoque Atual</h2>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <a href="?rota=dashboard" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
                <a href="?rota=cadastroEstoque" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Cadastrar Novo Produto
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Filial Hospital</th>
                            <th>Medicamento</th>
                            <th>Quantidade</th>
                            <th>Mínimo</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($itemEstoque)): ?>
                            <?php foreach ($itemEstoque as $item): 
                                $classeQuantidade = '';
                                if ($item['quantidade'] <= $item['quantidade_minima'] * 0.3) {
                                    $classeQuantidade = 'quantidade-critica';
                                } elseif ($item['quantidade'] <= $item['quantidade_minima']) {
                                    $classeQuantidade = 'quantidade-baixa';
                                }
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['id']) ?></td>
                                    <td><?= htmlspecialchars($item['filial_nome']) ?></td>
                                    <td><?= htmlspecialchars($item['medicamento_nome']) ?></td>
                                    <td class="<?= $classeQuantidade ?>">
                                        <?= htmlspecialchars($item['quantidade']) ?>
                                    </td>
                                    <td><?= htmlspecialchars($item['quantidade_minima']) ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary btn-actions dropdown-toggle" data-bs-toggle="dropdown">
                                                ⚙️ Ações
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="?rota=atualizarEstoque&id=<?= $item['id'] ?>">✏️ Editar</a></li>
                                                <li><a class="dropdown-item text-danger btn-deletar" data-id="<?= $item['id'] ?>" href="#">🗑️ Deletar</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle"></i> Nenhum item encontrado no estoque.
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">
                        <i class="bi bi-exclamation-triangle"></i> Confirmar Exclusão
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja excluir este item do estoque?</p>
                    <p class="text-muted">Esta ação não pode ser desfeita.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </button>
                    <a id="deleteLink" href="#" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Confirmar Exclusão
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Configuração do modal de exclusão
        document.querySelectorAll(".btn-deletar").forEach(botao => {
            botao.addEventListener("click", function (event) {
                event.preventDefault();
                let itemId = this.getAttribute("data-id");

                // Define o link de exclusão no modal
                document.getElementById("deleteLink").href = `?rota=deletarEstoque&id=${itemId}`;

                // Abre o modal
                let modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
                modal.show();
            });
        });

        // Adiciona tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
    </script>
</body>
</html>