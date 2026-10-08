<?php

namespace App\Core;

class Router
{
    private $routes = [];

    /**
     * Adiciona uma rota ao roteador.
     *
     * @param string $route A rota (ex: "medicamento/listar").
     * @param string $controller O arquivo que será carregado (ex: "medicamentos.php").
     */
    public function addRoute($route, $controller)
    {
        $this->routes[$route] = $controller;
    }

    /**
     * Processa a requisição e redireciona para o arquivo correspondente.
     */
    public function handleRequest()
    {
        // Obtém a rota da URL ou usa 'home' como padrão
        $rota = $_GET['rota'] ?? 'login';

        if ($this->isRelatorioRoute($rota)) {
            $this->handleRelatorioRequest($rota);
        } elseif (isset($this->routes[$rota])) {
            $this->handleStaticRoute($rota);
        } else {
            $this->sendNotFoundResponse();
        }
    }

    private function isRelatorioRoute($rota)
    {
        return preg_match('/^relatorio\/(listar|executar-relatorio)\/([\w-]+)$/', $rota);
    }

    private function handleRelatorioRequest($rota)
    {
        preg_match('/^relatorio\/(listar|executar-relatorio)\/([\w-]+)$/', $rota, $matches);
        $_REQUEST['tabela'] = $matches[2];
        require_once __DIR__ . '/../../routes/relatorio.php';
        exit;
    }

    private function handleStaticRoute($rota)
    {
        $rotaArquivo = file_exists(__DIR__ . '/../../routes/' . $this->routes[$rota])
            ? __DIR__ . '/../../routes/' . $this->routes[$rota]
            : __DIR__ . '/../../views/' . $this->routes[$rota];

        if (file_exists($rotaArquivo)) {
            require_once $rotaArquivo;
            exit;
        } else {
            echo json_encode(["erro" => "⚠️ Arquivo da rota não encontrado!"]);
            http_response_code(404);
        }
    }

    private function sendNotFoundResponse()
    {
        echo json_encode(["erro" => "⚠️ Rota não encontrada!"]);
        http_response_code(404);
    }

    
}

// Instancia o roteador e adiciona as rotas
$router = new Router();

// Rotas de Banco de Dados
$router->addRoute("testar-conexao", "banco.php");
$router->addRoute("verificar-banco", "banco.php");
$router->addRoute("criar-banco", "banco.php");

// Rotas de Medicamento
$router->addRoute("medicamento/listar", "medicamentos.php");
$router->addRoute("medicamento/criar", "medicamentos.php");
$router->addRoute("medicamento/atualizar", "medicamentos.php");
$router->addRoute("medicamento/deletar", "medicamentos.php");

// Rotas de Paciente
$router->addRoute("paciente/listar", "pacientes.php");
$router->addRoute("paciente/criar", "pacientes.php");
$router->addRoute("paciente/atualizar", "pacientes.php");
$router->addRoute("paciente/deletar", "pacientes.php");
$router->addRoute("paciente/buscarPorNomeOuCpf", "pacientes.php");

// Rotas de Estoque
$router->addRoute("estoque/listar", "estoque.php");
$router->addRoute("estoque/adicionarItem", "estoque.php");
$router->addRoute("estoque/atualizar", "estoque.php");
$router->addRoute("estoque/deletar", "estoque.php");
$router->addRoute("estoque/gerar-relatorio", "estoque.php");
$router->addRoute("estoque/executar-relatorio", "estoque.php");

// Rotas de Receita
$router->addRoute("receita/listar", "receitas.php");
$router->addRoute("receita/criar", "receitas.php");
$router->addRoute("receita/atualizar", "receitas.php");
$router->addRoute("receita/deletar", "receitas.php");

// Rotas de Médico
$router->addRoute("medico/listar", "medicos.php");
$router->addRoute("medico/criar", "medicos.php");
$router->addRoute("medico/atualizar", "medicos.php");
$router->addRoute("medico/deletar", "medicos.php");
$router->addRoute("medico/buscar", "medicos.php");

// Rotas de Leito
$router->addRoute("leito/listar", "leitos.php");
$router->addRoute("leito/listarLeito", "leitos.php");
$router->addRoute("leito/listarConsulta", "leitos.php");
$router->addRoute("leito/criar", "leitos.php");
$router->addRoute("leito/atualizar", "leitos.php");
$router->addRoute("leito/deletar", "leitos.php");

// Rotas de Filial Hospital
$router->addRoute("filialHospital/listar", "filialHospital.php");
$router->addRoute("filialHospital/criar", "filialHospital.php");
$router->addRoute("filialHospital/atualizar", "filialHospital.php");
$router->addRoute("filialHospital/deletar", "filialHospital.php");
$router->addRoute("filialHospital/buscar", "filialHospital.php");
$router->addRoute("filialHospital/buscarPorNome", "filialHospital.php");

// Rotas de Consulta
$router->addRoute("consulta/listar", "consultas.php");
$router->addRoute("consulta/criar", "consultas.php");
$router->addRoute("consulta/atualizar", "consultas.php");
$router->addRoute("consulta/deletar", "consultas.php");
$router->addRoute("consulta/buscarPorPaciente", "consultas.php");
$router->addRoute("consulta/listarConsulta", "consultas.php");

// Rotas de Tipo de Medicamento
$router->addRoute("tipo-medicamento/listar", "tipoMedicamento.php");
$router->addRoute("tipo-medicamento/criar", "tipoMedicamento.php");
$router->addRoute("tipo-medicamento/atualizar", "tipoMedicamento.php");
$router->addRoute("tipo-medicamento/deletar", "tipoMedicamento.php");

// Rotas de Tipo de Leito
$router->addRoute("tipoLeito/listar", "tipoLeito.php");
$router->addRoute("tipoLeito/criar", "tipoLeito.php");
$router->addRoute("tipoLeito/atualizar", "tipoLeito.php");
$router->addRoute("tipoLeito/deletar", "tipoLeito.php");

// Rotas de Tipo de Status
$router->addRoute("tipoStatus/listar", "tipoStatus.php");
$router->addRoute("tipoStatus/criar", "tipoStatus.php");
$router->addRoute("tipoStatus/atualizar", "tipoStatus.php");
$router->addRoute("tipoStatus/deletar", "tipoStatus.php");

$router->addRoute("especialidade/listar", "especialidades.php");
$router->addRoute("especialidade/criar", "especialidades.php");
$router->addRoute("especialidade/atualizar", "especialidades.php");
$router->addRoute("especialidade/deletar", "especialidades.php");

$router->addRoute("dashboard", "dashboard.php");
$router->addRoute("login", "login.php");
$router->addRoute("logout", "logout.php");
$router->addRoute("registrar", "registrar.php");
$router->addRoute("pagina_inicial", "pagina_inicial.php");

// Rotas referente a pasta views
// Rota de cadastro tabela Pacientes
$router->addRoute("cadastroPaciente", "cadastroPaciente.php");
$router->addRoute("listarPacientes", "listarPacientes.php");
$router->addRoute("atualizarPaciente", "atualizarPaciente.php");
$router->addRoute("editarPaciente", "editarPaciente.php");
$router->addRoute("deletarPaciente", "deletarPaciente.php");

// Rota de cadastro tabela Medicamentos
$router->addRoute("cadastroMedicamento", "cadastroMedicamento.php");
$router->addRoute("cadastroTipoMedicamento", "cadastroTipoMedicamento.php");
$router->addRoute("listarMedicamentos", "listarMedicamentos.php");
$router->addRoute("atualizarMedicamentos", "atualizarMedicamentos.php");
$router->addRoute("editarTipoMedicamento", "editarTipoMedicamento.php");
$router->addRoute("deletarMedicamento", "deletarMedicamento.php");
$router->addRoute("listarTipos", "listarTipos.php");
$router->addRoute("deletarTipo", "deletarTipo.php");

// Rota de cadastro Card Estoque
$router->addRoute("cadastroEstoque", "cadastroEstoque.php");
$router->addRoute("listarEstoque", "listarEstoque.php");
$router->addRoute("atualizarEstoque", "atualizarEstoque.php");
$router->addRoute("deletarEstoque", "deletarEstoque.php");

// Rota de cadastro Card Receita
$router->addRoute("cadastroReceita", "cadastroReceita.php");
$router->addRoute("listarReceitas", "listarReceitas.php");
$router->addRoute("atualizarReceita", "atualizarReceita.php");
$router->addRoute("deletarReceita", "deletarReceita.php");

// Rota de cadastro Card Médico
$router->addRoute("cadastroMedico", "cadastroMedico.php");
$router->addRoute("listarMedicos", "listarMedicos.php");
$router->addRoute("atualizarMedico", "atualizarMedico.php");
$router->addRoute("deletarMedico", "deletarMedico.php");

// Rota de cadastro de Consulta
$router->addRoute("cadastroConsulta", "cadastroConsulta.php");  
$router->addRoute("listarConsultas", "listarConsultas.php");
$router->addRoute("atualizarConsulta", "atualizarConsulta.php");
$router->addRoute("deletarConsulta", "deletarConsulta.php");

// Rota de cadastro Card Leito
$router->addRoute("cadastroLeito", "cadastroLeito.php");
$router->addRoute("listarLeito", "listarLeito.php");
$router->addRoute("atualizarLeito", "atualizarLeito.php");
$router->addRoute("deletarLeito", "deletarLeito.php");

$router->addRoute("tipo-medicamento/listarJSON", "tipoMedicamento.php");
$router->addRoute("medicamento/listarJSON", "medicamentos.php");


// Processa a requisição
$router->handleRequest();
