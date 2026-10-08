<?php
namespace App\Controllers;

use App\Models\Estoque;
use Exception;

class EstoqueController {
    private $estoque;

    public function __construct()
    {
        $this->estoque = new Estoque();
    }

    // Listar todos os itens do estoque
    public function listarItens() {
        try {
            $itens = $this->estoque->all();
            echo json_encode($itens);
        } catch (Exception $e) {
            echo json_encode(["erro" => "Erro ao listar itens do estoque: " . $e->getMessage()]);
        }
    }

    // Adicionar item ao estoque
    public function adicionarItem($dados)
    {
        try {
            // Verifica se os dados obrigatórios estão presentes
            if (!isset($dados['filial_hospital_id'], $dados['medicamento_id'], $dados['quantidade'], $dados['quantidade_minima'])) {
                throw new Exception("⚠️ Todos os campos são obrigatórios: 'filial_hospital_id', 'medicamento_id', 'quantidade' e 'quantidade_minima'.");
            }

            $filialId = $dados['filial_hospital_id'];
            $medicamentoId = $dados['medicamento_id'];
            $quantidade = $dados['quantidade'];
            $quantidadeMinima = $dados['quantidade_minima'];

            // Verifica se a quantidade é válida
            if ($quantidade < 1) {
                throw new Exception("⚠️ A quantidade deve ser pelo menos 1.");
            }

            // Verifica se o medicamento já existe no estoque da filial
            $itemExistente = $this->estoque->where([
                'medicamento_id' => $medicamentoId,
                'filial_hospital_id' => $filialId
            ]);

            if (!empty($itemExistente)) {
                // Atualiza a quantidade
                $novaQuantidade = $itemExistente[0]['quantidade'] + $quantidade;

                if ($novaQuantidade > 200) { // Limite global de estoque
                    throw new Exception("⚠️ O estoque total não pode ultrapassar 200 unidades.");
                }

                $this->estoque->update($itemExistente[0]['id'], [
                    'quantidade' => $novaQuantidade,
                    'data_atualizacao' => date('Y-m-d H:i:s')
                ]);

                echo json_encode(["mensagem" => "✅ Quantidade atualizada no estoque!"]);
            } else {
                // Cria um novo item no estoque
                $this->estoque->create([
                    'filial_hospital_id' => $filialId,
                    'medicamento_id' => $medicamentoId,
                    'quantidade' => $quantidade,
                    'quantidade_minima' => $quantidadeMinima
                ]);

                echo json_encode(["mensagem" => "✅ Item adicionado ao estoque com sucesso!"]);
            }
        } catch (Exception $e) {
            echo json_encode(["erro" => $e->getMessage()]);
        }
    }

    // Atualizar quantidade de um item no estoque
    public function atualizarQuantidade($id, $dados) {
        try {
            if (!isset($dados['quantidade'])) {
                throw new Exception("⚠️ O campo 'quantidade' é obrigatório.");
            }

            if ($dados['quantidade'] < 1) {
                throw new Exception("⚠️ A quantidade deve ser pelo menos 1.");
            }

            $this->estoque->update($id, [
                'quantidade' => $dados['quantidade'],
                'data_atualizacao' => date('Y-m-d H:i:s')
            ]);

            echo json_encode(["mensagem" => "✅ Quantidade atualizada com sucesso!"]);
        } catch (Exception $e) {
            echo json_encode(["erro" => "Erro ao atualizar a quantidade: " . $e->getMessage()]);
        }
    }

    // Deletar item do estoque
    public function deletarItem($id) {
        try {
            $this->estoque->delete($id);
            if ($this->isApiCall()) {
                header('Content-Type: application/json');
                echo json_encode(["mensagem" => "✅ Item removido do estoque com sucesso!"]);
                exit;
            }
            return json_encode(["mensagem" => "✅ Item removido do estoque com sucesso!"]);
        } 
        catch (Exception $e) 
        {
            error_log("Erro ao deletar item: " . $e->getMessage());
            if ($this->isApiCall()) {
                header('Content-Type: application/json');
                echo json_encode(["erro" => "❌ Erro ao remover o item do estoque: " . $e->getMessage()]);
                exit;
            }
            return json_encode(["erro" => "Erro ao remover o item do estoque: " . $e->getMessage()]);
        }
    }

    public function gerarRelatorio()
    {
        try 
        {
            $relatorioEstoque = $this->estoque->all();

            $relatorioFormatado = "";
            foreach ($relatorioEstoque as $item)
            {
                $relatorioFormatado .= "-Filial ID: {$item['filial_hospital_id']} | Medicamento ID: {$item['medicamento_id']} | Quantidade: {$item['quantidade']} | Quantidade Mínima {$item['quantidade']}-";

            }
            $prompt = "Você é um assistente de gestão de estoque hospitalar. Com base nos seguintes dados, gere um relatório detalhado:\n\n".
                "📋 **Estoque Atual:**\n".
                "$relatorioFormatado\n\n".
                "**Regras para a Análise:**\n".
                "1️⃣ Destaque medicamentos que estejam abaixo da quantidade mínima.\n".
                "2️⃣ Liste os medicamentos com maior e menor quantidade no estoque.\n".
                "3️⃣ Sugira medidas para reposição e balanceamento do estoque.\n".
                "4️⃣ Identifique padrões de consumo que possam impactar futuras compras.\n\n".
                "💡 **Retorne o relatório de maneira clara e organizada.**";
                echo json_encode(["mensagem" => "✅ Relatório gerado com sucesso!", "dados" => $prompt]);
        }
        catch (Exception $e)
        {
            error_log("Erro ao gerar_relatorio: " . $e->getMessage());
            echo json_encode(["erro" => "❌ Erro ao gerar relatorio."]);
        }
    }

    public function executarScriptPython()
    {
        // Caminho correto do Python dentro do ambiente virtual
        $pythonPath = "/var/www/html/aplicacao_web/provas/ads/hospital-web-app/venv_hospital/bin/python";
        

        $scriptPath = __DIR__ . '/../../scripts/googleai_service.py';
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

    private function isApiCall()
    {
        // Verifica se a requisição é uma chamada de API
        return isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;
    }
    private function isPostRequest()
    {
        // Verifica se a requisição é do tipo POST
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    private function isGetRequest()
    {
        // Verifica se a requisição é do tipo GET
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
    private function isPutRequest()
    {
        // Verifica se a requisição é do tipo PUT
        return $_SERVER['REQUEST_METHOD'] === 'PUT';
    }
    private function isDeleteRequest()
    {
        // Verifica se a requisição é do tipo DELETE
        return $_SERVER['REQUEST_METHOD'] === 'DELETE';
    }
    
    // Função para listar pacientes com paginação
    public function listarPaginado($pagina = 1, $limite = 10)
    {
        try {
            // Chama o método paginate do modelo
            $resultado = $this->estoque->paginate($pagina, $limite);

            if ($this->isApiCall()) {
                header('Content-Type: application/json');
                echo json_encode($resultado);
                exit;
            }
            return json_encode($resultado);
        } catch (Exception $e) {
            error_log("Erro ao listar pacientes paginados: " . $e->getMessage());
            echo json_encode(["erro" => "Erro ao listar pacientes paginados"]);
            exit;
        }
    }
    // Função para buscar um item por ID
    public function buscarPorId($id)
    {
        try {
            $item = $this->estoque->find($id);
            if ($item) {
                return json_encode($item);
            } else {
                return json_encode(["erro" => "⚠️ Item não encontrado!"]);
            }
        } catch (Exception $e) {
            error_log("Erro ao buscar item por ID: " . $e->getMessage());
            return json_encode(["erro" => "❌ Erro ao buscar item."]);
        }
    }

    /**
     * Atualiza os dados de um item no estoque.
     * @param int $id ID do item a ser atualizado
     * @param array $dados Dados a serem atualizados
     */
    public function atualizar($id, $dados)
    {
        try {
            // Verifica se o ID é válido
            if (!is_numeric($id)) {
                throw new Exception("⚠️ ID inválido.");
            }

            // Atualiza os dados do item
            $this->estoque->update($id, $dados);
            $response =[ "mensagem" => "✅ Item atualizado com sucesso!"];
            if ($this->isApiCall()) {
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
            
            // Se não for uma chamada de API, retorna a mensagem
            return json_encode($response);
        } catch (Exception $e) {
            error_log("Erro ao atualizar item: " . $e->getMessage());
            $response = ["erro" => "❌ Erro ao atualizar item: " . $e->getMessage()];
            if ($this->isApiCall()) {
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
            // Se for uma chamada de API, retorna o erro
            return json_encode($response);
        }
    }
     // Método para listar leitos com tipo, status, paciente e filial
     public function listarEstoque()
     {
         try 
         {
             $leitos = $this->estoque->allEstoque();
             return json_encode($leitos);
         }
         catch (Exception $e) 
         {
             return json_encode(["erro" => "Erro ao listar leitos: " . $e->getMessage()]);
         }
     }
}
?>
