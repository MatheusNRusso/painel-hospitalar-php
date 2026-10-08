<?php
namespace App\Controllers;

use App\Models\Relatorio;
use Exception;

date_default_timezone_set('America/Sao_Paulo'); 

class RelatorioController
{
    private $tabela;
    private $relatorio;

    public function __construct($tabela)
    {
        $this->relatorio = new Relatorio($tabela); 
        $this->tabela = $tabela;
    }

    // Gera o relatório baseado na tabela informada
    public function listarRelatorio()
    {
        try 
        {
            $dados = $this->relatorio->all(); // Obtém todos os registros da tabela

            if (!$dados) 
            {
                return (["erro" => "⚠️ Nenhum dado encontrado na tabela {$this->tabela}."]);
            }
            return (["tabela" => $this->tabela,
                     "dados" => $dados]);
        } 
        catch (\Exception $e) 
        {
            return (["erro" => "❌ Erro ao gerar o relatório: " . $e->getMessage()]);
        }
    }
    public function gerarRelatorio()
    {
        try
        {
            // Instancia dinamicamente a classe Relatorio
            $dadosTabela = $this->listarRelatorio();
            // Formata os dados para o relatório
            $tabelaFormat = $this->formatarRelatorioDinamico($dadosTabela);
            // Constrói o prompt
            $promptFormatado = $this->construirPrompt($tabelaFormat);
            return $promptFormatado;
        } 
        catch (Exception $e) 
        {
            return json_encode([
                "status" => "error",
                "mensagem" => "❌ Erro ao gerar o relatório: " . $e->getMessage()
            ]);
        }
    }

    public function formatarRelatorioDinamico($jsonRelatorio)
    {
        // Decodifica o JSON para um array associativo
        $dados = $jsonRelatorio;

        // Verifica se o JSON é válido
        if (!isset($dados["tabela"]) || !isset($dados["dados"])) 
        {
            return "❌ Erro: Dados inválidos no relatório.";
        }

        // Inicia o texto do relatório
        $textoRelatorio = "📊 **Relatório da Tabela: " . strtoupper($dados['tabela']) . "**\n\n";
        $textoRelatorio .= "----------------------------------\n";

        // Obtém dinamicamente as colunas disponíveis
        if (!empty($dados['dados'])) {
            $colunas = array_keys($dados['dados'][0]); // Pega as chaves do primeiro item

            // Adiciona cabeçalho da tabela
            $textoRelatorio .= implode(" | ", array_map(fn($col) => strtoupper($col), $colunas)) . "\n";
            $textoRelatorio .= str_repeat("-", 50) . "\n";

            // Adiciona os valores das linhas da tabela
            foreach ($dados['dados'] as $linha) {
                $textoRelatorio .= implode(" | ", array_map(fn($col) => $linha[$col], $colunas)) . "\n";
            }
            $textoRelatorio .= "----------------------------------\n";
        } else {
            $textoRelatorio .= "⚠️ Nenhum dado encontrado na tabela.\n";
        }

        return nl2br($textoRelatorio); // Adiciona quebras de linha para exibição no HTML
    }


    public function construirPrompt($tabelaFormat)
    {
        // Melhorando o prompt 
        $prompt =  "🩺 Você é um assistente de gestão hospitalar especializado em relatórios.\n\n" .
                   "Com base nos dados a seguir, elabore um relatório profissional, organizado nos seguintes tópicos:\n\n" .
                   "$tabelaFormat\n\n" .
                   "📋 **Instruções para o Relatório:**\n\n" .
                   
                   "🔹 **1. Introdução:**\n" .
                   "- Faça uma breve introdução contextualizando os dados.\n\n" .
                   
                   "🔹 **2. Estrutura dos Dados:**\n" .
                   "- Liste os principais campos (colunas) da tabela e explique resumidamente o significado de cada um.\n\n" .
                   
                   "🔹 **3. Análise dos Dados:**\n" .
                   "- Identifique padrões, anomalias, tendências ou pontos de destaque relevantes.\n" .
                   "- Indique relações entre os dados, se houver.\n\n" .
                   
                   "🔹 **4. Sugestões de Melhorias:**\n" .
                   "- Sugira formas de melhorar a coleta, organização ou análise desses dados.\n" .
                   "- Proponha ideias para tornar a gestão hospitalar mais eficiente com base nas informações.\n\n" .
                   
                   "🔹 **5. Conclusão:**\n" .
                   "- Faça um fechamento geral com as principais descobertas e eventuais recomendações futuras.\n\n" .
                   
                   "💡 **Importante:**\n" .
                   "- Utilize uma linguagem clara, objetiva e profissional.\n" .
                   "- Estruture o texto com separações de parágrafos.\n" .
                   "- Priorize frases mais curtas, limitando o tamanho máximo a 150 caracteres.\n" .
                   "- Evite jargões técnicos excessivos.\n";
                   return json_encode(["mensagem" => "✅ Relatório gerado com sucesso!",
                                              "data_geracao" => date("d/m/Y H:i:s"),
                                              "dados" => $prompt]);
    }


    public function executarScriptPython()
    {
        // Caminho correto do Python dentro do ambiente virtual
        $pythonPath = "/var/www/html/aplicacao_web/provas/ads/hospital-web-app/venv_hospital/bin/python";

        // Caminho do script Python
        $scriptPath = __DIR__ . '/../../scripts/googleia_service.py';

        // Verificar se o script Python e o caminho do Python existem
        if (!file_exists($pythonPath) || !file_exists($scriptPath)) {
            return json_encode(["erro" => "❌ Caminho do Python ou script não encontrado."]);
        }

        // Validar o nome da tabela antes de passar para o script
        if (empty($this->tabela)) {
            return json_encode(["erro" => "❌ O nome da tabela não foi fornecido."]);
        }

        // Montar o comando para executar o script Python passando a variável 'tabela'
        $comando = escapeshellcmd("$pythonPath $scriptPath " . escapeshellarg($this->tabela) . " 2>&1");

        // Executar o comando e capturar a saída
        $output = shell_exec($comando);

        // Verificar se houve erro na execução do script Python
        if ($output === null) {
            return json_encode(["erro" => "❌ Erro ao executar o script Python."]);
        }

        // Verificar se o comando retornou um erro
        if (strpos($output, "Error") !== false || strpos($output, "error") !== false) {
            return json_encode(["erro" => "❌ Erro na execução do script: " . $output]);
        }

        // Retornar o relatório formatado
        return $output;
    }

}
?>
