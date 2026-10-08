<?php

namespace App\Controllers;

use App\Models\FilialHospital;
use Exception;

class FilialHospitalController
{
    private $filialHospital;

    public function __construct()
    {
        $this->filialHospital = new FilialHospital();
    }

    public function listarTodos()
    {
        try 
        {
            $filiaisHospitais = $this->filialHospital->all();
            echo json_encode($filiaisHospitais);
        }
        catch (Exception $e) 
        {
            echo json_encode(["erro" => "Erro ao listar filiais/hospitais: " . $e->getMessage()]);
        }
    }

    public function criar($dados) 
    {
        try 
        {
            $existing = $this->filialHospital->where(['nome' => $dados['nome']]);
            if (!empty($existing))
            {
                echo json_encode(["erro" => "Filial/Hospital já cadastrado."]);
            }
            else
            { 
                $this->filialHospital->create($dados);
                echo json_encode(["mensagem" => "Filial/Hospital criado com sucesso"]);
            }
        }
        catch (Exception $e)
        {
            error_log("Erro ao criar filial/hospital: " . $e->getMessage());
            echo json_encode(["erro" => "Erro ao criar filial/hospital"]);
        }
    }

    public function atualizar($id, $dados) 
    {
        try 
        {
            $this->filialHospital->update($id, $dados);
            echo json_encode(["mensagem" => "Filial/Hospital atualizado com sucesso"]);
        }
        catch (Exception $e)
        {
            error_log("Erro ao atualizar filial/hospital: " . $e->getMessage());
            echo json_encode(["erro" => "Erro ao atualizar filial/hospital"]);
        }
    }

    public function deletar($id) 
    {
        try 
        {
            $this->filialHospital->delete($id);
            echo json_encode(["mensagem" => "Filial/Hospital deletado com sucesso"]);
        }
        catch (Exception $e)
        {
            error_log("Erro ao deletar filial/hospital: " . $e->getMessage());
            echo json_encode(["erro" => "Erro ao deletar filial/hospital"]);
        }
    }

    public function buscarPorId($id)
    {
        try {
            $filialHospitalEncontrado = $this->filialHospital->find($id);
            if ($filialHospitalEncontrado) {
                echo json_encode($filialHospitalEncontrado);
            } else {
                echo json_encode(["erro" => "Filial/Hospital não encontrado"]);
            }
        } catch (Exception $e) {
            echo json_encode(["erro" => "Erro ao buscar filial/hospital: " . $e->getMessage()]);
        }
    }

    public function buscarPorNome($nome)
    {
        try {
            $filialHospitalEncontrado = $this->filialHospital->where(['nome' => $nome]);
            if ($filialHospitalEncontrado) {
                echo json_encode($filialHospitalEncontrado);
            } else {
                echo json_encode(["erro" => "Filial/Hospital não encontrado"]);
            }
        } catch (Exception $e) {
            echo json_encode(["erro" => "Erro ao buscar filial/hospital: " . $e->getMessage()]);
        }
    }


}
?>