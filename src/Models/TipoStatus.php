<?php

namespace App\Models;

class TipoStatus extends BaseModel 
{
    protected $table = 'tipos_status'; // Nome da tabela no banco de dados

    /**
     * Define as regras de validação para criar/atualizar um tipo de status.
     */
    public function getValidationRules() 
    {
        return [
            'nome' => 'required|string|min:3|max:100', // Nome é obrigatório e deve ter entre 3 e 100 caracteres
            'descricao' => 'nullable|string', // Descrição é opcional
        ];
    }
}
?>