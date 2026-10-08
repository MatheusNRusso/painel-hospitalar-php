<?php

namespace App\Models;

class Receita extends BaseModel {

    protected $table = 'receitas';

    public function getValidationRules() {
        return [
            'paciente_id' => 'required|int|exists:pacientes,id',
            'medico_id' => 'required|int|exists:medicos,id',
            'medicamento_id' => 'required|int|exists:medicamentos,id',
            'dose' => 'required|int|min:1',
            'data_prescricao' => 'required|date'
        ];
    }
    public function allReceitas()
    {
        try 
        {
            $sql = "SELECT
                        receitas.*,
                        pacientes.nome AS paciente_nome,
                        medicos.nome AS medico_nome,
                        medicamentos.nome AS medicamento_nome
                    FROM receitas
                    LEFT JOIN pacientes ON receitas.paciente_id = pacientes.id
                    LEFT JOIN medicos ON receitas.medico_id = medicos.id
                    LEFT JOIN medicamentos ON receitas.medicamento_id = medicamentos.id";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        catch (\PDOException $e)
        {
            throw new \Exception("Erro ao buscar receitas: " . $e->getMessage());
        }
            
    }
}
?>