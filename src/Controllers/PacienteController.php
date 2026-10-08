<?php
// Caminho para a pasta Controllers
namespace App\Controllers;

// Caminho para classe Paciente
// Biblioteca Exception
use App\Models\Paciente;
use Exception;

class PacienteController

{
    private $paciente;
    
    // Construtor da class PacientesController
    public function __construct()
    {   
        // Inicializa a propriedade $paciente
        $this->paciente = new Paciente();
    } 

    // Funçao para listar paciente return JSON
    public function listar()
    {
        try 
        {
            $resultado = $this->paciente->all();
            echo json_encode($resultado);
        }
        catch (Exception $e)
        {
            echo json_encode(["erro" => "Erro ao listar paciente" . $e->getMessage()]);
        }
    }
    // Funçao para listar paciente return JSON
    public function listarJSON()
    {
        try 
        {
            $resultado = $this->paciente->all();
            return json_encode($resultado);
        }
        catch (Exception $e)
        {
            return  json_encode(["erro" => "Erro ao listar paciente" . $e->getMessage()]);
        }
    }
    
    // Função para listar todos os pacientes
    public function listarTodos()
    {
        try 
        {
            $pacientes = $this->paciente->all();
            if ($this->isApiCall())
            {
                header('Content-Type: application/json');
                
                if (!$pacientes)
                {
                    echo json_encode(["erro" => "Nenhum paciente encontrado."]);
                }
                else 
                {
                    echo json_encode($pacientes);
                }
                exit;
            }
            return json_encode($pacientes);
        }
        catch (Exception $e) 
        {
            error_log("Erro ao listar paciente: " . $e->getMessage());
            $response = [
                "erro" => "Erro ao listar paciente",
                "detalhes" => $e->getMessage()
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    }

    // Cria um novo Paciente
    public function criar($dados) 
    {
        try 
        {
            // Verifica se o paciente existe
            $existing = $this->paciente->where(['nome' => $dados['nome']]);
            if (!empty($existing))
            {
                echo json_encode(["erro" => "Paciente já cadastrado."]);
            }
            else
            { 
                // Cria um novo Paciente
                $this->paciente->create($dados);
                echo json_encode(["mensagem" => "Paciente criado com sucesso"]);
            }
        }
        catch (Exception $e)
        {
            error_log("Erro ao criar paciente: " . $e->getMessage());
            echo json_encode(["erro" => "Erro ao criar paciente"]);
        }
    }

    /**
     * Atualiza um medicamento existente.
     */

     public function atualizar($id, $dados)
     {
         try {
             $this->paciente->update($id, $dados);
             $response = ["mensagem" => "✅ Paciente atualizado com sucesso!"];
             
             // Se for chamada via API (Postman)
             if ($this->isApiCall()) {
                 header('Content-Type: application/json');
                 echo json_encode($response);
                 exit;
             }
             
             // Se for chamada via formulário
             return json_encode($response);
             
         } catch (Exception $e) {
             error_log("Erro ao atualizar paciente: " . $e->getMessage());
             $response = ["erro" => "❌ Erro ao atualizar paciente."];
             
             if ($this->isApiCall()) {
                 header('Content-Type: application/json');
                 echo json_encode($response);
                 exit;
             }
             
             return json_encode($response);
         }
     }

    // Deletar um Paciente
    public function deletar($id)
    {
        try
        {
            $this->paciente->delete($id);
            echo json_encode(["mensagem" => "Paciente deletado com sucesso"]);
        }
        catch (Exception $e)
        {
            error_log("Erro ao deletar paciente: " . $e->getMessage());
            echo json_encode(["erro" => "Erro ao deletar paciente"]);
        }
    }

    private function isApiCall()
    {
        // Verifica se a requisição é uma chamada de API
        return isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;
    }
    private function isPostRequest()
    {
        // Verifica se a requisição é do tipo POST
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    private function isGetRequest()
    {
        // Verifica se a requisição é do tipo GET
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
    private function isPutRequest()
    {
        // Verifica se a requisição é do tipo PUT
        return $_SERVER['REQUEST_METHOD'] === 'PUT';
    }
    private function isDeleteRequest()
    {
        // Verifica se a requisição é do tipo DELETE
        return $_SERVER['REQUEST_METHOD'] === 'DELETE';
    }
    // Função para listar pacientes com paginação
    public function listarPaginado($pagina = 1, $limite = 10)
    {
        try {
            // Chama o método paginate do modelo
            $resultado = $this->paciente->paginate($pagina, $limite);

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

    // Função para buscar paciente por ID
    public function buscarPorId($id)
    {
        try {
            $paciente = $this->paciente->find($id);
            if ($this->isApiCall()) {
                header('Content-Type: application/json');
                echo json_encode($paciente);
                exit;
            }
            return json_encode($paciente);
        } catch (Exception $e) {
            error_log("Erro ao buscar paciente por ID: " . $e->getMessage());
            echo json_encode(["erro" => "Erro ao buscar paciente por ID"]);
            exit;
        }
    }

    /**
     * Busca um medicamento pelo ID.
     */
    public function buscar($id)
    {
        try {
            $pacienteEncontrado = $this->paciente->find($id); // Usa o método find() do modelo Medicamento
            if ($pacienteEncontrado) {
                return json_encode($pacienteEncontrado);
            } else {
                echo json_encode(["erro" => "⚠️ Paciente não encontrado!"]);
            }
        } catch (Exception $e) {
            error_log("Erro ao buscar paciente: " . $e->getMessage());
            echo json_encode(["erro" => "❌ Erro ao buscar paciente."]);
        }
    }
    /**
     * Busca o paciente pelo CPF.
     */
    public function buscarPorCpf($cpf)
    {
        try {
            $resultado = $this->paciente->where('cpf', $cpf)->get();

            if ($this->isApiCall()) {
                header('Content-Type: application/json');
                echo json_encode($resultado);
                exit;
            }
            return json_encode($resultado);
        } catch (Exception $e) {
            error_log("Erro ao buscar paciente: " . $e->getMessage());
            echo json_encode(["erro" => "Erro ao buscar paciente"]);
            exit;
        }
    }
    /**
     * Busca o paciente pelo nome.
     */
    public function buscarPorNome($nome)
    {
        try {
            $resultado = $this->paciente->where('nome', $nome)->get();

            if ($this->isApiCall()) {
                header('Content-Type: application/json');
                echo json_encode($resultado);
                exit;
            }
            return json_encode($resultado);
        } catch (Exception $e) {
            error_log("Erro ao buscar paciente: " . $e->getMessage());
            echo json_encode(["erro" => "Erro ao buscar paciente"]);
            exit;
        }
    }
    /**
 * Busca o paciente pelo nome ou cpf
 */
    public function buscarPorNomeOuCpf($termo)
    {
        try 
        {
            $resultado = $this->paciente
                ->where(['cpf' => $termo]); // primeiro tenta pelo CPF exato

            if (empty($resultado)) 
            {
                $resultado = $this->paciente
                    ->where(['nome' => $termo]); // se não encontrar por CPF, tenta pelo nome exato
            }
            header('Content-Type: application/json');
            echo json_encode($resultado);
            exit;
            
        } catch (Exception $e) {
            error_log("Erro ao buscar paciente: " . $e->getMessage());
            echo json_encode(["erro" => "Erro ao buscar paciente"]);
            exit;
        }
    }


    
    /**
     * Busca o paciente pelo nome ou cpf
     */
    public function buscarPorNomeOuCpfComLimite($termo, $limite)
    {
        try {
            $resultado = $this->paciente->where('nome', 'LIKE', "%$termo%")
                ->orWhere('cpf', 'LIKE', "%$termo%")
                ->limit($limite)
                ->get();

            if ($this->isApiCall()) {
                header('Content-Type: application/json');
                echo json_encode($resultado);
                exit;
            }
            return json_encode($resultado);
        } catch (Exception $e) {
            error_log("Erro ao buscar paciente: " . $e->getMessage());
            echo json_encode(["erro" => "Erro ao buscar paciente"]);
            exit;
        }
    }
    /**
     * Busca o paciente pelo nome ou cpf
     */
    public function buscarPorNomeOuCpfComLimiteEOrdem($termo, $limite, $ordem)
    {
        try {
            $resultado = $this->paciente->where('nome', 'LIKE', "%$termo%")
                ->orWhere('cpf', 'LIKE', "%$termo%")
                ->limit($limite)
                ->orderBy($ordem)
                ->get();

            if ($this->isApiCall()) {
                header('Content-Type: application/json');
                echo json_encode($resultado);
                exit;
            }
            return json_encode($resultado);
        } catch (Exception $e) {
            error_log("Erro ao buscar paciente: " . $e->getMessage());
            echo json_encode(["erro" => "Erro ao buscar paciente"]);
            exit;
        }
    }
    /**
     * Busca o paciente pelo nome ou cpf
     */
    public function buscarPorNomeOuCpfComLimiteEOrdemDesc($termo, $limite, $ordem)
    {
        try {
            $resultado = $this->paciente->where('nome', 'LIKE', "%$termo%")
                ->orWhere('cpf', 'LIKE', "%$termo%")
                ->limit($limite)
                ->orderBy($ordem, 'desc')
                ->get();

            if ($this->isApiCall()) {
                header('Content-Type: application/json');
                echo json_encode($resultado);
                exit;
            }
            return json_encode($resultado);
        } catch (Exception $e) {
            error_log("Erro ao buscar paciente: " . $e->getMessage());
            echo json_encode(["erro" => "Erro ao buscar paciente"]);
            exit;
        }
    }
    /**
     * Busca o paciente pelo nome ou cpf
     */
    public function buscarPorNomeOuCpfComLimiteEOrdemDescComOffset($termo, $limite, $ordem, $offset)
    {
        try {
            $resultado = $this->paciente->where('nome', 'LIKE', "%$termo%")
                ->orWhere('cpf', 'LIKE', "%$termo%")
                ->limit($limite)
                ->offset($offset)
                ->orderBy($ordem, 'desc')
                ->get();

            if ($this->isApiCall()) {
                header('Content-Type: application/json');
                echo json_encode($resultado);
                exit;
            }
            return json_encode($resultado);
        } catch (Exception $e) {
            error_log("Erro ao buscar paciente: " . $e->getMessage());
            echo json_encode(["erro" => "Erro ao buscar paciente"]);
            exit;
        }
    }
}
?>