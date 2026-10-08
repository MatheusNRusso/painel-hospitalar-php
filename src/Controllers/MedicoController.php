<?php

namespace App\Controllers;

use App\Models\Medico;
use Exception;

class MedicoController
{
    private $medico;

    public function __construct()
    {
        $this->medico = new Medico();
    }

    /**
     * Lista todos os médicos cadastrados no sistema.
     * @return string JSON com os médicos ou mensagem de erro.
     */
    public function listarTodos()
    {
        try {
            $medicos = $this->medico->all();
            echo json_encode($medicos);
        } catch (Exception $e) {
            echo json_encode(["erro" => "Erro ao listar médicos: " . $e->getMessage()]);
        }
    }

    /**
     * Cria um novo médico no sistema.
     * @param array $dados Dados do médico a ser criado.
     */
    public function criar($dados)
    {
        try {
            $existing = $this->medico->where(['nome' => $dados['nome']]);

            if (!empty($existing)) {
                echo json_encode(["erro" => "Médico já cadastrado."]);
            } else {
                $this->medico->create($dados);
                echo json_encode(["mensagem" => "✅ Médico criado com sucesso"]);
            }
        } catch (Exception $e) {
            error_log("Erro ao criar médico: " . $e->getMessage());
            echo json_encode(["erro" => "❌ Erro ao criar médico"]);
        }
    }

    /**
     * Atualiza os dados de um médico.
     * @param int $id ID do médico a ser atualizado.
     * @param array $dados Dados atualizados.
     * @return string JSON com a mensagem de sucesso ou erro.
     */
    public function atualizar($id, $dados)
    {
        try {
            if (!is_numeric($id)) {
                throw new Exception("⚠️ ID inválido.");
            }

            $this->medico->update($id, $dados);
            $response = ["mensagem" => "✅ Médico atualizado com sucesso!"];

            if ($this->isApiCall()) {
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }

            return json_encode($response);
        } catch (Exception $e) {
            error_log("Erro ao atualizar médico: " . $e->getMessage());
            $response = ["erro" => "❌ Erro ao atualizar médico: " . $e->getMessage()];

            if ($this->isApiCall()) {
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }

            return json_encode($response);
        }
    }

    /**
     * Deleta um médico com base no ID.
     * @param int $id ID do médico.
     */
    public function deletar($id)
    {
        try {
            $this->medico->delete($id);
            echo json_encode(["mensagem" => "✅ Médico deletado com sucesso"]);
        } catch (Exception $e) {
            error_log("Erro ao deletar médico: " . $e->getMessage());
            echo json_encode(["erro" => "❌ Erro ao deletar médico"]);
        }
    }
    
    
    /**
     * Busca um médico por ID.
     * @param int $id ID do médico.
     * @return string JSON com os dados do médico ou mensagem de erro.
     */
    public function buscarId($id)
    {
        try {
            $medico = $this->medico->find($id);

            if ($medico) {
                return json_encode($medico);
            } else {
                return json_encode(["erro" => "⚠️ Médico não encontrado!"]);
            }
        } catch (Exception $e) {
            error_log("Erro ao buscar médico por ID: " . $e->getMessage());
            return json_encode(["erro" => "❌ Erro ao buscar médico."]);
        }
    }

    /**
     * Busca um médico por ID.
     * @param int $id ID do médico.
     * @return string JSON com os dados do médico ou mensagem de erro.
     */
    public function buscarPorId($id)
    {
        try {
            $medico = $this->medico->find($id);

            if ($medico) {
                echo json_encode($medico);
            } else {
                echo json_encode(["erro" => "⚠️ Médico não encontrado!"]);
            }
        } catch (Exception $e) {
            error_log("Erro ao buscar médico por ID: " . $e->getMessage());
            echo json_encode(["erro" => "❌ Erro ao buscar médico."]);
        }
    }

    /**
     * Lista médicos paginados.
     * @param int $pagina Página atual.
     * @param int $limite Número de itens por página.
     * @return string JSON com os dados paginados.
     */
    public function listarPaginado($pagina = 1, $limite = 10)
    {
        try {
            $resultado = $this->medico->paginate($pagina, $limite);

            if ($this->isApiCall()) {
                header('Content-Type: application/json');
                echo json_encode($resultado);
                exit;
            }

            return json_encode($resultado);
        } catch (Exception $e) {
            error_log("Erro ao listar médicos paginados: " . $e->getMessage());
            echo json_encode(["erro" => "Erro ao listar médicos paginados"]);
            exit;
        }
    }

    /**
     * Verifica se a requisição é uma chamada de API.
     * @return bool Verdadeiro se for API.
     */
    private function isApiCall()
    {
        return isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;
    }

    // Método para listar leitos com tipo, status, paciente e filial
    public function listarMedicos()
    {
        try 
        {
            $listaMedico = $this->medico->allMedicos();
            return json_encode($listaMedico);
        }
        catch (Exception $e) 
        {
            return json_encode(["erro" => "Erro ao listar medicos: " . $e->getMessage()]);
        }
    }
}
