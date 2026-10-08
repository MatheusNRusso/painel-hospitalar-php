<?php

use App\Controllers\RelatorioController;

// Obtém a tabela capturada pela rota
$tabela = $_REQUEST['tabela'] ?? null;

// Verifica se o nome da tabela foi passado corretamente
if (!$tabela) {
    echo json_encode(["erro" => "❌ Nenhuma tabela especificada na URL. Use: relatorio/listar/{tabela}"]);
    exit;
}

// Obtém a rota da URL (caso não esteja definida, assume vazia para evitar erro)
$rota = $_GET['rota'] ?? '';

// Instancia o controlador de relatório
try {
    $relatorioController = new RelatorioController($tabela);
} catch (Exception $e) {
    echo json_encode(["erro" => "❌ Erro ao inicializar RelatorioController: " . $e->getMessage()]);
    exit;
}

// Rota para listar os dados da tabela
if (preg_match('/^relatorio\/listar\/([\w-]+)$/', $rota)) {
    echo $relatorioController->gerarRelatorio();
    exit;
}

if (preg_match('/^relatorio\/executar-relatorio\/([\w-]+)$/', $rota)) {
    $relatorio = $relatorioController->executarScriptPython();
    echo $relatorio;
    exit;
}


// Se nenhuma rota correspondente for encontrada
echo json_encode(["erro" => "❌ Rota inválida ou não encontrada."]);
http_response_code(404);
exit;

?>
