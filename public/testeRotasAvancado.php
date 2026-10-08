<?php
// Obter a rota da URL
$rota = $_GET['rota'] ?? 'home';

// Log para depuração
error_log("Rota acessada: $rota");

// Roteamento Padrão
if ($rota === 'home') {
    echo "<h1>Use as rotas para testar o sistema</h1>";
    echo "<ul>
            <li><a href='?rota=medico/listar'>Listar Medicos</a></li>
            <li><a href='?rota=medico/criar'>Criar Medicos</a></li>
            <li><a href='?rota=medico/atualizar'>Atualizar Médico</a></li>
            <li><a href='?rota=medico/deletar'>Deletar Médico</a></li>
            <li><a href='?rota=leito/listar'>Listar Leitos</a></li>
            <li><a href='?rota=leito/criar'>Criar Leitos</a></li>
            <li><a href='?rota=leito/atualizar'>Atualizar Leitos</a></li>
            <li><a href='?rota=leito/deletar'>Remover Leitos</a></li>
            <li><a href='?rota=consulta/listar'>Listar Consultas</a></li>
            <li><a href='?rota=consulta/criar'>Criar Consulta</a></li>
            <li><a href='?rota=consulta/atualizar'>Atualizar Consulta</a></li>
            <li><a href='?rota=consulta/deletar'>Deletar Consulta</a></li>
            <li><a href='?rota=especialidade/listar'>Listar Especialidades</a></li>  
            <li><a href='?rota=especialidade/criar'>Criar Especialidades</a></li>
            <li><a href='?rota=especialidade/deletar'>Deletar Especilidades</a></li>
            <li><a href='?rota=especialidade/atualizar'>Atualizar Especialidades</a></li>
            <li><a href='?rota=tipoStatus/listar'>Listar Status</a></li>
            <li><a href='?rota=tipoStatus/criar'>Criar Status</a></li>
            <li><a href='?rota=tipoStatus/atualizar'>Atualizar Status</a></li>
            <li><a href='?rota=tipoStatus/deletar'>Deletar Status</a></li>
            <li><a href='?rota=tipoLeito/listar'>Listar os tipos de leitos</a></li>
            <li><a href='?rota=tipoLeito/criar'>Criar um tipo leito</a></li>
            <li><a href='?rota=tipoLeito/atualizar'>Atualizar um tipo leito</a></li>
            <li><a href='?rota=tipoLeito/deletar'>Deletar um tipo leito</a></li>
            <li><a href='?rota=filialHospital/listar'>Listar as Filiais</a></li>
            <li><a href='?rota=filialHospital/criar'>Criar uma nova Filial</a></li>
            <li><a href='?rota=filialHospital/atualizar'>Atualizar Filial</a></li>
            <li><a href='?rota=filialHospital/deletar'>Deletar Filial</a></li>
        </ul>";
}

// Rotas relacionadas ao banco de dados
else if (in_array($rota, ['testar-conexao', 'verificar-banco', 'criar-banco'])) {
    require_once __DIR__ . '/banco.php';
    exit;
}

// Rotas relacionadas a medicamentos
else if (preg_match('/^medico\/.+$/', $rota)) {
    require_once __DIR__ . '/medicos.php';
    exit;
}

// Rotas relacionas a pacientes
else if (preg_match('/^consulta\/.+$/', $rota)) {
    require_once __DIR__ . '/consultas.php';
    exit;
}

// Rotas realcionadas a estoque
else if (preg_match('/^leito\/.+$/', $rota)) {
    require_once __DIR__ . '/leitos.php';
    exit;
}

// Rotas relacionadas a receitas
else if (preg_match('/^filialHospital\/.+$/', $rota)) {
    require_once __DIR__ . '/filialHospital.php';
    exit;
}
// Rotas relacionadas a tipos de medicamentos
else if (preg_match('/^especialidade\/.+$/', $rota)) {
    require_once __DIR__ . '/especialidades.php';
    exit;
}

// Rotas relacionadas a tipos de leitos
else if (preg_match('/^tipoLeito\/.+$/', $rota)) {
    require_once __DIR__ . '/tipoLeito.php';
    exit;
}
// Rotas relacionadas a tipos de status
else if (preg_match('/^tipoStatus\/.+$/', $rota)) {
    require_once __DIR__ . '/tipoStatus.php';
    exit;
}
// Rotas relacionadas a filialHospital
else if (preg_match('/^filialHospital\/.+$/', $rota)) {
    require_once __DIR__ . '/filialHospital.php';
    exit;
}

// Rota não encontrada
else {
    error_log("Rota não encontrada: $rota");
    echo json_encode(["erro" => "⚠️ Rota não encontrada: $rota"]);
}
?>