<?php

namespace App\Models;

class Paciente extends BaseModel {

    protected $table = 'pacientes';

    public function getValidationRules() 
    {
        return [
            'nome' => 'required|string',
            'cpf' => 'required|string|unique',
            'data_nascimento' => 'required|date',
            'peso' => 'required|float|min:0'
        ];
    }

    // Adiciona colunas permitidas para ordenação
    protected function getAllowedOrderColumns(): array
    {
        return ['id', 'nome', 'cpf', 'data_nascimento', 'peso'];
    }
}
?>
