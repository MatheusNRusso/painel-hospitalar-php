<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\EstoqueController;

$estoqueController = new EstoqueController();

// Verifica se o ID foi passado
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: ?rota=listarEstoque');
    exit();
}

$id = (int)$_GET['id'];

// Busca os dados do item
$item = json_decode($estoqueController->buscarPorId($id), true);

if (!$item || isset($item['error'])) {
    header('Location: ?rota=listarEstoque');
    exit();
}

// Processa o formulário de atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'filial_hospital_id' => (int)$_POST['filial_hospital_id'],
        'medicamento_id' => (int)$_POST['medicamento_id'],
        'quantidade' => (int)$_POST['quantidade'],
        'quantidade_minima' => (int)$_POST['quantidade_minima']
    ];

    $resultado = $estoqueController->atualizar($id, $dados);
    $resultadoArray = json_decode($resultado, true);
    
    if (isset($resultadoArray['mensagem'])) {
        $_SESSION['sucesso'] = $resultadoArray['mensagem'];
        header('Location: ?rota=listarEstoque');
        exit();
    } else {
        $erro = $resultadoArray['erro'] ?? "Erro ao atualizar item.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Item do Estoque</title>
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
        .quantidade-baixa {
            border-left: 4px solid #ffc107;
        }
        .quantidade-critica {
            border-left: 4px solid #dc3545;
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
                    <h2 class="mb-0"><i class="bi bi-pencil-square"></i> Editar Item do Estoque</h2>
                </div>
                <div class="card-body">
                    <?php if (isset($erro)): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-octagon"></i> <?= htmlspecialchars($erro) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="filial_hospital_id" class="form-label">Filial Hospitalar</label>
                            <select class="form-select" id="filial_hospital_id" name="filial_hospital_id" required></select>
                        </div>

                        <div class="mb-3">
                            <label for="medicamento_id" class="form-label">Medicamento</label>
                            <select class="form-select" id="medicamento_id" name="medicamento_id" required></select>
                        </div>

                        <div class="mb-3">
                            <label for="quantidade" class="form-label">Quantidade</label>
                            <input type="number" class="form-control" id="quantidade" name="quantidade" 
                                   value="<?= htmlspecialchars($item['quantidade']) ?>" min="0" required>
                        </div>

                        <div class="mb-3">
                            <label for="quantidade_minima" class="form-label">Quantidade Mínima</label>
                            <input type="number" class="form-control" id="quantidade_minima" name="quantidade_minima" 
                                   value="<?= htmlspecialchars($item['quantidade_minima']) ?>" min="1" required>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="?rota=listarEstoque" class="btn btn-secondary me-md-2">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Salvar Alterações
                            </button>
                        </div>
                    </form>
                    <div id="loader" class="mt-3">⏳ Carregando...</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
        carregarOpcoes("filialHospital/listar", "filial_hospital_id", <?= (int)$item['filial_hospital_id'] ?>);
        carregarOpcoes("medicamento/listarJSON", "medicamento_id", <?= (int)$item['medicamento_id'] ?>);
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