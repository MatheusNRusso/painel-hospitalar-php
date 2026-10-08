<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\Relatorio;
use App\Controllers\RelatorioController;

try {
    // Testar com a tabela 'estoque'
    //$relatorio = new Relatorio('estoque');
    //$resultado = json_encode($relatorio->all());

    $relatorioia = new RelatorioController('estoque');
    $resultadoia = $relatorioia->gerarRelatorio();

    
 

    // Exibir resultado formatado
    //header('Content-Type: application/json');
    return $resultadoia;
} catch (Exception $e) {
    echo json_encode(["erro" => $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
?>
