<?php
// Carregar as dependências do Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Incluir a classe Database
require_once __DIR__ . '/../src/Config/Database.php';

use App\Config\Database; // Certifique-se de que o namespace esteja correto

try {
    // Obter a conexão com o banco de dados
    $pdo = Database::getConnection();
    echo "✅ Conexão bem-sucedida com o banco de dados!\n";
} catch (\Exception $e) {
    echo "❌ Erro ao conectar ao banco de dados: " . $e->getMessage() . "\n";
}
?>