<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\ReceitaController;

$controller = new ReceitaController();
$receitas = json_decode($controller->listarReceitas(), true);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Receitas Médicas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="container mt-4">
    <div class="card">
        <div class="card-header bg-success text-white">
            <h2 class="text-center">Receitas Médicas</h2>
        </div>
        <div class="card-body">
            <a href="?rota=dashboard" class="btn btn-secondary mb-3">← Voltar</a>
            <a href="?rota=cadastroReceita" class="btn btn-success mb-3"><i class="bi bi-plus-circle"></i> Nova Receita</a>
            <div class="alert alert-info">
                <strong>Nota:</strong> As receitas são listadas com os nomes dos pacientes, médicos e medicamentos, se disponíveis.
            </div>
            <div class="alert alert-warning">
                <strong>Atenção:</strong> As receitas são exibidas com os dados mais relevantes. Para detalhes completos, clique na receita desejada.
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Paciente</th>
                            <th>Médico</th>
                            <th>Medicamento</th>
                            <th>Dose</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($receitas)): ?>
                            <?php foreach ($receitas as $r): ?>
                                <tr>
                                    <td><?= htmlspecialchars($r['id']) ?></td>
                                    <td><?= htmlspecialchars($r['paciente_nome'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($r['medico_nome'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($r['medicamento_nome'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($r['dose']) ?></td>
                                    <td><?= htmlspecialchars(date("d/m/Y", strtotime($r['data_prescricao']))) ?></td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                Ações
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="?rota=atualizarReceita&id=<?= htmlspecialchars($r['id']) ?>"><i class="bi bi-pencil-square"></i> Editar</a></li>
                                                <li><a class="dropdown-item text-danger" href="?rota=deletarReceita&id=<?= htmlspecialchars($r['id']) ?>"><i class="bi bi-trash"></i> Deletar</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center text-muted">Nenhuma receita registrada.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

<!-- Bootstrap JS (necessário para dropdowns funcionarem) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>
