<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\MedicoController;

$medicoController = new MedicoController();

// Verifica se o ID foi passado corretamente
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: ?rota=listarMedicos');
    exit();
}

$id = (int)$_GET['id'];

// Busca os dados do médico pelo ID
$medico = json_decode($medicoController->buscarId($id), true);

if (!$medico || isset($medico['error'])) {
    header('Location: ?rota=listarMedicos');
    exit();
}

// Processa o formulário de atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'nome' => $_POST['nome'],
        'especialidade_id' => (int)$_POST['especialidade_id'],
        'crm' => $_POST['crm'],
        'filial_hospital_id' => (int)$_POST['filial_hospital_id']
    ];

    $resultado = $medicoController->atualizar($id, $dados);
    $resultadoArray = json_decode($resultado, true);

    if (isset($resultadoArray['mensagem'])) {
        $_SESSION['sucesso'] = $resultadoArray['mensagem'];
        header('Location: ?rota=listarMedicos');
        exit();
    } else {
        $erro = $resultadoArray['erro'] ?? "Erro ao atualizar médico.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Médico</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .card {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
        }
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        #loader {
            display: none;
        }
    </style>
</head>
<body class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0"><i class="bi bi-pencil-square"></i> Editar Médico</h2>
                </div>
                <div class="card-body">
                    <?php if (isset($erro)): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-octagon"></i> <?= htmlspecialchars($erro) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome"
                                   value="<?= htmlspecialchars($medico['nome']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="especialidade_id" class="form-label">Especialidade</label>
                            <select class="form-select" id="especialidade_id" name="especialidade_id" required></select>
                        </div>

                        <div class="mb-3">
                            <label for="crm" class="form-label">CRM</label>
                            <input type="text" class="form-control" id="crm" name="crm"
                                   value="<?= htmlspecialchars($medico['crm']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="filial_hospital_id" class="form-label">Filial</label>
                            <select class="form-select" id="filial_hospital_id" name="filial_hospital_id" required></select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="?rota=listarMedicos" class="btn btn-outline-secondary">⬅️ Voltar</a>
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
        carregarOpcoes("filialHospital/listar", "filial_hospital_id", <?= (int)$medico['filial_hospital_id'] ?>);
        carregarOpcoes("especialidade/listar", "especialidade_id", <?= (int)$medico['especialidade_id'] ?>);
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
