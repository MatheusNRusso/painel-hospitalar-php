<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Config/Database.php'; // Ajuste o caminho conforme necessário
require_once __DIR__ . '/../src/Models/BaseModel.php';

use App\Config\Database; // Ajuste o namespace conforme necessário
use App\Models\BaseModel;

class TestModel extends BaseModel {
    protected $table = 'test_table';

    // Regras de validação
    protected function getValidationRules()
    {
        return [
            'name' => 'required|string',
            'description' => 'required|string'
        ];
    }
}

$testModel = new TestModel();

try {
    // Create
    echo "Criando registro...\n";
    $created = $testModel->create([
        'name' => 'Teste',
        'description' => 'Este é um teste'
    ]);
    if ($created) {
        echo "Registro criado com sucesso!\n";
    } else {
        echo "Falha ao criar o registro!\n";
    }

    // Read (all)
    echo "Listando todos os registros:\n";
    $allRecords = $testModel->all();
    print_r($allRecords);

    // Buscar ID específico
    $id = $allRecords[0]['id'] ?? null;
    if ($id) {
        echo "Buscando registro com ID: $id\n";
        $record = $testModel->findOrFail($id);
        print_r($record);

        // Update
        echo "Atualizando registro...\n";
        $updated = $testModel->update($id, ['description' => 'Descrição atualizada']);
        echo $updated ? "Registro atualizado!\n" : "Falha na atualização!\n";

        // Buscar após atualização
        echo "Verificando atualização...\n";
        print_r($testModel->find($id));

        // Delete
        echo "Deletando registro...\n";
        $deleted = $testModel->delete($id);
        echo $deleted ? "Registro deletado!\n" : "Falha ao deletar!\n";
    } else {
        echo "Nenhum ID encontrado para testar find, update e delete.\n";
    }

    // Teste de paginação (opcional)
    echo "Testando paginação...\n";
    $paginatedRecords = $testModel->paginate(1, 5);
    print_r($paginatedRecords);

} catch (\Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
?>