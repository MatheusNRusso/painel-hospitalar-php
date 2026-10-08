<?php

namespace App\Controllers;

use App\Models\Especialidade;
use Exception;

class EspecialidadeController
{
    private $especialidade;
    
    public function __construct()
    {
        $this->especialidade = new Especialidade();
    }
    
    public function listarTodos()
    {
        try 
        {
            $especialidades = $this->especialidade->all();
            echo json_encode($especialidades);
        }
        catch (Exception $e) 
        {
            echo json_encode(["erro" => "Erro ao listar especialidades: " . $e->getMessage()]);
        }
    }

    public function criar($dados) 
    {
        try 
        {
            $existing = $this->especialidade->where(['nome' => $dados['nome']]);
            if (!empty($existing))
            {
                echo json_encode(["erro" => "Especialidade já cadastrada."]);
            }
            else
            { 
                $this->especialidade->create($dados);
                echo json_encode(["mensagem" => "Especialidade criada com sucesso"]);
            }
        }
        catch (Exception $e)
        {
            error_log("Erro ao criar especialidade: " . $e->getMessage());
            echo json_encode(["erro" => "Erro ao criar especialidade"]);
        }
    }

    public function atualizar($id, $dados) 
    {
        try 
        {
            $this->especialidade->update($id, $dados);
            echo json_encode(["mensagem" => "Especialidade atualizada com sucesso"]);
        }
        catch (Exception $e)
        {
            echo json_encode(["erro" => "Erro ao atualizar especialidade: " . $e->getMessage()]);
        }
    }

    public function deletar($id) 
    {
        try 
        {
            $this->especialidade->delete($id);
            echo json_encode(["mensagem" => "Especialidade deletada com sucesso"]);
        }
        catch (Exception $e)
        {
            echo json_encode(["erro" => "Erro ao deletar especialidade: " . $e->getMessage()]);
        }
    }
}
?>