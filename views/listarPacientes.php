<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\PacienteController;

$pacienteController = new PacienteController();

// Paginação
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$limite = 10; // Número de pacientes por página

// Listagem de pacientes com paginação
$resultado = json_decode($pacienteController->listarPaginado($pagina, $limite), true);

$pacientes = $resultado['data'];
$totalPaginas = $resultado['pagination']['pages'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Pacientes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="container mt-4">

    <h2 class="text-center">💊 Lista de Pacientes</h2>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_GET['msg']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between mb-3">
        <a href="?rota=dashboard" class="btn btn-secondary">🔙 Voltar</a>
        <a href="?rota=cadastroPaciente" class="btn btn-success">➕ Cadastrar Novo Paciente</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Data Nascimento</th>
                    <th>Peso</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pacientes)): ?>
                    <?php usort($pacientes, fn($a, $b) => strcasecmp($a['nome'], $b['nome'])); ?>

                    <?php foreach ($pacientes as $paciente): ?>
                        <tr>
                            <td><?= htmlspecialchars($paciente['id']) ?></td>
                            <td><?= htmlspecialchars($paciente['nome']) ?></td>
                            <td>
                                <?php 
                                    $cpf = $paciente['cpf'];
                                    $cpfFormatado = substr($cpf, 0, 3) . '.***.***-' . substr($cpf, -2);
                                    echo htmlspecialchars($cpfFormatado);
                                ?>
                            </td>
                            <td><?= isset($paciente['data_nascimento']) ? date('d/m/Y', strtotime($paciente['data_nascimento'])) : 'N/A' ?></td>
                            <td><?= number_format($paciente['peso'], 2, ',', '.') ?></td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                        ⚙️ Ações
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="?rota=atualizarPaciente&id=<?= $paciente['id'] ?>" aria-label="Editar paciente <?= htmlspecialchars($paciente['nome']) ?>"><i class="bi bi-pencil"></i> Editar</a></li>
                                        <li><a class="dropdown-item text-danger btn-deletar" data-id="<?= $paciente['id'] ?>" href="#"><i class="bi bi-trash"></i>  Deletar</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="<?= count($pacientes[0] ?? []) + 1 ?>" class="text-center">Nenhum paciente encontrado.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    <nav aria-label="Navegação de páginas">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <li class="page-item <?= $i === $pagina ? 'active' : '' ?>">
                    <a class="page-link" href="?rota=listarPacientes&pagina=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>

    <!-- Modal de Confirmação de Exclusão -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Tem certeza que deseja excluir este paciente?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a id="deleteLink" href="#" class="btn btn-danger">Excluir</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".btn-deletar").forEach(botao => {
            botao.addEventListener("click", function (event) {
                event.preventDefault();
                let pacienteId = this.getAttribute("data-id");

                // Define o link de exclusão no modal
                document.getElementById("deleteLink").href = `?rota=deletarPaciente&id=${pacienteId}`;

                // Abre o modal
                let modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
                modal.show();
            });
        });
    });
    </script>

</body>
</html>
