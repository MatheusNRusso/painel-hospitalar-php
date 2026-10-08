<?php

namespace App\Controllers;

use App\Models\TipoLeito;
use Exception;

class TipoLeitoController
{
    private $tipoLeito;

    public function __construct()
    {
        $this->tipoLeito = new TipoLeito();
    }

    public function listarTodos()
    {
        try 
        {
            $tiposLeitos = $this->tipoLeito->all();
            echo json_encode($tiposLeitos);
        }
        catch (Exception $e) 
        {
            echo json_encode(["erro" => "Erro ao listar tipos de leitos: " . $e->getMessage()]);
        }
    }

    public function criar($dados) 
    {
        try 
        {
            $existing = $this->tipoLeito->where(['nome' => $dados['nome']]);
            if (!empty($existing))
            {
                echo json_encode(["erro" => "Tipo de leito já cadastrado."]);
            }
            else
            { 
                $this->tipoLeito->create($dados);
                echo json_encode(["mensagem" => "Tipo de leito criado com sucesso"]);
            }
        }
        catch (Exception $e)
        {
            error_log("Erro ao criar tipo de leito: " . $e->getMessage());
            echo json_encode(["erro" => "Erro ao criar tipo de leito"]);
        }
    }

    public function atualizar($id, $dados) 
    {
        try 
        {
            $this->tipoLeito->update($id, $dados);
            echo json_encode(["mensagem" => "Tipo de leito atualizado com sucesso"]);
        }
        catch (Exception $e)
        {
            error_log("Erro ao atualizar tipo de leito: " . $e->getMessage());
            echo json_encode(["erro" => "Erro ao atualizar tipo de leito"]);
        }
    }

    public function deletar($id) 
    {
        try 
        {
            $this->tipoLeito->delete($id);
            echo json_encode(["mensagem" => "Tipo de leito deletado com sucesso"]);
        }
        catch (Exception $e)
        {
            error_log("Erro ao deletar tipo de leito: " . $e->getMessage());
            echo json_encode(["erro" => "Erro ao deletar tipo de leito"]);
        }
    }

    public function listarLeitos($id)
    {
        try 
        {
            $leitos = $this->tipoLeito->find($id)->leitos;
            echo json_encode($leitos);
        }
        catch (Exception $e) 
        {
            echo json_encode(["erro" => "Erro ao listar leitos: " . $e->getMessage()]);
        }
    }

    public function adicionarLeito($id, $dados)
    {
        try 
        {
            $tipoLeito = $this->tipoLeito->find($id);
            $tipoLeito->leitos()->create($dados);
            echo json_encode(["mensagem" => "Leito adicionado ao tipo de leito com sucesso"]);
        }
        catch (Exception $e)
        {
            error_log("Erro ao adicionar leito ao tipo de leito: " . $e->getMessage());
            echo json_encode(["erro" => "Erro ao adicionar leito ao tipo de leito"]);
        }
    }

    public function removerLeito($id, $leitoId)
    {
        try 
        {
            $tipoLeito = $this->tipoLeito->find($id);
            $tipoLeito->leitos()->detach($leitoId);
            echo json_encode(["mensagem" => "Leito removido do tipo de leito com sucesso"]);
        }
        catch (Exception $e)
        {
            error_log("Erro ao remover leito do tipo de leito: " . $e->getMessage());
            echo json_encode(["erro" => "Erro ao remover leito do tipo de leito"]);
        }
    }
}
?>