<?php

namespace App\Controllers;

use App\Models\Consulta;
use Exception;

class ConsultaController
{
    private $consulta;

    /**
     * Construtor da classe.
     * Inicializa a instância do modelo Consulta.
     */
    public function __construct()
    {
        $this->consulta = new Consulta();
    }

    /**
     * Lista todas as consultas.
     */
    public function listarTodos()
    {
        try {
            $consultas = $this->consulta->all();
            echo json_encode($consultas);
        } catch (Exception $e) {
            echo json_encode(["erro" => "❌ Erro ao listar consultas: " . $e->getMessage()]);
        }
    }

    /**
     * Lista as consultas com informações relacionadas (paciente, médico, filial etc.).
     * Este método depende de um método adicional no modelo Consulta (ex: allConsultas).
     */
    public function listarConsulta()
    {
        try {
            $consultas = $this->consulta->allConsultas();
            return json_encode($consultas);
        } catch (Exception $e) {
            return json_encode(["erro" => "Erro ao listar consultas: " . $e->getMessage()]);
        }
    }

    /**
     * Cria uma nova consulta.
     */
    public function criar($dados)
    {
        try {
            // Verifica campos obrigatórios
            if (!isset(
                $dados['paciente_id'],
                $dados['medico_id'],
                $dados['filial_hospital_id'],
                $dados['data_consulta'],
                $dados['descricao'],
                $dados['status']
            )) {
                throw new Exception("⚠️ Todos os campos obrigatórios devem ser preenchidos.");
            }

            // Confere conflitos de horário para o médico
            $conflitoMedico = $this->consulta->where([
                'data_consulta' => $dados['data_consulta'],
                'medico_id' => $dados['medico_id']
            ]);

            if (!empty($conflitoMedico)) {
                throw new Exception("⚠️ Este médico já possui uma consulta neste horário.");
            }

            // Confere conflitos para o paciente
            $conflitoPaciente = $this->consulta->where([
                'data_consulta' => $dados['data_consulta'],
                'paciente_id' => $dados['paciente_id']
            ]);

            if (!empty($conflitoPaciente)) {
                throw new Exception("⚠️ Este paciente já possui uma consulta neste horário.");
            }

            // Trata campo opcional
            $dados['leito_id'] = isset($dados['leito_id']) && $dados['leito_id'] !== '' ? $dados['leito_id'] : null;

            // Cria a consulta
            $novaConsulta = $this->consulta->create($dados);

            echo json_encode([
                "mensagem" => "✅ Consulta criada com sucesso!",
                "consulta_id" => $novaConsulta['id'] ?? null
            ]);
        } catch (Exception $e) {
            echo json_encode(["erro" => $e->getMessage()]);
        }
    }
    
    /**
     * Atualiza uma consulta existente.
     */
    public function atualizar($id, $dados)
    {
        try {
            if (!is_numeric($id)) {
                throw new Exception("⚠️ ID inválido.");
            }

            $this->consulta->update($id, $dados);
            $response = ["mensagem" => "✅ Consulta atualizada com sucesso!"];

            if ($this->isApiCall()) {
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }

            return json_encode($response);
        } catch (Exception $e) {
            error_log("Erro ao atualizar consulta: " . $e->getMessage());
            $response = ["erro" => "❌ Erro ao atualizar consulta: " . $e->getMessage()];

            if ($this->isApiCall()) {
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }

            return json_encode($response);
        }
    }

    /**
     * Busca uma consulta pelo ID.
     */
    public function buscarPorId($id)
    {
        try {
            $consulta = $this->consulta->find($id);
            if ($consulta) {
                return json_encode($consulta);
            } else {
                return json_encode(["erro" => "⚠️ Consulta não encontrada!"]);
            }
        } catch (Exception $e) {
            error_log("Erro ao buscar consulta: " . $e->getMessage());
            return json_encode(["erro" => "❌ Erro ao buscar consulta."]);
        }
    }

    /**
     * Deleta uma consulta.
     */
    public function deletar($id)
    {
        try {
            $this->consulta->delete($id);
            echo json_encode(["mensagem" => "Consulta deletada com sucesso"]);
        } catch (Exception $e) {
            error_log("Erro ao deletar consulta: " . $e->getMessage());
            echo json_encode(["erro" => "❌ Erro ao deletar consulta."]);
        }
    }

    /**
     * Lista consultas paginadas.
     */
    public function listarPaginado($pagina = 1, $limite = 10)
    {
        try {
            $resultado = $this->consulta->paginate($pagina, $limite);

            if ($this->isApiCall()) {
                header('Content-Type: application/json');
                echo json_encode($resultado);
                exit;
            }

            return json_encode($resultado);
        } catch (Exception $e) {
            error_log("Erro ao listar consultas paginadas: " . $e->getMessage());
            echo json_encode(["erro" => "Erro ao listar consultas paginadas"]);
            exit;
        }
    }

    /**
     * Verifica se a requisição é uma chamada de API.
     */
    private function isApiCall()
    {
        return isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;
    }

    /**
     * Busca consultas por paciente. 
     */
    public function buscarPorPaciente($pacienteId)
    {
        try {
            $consultas = $this->consulta->where(['paciente_id' => $pacienteId]);

            header('Content-Type: application/json');
            echo json_encode($consultas);
            exit;
        } catch (Exception $e) {
            error_log("Erro ao buscar consultas: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(["erro" => "Erro ao buscar consultas"]);
            exit;
        }
    }

    /**
     * Busca consultas por médico.
     */
    public function buscarPorMedico($medicoId)
    {
        try {
            $consultas = $this->consulta->where(['medico_id' => $medicoId]);
            return json_encode($consultas);
        } catch (Exception $e) {
            error_log("Erro ao buscar consultas por médico: " . $e->getMessage());
            return json_encode(["erro" => "❌ Erro ao buscar consultas por médico."]);
        }
    }

}
