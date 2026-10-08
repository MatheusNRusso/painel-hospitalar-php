<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\LeitoController;

$leitoController = new LeitoController();

// Paginação
//$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
//$limite = 10; // Número de leitos por página

// Listagem de leitos com paginação (assumindo que já retorna dados relacionados)
$leitos= json_decode($leitoController->listarLeito(), true);

//$leitos = $resultado['data'];
//$totalPaginas = $resultado['pagination']['pages'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Leitos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .table-responsive {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .status-disponivel { background-color: #d1e7dd; }
        .status-ocupado { background-color: #f8d7da; }
        .status-manutencao { background-color: #fff3cd; }
        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .btn-actions { min-width: 100px; }
        .badge { font-size: 0.9em; }
    </style>
</head>
<body class="container mt-4">

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0 text-center"><i class="bi bi-hospital"></i> Gestão de Leitos</h2>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <a href="?rota=dashboard" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
                <a href="?rota=cadastroLeito" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Cadastrar Novo Leito
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Número</th>
                            <th>Tipo</th>
                            <th>Status</th>
                            <th>Paciente</th>
                            <th>Filial</th>
                            <th>Cadastrado em</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($leitos)): ?>
                            <?php foreach ($leitos as $leito): ?>
                                <tr>
                                    <td><?= htmlspecialchars($leito['id']) ?></td>
                                    <td><?= htmlspecialchars($leito['numero']) ?></td>
                                    <td><?= htmlspecialchars($leito['tipo_nome'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($leito['status_nome'] ?? 'N/A') ?></td>
                                    <td><?=htmlspecialchars($leito['paciente_nome'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($leito['filial_nome'] ?? 'N/A') ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($leito['created_at'])) ?></td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-primary dropdown-toggle btn-actions" type="button" 
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-gear"></i> Ações
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="?rota=atualizarLeito&id=<?= $leito['id'] ?>">
                                                        <i class="bi bi-pencil"></i> Editar
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-danger btn-deletar" href="#" 
                                                       data-id="<?= $leito['id'] ?>">
                                                        <i class="bi bi-trash"></i> Excluir
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="?rota=detalhesLeito&id=<?= $leito['id'] ?>">
                                                        <i class="bi bi-info-circle"></i> Detalhes
                                                    </a>
                                                </li>
                                                <?php if (($leito['status_nome'] ?? '') === 'Disponível' && empty($leito['paciente_id'])): ?>
                                                <li>
                                                    <a class="dropdown-item text-success" href="?rota=ocuparLeito&id=<?= $leito['id'] ?>">
                                                        <i class="bi bi-person-plus"></i> Ocupar Leito
                                                    </a>
                                                </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle"></i> Nenhum leito cadastrado.
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
                    <p>Tem certeza que deseja excluir este leito?</p>
                    <p class="text-muted">Esta ação não pode ser desfeita e pode afetar pacientes associados.</p>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Configuração do modal de exclusão
        document.querySelectorAll(".btn-deletar").forEach(botao => {
            botao.addEventListener("click", function (event) {
                event.preventDefault();
                let leitoId = this.getAttribute("data-id");

                // Define o link de exclusão no modal
                document.getElementById("deleteLink").href = `?rota=deletarLeito&id=${leitoId}`;

                // Abre o modal
                let modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
                modal.show();
            });
        });
    });
    </script>
</body>
</html>