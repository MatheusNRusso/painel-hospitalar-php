<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\TipoMedicamentoController;

$controller = new TipoMedicamentoController();
$tipos = json_decode($controller->listar(), true);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Tipos de Medicamentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Tipos de Medicamentos</h2>
        <a href="?rota=dashboard" class="btn btn-secondary mb-3">🔙 Voltar</a>
        
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tipos as $tipo): ?>
                <tr>
                    <td><?= htmlspecialchars($tipo['id']) ?></td>
                    <td><?= htmlspecialchars($tipo['nome']) ?></td>
                    <td>
                        <a href="?rota=cadastroTipoMedicamento" class="btn btn-sm btn-primary">Cadastrar</a>
                        <a href="?rota=editarTipoMedicamento&id=<?= $tipo['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="?rota=deletarTipo&id=<?= $tipo['id'] ?>" class="btn btn-sm btn-danger" 
                           onclick="return confirm('Tem certeza?')">Excluir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>