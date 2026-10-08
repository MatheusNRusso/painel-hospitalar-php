<?php

namespace App\Controllers;

use App\Models\TipoStatus;
use Exception;

class TipoStatusController
{
    private $tipoStatus;
    
    public function __construct()
    {
        $this->tipoStatus = new TipoStatus();
    }
    
    public function listarTodos()
    {
        try 
        {
            $tipoStatus = $this->tipoStatus->all();
            echo json_encode($tipoStatus);
        }
        catch (Exception $e) 
        {
            echo json_encode(["erro" => "Erro ao listar tipos de status: " . $e->getMessage()]);
        }
    }

    public function criar($dados) 
    {
        try 
        {
            $existing = $this->tipoStatus->where(['nome' => $dados['nome']]);
            if (!empty($existing))
            {
                echo json_encode(["erro" => "Tipo de status já cadastrado."]);
            }
            else
            { 
                $this->tipoStatus->create($dados);
                echo json_encode(["mensagem" => "Tipo de status criado com sucesso"]);
            }
        }
        catch (Exception $e)
        {
            error_log("Erro ao criar tipo de status: " . $e->getMessage());
            echo json_encode(["erro" => "Erro ao criar tipo de status"]);
        }
    }

    public function atualizar($id, $dados) 
    {
        try 
        {
            $this->tipoStatus->update($id, $dados);
            echo json_encode(["mensagem" => "Tipo de status atualizado com sucesso"]);
        }
        catch (Exception $e)
        {
            echo json_encode(["erro" => "Erro ao atualizar tipo de status"]);
        }
    }
}
?>