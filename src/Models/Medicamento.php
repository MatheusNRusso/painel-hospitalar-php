<?php

namespace App\Models;
use PDO;
use PDOException;

class Medicamento extends BaseModel 
{
    protected $table = 'medicamentos';

    public function getValidationRules() 
    {
        return [
            'nome' => 'required|string',
            'tipo_id' => 'required|int|exists:tipos_medicamentos,id',
            'doses_por_caixa' => 'required|int|min:1',
            'preco_por_caixa' => 'required|float|min:0'
        ];
    }

    protected function getAllowedOrderColumns(): array
    {
        return ['id', 'nome', 'tipo_id', 'doses_por_caixa', 'preco_por_caixa'];
    }

    // Método allTipos() para retornar o nome de Tipo de Medicamento
    public function allTipo()
    {
        try {
            $sql = "SELECT 
                        medicamentos.*, 
                        tipos_medicamentos.nome AS tipo_nome 
                    FROM medicamentos 
                    LEFT JOIN tipos_medicamentos 
                        ON medicamentos.tipo_id = tipos_medicamentos.id";

            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Erro ao buscar medicamentos com tipo: " . $e->getMessage());
        }
    }

    public function listarComTipoPaginado($limite, $offset)
    {
        $sql = "SELECT 
                    medicamentos.*, 
                    tipos_medicamentos.nome AS tipo_nome 
                FROM medicamentos 
                LEFT JOIN tipos_medicamentos ON medicamentos.tipo_id = tipos_medicamentos.id 
                LIMIT :limite OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarTotal()
    {
        $sql = "SELECT COUNT(*) as total FROM medicamentos";
        return (int)$this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC)['total'];
    }


}
?>