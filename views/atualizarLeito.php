<?php
require_once __DIR__ . '/../vendor/autoload.php';;

use App\Controllers\LeitoController;

$leitoController = new LeitoController();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['erro'] = 'ID inválido.';
    header('Location: ?rota=listarLeito');
    exit();
}

$id = (int) $_GET['id'];
$item = json_decode($leitoController->buscarPorId($id), true);

if (!$item) {
    $_SESSION['erro'] = 'Leito não encontrado.';
    header('Location: ?rota=listarLeito');
    exit();
}

// Tratamento do POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'numero' => (int) $_POST['numero'],
        'tipo_id' => (int) $_POST['tipo_id'],
        'filial_hospital_id' => (int) $_POST['filial_hospital_id'],
        'status_id' => (int) $_POST['status_id'],
        'paciente_id' => $_POST['paciente_id'] !== '' ? (int) $_POST['paciente_id'] : null
    ];

    $resultado = json_decode($leitoController->atualizar($id, $dados), true);

    if (isset($resultado['mensagem'])) {
        $_SESSION['sucesso'] = $resultado['mensagem'];
        header('Location: ?rota=listarLeito');
        exit();
    } else {
        $erro = $resultado['erro'] ?? "Erro ao atualizar leito.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Leito</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .card {
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        #loader {
            display: none;
        }
    </style>
</head>
<body class="container mt-5">

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="bi bi-pencil-square"></i> Editar Leito</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($erro)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="numero" class="form-label">Número do Leito</label>
                            <input type="number" class="form-control" name="numero" id="numero" required
                                   value="<?= htmlspecialchars($item['numero']) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="tipo_id" class="form-label">Tipo de Leito</label>
                            <select name="tipo_id" id="tipo_id" class="form-select" required></select>
                        </div>

                        <div class="mb-3">
                            <label for="filial_hospital_id" class="form-label">Filial Hospitalar</label>
                            <select name="filial_hospital_id" id="filial_hospital_id" class="form-select" required></select>
                        </div>

                        <div class="mb-3">
                            <label for="status_id" class="form-label">Status</label>
                            <select name="status_id" id="status_id" class="form-select" required></select>
                        </div>

                        <div class="mb-3">
                            <label for="paciente_id" class="form-label">ID do Paciente (opcional)</label>
                            <input type="number" class="form-control" name="paciente_id" id="paciente_id"
                                   value="<?= htmlspecialchars($item['paciente_id'] ?? '') ?>">
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="?rota=listarLeito" class="btn btn-outline-secondary">⬅️ Voltar</a>
                            <button type="submit" class="btn btn-primary">💾 Atualizar</button>
                        </div>
                    </form>

                    <div id="loader" class="mt-3">⏳ Carregando...</div>
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    carregarOpcoes("tipoLeito/listar", "tipo_id", <?= (int)$item['tipo_id'] ?>);
    carregarOpcoes("filialHospital/listar", "filial_hospital_id", <?= (int)$item['filial_hospital_id'] ?>);
    carregarOpcoes("tipoStatus/listar", "status_id", <?= (int)$item['status_id'] ?>);
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
                opt.textContent = opcao.nome;
                if (parseInt(opcao.id) === selecionado) {
                    opt.selected = true;
                }
                select.appendChild(opt);
            });
        })
        .catch(error => {
            console.error(`Erro ao carregar opções (${rota})`, error);
        });
}
</script>

</body>
</html>
