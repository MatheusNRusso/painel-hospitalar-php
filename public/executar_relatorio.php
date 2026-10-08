<?php
function executarScriptPython($scriptPath, $jsonData)
{
    // Caminho correto do Python (confirme com `which python3` no terminal)
    $pythonPath = "/var/www/html/aplicacao_web/provas/ads/hospital-web-app/venv_hospital/bin/python";

    // Transformar o array em JSON (caso ainda não esteja no formato correto)
    $jsonArgumento = json_encode($jsonData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    // Garantir que o argumento seja seguro para o shell
    $jsonSeguro = escapeshellarg($jsonArgumento);

    // Montar o comando para executar o script Python
    $comando = "$pythonPath $scriptPath $jsonSeguro 2>&1";

    // Executar o comando e capturar a saída
    $output = shell_exec($comando);

    // Verificar se houve erro
    if (!$output) {
        return "❌ Erro ao executar o script Python.";
    }

    // Retornar o relatório formatado
    return "📊 **Relatório Gerado:**\n\n" . $output;
}

// **Exemplo de uso**
$scriptPath = __DIR__ . '/../scripts/googleia_service.py'; // Ajuste o caminho do script

// JSON de exemplo gerado pela API
$dadosEstoque = [
    "mensagem" => "✅ Relatório gerado com sucesso!",
    "dados" => "Você é um assistente de gestão de estoque hospitalar. Com base nos seguintes dados, gere um relatório detalhado:\n\n📋 **Estoque Atual:**\n-Filial ID: 1 | Medicamento ID: 1 | Quantidade: 200 | Quantidade Mínima 200--Filial ID: 1 | Medicamento ID: 2 | Quantidade: 50 | Quantidade Mínima 50--Filial ID: 2 | Medicamento ID: 3 | Quantidade: 200 | Quantidade Mínima 200--Filial ID: 3 | Medicamento ID: 4 | Quantidade: 200 | Quantidade Mínima 200--Filial ID: 3 | Medicamento ID: 5 | Quantidade: 80 | Quantidade Mínima 80--Filial ID: 1 | Medicamento ID: 7 | Quantidade: 50 | Quantidade Mínima 50--Filial ID: 3 | Medicamento ID: 1 | Quantidade: 20 | Quantidade Mínima 20--Filial ID: 2 | Medicamento ID: 1 | Quantidade: 20 | Quantidade Mínima 20--Filial ID: 1 | Medicamento ID: 6 | Quantidade: 20 | Quantidade Mínima 20-\n\n**Regras para a Análise:**\n1️⃣ Destaque medicamentos que estejam abaixo da quantidade mínima.\n2️⃣ Liste os medicamentos com maior e menor quantidade no estoque.\n3️⃣ Sugira medidas para reposição e balanceamento do estoque.\n4️⃣ Identifique padrões de consumo que possam impactar futuras compras.\n\n💡 **Retorne o relatório de maneira clara e organizada.**"
];

// Chamar a função e exibir o resultado
$resultado = executarScriptPython($scriptPath, $dadosEstoque);
echo "<pre>$resultado</pre>";
?>
