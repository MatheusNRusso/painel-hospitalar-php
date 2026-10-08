<?php

namespace App\Controllers;

use App\Models\Receita;
use Exception;

class ReceitaController
{
    private $receita;

    /**
     * Construtor da classe.
     * Inicializa a instância do modelo Receita.
     */
    public function __construct()
    {
        // Inicializa a propriedade $receita
        $this->receita = new Receita();
    }

     /**
     * Lista todas as receitas com os nomes.
     */
    public function listarReceitas()
    {
        try 
        {
            $receitas = $this->receita->allReceitas(); // Usa o método all() do modelo Receita
            return json_encode($receitas);
        } 
        catch (Exception $e) 
        {
            error_log("Erro ao listar receitas: " . $e->getMessage());
            return json_encode(["erro" => "❌ Erro ao listar receitas."]);
        }
    }


    /**
     * Lista todas as receitas.
     */
    public function listar()
    {
        try 
        {
            $receitas = $this->receita->all(); // Usa o método all() do modelo Receita
            echo json_encode($receitas);
        } 
        catch (Exception $e) 
        {
            error_log("Erro ao listar receitas: " . $e->getMessage());
            echo json_encode(["erro" => "❌ Erro ao listar receitas."]);
        }
    }

    /**
     * Cria uma nova receita.
     */
    public function criar($dados)
    {
        try {
            // Verifica se os dados obrigatórios estão presentes
            if (!isset(
                $dados['paciente_id'],
                $dados['medico_id'],
                $dados['medicamento_id'],
                $dados['dose'],
                $dados['data_prescricao']
            )) 
            {
                throw new Exception("⚠️ Todos os campos obrigatórios devem ser preenchidos.");
            }

            // Verifica se a receita já existe
            $existing = $this->receita->where([
                'paciente_id' => $dados['paciente_id'],
                'medico_id' => $dados['medico_id'],
                'medicamento_id' => $dados['medicamento_id'],
                'dose' => $dados['dose'],
                'data_prescricao' => $dados['data_prescricao']
            ]);

            if (!empty($existing)) {
                echo json_encode([
                    "mensagem" => "⚠️ Receita já cadastrada. Redirecionando...",
                    "status" => "existente"
                ]);
                return;
            }

            // Cria a receita
            $this->receita->create($dados);

            // Retorna a confirmação e status de sucesso
            echo json_encode([
                "mensagem" => "✅ Receita criada com sucesso! Redirecionando...",
                "status" => "novo"
            ]);
        } catch (Exception $e) {
            error_log("Erro ao criar receita: " . $e->getMessage());
            echo json_encode(["erro" => "❌ Erro ao criar receita."]);
        }
    }

    /**
     * Atualiza uma receita existente.
     */
    public function atualizar($id, $dados)
    {
        try {
           
            // Verifica se os dados obrigatórios estão presentes
            if (!isset(
                $dados['paciente_id'],
                $dados['medico_id'],
                $dados['medicamento_id'],
                $dados['dose'],
                $dados['data_prescricao']
            )) {
                throw new Exception("⚠️ Todos os campos obrigatórios devem ser preenchidos.");
            }

            // Atualiza a receita
            $this->receita->update($id, $dados); // Usa o método update() do modelo Receita
            $response = ["mensagem" => "✅ Receita atualizada com sucesso!"];

            if ($this->isApiCall()) {
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
            return json_encode(["mensagem" => "✅ Receita atualizada com sucesso!"]);
        } catch (Exception $e) {
            error_log("Erro ao atualizar receita: " . $e->getMessage());
            $response = ["erro" => "❌ Erro ao atualizar receita: " . $e->getMessage()];

            if ($this->isApiCall()) {
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }

            return json_encode(["erro" => "❌ Erro ao atualizar receita."]);
        }
    }

    /**
     * Deleta uma receita.
     */
    public function deletar($id)
    {
        try {
            $this->receita->delete($id); // Usa o método delete() do modelo Receita
            echo json_encode(["mensagem" => "✅ Receita deletada com sucesso!"]);
        } catch (Exception $e) {
            error_log("Erro ao deletar receita: " . $e->getMessage());
            echo json_encode(["erro" => "❌ Erro ao deletar receita."]);
        }
    }

    /**
     * Busca uma receita pelo ID.
     */
    public function buscar($id)
    {
        try {
            $receitaEncontrada = $this->receita->find($id); // Usa o método find() do modelo Receita
            if ($receitaEncontrada) {
                echo json_encode($receitaEncontrada);
            } else {
                echo json_encode(["erro" => "⚠️ Receita não encontrada!"]);
            }
        } catch (Exception $e) {
            error_log("Erro ao buscar receita: " . $e->getMessage());
            echo json_encode(["erro" => "❌ Erro ao buscar receita."]);
        }
    }

     /**
     * Busca uma receita pelo ID com return.
     */
    public function buscarPorId($id)
    {
        try {
            $receitaEncontrada = $this->receita->find($id); // Usa o método find() do modelo Receita
            if ($receitaEncontrada) {
                return json_encode($receitaEncontrada);
            } else {
                return json_encode(["erro" => "⚠️ Receita não encontrada!"]);
            }
        } catch (Exception $e) {
            error_log("Erro ao buscar receita: " . $e->getMessage());
            return json_encode(["erro" => "❌ Erro ao buscar receita."]);
        }
    }

    /**
     * Remove receitas duplicadas, mantendo apenas um registro por paciente, medicamento, dose e data.
     */
    public function removerDuplicatas()
    {
        try {
            // Consulta todas as receitas do banco de dados
            $receitas = $this->receita->all();

            // Array para rastrear combinações já processadas
            $combinacoesProcessadas = [];
            $registrosExcluidos = [];

            foreach ($receitas as $receita) {
                $chave = implode('-', [
                    $receita['paciente_id'],
                    $receita['medico_id'],
                    $receita['medicamento_id'],
                    $receita['dose'],
                    $receita['data_prescricao']
                ]);

                // Se a combinação já foi processada, exclui o registro duplicado
                if (in_array($chave, $combinacoesProcessadas)) {
                    $this->receita->delete($receita['id']);
                    $registrosExcluidos[] = $receita; // Registra o registro excluído
                    continue;
                }

                // Adiciona a combinação à lista de combinações processadas
                $combinacoesProcessadas[] = $chave;
            }

            echo json_encode([
                "mensagem" => "✅ Duplicatas removidas com sucesso!",
                "registros_excluidos" => $registrosExcluidos
            ]);
        } catch (Exception $e) {
            error_log("Erro ao remover duplicatas: " . $e->getMessage());
            echo json_encode(["erro" => "❌ Erro ao remover duplicatas."]);
        }
    }
    /**
     * Verifica se a chamada é uma API.
     */
    private function isApiCall()
    {
        return isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;
    }
    /**
     * Lista consultas paginadas.
     */
    public function listarPaginado($pagina = 1, $limite = 10)
    {
        try {
            $resultado = $this->receita->paginate($pagina, $limite);

            if ($this->isApiCall()) {
                header('Content-Type: application/json');
                echo json_encode($resultado);
                exit;
            }

            return json_encode($resultado);
        } catch (Exception $e) {
            error_log("Erro ao listar receitas paginadas: " . $e->getMessage());
            echo json_encode(["erro" => "Erro ao listar receitas paginadas"]);
            exit;
        }
    }

}
?>