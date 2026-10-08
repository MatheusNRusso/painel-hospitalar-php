<?php
// Obter a rota da URL
$rota = $_GET['rota'] ?? 'home';

// Log para depuração
error_log("Rota acessada: $rota");

// Roteamento Padrão
if ($rota === 'home') {
    echo "<h1>Bem-vindo ao sistema</h1>";
    echo "<p>Use as rotas abaixo para testar as funcionalidades:</p>";
    echo "<ul>
            <li><a href='?rota=testar-conexao'>Testar Conexão com o Banco de Dados</a></li>
            <li><a href='?rota=verificar-banco'>Verificar se o Banco já foi criado</a></li>
            <li><a href='?rota=criar-banco'>Criar Banco e Tabelas</a></li>
            <li><a href='?rota=medicamento/listar'>Listar Medicamentos</a></li>
            <li><a href='?rota=medicamento/criar'>Criar Medicamentos</a></li>
            <li><a href='?rota=medicamento/remover-duplicatas'>Remover Duplicatas de Medicamentos</a></li>
            <li><a href='?rota=paciente/listar'>Listar Pacientes</a></li>
            <li><a href='?rota=paciente/criar'>Criar Pacientes</a></li>
            <li><a href='?rota=paciente/atualizar'>Atualizar Pacientes</a></li>
            <li><a href='?rota=paciente/deletar'>Remover Pacientes</a></li>
            <li><a href='?rota=estoque/listarItens'>Listar Itens do Estoque</a></li>
            <li><a href='?rota=estoque/adicionarItem'>Adicionar Item ao Estoque</a></li>
            <li><a href='?rota=estoque/atualizarQuantidade'>Atualizar Quantidade de um Item no Estoque</a></li>
            <li><a href='?rota=estoque/deletarItem'>Deletar Item do Estoque</a></li>
            <li><a href='?rota=estoque/verificarBalanceamento'>Verificar Balanceamento do Estoque</a></li>  
            <li><a href='?rota=receita/listar'>Listar Receitas</a></li>
            <li><a href='?rota=receita/criar'>Criar Receitas</a></li>
            <li><a href='?rota=receita/atualizar'>Atualizar Receitas</a></li>
            <li><a href='?rota=receita/deletar'>Remover Receitas</a></li>
            <li><a href='?rota=receita/buscar'>Buscar Receitas</a></li>
            <li><a href='?rota=tipo-medicamento/listar'>Listar Tipos de Medicamentos</a></li>
            <li><a href='?rota=tipo-medicamento/criar'>Criar Tipos de Medicamentos</a></li>
            <li><a href='?rota=tipo-medicamento/atualizar'>Atualizar Tipos de Medicamentos</a></li>
            <li><a href='?rota=tipo-medicamento/deletar'>Remover Tipos de Medicamentos</a></li>
          </ul>";
}

// Rotas relacionadas ao banco de dados
else if (in_array($rota, ['testar-conexao', 'verificar-banco', 'criar-banco'])) {
    require_once __DIR__ . '/banco.php';
    exit;
}

// Rotas relacionadas a medicamentos
else if (preg_match('/^medicamento\/.+$/', $rota)) {
    require_once __DIR__ . '/medicamentos.php';
    exit;
}

// Rotas relacionas a pacientes
else if (preg_match('/^paciente\/.+$/', $rota)) {
    require_once __DIR__ . '/pacientes.php';
    exit;
}

// Rotas realcionadas a estoque
else if (preg_match('/^estoque\/.+$/', $rota)) {
    require_once __DIR__ . '/estoque.php';
    exit;
}

// Rotas relacionadas a receitas
else if (preg_match('/^receita\/.+$/', $rota)) {
    require_once __DIR__ . '/receitas.php';
    exit;
}
// Rotas relacionadas a tipos de medicamentos
else if (preg_match('/^tipo-medicamento\/.+$/', $rota)) {
    require_once __DIR__ . '/tipoMedicamento.php';
    exit;
}
// Rota não encontrada
else {
    error_log("Rota não encontrada: $rota");
    echo json_encode(["erro" => "⚠️ Rota não encontrada: $rota"]);
}
?>