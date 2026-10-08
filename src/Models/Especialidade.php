<?php

namespace App\Models;

class Especialidade extends BaseModel 
{
    protected $table = 'especialidades';

    public function getValidationRules() 
    {
        return [
            'nome' => 'required|string',
            'descricao' => 'required|string'
        ];
    }
}

?>