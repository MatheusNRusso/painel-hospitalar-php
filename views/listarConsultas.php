<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\ConsultaController;

$controller = new ConsultaController();
$consultas = json_decode($controller->listarConsulta(), true);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Consultas Realizadas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="container mt-4">
    <div class="card">
        <div class="card-header bg-info text-white">
            <h2 class="text-center">Consultas Realizadas</h2>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <a href="?rota=dashboard" class="btn btn-secondary">← Voltar</a>
                <a href="?rota=cadastroConsulta" class="btn btn-success"><i class="bi bi-plus-circle"></i> Nova Consulta</a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Paciente</th>
                            <th>Médico</th>
                            <th>Filial</th>
                            <th>Leito</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th>Descrição</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if (!empty($consultas)):
                                // Ordenar as consultas pela data da consulta (mais antigas primeiro)
                                usort($consultas, fn($a, $b) => strtotime($a['data_consulta']) <=> strtotime($b['data_consulta']));
                                // Função para retornar a classe do badge conforme o status
                                function getBadgeClass(string $status): string {
                                    return match (strtolower($status)) {
                                        'concluída' => 'success',
                                        'cancelada' => 'danger',
                                        'agendada' => 'warning',
                                        default => 'secondary',
                                    };
                                }
                                // Exibir as consultas
                                foreach ($consultas as $c):
                                    $badgeClass = getBadgeClass($c['status'] ?? '');
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($c['id']) ?></td>
                                        <td><?= htmlspecialchars($c['paciente_nome'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($c['medico_nome'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($c['filial_nome'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($c['leito_id'] ?? 'N/A') ?></td>
                                        <td class="text-nowrap">
                                            <?= !empty($c['data_consulta']) ? htmlspecialchars(date("d/m/Y", strtotime($c['data_consulta']))) : 'N/A' ?>
                                        </td>
                                        <td><span class="badge bg-<?= $badgeClass ?>"><?= htmlspecialchars($c['status']) ?></span></td>
                                        <td><?= htmlspecialchars($c['descricao'] ?? '') ?></td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-label="Ações para a consulta">
                                                    Ações
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="?rota=atualizarConsulta&id=<?= htmlspecialchars($c['id']) ?>"><i class="bi bi-pencil-square"></i> Editar</a></li>
                                                    <li><a class="dropdown-item text-danger" href="?rota=deletarConsulta&id=<?= htmlspecialchars($c['id']) ?>"><i class="bi bi-trash"></i> Deletar</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center">Nenhuma consulta encontrada.</td>
                                </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
