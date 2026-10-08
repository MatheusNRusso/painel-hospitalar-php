<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        #loader {
            display: none;
            font-size: 18px;
            text-align: center;
            margin-top: 10px;
        }
        .btn-link {
            text-decoration: none; /* Remove o sublinhado dos links */
            color: inherit; /* Mantém a cor do texto padrão */
        }

        .custom-title {
            font-size: 2.5rem;
            color: #2c3e50;
            font-weight: bold;
            margin-bottom: 1.5rem;
        }

        .custom-subtitle {
            font-size: 1.25rem;
            color: #34495e;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="custom-title text-center">Bem-vindo ao Sistema</h2>
        <p class="custom-subtitle text-center">Selecione uma opção abaixo:</p>
        

        <!-- Lista de botões com links -->
        <div class="d-grid gap-3">
            <a href="cadastroMedicamento.php" class="btn btn-primary">Cadastrar Medicamento</a>
            <a href="cadastroPaciente.php" class="btn btn-primary">Cadastrar Paciente</a>
            <a href="cadastroEstoque.php" class="btn btn-primary">Adicionar ao Estoque</a>
            <a href="cadastroTipoMedicamento.php" class="btn btn-primary">Cadastrar Tipo de Medicamento</a>
            <a href="cadastroReceita.php" class="btn btn-primary">Cadastrar Receita</a>
            <a href="testarRotas.php" class="btn btn-primary">Testar Rotas</a>
        </div>

        <!-- Botão para gerar relatório -->
        <button id="btnGerarRelatorio" class="btn btn-primary mt-3">📊 Gerar Relatório</button>
        <div id="loader" style="display: none;">⏳ Gerando relatório...</div>

        <!-- Área para exibir o relatório -->
        <div id="relatorio" class="mt-4"></div>

        <!-- Botão de logout -->
        <a href="/?rota=logout" class="btn btn-danger mt-4">Sair</a>
    </div>

    <script>
    $(document).ready(function () {
        $("#btnGerarRelatorio").click(function () {
            $("#loader").show();  // Exibe o loader enquanto gera o relatório
            $("#relatorio").html(""); // Limpa o conteúdo anterior

            $.ajax({
                url: "http://hospital-web.local/?rota=estoque/executar-relatorio", // 🚀 CORRIGIDO para chamar o script correto
                type: "GET",
                dataType: "html", // Alterado para aceitar HTML caso JSON falhe
                success: function (response) {
                    $("#loader").hide(); // Oculta o loader

                    if (response.includes("❌")) {
                        $("#relatorio").html(`<div class='alert alert-danger'>❌ Erro ao gerar o relatório.</div>`);
                    } else {
                        $("#relatorio").html(`<div class='alert alert-success'><h4>📊 Relatório</h4><pre>${response}</pre></div>`);
                    }
                },
                error: function () {
                    $("#loader").hide();
                    $("#relatorio").html("<div class='alert alert-danger'>❌ Erro ao gerar o relatório.</div>");
                }
            });
        });
    });
    </script>
</body>
</html>
