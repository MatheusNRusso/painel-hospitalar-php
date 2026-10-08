<?php
// Definir as rotas que podem ser testadas
$rotas = [
    "testar-conexao" => "Testar Conexão com o Banco",
    "verificar-banco" => "Verificar Banco",
    "criar-banco" => "Criar Banco e Tabelas",
    "medicamento/listar" => "Listar Medicamentos",
    "tipo-medicamento/listar" => "Listar Tipos de Med",
    "tipo-medicamento/deletar" => "Deletar Medicamento",
    "paciente/listar" => "Listar Pacientes",
    "estoque/listar" => "Listar Itens do Estoque",
    "medico/listar" => "Listar Medicos",
    "leito/listar" => "Listar Leitos",
    "consulta/listar" => "Listar consultas",
    "tipoLeito/listar" => "Listar os tipos de Leito",
    "tipoStatus/listar" => "Listar Status",
    "filialHospital/listar" => "Listar Filiais",
    "especialidade/listar" => "Listar Especialidades",
    "estoque/gerar-relatorio" => "Executar Relatório"
];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testar Rotas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        #loader {
            display: none;
            text-align: center;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Testar Rotas do Sistema</h2>
        <p>Clique em uma rota para testar a funcionalidade correspondente.</p>
        
        <div class="list-group">
            <?php foreach ($rotas as $rota => $descricao): ?>
                <button class="list-group-item list-group-item-action" onclick="testarRota('<?php echo $rota; ?>')">
                    <?php echo $descricao; ?>
                </button>
            <?php endforeach; ?>
        </div>

        <div id="loader" class="mt-3">⏳ Carregando...</div>

        <div class="mt-4">
            <h4>Resposta:</h4>
            <pre id="resposta" class="bg-light p-3 border"></pre>
        </div>
    </div>
    
    <script>
        function testarRota(rota) {
            let respostaElemento = document.getElementById("resposta");
            let loader = document.getElementById("loader");

            respostaElemento.textContent = "";  // Limpa a resposta anterior
            loader.style.display = "block"; // Mostra o loader

            fetch(`http://hospital-web.local/?rota=${rota}`)
                .then(response => response.json().catch(() => { throw new Error("Resposta inválida"); }))
                .then(data => {
                    respostaElemento.textContent = JSON.stringify(data, null, 2);
                })
                .catch(error => {
                    respostaElemento.textContent = "❌ Erro ao acessar a rota: " + error.message;
                })
                .finally(() => {
                    loader.style.display = "none"; // Esconde o loader após a requisição
                });
        }
    </script>
</body>
</html>
