<?php

namespace App\Controllers;

use App\Database;
use Exception;

class ChatIAController
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function consultar()
    {
        header('Content-Type: application/json');

        // Recebe a pergunta do usuário via POST
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, true);

        if (!isset($input['pergunta']) || empty(trim($input['pergunta']))) {
            echo json_encode(["erro" => "A pergunta não pode estar vazia."]);
            exit;
        }

        $pergunta = trim($input['pergunta']);

        // 🔎 Verifica se a pergunta pode ser respondida via SQL
        $respostaSQL = $this->consultarBanco($pergunta);

        if ($respostaSQL) {
            echo json_encode(["resposta" => $respostaSQL]);
            exit;
        }

        // Se não encontrou no banco, chama a IA
        $respostaIA = $this->consultarGemini($pergunta);
        echo json_encode(["resposta" => $respostaIA]);
    }

    private function consultarBanco($pergunta)
    {
        try {
            if (stripos($pergunta, "quantos leitos disponíveis") !== false) {
                $query = "SELECT COUNT(*) AS total FROM leitos WHERE status = 'disponível'";
                $stmt = $this->db->query($query);
                $result = $stmt->fetch();
                return "Atualmente há {$result['total']} leitos disponíveis.";
            }

            if (stripos($pergunta, "quantos medicamentos cadastrados") !== false) {
                $query = "SELECT COUNT(*) AS total FROM medicamentos";
                $stmt = $this->db->query($query);
                $result = $stmt->fetch();
                return "O sistema possui {$result['total']} medicamentos cadastrados.";
            }

            return null; // Se não encontrou resposta no banco, retorna null
        } catch (Exception $e) {
            error_log("Erro ao consultar banco: " . $e->getMessage());
            return null;
        }
    }

    private function consultarGemini($pergunta)
    {
        $apiKey = "SUA_CHAVE_API_GEMINI"; // 🔴 SUBSTITUA pela sua chave
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateText?key={$apiKey}";

        $data = json_encode([
            "prompt" => ["text" => $pergunta],
            "temperature" => 0.7,
            "maxTokens" => 200
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $response = curl_exec($ch);
        curl_close($ch);

        $decodedResponse = json_decode($response, true);
        return $decodedResponse['candidates'][0]['output'] ?? "Não foi possível obter uma resposta no momento.";
    }
}
