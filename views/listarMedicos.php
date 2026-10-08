<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\MedicoController;

$controller = new MedicoController();
$medicos = json_decode($controller->listarMedicos(), true);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Médicos Cadastrados</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h2 class="text-center">Médicos Cadastrados</h2>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <a href="?rota=dashboard" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> ← Voltar
                </a>
                <a href="?rota=cadastroMedico" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i>➕ Cadastrar Novo Médico
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>CRM</th>
                            <th>Especialidade</th>
                            <th>Filial</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($medicos)): ?>
                            <?php foreach ($medicos as $medico): ?>
                                <tr>
                                    <td><?= htmlspecialchars($medico['id']) ?></td>
                                    <td><?= htmlspecialchars($medico['nome']) ?></td>
                                    <td><?= htmlspecialchars($medico['crm']) ?></td>
                                    <td><?= htmlspecialchars($medico['especialidade_nome'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($medico['filial_nome'] ?? 'N/A') ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary btn-actions dropdown-toggle" data-bs-toggle="dropdown">
                                                ⚙️ Ações
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="?rota=atualizarMedico&id=<?= $medico['id'] ?>">✏️ Editar</a></li>
                                                <li><a class="dropdown-item text-danger btn-deletar" data-id="<?= $medico['id'] ?>" href="#">🗑️ Deletar</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center text-muted">Nenhum médico cadastrado.</td></tr>
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
                    <p>Tem certeza que deseja excluir este médico?</p>
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
                document.getElementById("deleteLink").href = `?rota=deletarMedico&id=${itemId}`;

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
