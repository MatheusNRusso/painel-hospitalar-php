<?php

namespace App\Controllers;

use App\Models\Leito;
use Exception;

class LeitoController 
{
    private $leito;
    
    public function __construct()
    {
        $this->leito = new Leito();
    }

    // Funçao para listar paciente return JSON
    public function listar()
    {
        try 
        {
            $resultado = $this->leito->all();
            echo json_encode($resultado);
        }
        catch (Exception $e)
        {
            echo json_encode(["erro" => "Erro ao listar paciente" . $e->getMessage()]);
        }
    }
    
    public function listarTodos()
    {
        try 
        {
            $leitos = $this->leito->all();
            return json_encode($leitos);
        }
        catch (Exception $e) 
        {
            return  json_encode(["erro" => "Erro ao listar leitos: " . $e->getMessage()]);
        }
    }

    // Método para listar leitos com tipo, status, paciente e filial
    public function listarLeito()
    {
        try 
        {
            $leitos = $this->leito->allLeitos();
            return json_encode($leitos);
        }
        catch (Exception $e) 
        {
            return json_encode(["erro" => "Erro ao listar leitos: " . $e->getMessage()]);
        }
    }
     // Método para listarConsultas  com tipo, status, paciente e filial 
     public function listarConsulta()
     {
         try 
         {
             $leitos = $this->leito->allLeitos();
             echo json_encode($leitos);
         }
         catch (Exception $e) 
         {
             echo json_encode(["erro" => "Erro ao listar leitos: " . $e->getMessage()]);
         }
     }



    public function criar($dados) 
    {
        try 
        {
            $existing = $this->leito->where(['numero' => $dados['numero']]);
            if (!empty($existing))
            {
                echo json_encode(["erro" => "Leito já cadastrado."]);
            }
            else
            { 
                // ✅ Aqui está o tratamento para paciente_id
                $dados['paciente_id'] = isset($dados['paciente_id']) && $dados['paciente_id'] !== '' ? $dados['paciente_id'] : null;

                $this->leito->create($dados);
                echo json_encode(["mensagem" => "Leito criado com sucesso"]);
            }
        }
        catch (Exception $e)
        {
            error_log("Erro ao criar leito: " . $e->getMessage());
            echo json_encode(["erro" => "Erro ao criar leito"]);
        }
    }
    /**
     * Atualiza os dados do Leito.
     * @param int $id ID do leito a ser atualizado
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
            $this->leito->update($id, $dados);
            $response =[ "mensagem" => "✅ Leito atualizado com sucesso!"];
            if ($this->isApiCall()) {
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
            
            // Se não for uma chamada de API, retorna a mensagem
            return json_encode($response);
        } catch (Exception $e) {
            error_log("Erro ao atualizar leito: " . $e->getMessage());
            $response = ["erro" => "❌ Erro ao atualizar leito: " . $e->getMessage()];
            if ($this->isApiCall()) {
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
            // Se for uma chamada de API, retorna o erro
            return json_encode($response);
        }
    }


    // Função para buscar um item por ID
    public function buscarPorId($id)
    {
        try {
            $item = $this->leito->find($id);
            if ($item) {
                return json_encode($item);
            } else {
                return json_encode(["erro" => "⚠️ Leito não encontrado!"]);
            }
        } catch (Exception $e) {
            error_log("Erro ao buscar leito por ID: " . $e->getMessage());
            return json_encode(["erro" => "❌ Erro ao buscar leito."]);
        }
    }


    public function deletar($id) 
    {
        try 
        {
            $this->leito->delete($id);
            echo json_encode(["mensagem" => "Leito deletado com sucesso"]);
        }
        catch (Exception $e)
        {
            error_log("Erro ao deletar leito: " . $e->getMessage());
            echo json_encode(["erro" => "Erro ao deletar leito"]);
        }
    }

    // Paginação para listar leitos
    // Adiciona o método listarPaginado
    public function listarPaginado($pagina = 1, $limite = 10)
    {
        try {
            // Chama o método paginate do modelo
            $resultado = $this->leito->paginate($pagina, $limite);

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
    // Método para verificar se a requisição é uma chamada de API
    // Adiciona o método isApiCall
    private function isApiCall()
    {
        // Verifica se a requisição é uma chamada de API
        return isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;
    }




}
?>