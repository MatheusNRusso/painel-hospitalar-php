<?php

namespace App\Controllers;

use App\Services\OpenAIService;

class OpenAIController
{
    private $openAI;

    public function __construct()
    {
        $this->openAI = new OpenAIService();
    }

    public function gerarRelatorio()
    {
        $prompt = "Gerar um relatório sobre o estado atual do estoque de medicamentos no hospital.";
        $resposta = $this->openAI->gerarTexto($prompt);

        echo json_encode(["relatorio" => trim($resposta)]);
    }
}
