<?php

namespace App\Models;

class TipoMedicamento extends BaseModel {

    protected $table = 'tipos_medicamentos';

    public function getValidationRules() 
    {
        return [
            'nome' => 'required|string',
        ];
    }
}

?>