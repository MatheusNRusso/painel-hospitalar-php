<?php
// Inicia o autoload para carregar classes automaticamente
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\ConsultaController;

// Instancia o controller responsável pelas consultas
$consultaController = new ConsultaController();

// Valida o ID enviado via GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['erro'] = 'ID da consulta inválido.';
    header('Location: ?rota=listarConsultas');
    exit();
}

$id = (int) $_GET['id'];
// Busca os dados da consulta a ser editada
$item = json_decode($consultaController->buscarPorId($id), true);

if (!$item) {
    $_SESSION['erro'] = 'Consulta não encontrada.';
    header('Location: ?rota=listarConsultas');
    exit();
}

// Tratamento do envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta e sanitiza os dados do formulário
    $dados = [
        'paciente_id' => isset($_POST['paciente_id']) ? (int) $_POST['paciente_id'] : null,
        'medico_id' => isset($_POST['medico_id']) ? (int) $_POST['medico_id'] : null,
        'filial_hospital_id' => isset($_POST['filial_hospital_id']) ? (int) $_POST['filial_hospital_id'] : null,
        'leito_id' => $_POST['leito_id'] !== '' ? (int) $_POST['leito_id'] : null,
        'data_consulta' => $_POST['data_consulta'] ?? '',
        'descricao' => trim($_POST['descricao'] ?? ''),
        'status' => $_POST['status'] ?? ''
    ];

    // Atualiza a consulta
    $resultado = json_decode($consultaController->atualizar($id, $dados), true);

    if (isset($resultado['mensagem'])) {
        $_SESSION['sucesso'] = $resultado['mensagem'];
        header('Location: ?rota=listarConsultas');
        exit();
    } else {
        $erro = $resultado['erro'] ?? 'Erro ao atualizar consulta.';
    }
}
?>

<!-- HTML - Formulário de edição -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Consulta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .card { border-radius: 0.5rem; box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15); }
        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.25);
        }
        #loader { display: none; }
    </style>
</head>
<body class="container mt-5">

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0"><i class="bi bi-pencil-square"></i> Editar Consulta</h3>
            </div>
            <div class="card-body">
                <?php if (isset($erro)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
                <?php endif; ?>

                <!-- Formulário -->
                <form method="POST">
                    <div class="mb-3">
                        <label for="paciente_id" class="form-label">Paciente</label>
                        <select name="paciente_id" id="paciente_id" class="form-select" required></select>
                    </div>

                    <div class="mb-3">
                        <label for="medico_id" class="form-label">Médico</label>
                        <select name="medico_id" id="medico_id" class="form-select" required></select>
                    </div>

                    <div class="mb-3">
                        <label for="filial_hospital_id" class="form-label">Filial Hospitalar</label>
                        <select name="filial_hospital_id" id="filial_hospital_id" class="form-select" required></select>
                    </div>

                    <div class="mb-3">
                        <label for="leito_id" class="form-label">Leito (opcional)</label>
                        <select name="leito_id" id="leito_id" class="form-select">
                            <option value="">Nenhum</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="data_consulta" class="form-label">Data da Consulta</label>
                        <input type="datetime-local" class="form-control" name="data_consulta" id="data_consulta" required
                               value="<?= htmlspecialchars(date('Y-m-d\TH:i', strtotime($item['data_consulta']))) ?>">
                    </div>

                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" name="descricao" id="descricao" rows="4" required><?= htmlspecialchars($item['descricao']) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="Agendada" <?= $item['status'] === 'Agendada' ? 'selected' : '' ?>>Agendada</option>
                            <option value="Realizada" <?= $item['status'] === 'Realizada' ? 'selected' : '' ?>>Realizada</option>
                            <option value="Cancelada" <?= $item['status'] === 'Cancelada' ? 'selected' : '' ?>>Cancelada</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="?rota=listarConsultas" class="btn btn-outline-secondary">⬅️ Voltar</a>
                        <button type="submit" class="btn btn-primary">💾 Atualizar</button>
                    </div>
                </form>

                <div id="loader" class="mt-3">⏳ Carregando...</div>
            </div>
        </div>
    </div>
</div>

<!-- Script para carregar opções dos selects dinamicamente -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    carregarOpcoes("paciente/listar", "paciente_id", <?= (int)$item['paciente_id'] ?>);
    carregarOpcoes("medico/listar", "medico_id", <?= (int)$item['medico_id'] ?>);
    carregarOpcoes("filialHospital/listar", "filial_hospital_id", <?= (int)$item['filial_hospital_id'] ?>);
    carregarOpcoes("leito/listarConsulta", "leito_id", <?= $item['leito_id'] ? (int)$item['leito_id'] : 'null' ?>);
});

function carregarOpcoes(rota, idSelect, selecionado) {
    const select = document.getElementById(idSelect);
    fetch(`http://hospital-web.local/?rota=${rota}`)
        .then(res => res.json())
        .then(data => {
            select.innerHTML = `<option value="">Selecione</option>`;
            data.forEach(opcao => {
                const opt = document.createElement("option");
                opt.value = opcao.id;

                if (idSelect === "leito_id") {
                    const tipo = opcao.tipo_nome || "Tipo N/D";
                    const status = opcao.status_nome || "Status N/D";
                    const filial = opcao.filial_nome || "Filial N/D";
                    opt.textContent = `${tipo} - ${filial} - ${status}`;
                } else {
                    opt.textContent = opcao.nome;
                }

                if (parseInt(opcao.id) === selecionado) {
                    opt.selected = true;
                }
                select.appendChild(opt);
            });
        })
        .catch(error => {
            console.error(`Erro ao carregar opções (${rota}):`, error);
        });
}
</script>

</body>
</html>
