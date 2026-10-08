
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentação da API</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .route-item {
            margin-bottom: 1rem;
        }
        .route-method {
            font-weight: bold;
            text-transform: uppercase;
            margin-right: 1rem;
        }
        .route-url {
            color: #0d6efd;
        }
        .route-description {
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-5">Documentação da API</h1>
        <p class="text-center mb-4">Abaixo estão listadas todas as rotas disponíveis no sistema.</p>

        <!-- Rotas relacionadas ao banco de dados -->
        <div class="route-item">
            <h4>Banco de Dados</h4>
            <ul class="list-unstyled">
                <li>
                    <span class="route-method">GET</span>
                    <span class="route-url">/?rota=testar-conexao</span>
                    <span class="route-description">Testar conexão com o banco de dados.</span>
                </li>
                <li>
                    <span class="route-method">GET</span>
                    <span class="route-url">/?rota=verificar-banco</span>
                    <span class="route-description">Verificar se o banco de dados já foi criado.</span>
                </li>
                <li>
                    <span class="route-method">GET</span>
                    <span class="route-url">/?rota=criar-banco</span>
                    <span class="route-description">Criar o banco de dados e tabelas.</span>
                </li>
            </ul>
        </div>

        <!-- Rotas relacionadas a medicamentos -->
        <div class="route-item">
            <h4>Medicamentos</h4>
            <ul class="list-unstyled">
                <li>
                    <span class="route-method">GET</span>
                    <span class="route-url">/?rota=medicamento/listar</span>
                    <span class="route-description">Listar todos os medicamentos.</span>
                </li>
                <li>
                    <span class="route-method">POST</span>
                    <span class="route-url">/?rota=medicamento/criar</span>
                    <span class="route-description">Criar um novo medicamento.</span>
                </li>
                <li>
                    <span class="route-method">POST</span>
                    <span class="route-url">/?rota=medicamento/remover-duplicatas</span>
                    <span class="route-description">Remover duplicatas de medicamentos.</span>
                </li>
            </ul>
        </div>

        <!-- Rotas relacionadas a pacientes -->
        <div class="route-item">
            <h4>Pacientes</h4>
            <ul class="list-unstyled">
                <li>
                    <span class="route-method">GET</span>
                    <span class="route-url">/?rota=paciente/listar</span>
                    <span class="route-description">Listar todos os pacientes.</span>
                </li>
                <li>
                    <span class="route-method">POST</span>
                    <span class="route-url">/?rota=paciente/criar</span>
                    <span class="route-description">Criar um novo paciente.</span>
                </li>
                <li>
                    <span class="route-method">POST</span>
                    <span class="route-url">/?rota=paciente/atualizar</span>
                    <span class="route-description">Atualizar um paciente existente.</span>
                </li>
                <li>
                    <span class="route-method">POST</span>
                    <span class="route-url">/?rota=paciente/deletar</span>
                    <span class="route-description">Remover um paciente.</span>
                </li>
            </ul>
        </div>

        <!-- Rotas relacionadas ao estoque -->
        <div class="route-item">
            <h4>Estoque</h4>
            <ul class="list-unstyled">
                <li>
                    <span class="route-method">GET</span>
                    <span class="route-url">/?rota=estoque/listarItens</span>
                    <span class="route-description">Listar todos os itens do estoque.</span>
                </li>
                <li>
                    <span class="route-method">POST</span>
                    <span class="route-url">/?rota=estoque/adicionarItem</span>
                    <span class="route-description">Adicionar um item ao estoque.</span>
                </li>
                <li>
                    <span class="route-method">POST</span>
                    <span class="route-url">/?rota=estoque/atualizarQuantidade</span>
                    <span class="route-description">Atualizar a quantidade de um item no estoque.</span>
                </li>
                <li>
                    <span class="route-method">POST</span>
                    <span class="route-url">/?rota=estoque/deletarItem</span>
                    <span class="route-description">Deletar um item do estoque.</span>
                </li>
                <li>
                    <span class="route-method">GET</span>
                    <span class="route-url">/?rota=estoque/verificarBalanceamento</span>
                    <span class="route-description">Verificar o balanceamento do estoque.</span>
                </li>
            </ul>
        </div>

        <!-- Rotas relacionadas a receitas -->
        <div class="route-item">
            <h4>Receitas</h4>
            <ul class="list-unstyled">
                <li>
                    <span class="route-method">GET</span>
                    <span class="route-url">/?rota=receita/listar</span>
                    <span class="route-description">Listar todas as receitas.</span>
                </li>
                <li>
                    <span class="route-method">POST</span>
                    <span class="route-url">/?rota=receita/criar</span>
                    <span class="route-description">Criar uma nova receita.</span>
                </li>
                <li>
                    <span class="route-method">POST</span>
                    <span class="route-url">/?rota=receita/atualizar</span>
                    <span class="route-description">Atualizar uma receita existente.</span>
                </li>
                <li>
                    <span class="route-method">POST</span>
                    <span class="route-url">/?rota=receita/deletar</span>
                    <span class="route-description">Remover uma receita.</span>
                </li>
                <li>
                    <span class="route-method">GET</span>
                    <span class="route-url">/?rota=receita/buscar</span>
                    <span class="route-description">Buscar uma receita específica.</span>
                </li>
            </ul>
        </div>

        <!-- Rotas relacionadas a tipos de medicamentos -->
        <div class="route-item">
            <h4>Tipos de Medicamentos</h4>
            <ul class="list-unstyled">
                <li>
                    <span class="route-method">GET</span>
                    <span class="route-url">/?rota=tipo-medicamento/listar</span>
                    <span class="route-description">Listar todos os tipos de medicamentos.</span>
                </li>
                <li>
                    <span class="route-method">POST</span>
                    <span class="route-url">/?rota=tipo-medicamento/criar</span>
                    <span class="route-description">Criar um novo tipo de medicamento.</span>
                </li>
                <li>
                    <span class="route-method">POST</span>
                    <span class="route-url">/?rota=tipo-medicamento/atualizar</span>
                    <span class="route-description">Atualizar um tipo de medicamento existente.</span>
                </li>
                <li>
                    <span class="route-method">POST</span>
                    <span class="route-url">/?rota=tipo-medicamento/deletar</span>
                    <span class="route-description">Remover um tipo de medicamento.</span>
                </li>
            </ul>
    </div>
</body>
</html>
        