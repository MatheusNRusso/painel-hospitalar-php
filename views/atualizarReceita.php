<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\ReceitaController;

$receitaController = new ReceitaController();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['erro'] = 'ID da receita inválido.';
    header('Location: ?rota=listarReceitas');
    exit();
}

$id = (int) $_GET['id'];
$receita = json_decode($receitaController->buscarPorId($id), true);

if (!$receita) {
    $_SESSION['erro'] = 'Receita não encontrada.';
    header('Location: ?rota=listarReceitas');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'paciente_id' => (int) $_POST['paciente_id'],
        'medico_id' => (int) $_POST['medico_id'],
        'medicamento_id' => (int) $_POST['medicamento_id'],
        'dose' => (int) $_POST['dose'],
        'data_prescricao' => $_POST['data_prescricao']
    ];

    $resultado = json_decode($receitaController->atualizar($id, $dados), true);

    if (isset($resultado['mensagem'])) {
        $_SESSION['sucesso'] = $resultado['mensagem'];
        header('Location: ?rota=listarReceitas');
        exit();
    } else {
        $erro = $resultado['erro'] ?? "Erro ao atualizar receita.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Atualizar Receita</title>
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
                <h3 class="mb-0"><i class="bi bi-capsule-pill"></i> Atualizar Receita</h3>
            </div>
            <div class="card-body">
                <?php if (isset($erro)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
                <?php endif; ?>

                <form method="POST" id="formReceita">
                    <div class="mb-3">
                        <label for="paciente_id" class="form-label">Paciente</label>
                        <select name="paciente_id" id="paciente_id" class="form-select" required>
                            <option value="">Carregando pacientes...</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="medico_id" class="form-label">Médico</label>
                        <select name="medico_id" id="medico_id" class="form-select" required>
                            <option value="">Carregando médicos...</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="medicamento_id" class="form-label">Medicamento</label>
                        <select name="medicamento_id" id="medicamento_id" class="form-select" required>
                            <option value="">Carregando medicamentos...</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="dose" class="form-label">Dose</label>
                        <input type="number" name="dose" id="dose" class="form-control"
                               value="<?= htmlspecialchars($receita['dose']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="data_prescricao" class="form-label">Data da Prescrição</label>
                        <input type="datetime-local" name="data_prescricao" id="data_prescricao" class="form-control"
                               value="<?= date('Y-m-d\TH:i', strtotime($receita['data_prescricao'])) ?>" required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="?rota=listarReceitas" class="btn btn-outline-dark">⬅️ Voltar</a>
                        <div>
                            <button type="submit" class="btn btn-primary">💾 Atualizar</button>
                            <a href="?rota=dashboard" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const receita = <?= json_encode($receita) ?>;

    document.addEventListener("DOMContentLoaded", () => {
        carregarOpcoes("paciente/listar", "paciente_id", receita.paciente_id);
        carregarOpcoes("medico/listar", "medico_id", receita.medico_id);
        carregarOpcoes("medicamento/listarJSON", "medicamento_id", receita.medicamento_id);
    });

    function carregarOpcoes(rota, selectId, valorSelecionado) {
        const select = document.getElementById(selectId);
        fetch(`?rota=${rota}`)
            .then(res => res.json())
            .then(data => {
                select.innerHTML = `<option value="">Selecione</option>`;
                data.forEach(item => {
                    const option = document.createElement("option");
                    option.value = item.id;
                    option.textContent = item.nome;
                    if (item.id == valorSelecionado) {
                        option.selected = true;
                    }
                    select.appendChild(option);
                });
            })
            .catch(err => {
                console.error("Erro ao carregar", rota, err);
                select.innerHTML = `<option value="">Erro ao carregar</option>`;
            });
    }
</script>

</body>
</html>
