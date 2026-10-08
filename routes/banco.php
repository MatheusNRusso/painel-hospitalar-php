<?php
use App\Config\Database; // Importa a classe Database
use App\Services\DatabaseService;

// Testar conexão com o banco de dados
if ($rota === 'testar-conexao') {
    try {
        $conexao = Database::getConnection();
        echo json_encode(["mensagem" => "✅ Conexão com o banco de dados bem-sucedida!"], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        error_log("Erro ao conectar ao banco de dados: " . $e->getMessage());
        echo json_encode(["erro" => "❌ Erro ao conectar ao banco de dados."]);
    }
    exit;
}

// Criar Banco e Tabelas
if ($rota === 'criar-banco') {
    try {
        $databaseService = new DatabaseService();
        $databaseService->criarBancoETabelas();
        echo json_encode(["mensagem" => "✅ Banco de dados e tabelas criados com sucesso!"]);
    } catch (Exception $e) {
        error_log("Erro ao criar banco de dados: " . $e->getMessage());
        echo json_encode(["erro" => "❌ Erro ao criar banco de dados."]);
    }
    exit;
}

// Verificar se o Banco já foi criado
if ($rota === 'verificar-banco') {
    try {
        $databaseService = new DatabaseService();
        if ($databaseService->verificarTabelaMedicamentos()) {
            echo json_encode(["mensagem" => "✅ O banco de dados e as tabelas já foram criados!"]);
        } else {
            echo json_encode(["mensagem" => "❌ O banco de dados ainda não foi criado."]);
        }
    } catch (Exception $e) {
        error_log("Erro ao verificar o banco de dados: " . $e->getMessage());
        echo json_encode(["erro" => "❌ Erro ao verificar o banco de dados."]);
    }
    exit;
}
?>