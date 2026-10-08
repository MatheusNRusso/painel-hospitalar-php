<?php

namespace App\Models;

class Relatorio extends BaseModel
{
    protected $table;
    private $tabelasPermitidas; 


    public function __construct($tabela)
    {
        parent::__construct();

        $this->tabelasPermitidas = self::getTabelasPermitidas();
        
        // Verifica se a tabela é permitida
        if (!in_array($tabela, $this->tabelasPermitidas)) 
        {
            throw new \Exception("⚠️ Tabela inválida ou não permitida: $tabela");
        }
        
        $this->table = $tabela;
    }

    public static function getTabelasPermitidas()
    {
        // Obtém a string das tabelas do .env
        $tabelas = $_ENV['TABELAS_PERMITIDAS'] ?? '';
    
        // Divide a string em um array e remove espaços extras
        return array_map('trim', explode(',', $tabelas));
    }
    
}
