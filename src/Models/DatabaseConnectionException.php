<?php

namespace App\Models;

use Exception;

class DatabaseConnectionException extends Exception 
{
    public function customMessage()
    {
        return "Erro na conexão com o Banco de Dados: ".$this->getMessage();
    }
}
?>