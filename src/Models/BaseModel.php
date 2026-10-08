<?php
namespace App\Models;

use PDO;
use PDOException;
use App\Config\Database;

abstract class BaseModel 
{
    protected $pdo;
    protected $table;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    // 1. Create (Criar)
    public function create(array $data)
    {
        try {
            // Valida os dados antes de criar
            $this->validate($data, $this->getValidationRules());

            // Monta a query de inserção
            $columns = implode(', ', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));
            $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
            $stmt = $this->pdo->prepare($sql);

            // Associa os valores aos placeholders
            foreach ($data as $key => $value) {
                // Trata explicitamente valores null
                if ($value === null) {
                    $stmt->bindValue(":$key", null, PDO::PARAM_NULL);
                } else {
                    $stmt->bindValue(":$key", $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
                }
            }

            // Executa a query
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao criar registro: " . $e->getMessage());
            error_log("Dados recebidos: " . print_r($data, true));
            throw new \Exception("Erro ao criar o registro: " . $e->getMessage());
        }
    }
    

    // 2. Read (Ler)
    public function all()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM {$this->table}");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new \Exception("Erro ao ler os registros: " . $e->getMessage());
        }
    }

    // Buscar registro por ID
    public function find($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new \Exception("Erro ao buscar o registro: " . $e->getMessage());
        }
    }

    // Buscar registro ou falhar
    public function findOrFail($id)
    {
        $record = $this->find($id);
        if (!$record) {
            throw new \Exception("Registro não encontrado.");
        }
        return $record;
    }

    // 3. Update (Atualizar)
    public function update($id, array $data)
    {
        try {
            $existingRecord = $this->find($id);
            if (!$existingRecord) {
                throw new \Exception("Registro não encontrado.");
            }

            // Garante que os dados obrigatórios estejam presentes
            $mergedData = array_merge($existingRecord, $data);
            $this->validate($mergedData, $this->getValidationRules());

            $setClause = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));
            $sql = "UPDATE {$this->table} SET $setClause WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new \Exception("Erro ao atualizar o registro: " . $e->getMessage());
        }
    }

    // 4. Delete (Deletar)
    public function delete($id)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new \Exception("Erro ao deletar o registro: " . $e->getMessage());
        }
    }

    // 5. Buscar registros com condições
    public function where(array $conditions, string $operator = '=')
    {
        try {
            $whereClause = implode(' AND ', array_map(fn($key) => "$key $operator :$key", array_keys($conditions)));
            $sql = "SELECT * FROM {$this->table} WHERE $whereClause";
            $stmt = $this->pdo->prepare($sql);
            foreach ($conditions as $key => $value) {
                $stmt->bindValue(":$key", $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new \Exception("Erro ao buscar registros: " . $e->getMessage());
        }
    }

    // 6. Contar registros
    public function count()
    {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM {$this->table}");
            $stmt->execute();
            return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (PDOException $e) {
            throw new \Exception("Erro ao contar os registros: " . $e->getMessage());
        }
    }

    // 7. Paginar registros
    public function paginate($page = 1, $perPage = 10, $orderBy = 'id', $direction = 'ASC')
    {
        try {
            // Validação de segurança
            $allowedColumns = $this->getAllowedOrderColumns();
            $allowedDirections = ['ASC', 'DESC'];

            // Normaliza e valida entrada
            $orderBy = in_array($orderBy, $allowedColumns) ? $orderBy : 'id';
            $direction = in_array(strtoupper($direction), $allowedDirections) ? strtoupper($direction) : 'ASC';

            $offset = ($page - 1) * $perPage;

            // Monta SQL com ordenação
            $sql = "SELECT * FROM {$this->table} ORDER BY {$orderBy} {$direction} LIMIT :limit OFFSET :offset";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            // Contagem total de registros
            $total = $this->count();
            $totalPages = ceil($total / $perPage);

            return [
                'data' => $stmt->fetchAll(PDO::FETCH_ASSOC),
                'pagination' => [
                    'total' => $total,
                    'pages' => $totalPages,
                    'currentPage' => $page,
                    'perPage' => $perPage,
                ],
            ];
        } catch (PDOException $e) {
            throw new \Exception("Erro ao paginar os registros: " . $e->getMessage());
        }
    }
    // Lista de colunas que podem ser usadas para ordenação (pode ser sobrescrita nas classes filhas)
    protected function getAllowedOrderColumns(): array
    {
        return ['id']; // padrão seguro, sobrescreva nas filhas conforme necessário
    }




    // 8. Validação dos registros
    protected function validate(array $data, array $rules)
    {
        foreach ($rules as $field => $ruleString) {
            $value = $data[$field] ?? null;
            $rulesList = explode('|', $ruleString);

            // Se for nullable e o valor for null, pula outras validações
            if (in_array('nullable', $rulesList) && is_null($value)) {
                continue;
            }

            // Validações individuais
            foreach ($rulesList as $rule) {
                if ($rule === 'required' && !isset($data[$field])) {
                    throw new \Exception("O campo $field é obrigatório.");
                }

                if ($rule === 'int' && !is_int($value)) {
                    throw new \Exception("O campo $field deve ser um número inteiro.");
                }

                if ($rule === 'string' && !is_string($value)) {
                    throw new \Exception("O campo $field deve ser uma string.");
                }

                if ($rule === 'date' && !strtotime($value)) {
                    throw new \Exception("O campo $field deve ser uma data válida.");
                }

                if (strpos($rule, 'length:') === 0) {
                    $length = (int) explode(':', $rule)[1];
                    if (strlen($value) !== $length) {
                        throw new \Exception("O campo $field deve ter exatamente $length caracteres.");
                    }
                }

                if (strpos($rule, 'min:') === 0) {
                    $min = (int) explode(':', $rule)[1];
                    if ($value < $min) {
                        throw new \Exception("O campo $field deve ser maior ou igual a $min.");
                    }
                }

                if (strpos($rule, 'max:') === 0) {
                    $max = (int) explode(':', $rule)[1];
                    if ($value > $max) {
                        throw new \Exception("O campo $field deve ser menor ou igual a $max.");
                    }
                }

                if (strpos($rule, 'exists:') === 0) {
                    // Verifica se valor é null antes de aplicar o exists
                    if (!is_null($value)) {
                        [$table, $column] = explode(',', substr($rule, 7));
                        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM $table WHERE $column = :value");
                        $stmt->execute(['value' => $value]);
                        if ($stmt->fetchColumn() == 0) {
                            throw new \Exception("O valor informado para '$field' não existe na tabela '$table'.");
                        }
                    }
                }
            }
        }
    }


    // 9. Validação que será sobrescrita pelas classes filhas
    protected function getValidationRules()
    {
        return [];
    }

}
?>