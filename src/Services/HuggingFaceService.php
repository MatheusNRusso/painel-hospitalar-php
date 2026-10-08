<?php

namespace App\Services;

class HuggingFaceService
{
    public function gerarTexto($prompt)
    {
        // Caminho para o script Python
        $scriptPath = __DIR__ . '/../../scripts/huggingface_service.py';

        // Caminho para o Python do ambiente virtual
        $venvPath = "/var/www/html/aplicacao_web/provas/ads/hospital-web-app/venv_hospital/bin/activate";
        $pythonPath = "/var/www/html/aplicacao_web/provas/ads/hospital-web-app/venv_hospital/bin/python";

        
        // Escapar o argumento para evitar injeção de comandos
        $promptSeguro = escapeshellarg($prompt);

        // Comando para executar o script Python dentro do ambiente virtual
        $comando = "source $venvPath && $pythonPath $scriptPath $promptSeguro";

        //error_log("📌 Comando a ser executado: $comando");

        $saida = shell_exec($comando); // Captura erros do Python também

        //error_log("📌 Saída do script Python: " . $saida);

        // Verifica se a saída é válida
        if (empty($saida)) {
            error_log("❌ Erro ao executar o script Python.");
            return "Erro ao processar o pedido.";
        }

        
        return $saida;
    }
}
?>