<?php

namespace App\Models;
use PDO;
use PDOException;

class Exame extends BaseModel 
{
    protected $table = 'exame';

    public function getValidationRules() 
    {
        return [
            'prontuario_id' => 'required|int|exists:prontuario,id',
            'paciente_id' => 'required|int|exists:paciente,id',
            'tipo' => 'required|string',
            'descricao' => 'required|string',
            'resultado' => 'nullable|string',
            'data_exame' => 'required|date'
        ];
    }

    protected function getAllowedOrderColumns(): array
    {
        return [
            'id', 
            'prontuario_id', 
            'paciente_id', 
            'tipo', 
            'descricao', 
            'resultado', 
            'data_exame'
        ];
    }

    public function allTipo()
    {
        try {
            $sql = "SELECT 
                        exame.*, 
                        tipos_exames.nome AS tipo_nome 
                    FROM exame 
                    LEFT JOIN tipos_exames 
                        ON exame.tipo_id = tipos_exame.id";

            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Erro ao buscar exames com tipo: " . $e->getMessage());
        }
    }

    public function listarComTipoPaginado($limite, $offset)
    {
        $sql = "SELECT 
                    exames.*, 
                    tipos_exames.nome AS tipo_nome 
                FROM exames 
                LEFT JOIN tipos_exames ON exames.tipo_id = tipos_exames.id 
                LIMIT :limite OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>