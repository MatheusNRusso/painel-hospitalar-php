<?php

namespace App\Models;
use PDO;
use PDOException;

class Leito extends BaseModel 
{
    protected $table = 'leitos';

    public function getValidationRules() 
    {
        return [
            'numero' => 'required|int|min:1',
            'tipo_id' => 'required|int|exists:tipos_leitos,id',
            'status_id' => 'required|int|exists:tipos_status,id',
            'paciente_id' => 'nullable|int|exists:pacientes,id',
            'filial_hospital_id' => 'required|int|exists:filial_hospital,id'
        ];
    }

    // Método allTipos() para retornar o nome de Tipo de Leito
    public function allTipo()
    {
        try {
            $sql = "SELECT 
                        leitos.*, 
                        tipos_leitos.nome AS tipo_nome 
                    FROM leitos 
                    LEFT JOIN tipos_leitos 
                        ON leitos.tipo_id = tipos_leitos.id";

            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Erro ao buscar leitos com tipo: " . $e->getMessage());
        }
    }
    // Método allStatus() para retornar o nome de Status de Leito
    public function allStatus()
    {
        try {
            $sql = "SELECT 
                        leitos.*, 
                        tipos_status.nome AS status_nome 
                    FROM leitos 
                    LEFT JOIN tipos_status 
                        ON leitos.status_id = tipos_status.id";

            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Erro ao buscar leitos com status: " . $e->getMessage());
        }
    }
    // Método allPacientes() para retornar o nome de Paciente
    public function allPacientes()
    {
        try {
            $sql = "SELECT 
                        leitos.*, 
                        pacientes.nome AS paciente_nome 
                    FROM leitos 
                    LEFT JOIN pacientes 
                        ON leitos.paciente_id = pacientes.id";

            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Erro ao buscar leitos com paciente: " . $e->getMessage());
        }
    }
    // Método allFiliais() para retornar o nome de Filial de Hospital
    public function allFiliais()
    {
        try {
            $sql = "SELECT 
                        leitos.*, 
                        filial_hospital.nome AS filial_nome 
                    FROM leitos 
                    LEFT JOIN filial_hospital 
                        ON leitos.filial_hospital_id = filial_hospital.id";

            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Erro ao buscar leitos com filial: " . $e->getMessage());
        }
    }
    // Método allLeitos() para retornar todos os leitos com os nomes dos tipos, status, pacientes e filiais
    public function allLeitos()
    {
        try {
            $sql = "SELECT 
                        leitos.*, 
                        tipos_leitos.nome AS tipo_nome, 
                        tipos_status.nome AS status_nome, 
                        pacientes.nome AS paciente_nome, 
                        filial_hospital.nome AS filial_nome
                    FROM leitos 
                    LEFT JOIN tipos_leitos 
                        ON leitos.tipo_id = tipos_leitos.id
                    LEFT JOIN tipos_status 
                        ON leitos.status_id = tipos_status.id
                    LEFT JOIN pacientes 
                        ON leitos.paciente_id = pacientes.id
                    LEFT JOIN filial_hospital 
                        ON leitos.filial_hospital_id = filial_hospital.id";

            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Erro ao buscar leitos com todos os detalhes: " . $e->getMessage());
        }
    }
}
?>