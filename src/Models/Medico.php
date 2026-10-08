<?php

namespace App\Models;

class Medico extends BaseModel 
{
    protected $table = 'medicos';

    public function getValidationRules() 
    {
        return [
            'nome' => 'required|string',
            'crm' => 'required|string',
            'especialidade_id' => 'required|int|exists:especialidades,id',
            'filial_hospital_id' => 'required|int|exists:filial_hospital,id'
        ];
    }

    // Método para listar médicos com nome da especialidade e da filial hospitalar
    public function allMedicos()
    {
        try {
            $sql = "SELECT 
                        medicos.*, 
                        especialidades.nome AS especialidade_nome, 
                        filial_hospital.nome AS filial_nome 
                    FROM medicos 
                    LEFT JOIN especialidades 
                        ON medicos.especialidade_id = especialidades.id 
                    LEFT JOIN filial_hospital 
                        ON medicos.filial_hospital_id = filial_hospital.id";

            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Erro ao buscar médicos com especialidade e filial: " . $e->getMessage());
        }
    }
}
