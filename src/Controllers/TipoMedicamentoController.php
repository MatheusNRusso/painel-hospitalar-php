<?php
namespace App\Controllers;

use App\Models\TipoMedicamento;
use Exception;

class TipoMedicamentoController 
{
    private $tipoMedicamento;

    public function __construct()
    {
        $this->tipoMedicamento = new TipoMedicamento();
    }

    public function listarJSON()
    {
        try
        {
            $tipo = $this->tipoMedicamento->all();
            echo json_encode($tipo); 
        }
        catch (Exception $e)
        {
            echo json_encode(["erro" => "Erro ao listar medicamento: " . $e->getMessage()]);
        }
    }

    // Listar todos os tipos de medicamentos
    public function listar()
    {
        try 
        {
            $tipo = $this->tipoMedicamento->all();

            if ($this->isApiCall()) 
            {
                header('Content-Type: application/json'); // ✅ Certo agora
               
                if (!$tipo) 
                {
                    echo json_encode(["erro" => "Nenhum tipo de medicamento encontrado."]);
                }
                else 
                {
                    echo json_encode($tipo);
                }
                exit;
            }
            return json_encode($tipo);
        } 
        catch (Exception $e) 
        {
            error_log("Erro ao listar tipos de medicamentos: " . $e->getMessage());
            header('Content-Type: application/json'); // ✅ Adicionado para garantir JSON válido
            echo json_encode(["erro" => "❌ Erro ao listar tipos de medicamentos."]);
            exit;
        }
    }



    // Criar um novo tipo de medicamento
    public function criar($dados)
    {
        try {
            // Verifica se o tipo já existe
            $existente = $this->tipoMedicamento->where(['nome' => $dados['nome']]);
            if (!empty($existente)) {
                echo json_encode(["erro" => "⚠️ Tipo de medicamento já cadastrado."]);
                return;
            }

            $this->tipoMedicamento->create($dados);
            echo json_encode(["mensagem" => "✅ Tipo de medicamento criado com sucesso!"]);
        } 
        catch (Exception $e) {
            error_log("Erro ao criar tipo de medicamento: " . $e->getMessage());
            echo json_encode(["erro" => "❌ Erro ao criar tipo de medicamento."]);
        }
    }

    // Atualizar um tipo de medicamento existente
    public function atualizar($id, $dados)
    {
        try {
            $this->tipoMedicamento->update($id, $dados);
            $response = ["mensagem" => "✅ Tipo de medicamento atualizado com sucesso!"];
            if ($this->isApiCall())
            {
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
            
            return json_encode($response);
        }
        catch (Exception $e) 
        {
            error_log("Erro ao atualizar tipo de medicamento: " . $e->getMessage());
            if ($this->isApiCall())
            {
                header('Content-Type: application/json');
                echo json_encode(["erro" => "❌ Erro ao atualizar tipo de medicamento."]);
                exit;
            }
            return json_encode(['erro' => "❌ Erro ao atualizar tipo de medicamento."]);
        }
    }

    // Deletar um tipo de medicamento
    public function deletar($id)
    {
        try {
            $this->tipoMedicamento->delete($id);
            echo json_encode(["mensagem" => "✅ Tipo de medicamento deletado com sucesso!"]);
        } 
        catch (Exception $e) {
            error_log("Erro ao deletar tipo de medicamento: " . $e->getMessage());
            echo json_encode(["erro" => "❌ Erro ao deletar tipo de medicamento."]);
        }
    }

    /**
     * Busca um tipo_medicamento pelo ID.
     */
    public function buscar($id)
    {
        try {
            $tipoEncontrado = $this->tipoMedicamento->find($id); // Usa o método find() do modelo Medicamento
            if ($tipoEncontrado) {
                return json_encode($tipoEncontrado);
            } else {
                echo json_encode(["erro" => "⚠️ Tipo de medicamento não encontrado!"]);
            }
        } catch (Exception $e) {
            error_log("Erro ao buscar tipo de medicamento: " . $e->getMessage());
            echo json_encode(["erro" => "❌ Erro ao buscar tipo de medicamento."]);
        }
    }

    private function isApiCall()
    {
        // Verifica se é uma chamada API (Postman)
        return (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false
            || strpos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false);
    }

}
?>
