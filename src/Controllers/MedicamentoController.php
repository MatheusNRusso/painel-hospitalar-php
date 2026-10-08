<?php
namespace App\Controllers;

use App\Models\Medicamento;
use Exception;

class MedicamentoController
{
    private $medicamento;

    /**
     * Construtor da classe.
     * Inicializa a instância do modelo Medicamento.
     */
    public function __construct()
    {
        // Inicializa a propriedade $medicamento
        $this->medicamento = new Medicamento();
    }
    public function listarJSON()
    {
        try 
        {
            $resultado = $this->medicamento->all();
            echo json_encode($resultado);
        }
        catch (Exception $e)
        {
            echo json_encode(["erro" => "Erro ao listar medicamento" . $e->getMessage()]);
        }
    }
    /**
     * Retorna todos os medicamentos com o nome do tipo de medicamento.
     */
    public function listarComTipo()
    {
        try 
        {
            $medicamentos = $this->medicamento->allTipo(); // Usa o método allTipo() do modelo Medicamento
            return json_encode($medicamentos);
        } 
        catch (Exception $e) 
        {
            error_log("Erro ao listar medicamentos com tipo: " . $e->getMessage());
            return json_encode(["erro" => "❌ Erro ao listar medicamentos com tipo."]);
        }
    }


    /**
     * Lista todos os medicamentos.
     */
    public function listar()
    {
        try 
        {
            $medicamentos = $this->medicamento->all(); // Usa o método all() do modelo Medicamento
           
            if ($this->isApiCall())
            {
                header('Content-Type: aplication/json');
                
                if (!$medicamentos)
                {
                    echo json_encode(["erro" => "Nenhum medicamento encontrado."]);
                }
                else 
                {
                    echo json_encode($medicamentos);
                }
                exit;
            }
            return json_encode($medicamentos);
        } 
        catch (Exception $e) 
        {
            error_log("Erro ao listar medicamento: " . $e->getMessage());
            $response = ["erro" => "❌ Erro ao listar medicamento."];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
            
    }
    

    /**
     * Cria um novo medicamento.
     */
    public function criar($dados)
    {
        try {
            // Verifica se os dados obrigatórios estão presentes
            if (empty($dados['nome']) || empty($dados['tipo_id']) || empty($dados['doses_por_caixa']) || empty($dados['preco_por_caixa'])) {
                echo json_encode(["erro" => "⚠️ Todos os campos são obrigatórios."]);
                return;
            }

            // Verifica se o medicamento já existe
            $existing = $this->medicamento->where(['nome' => $dados['nome']]);
            if (!empty($existing)) {
                echo json_encode([
                    "mensagem" => "⚠️ Medicamento já cadastrado. Redirecionando...",
                    "status" => "existente"
                ]);
                return;
            }

            // Cria o medicamento
            $this->medicamento->create($dados);
            
            // Retorna a confirmação e status de sucesso
            echo json_encode([
                "mensagem" => "✅ Medicamento criado com sucesso! Redirecionando...",
                "status" => "novo"
            ]);
        } catch (Exception $e) {
            error_log("Erro ao criar medicamento: " . $e->getMessage());
            echo json_encode(["erro" => "❌ Erro ao criar medicamento."]);
        }
    }


    /**
     * Atualiza um medicamento existente.
     */

    public function atualizar($id, $dados)
    {
        try {
            $this->medicamento->update($id, $dados);
            $response = ["mensagem" => "✅ Medicamento atualizado com sucesso!"];
            
            // Se for chamada via API (Postman)
            if ($this->isApiCall()) {
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
            
            // Se for chamada via formulário
            return json_encode($response);
            
        } catch (Exception $e) {
            error_log("Erro ao atualizar medicamento: " . $e->getMessage());
            $response = ["erro" => "❌ Erro ao atualizar medicamento."];
            
            if ($this->isApiCall()) {
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
            
            return json_encode($response);
        }
    }
    
    /**
     * Deleta um medicamento.
     */
    public function deletar($id)
    {
        try 
        {
            $this->medicamento->delete($id);
              
            if ($this->isApiCall()) 
            {
                header('Content-Type: application/json');
                echo json_encode(['mensagem' => '✅ Medicamento removido com sucesso!']);
                exit;
            }
            return json_encode(['mensagem' => '✅ Medicamento removido com sucesso!']);
        } 
        catch (\Exception $e) 
        {
            error_log("Erro ao deletar medicamento: " . $e->getMessage());
              
            if ($this->isApiCall()) 
            {
                header('Content-Type: application/json');
                echo json_encode(['erro' => '❌ ' . $e->getMessage()]);
                exit;
            }
            return json_encode(['erro' => '❌ ' . $e->getMessage()]);
        }
    }
  

    private function isApiCall()
    {
        // Verifica se é uma chamada API (Postman)
        return (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false
            || strpos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false);
    }

    /**
     * Busca um medicamento pelo ID.
     */
    public function buscar($id)
    {
        try {
            $medicamentoEncontrado = $this->medicamento->find($id); // Usa o método find() do modelo Medicamento
            if ($medicamentoEncontrado) {
                return json_encode($medicamentoEncontrado);
            } else {
                echo json_encode(["erro" => "⚠️ Medicamento não encontrado!"]);
            }
        } catch (Exception $e) {
            error_log("Erro ao buscar medicamento: " . $e->getMessage());
            echo json_encode(["erro" => "❌ Erro ao buscar medicamento."]);
        }
    }

    /**
     * Remove medicamentos duplicados, mantendo apenas um registro por nome.
     */
    public function removerDuplicatas()
    {
        try {
            // Consulta todos os medicamentos do banco de dados
            $medicamentos = $this->medicamento->all();

            // Array para rastrear nomes já processados
            $nomesProcessados = [];
            $registrosExcluidos = [];

            foreach ($medicamentos as $medicamento) {
                $nome = $medicamento['nome'];

                // Se o nome já foi processado, exclui o registro duplicado
                if (in_array($nome, $nomesProcessados)) {
                    $this->medicamento->delete($medicamento['id']);
                    $registrosExcluidos[] = $medicamento; // Registra o registro excluído
                    continue;
                }

                // Adiciona o nome à lista de nomes processados
                $nomesProcessados[] = $nome;
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

    public function listarComTipoPaginado($offset, $limite, $busca = '', $tipoId = null)
    {
        //$medicamentoModel = new Medicamento();
        return json_encode($this->medicamento->listarComTipoPaginado($offset, $limite, $busca, $tipoId));
    }


    public function contarTotalMedicamentos()
    {
        //$medicamento = new \App\Models\Medicamento();
        return $this->medicamento->contarTotal();
    }

}