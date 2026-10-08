<?php

namespace App\Models;

class FilialHospital extends BaseModel 
{
    protected $table = 'filial_hospital'; // Nome da tabela no banco de dados

    /**
     * Define as regras de validação para criar/atualizar uma filial do hospital.
     */
    public function getValidationRules() 
    {
        return [
            'nome' => 'required|string|min:3|max:255', // Nome é obrigatório e deve ter entre 3 e 255 caracteres
            'quantidade_leitos' => 'required|int|min:1', // Quantidade de leitos é obrigatório e deve ser um número inteiro maior que 0
            'endereco' => 'required|string|min:3|max:255'// Endereço é obrigatório e deve ter entre 3 e 255 caracteres
        ];  
    }
}