<?php

namespace App\Models;

class Consulta extends BaseModel 
{
    protected $table = 'consulta'; // Nome da tabela no banco de dados

    /**
     * Define as regras de validação para criar/atualizar uma consulta.
     */
    public function getValidationRules() 
    {
        return [
            'paciente_id' => 'required|int|exists:pacientes,id',
            'medico_id' => 'required|int|exists:medicos,id',
            'filial_hospital_id' => 'required|int|exists:filial_hospital,id',
            'leito_id' => 'nullable|int|exists:leitos,id', // Campo opcional
            'data_consulta' => 'required|date',
            'descricao' => 'required|string',
            'status' => 'required|in:Agendada,Realizada,Cancelada'
        ];
    }

    /**
     * 
     */
    public function allConsultas()
    {
        try {
            $sql = "SELECT 
                        consulta.*, 
                        pacientes.nome AS paciente_nome, 
                        medicos.nome AS medico_nome, 
                        filial_hospital.nome AS filial_nome, 
                        leitos.numero AS leito_numero
                    FROM consulta 
                    LEFT JOIN pacientes ON consulta.paciente_id = pacientes.id
                    LEFT JOIN medicos ON consulta.medico_id = medicos.id
                    LEFT JOIN filial_hospital ON consulta.filial_hospital_id = filial_hospital.id
                    LEFT JOIN leitos ON consulta.leito_id = leitos.id";
            
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Erro ao buscar consultas: " . $e->getMessage());
        }
    }
}
?>