<?php
function executarScriptPython()
{
    // Caminho correto do Python dentro do ambiente virtual
    $pythonPath = "/var/www/html/aplicacao_web/provas/ads/hospital-web-app/venv_hospital/bin/python";
    

    $scriptPath = __DIR__ . '/../scripts/googleai_service.py';
    // Criar um arquivo temporário para armazenar os dados JSON
    //$tempFile = tempnam(sys_get_temp_dir(), 'json_') . '.json';
    //file_put_contents($tempFile, json_encode($jsonData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

    // Montar o comando para executar o script Python passando o caminho do arquivo JSON
    $comando = escapeshellcmd("$pythonPath $scriptPath 2>&1");

    // Executar o comando e capturar a saída
    $output = shell_exec($comando);

    // Remover o arquivo temporário após a execução
    //unlink($tempFile);

    // Verificar se houve erro
    if (!$output) {
        return "❌ Erro ao executar o script Python.";
    }

    // Retornar o relatório formatado
    return "📊 **Relatório Gerado:**\n\n" . $output;

    
}

$resultado = executarScriptPython();
echo "<pre>$resultado</pre>";

?>
