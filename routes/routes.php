<?php
// Obter a rota da URL
$rota = $_GET['rota'] ?? 'home';

// Definir as rotas em um array associativo
$rotas = [
    "testar-conexao" => "/banco.php",
    "verificar-banco" => "/banco.php",
    "criar-banco" => "/banco.php",
    
    "medicamento/listar" => "/medicamentos.php",
    "medicamento/criar" => "/medicamentos.php",
    "medicamento/remover" => "/medicamentos.php",

    "paciente/listar" => "/pacientes.php",
    "paciente/criar" => "/pacientes.php",
    "paciente/atualizar" => "/pacientes.php",
    "paciente/deletar" => "/pacientes.php",

    "medico/listar" => "/medicos.php",
    "medico/criar" => "/medicos.php",
    "medico/atualizar" => "/medicos.php",
    "medico/deletar" => "/medicos.php",

    "consulta/listar" => "/consultas.php",
    "consulta/criar" => "/consultas.php",
    "consulta/atualizar" => "/consultas.php",
    "consulta/deletar" => "/consultas.php",
    "consulta/buscar" => "/consultas.php",
    "consulta/buscar-paciente" => "/consultas.php",
    "consulta/buscar-medico" => "/consultas.php",

    "tipoLeito/listar" => "/tipoLeito.php",
    "tipoLeito/criar" => "/tipoLeito.php",
    "tipoLeito/atualizar" => "/tipoLeito.php",
    "tipoLeito/deletar" => "/tipoLeito.php",

    "tipoStatus/listar" => "/tipoStatus.php",
    "tipoStatus/criar" => "/tipoStatus.php",
    "tipoStatus/atualizar" => "/tipoStatus.php",
    "tipoStatus/deletar" => "/tipoStatus.php", 

    "leito/listar" => "/leitos.php",
    "leito/criar" => "/leitos.php",
    "leito/atualizar" => "/leitos.php",
    "leito/deletar" => "/leitos.php",

    "estoque/listar" => "/estoque.php",
    "estoque/adicionarItem" => "/estoque.php",
    "estoque/atualizar" => "/estoque.php",
    "estoque/deletarItem" => "/estoque.php",
    
    "receita/listar" => "/receitas.php",
    "receita/criar" => "/receitas.php",
    "receita/atualizar" => "/receitas.php",
    "receita/deletar" => "/receitas.php",

    "tipo-medicamento/listar" => "/tipoMedicamento.php",
    "tipo-medicamento/criar" => "/tipoMedicamento.php",
    "tipo-medicamento/atualizar" => "/tipoMedicamento.php",
    "tipo-medicamento/deletar" => "/tipoMedicamento.php"
];

// Verificar se a rota existe no array
if (isset($rotas[$rota])) 
{
    require_once __DIR__ . $rotas[$rota];
    exit;
}
?>