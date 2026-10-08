<?php

namespace App\Models;


class Estoque extends BaseModel {
    
    protected $table = 'estoque';
    
    public function getValidationRules() 
    {
        return [
            'filial_hospital_id' => 'required|int|exists:filial_hospital,id',
            'medicamento_id' => 'required|int|exists:medicamentos,id',
            'quantidade' => 'required|int|min:6|max:100',
            'quantidade_minima' => 'required|int|min:5'
        ];
    }

    // Método allEstoque() para retornar o nome de Medicamento e Filial
    public function allEstoque()
    {
        try {
            $sql = "SELECT 
                        estoque.*, 
                        medicamentos.nome AS medicamento_nome, 
                        filial_hospital.nome AS filial_nome 
                    FROM estoque 
                    LEFT JOIN medicamentos 
                        ON estoque.medicamento_id = medicamentos.id
                    LEFT JOIN filial_hospital 
                        ON estoque.filial_hospital_id = filial_hospital.id";
            
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Erro ao buscar estoque com medicamento e filial: " . $e->getMessage());
        }
    }
}
?>


  