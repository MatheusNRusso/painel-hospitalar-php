<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema Hospitalar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --card-height: 120px;
            --transition-speed: 0.3s;
            --primary-gradient: linear-gradient(135deg, #6e8efb, #a777e3);
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #1e88e5, #0d47a1);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .dashboard-card {
            height: var(--card-height);
            display: flex;
            flex-direction: column;
            justify-content: flex-start; /* Alinhar conteúdo ao topo */
            align-items: flex-start; /* Alinhar conteúdo à esquerda */
            border-radius: 10px;
            transition: all var(--transition-speed) ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            color: #343a40; /* Cor padrão do texto para caso algo não tenha classe de cor */
            background-color: white;
            border: 1px solid #f8f9fa; /* Borda sutil para destacar os cards no fundo */
            padding: 15px; /* Adicionar padding interno */
            z-index: 10;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        .card-header-left {
            display: flex;
            align-items: center;
            margin-bottom: 5px; /* Espaçamento entre o cabeçalho e o restante do conteúdo (se houver) */
        }

        .card-header-left i {
            font-size: 1.5rem; /* Tamanho proporcionalmente menor para o ícone */
            margin-right: 8px;
        }

        .card-header-left h4 {
            font-size: 1rem; /* Tamanho proporcionalmente menor para o título */
            margin-top: 0;
            margin-bottom: 0;
        }

        .card-options {
            position: relative;
            top: 10px;
            left: 0;
            width: 100%;
            background: white;
            border-radius: 0 0 8px 8px;
            padding: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            z-index: 1000;
            animation: fadeIn 0.3s ease-out;
            display: none;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card-options.active {
            display: block;
        }

        .option-btn {
            width: 100%;
            margin-bottom: 8px;
            text-align: left;
            padding: 10px 15px;
            border-radius: 5px;
            transition: all 0.2s ease;
            border: 1px solid #dee2e6;
            background: white;
        }

        .option-btn:hover {
            transform: translateX(5px);
            background: #f8f9fa;
        }

        .option-btn i {
            margin-right: 10px;
            font-size: 1rem;
            width: 20px;
            text-align: center;
        }

        #botao-chat {
            position: absolute;
            bottom: 15px;
            right: 15px;
            width: 60px;
            height: 60px;
            z-index: 9999;
            font-size: 1.5rem;
        }

        #janela-chat {
            position: absolute;
            bottom: 85px;
            right: 15px;
            width: 350px;
            max-height: 500px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            border-radius: 12px;
        }

        .chat-body {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        #chat-mensagens {
            flex: 1;
            overflow-y: auto;
            padding-bottom: 1rem;
        }

        .badge-ia {
            transition: all 0.3s ease;
        }
        .badge-ia:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .chat-container {
            display: flex;
            flex-direction: column;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .chat-container .card-header {
            border-radius: 12px 12px 0 0;
        }

        .chat-container .card-body {
            border-radius: 0 0 12px 12px;
        }

        .relatorio-content {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 1rem;
            line-height: 1.8;
            color: #333;
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            overflow-x: auto;
            white-space: pre-wrap;
            word-wrap: break-word;
            max-width: 800px; /* Limita largura para melhor leitura */
            margin: 0 auto; /* Centraliza */
        }

        /* Títulos */
        .relatorio-content h1, .relatorio-content h2, .relatorio-content h3 {
            color: #0d6efd;
            border-left: 4px solid #0d6efd;
            padding-left: 8px;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem; /* separa do conteúdo */
            position: relative;
        }

        .relatorio-content h1::before,
        .relatorio-content h2::before,
        .relatorio-content h3::before {
            content: "📌 ";
            margin-right: 5px;
        }

        /* Parágrafos */
        .relatorio-content p {
            margin-bottom: 1rem;
        }

        /* Listas */
        .relatorio-content ul {
            list-style: disc inside;
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }

        .relatorio-content ol {
            list-style: decimal inside;
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }

        /* Negrito */
        .relatorio-content strong {
            color: #0d6efd;
        }

        /* Rodapé */
        .relatorio-footer {
            margin-top: 1rem;
            font-size: 0.85rem;
            color: #6c757d;
            border-top: 1px solid #ddd;
            padding-top: 0.5rem;
        }

        /* Modo escuro */
        .relatorio-content.dark-mode {
            background: #1c1c1c;
            color: #f1f1f1;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
        }

        .relatorio-content.dark-mode h1,
        .relatorio-content.dark-mode h2,
        .relatorio-content.dark-mode h3,
        .relatorio-content.dark-mode strong {
            color: #0dcaf0;
        }

        .relatorio-content.dark-mode .relatorio-footer {
            color: #aaa;
            border-top: 1px solid #444;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="dashboard-header text-center">
            <h1>Painel Administrativo</h1>
            <p class="mb-0">Gerencie todos os aspectos do sistema hospitalar</p>
            <div class="mt-3">
                <span class="badge bg-white text-primary fs-6 shadow-sm">
                    Relatórios Dinâmicos com IA Integrada
                </span>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4 position-relative">
                <div class="dashboard-card" id="card-pacientes">
                    <div class="card-header-left">
                        <i class="fas fa-user-injured text-primary"></i>
                        <h4 class="text-primary">Pacientes</h4>
                    </div>
                </div>
                <div class="card-options" id="paciente-options">
                    <a href="?rota=listarPacientes" class="btn btn-outline-primary option-btn d-block">
                        <i class="fas fa-list"></i> Gerenciar Pacientes
                    </a>
                    <a href="?rota=cadastroPaciente" class="btn btn-outline-secondary option-btn d-block">
                        <i class="fas fa-plus-circle"></i> Novo Paciente
                    </a>
                </div>
            </div>

            <div class="col-md-4 position-relative">
                <div class="dashboard-card" id="card-medicamentos">
                    <div class="card-header-left">
                        <i class="fas fa-pills text-success"></i>
                        <h4 class="text-success">Medicamentos</h4>
                    </div>
                </div>
                <div class="card-options" id="medicamento-options">
                    <a href="?rota=listarMedicamentos" class="btn btn-outline-success option-btn d-block">
                        <i class="fas fa-list"></i> Gerenciar Medicamentos
                    </a>
                    <a href="?rota=listarTipos" class="btn btn-outline-info option-btn d-block">
                        <i class="fas fa-tags"></i> Tipos de Medicamentos
                    </a>
                </div>
            </div>

            <div class="col-md-4 position-relative">
                <div class="dashboard-card" id="card-leitos">
                    <div class="card-header-left">
                        <i class="fas fa-bed text-danger"></i>
                        <h4 class="text-danger">Leitos</h4>
                    </div>
                </div>
                <div class="card-options" id="leito-options">
                    <a href="?rota=listarLeito" class="btn btn-outline-danger option-btn d-block">
                        <i class="fas fa-list"></i> Gerenciar Leitos
                    </a>
                    <a href="?rota=cadastroLeito" class="btn btn-outline-primary option-btn d-block">
                        <i class="fas fa-plus-circle"></i> Novo Leito
                    </a>
                </div>
            </div>

            <div class="col-md-4 position-relative">
                <div class="dashboard-card" id="card-consultas">
                    <div class="card-header-left">
                        <i class="fas fa-calendar-check text-secondary"></i>
                        <h4 class="text-secondary">Consultas</h4>
                    </div>
                </div>
                <div class="card-options" id="consulta-options">
                    <a href="?rota=listarConsultas" class="btn btn-outline-secondary option-btn d-block">
                        <i class="fas fa-list"></i> Gerenciar Consultas
                    </a>
                    <a href="?rota=cadastroConsulta" class="btn btn-outline-primary option-btn d-block">
                        <i class="fas fa-plus-circle"></i> Nova Consulta
                    </a>
                </div>
            </div>

            <div class="col-md-4 position-relative">
                <div class="dashboard-card" id="card-relatorios">
                    <div class="card-header-left">
                        <i class="fas fa-chart-line text-purple-700"></i>
                        <h4 class="text-purple-700">Relatórios</h4>
                    </div>
                </div>
                <div class="card-options" id="relatorio-options">
                    <h5 class="text-center mb-3"><i class="fas fa-chart-pie"></i> Relatórios Disponíveis</h5>
                    <div class="row g-2">
                        <?php
                        // Função para remover acentos e converter em minúsculas
                        function normalizar($string) {
                            $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
                            return strtolower($string);
                        }

                        $relatorios = [
                            'Estoque' => 'fa-boxes',
                            'Pacientes' => 'fa-user-injured',
                            'Médicos' => 'fa-user-md',
                            'Receitas' => 'fa-file-prescription',
                            'Leitos' => 'fa-bed',
                            'Consulta' => 'fa-calendar-check',
                            'Medicamentos' => 'fa-pills'
                        ];

                        foreach ($relatorios as $nome => $icone) {
                            $cor = 'btn-outline-secondary'; // Cor padrão
                            switch (normalizar($nome)) {
                                case 'pacientes': $cor = 'btn-outline-primary'; break;
                                case 'medicamentos': $cor = 'btn-outline-success'; break;
                                case 'leitos': $cor = 'btn-outline-danger'; break;
                                case 'consulta': $cor = 'btn-outline-secondary'; break;
                                case 'medicos': $cor = 'btn-outline-info'; break;
                                case 'estoque': $cor = 'btn-outline-dark'; break;
                                case 'receitas': $cor = 'btn-outline-warning'; break;
                                // 'relatorios' não precisa de cor específica aqui
                                // 'usuarios' não está na lista de relatórios
                            }
                            echo '
                            <div class="col-md-6">
                                <button class="btn option-btn gerar-relatorio w-100 '.$cor.'"
                                        data-tabela="'.normalizar($nome).'">
                                    <i class="fas '.$icone.'"></i> '.$nome.'
                                </button>
                            </div>';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4 position-relative">
                <div class="dashboard-card" id="card-estoque">
                    <div class="card-header-left">
                        <i class="fas fa-hospital-alt text-dark"></i>
                        <h4 class="text-dark">Estoque</h4>
                    </div>
                </div>
                <div class="card-options" id="estoque-options">
                    <a href="?rota=listarEstoque" class="btn btn-outline-dark option-btn d-block">
                        <i class="fas fa-list"></i> Gerenciar estoque
                    </a>
                    <a href="?rota=cadastroEstoque" class="btn btn-outline-primary option-btn d-block">
                        <i class="fas fa-plus-circle"></i> Adicionar ao Estoque
                    </a>
                </div>
            </div>

            <div class="col-md-4 position-relative">
                <div class="dashboard-card" id="card-receitas">
                    <div class="card-header-left">
                        <i class="fas fa-file-medical text-warning"></i>
                        <h4 class="text-warning">Receitas</h4>
                    </div>
                </div>
                <div class="card-options" id="receita-options">
                    <a href="?rota=listarReceitas" class="btn btn-outline-warning option-btn d-block">
                        <i class="fas fa-list"></i> Gerenciar Receitas
                    </a>
                    <a href="?rota=cadastroReceita" class="btn btn-outline-primary option-btn d-block">
                        <i class="fas fa-plus-circle"></i> Nova Receita
                    </a>
                </div>
            </div>

            <div class="col-md-4 position-relative">
                <div class="dashboard-card" id="card-medicos">
                    <div class="card-header-left">
                        <i class="fas fa-user-md text-info"></i>
                        <h4 class="text-info">Médicos</h4>
                    </div>
                </div>
                <div class="card-options" id="medico-options">
                    <a href="?rota=listarMedicos" class="btn btn-outline-info option-btn d-block">
                        <i class="fas fa-list"></i> Gerenciar Médicos
                    </a>
                    <a href="?rota=cadastroMedico" class="btn btn-outline-primary option-btn d-block">
                        <i class="fas fa-plus-circle"></i> Novo Médico
                    </a>
                </div>
            </div>
            
            
            <div class="col-md-4 position-relative">
                <div class="dashboard-card" id="card-usuarios">
                    <div class="card-header-left">
                        <i class="fas fa-user-shield text-indigo"></i>
                        <h4 class="text-indigo-700">Usuários</h4>
                    </div>
                </div>
                <div class="card-options" id="usuario-options">
                    <a href="?rota=#" class="btn btn-outline-dark option-btn d-block">
                        <i class="fas fa-list"></i> Gerenciar Usuários
                    </a>
                    <a href="?rota=registrar" class="btn btn-outline-primary option-btn d-block">
                        <i class="fas fa-plus-circle"></i> Novo Usuário
                    </a>
                </div>
            </div>
        </div>

        <button id="botao-chat" class="btn btn-primary rounded-circle shadow-lg" title="Abrir Assistente">
            <i class="fas fa-robot"></i>
        </button>

        <div id="janela-chat" class="card shadow chat-container d-none">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <span><i class="fas fa-robot me-2"></i> Assistente Virtual</span>
                <button class="btn btn-sm btn-light" id="fechar-chat"><i class="bi bi-x"></i></button>
            </div>
            <div class="card-body chat-body overflow-auto">
                <div id="chat-mensagens" class="mb-3"></div> <form id="form-chat" class="d-flex">
                    <input type="text" class="form-control me-2" id="mensagem-chat" placeholder="Digite seu CPF ou Nome..." required>
                    <button class="btn btn-primary" type="submit"><i class="bi bi-send"></i></button>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade modal-relatorio" id="modalRelatorio" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="bi bi-graph-up me-2"></i>
                        <span id="relatorio-titulo" class="text-truncate">Relatório</span>
                    </h5>
                    <div class="d-flex gap-2">
                        <!-- Botão de modo escuro -->
                        <button class="btn btn-sm btn-light" id="btn-modo-escuro" title="Modo Escuro">
                            🌙
                        </button>
                        <!-- Botão de imprimir -->
                        <button class="btn btn-sm btn-light" id="btn-print" title="Imprimir">
                            <i class="bi bi-printer"></i>
                        </button>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                </div>
                <div class="modal-body p-0">
                    <div id="loading-relatorio" class="d-flex flex-column justify-content-center align-items-center py-5">
                        <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;"></div>
                        <p class="mt-3 fs-5">Gerando análise...</p>
                    </div>
                    <!-- Conteúdo do relatório com estilização -->
                    <div id="conteudoRelatorio" class="p-4"></div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script>
    $(document).ready(function() {
        // Controla a exibição dos menus de opções
        function setupCard(cardId, optionsId) {
            $(cardId).click(function(e) {
                e.preventDefault();
                e.stopPropagation();

                // Fecha todos os outros menus primeiro
                $('.card-options').not(optionsId).removeClass('active');

                // Alterna o menu atual
                $(optionsId).toggleClass('active');
            });
        }

        // Configura os cards interativos
        const cards = {
            '#card-pacientes': '#paciente-options',
            '#card-medicamentos': '#medicamento-options',
            '#card-leitos': '#leito-options',
            '#card-consultas': '#consulta-options',
            '#card-medicos': '#medico-options',
            '#card-estoque': '#estoque-options',
            '#card-receitas': '#receita-options',
            '#card-relatorios': '#relatorio-options',
            '#card-usuarios': '#usuario-options'
        };

        Object.entries(cards).forEach(([card, options]) => {
            setupCard(card, options);
        });

        // Fecha os menus ao clicar em qualquer lugar da página
        $(document).click(function() {
            $('.card-options').removeClass('active');
        });

        // Impede que o clique dentro do menu feche o mesmo
        $('.card-options').click(function(e) {
            e.stopPropagation();
        });

        // Modo escuro
        $('#btn-modo-escuro').click(function() {
            $('#conteudoRelatorio .relatorio-content').toggleClass('dark-mode');
        });

        // Gerar relatório no modal
        $('.gerar-relatorio').click(function() {
            const tabela = $(this).data('tabela');
            const modal = $('#modalRelatorio');

            // Configuração do modal
            modal.find('#relatorio-titulo').text(`Relatório de ${$(this).text().trim()}`);
            $('#conteudoRelatorio').html('').addClass('d-none');
            $('#loading-relatorio').removeClass('d-none');

            modal.modal('show');

            $.ajax({
                url: `?rota=relatorio/executar-relatorio/${tabela}`,
                type: 'GET',
                dataType: 'html',
                success: function(html) {
                    if (!html.trim()) {
                        html = `<div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            Nenhum dado encontrado para este relatório
                        </div>`;
                    }
                    // Remove as tags <pre> se existirem
                    const htmlContent = marked.parse(html);

                    $('#conteudoRelatorio').html(`
                        <div class="relatorio-content">
                            ${htmlContent}
                            <div class="relatorio-footer">
                                <small class="text-muted">
                                    <i class="bi bi-clock-history"></i>
                                    Gerado em ${new Date().toLocaleString('pt-BR')}
                                </small>
                            </div>
                        </div>
                    `);
                },
                error: function(xhr) {
                    $('#conteudoRelatorio').html(`
                        <div class="alert alert-danger">
                            <i class="bi bi-x-circle-fill"></i>
                            Erro ${xhr.status}: ${xhr.statusText || 'Falha na requisição'}
                        </div>
                    `);
                },
                complete: function() {
                    $('#loading-relatorio').addClass('d-none');
                    $('#conteudoRelatorio').removeClass('d-none');
                }
            });
        });
        // Função para impressão
        $('#btn-print').click(function() {
            const content = $('#conteudoRelatorio').html();
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>${$('#relatorio-titulo').text()}</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        @page { size: auto; margin: 5mm; }
                        body { padding: 20px; }
                        .relatorio-table { font-size: 0.9rem; }
                        .relatorio-table th { background-color: #f8f9fa !important; }
                    </style>
                </head>
                <body>
                    <h3 class="mb-4">${$('#relatorio-titulo').text()}</h3>
                    ${content}
                    <div class="text-end mt-4">
                        <small>Impresso em ${new Date().toLocaleString('pt-BR')}</small>
                    </div>
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.focus();
            setTimeout(() => printWindow.print(), 500);
        });

        // Controle do Chat
        let pacienteEncontrado = null;
        let consultaEncontrada = null;
        let chatIniciado = false;
        let timerInatividade = null;

        // Função para escrever mensagem com efeito de digitação
        function escreverMensagem(texto, containerId, classe = 'text-muted small', delay = 30) {
            const container = document.getElementById(containerId);
            const novaMensagem = document.createElement('div');
            novaMensagem.className = `${classe} mb-2`;
            container.appendChild(novaMensagem);

            let i = 0;
            const textoTemporario = document.createElement('span');

            const intervalo = setInterval(() => {
                if (i < texto.length) {
                    textoTemporario.textContent += texto.charAt(i);
                    novaMensagem.innerHTML = textoTemporario.textContent.replace(/\n/g, '<br>');
                    container.scrollTop = container.scrollHeight;
                    i++;
                } else {
                    novaMensagem.innerHTML = texto;
                    container.scrollTop = container.scrollHeight;
                    clearInterval(intervalo);
                }
            }, delay);
        }

        // Mostra "digitando..." temporariamente
        function mostrarDigitando() {
            const container = document.getElementById('chat-mensagens');
            const digitando = document.createElement('div');
            digitando.className = 'text-muted small mb-2';
            digitando.id = 'digitando-msg';
            digitando.innerHTML = "🤖 Digitando...";
            container.appendChild(digitando);
            container.scrollTop = container.scrollHeight;
        }

        // Remove "digitando..."
        function removerDigitando() {
            const digitando = document.getElementById('digitando-msg');
            if (digitando) digitando.remove();
        }

        // Controle do botão para abrir chat
        document.getElementById('botao-chat').addEventListener('click', () => {
            const janela = document.getElementById('janela-chat');
            janela.classList.toggle('d-none');

            if (!janela.classList.contains('d-none') && !chatIniciado) {
                limparMensagensChat();
                escreverMensagem("🤖 Bem-vindo ao Sistema Hospitalar!<br><br>🩺 Para melhor atendê-lo, digite seu CPF ou Nome completo.", 'chat-mensagens');
                chatIniciado = true;
            }
        });

        // Controle do botão para fechar chat
        document.getElementById('fechar-chat').addEventListener('click', () => {
            document.getElementById('janela-chat').classList.add('d-none');
            resetarChat();
        });

        // Limpa todas as mensagens do chat
        function limparMensagensChat() {
            const chatBox = document.getElementById('chat-mensagens');
            chatBox.innerHTML = '';
        }

        // Reseta estado do chat
        function resetarChat() {
            pacienteEncontrado = null;
            consultaEncontrada = null;
            chatIniciado = false;
            limparMensagensChat();
            limparTimerInatividade();
        }

        // Limpa o timer de inatividade
        function limparTimerInatividade() {
            if (timerInatividade) {
                clearTimeout(timerInatividade);
                timerInatividade = null;
            }
        }

        // Reseta o timer de inatividade
        function resetarTimerInatividade() {
            limparTimerInatividade();
            timerInatividade = setTimeout(() => {
                escreverMensagem("⚡ Ainda está aí? Estamos prontos para continuar!", 'chat-mensagens');
            }, 120000); // 2 minutos
        }

        // Envio de mensagem
        document.getElementById('form-chat').addEventListener('submit', async function (e) {
            e.preventDefault();
            const input = document.getElementById('mensagem-chat');
            const mensagem = input.value.trim();
            const chatBox = document.getElementById('chat-mensagens');

            if (!mensagem) return;

            // Mensagem do usuário
            chatBox.innerHTML += `<div class="text-end mb-2"><span class="badge bg-primary">${mensagem}</span></div>`;
            chatBox.scrollTop = chatBox.scrollHeight;
            input.value = '';
            resetarTimerInatividade();

            if (!pacienteEncontrado) {
                // Procurar paciente pelo nome ou CPF
                try {
                    mostrarDigitando();
                    const response = await fetch(`http://hospital-web.local/?rota=paciente/buscarPorNomeOuCpf&termo=${encodeURIComponent(mensagem)}`);
                    const pacientes = await response.json();
                    removerDigitando();

                    if (pacientes.length > 0) {
                        pacienteEncontrado = pacientes[0];
                        escreverMensagem(`🤖 Olá <b>${pacienteEncontrado.nome}</b>! Buscando suas consultas...`, 'chat-mensagens');
                        buscarConsultaDoPaciente(pacienteEncontrado.id);
                    } else {
                        escreverMensagem(`❌ Não encontramos nenhum paciente com esses dados. Tente novamente.`, 'chat-mensagens');
                    }
                } catch (error) {
                    removerDigitando();
                    escreverMensagem(`❌ Erro ao buscar paciente.`, 'chat-mensagens');
                }
            } 
            else if (pacienteEncontrado && consultaEncontrada) 
            {
                tratarRespostaUsuario(mensagem);
            }
        });

        // Trata respostas do usuário após mostrar consulta
        function tratarRespostaUsuario(mensagem) {
            if (mensagem === '1') {
                escreverMensagem(`✅ Sua consulta foi confirmada! Nos vemos em breve.`, 'chat-mensagens');
                removerOpcoes();
            } else if (mensagem === '2') {
                escreverMensagem(`📅 Para remarcar, informe uma nova data e hora (ex: 10/05/2025 14:00)`, 'chat-mensagens');
                removerOpcoes();
            } else if (mensagem === '3') {
                escreverMensagem(`❌ Consulta cancelada. Caso precise, estamos à disposição para remarcar.`, 'chat-mensagens');
                removerOpcoes();
            } else {
                escreverMensagem(`🤖 Opção inválida!<br>Digite:<br><b>1</b> Confirmar<br><b>2</b> Remarcar<br><b>3</b> Cancelar`, 'chat-mensagens');
            }
        }

        // Remove botões de opções
        function removerOpcoes() {
            const botoes = document.getElementById('botoes-opcoes');
            if (botoes) botoes.remove();
        }

        // Buscar consultas do paciente
        async function buscarConsultaDoPaciente(pacienteId) {
            try {
                mostrarDigitando();
                const response = await fetch(`http://hospital-web.local/?rota=consulta/buscarPorPaciente&id=${pacienteId}`);
                const consultas = await response.json();
                removerDigitando();

                if (consultas.length > 0) {
                    consultaEncontrada = consultas[0];
                    buscarDetalhesConsulta(consultaEncontrada);
                } else {
                    escreverMensagem(`ℹ️ Nenhuma consulta encontrada para você no momento.`, 'chat-mensagens');
                }
            } catch (error) {
                removerDigitando();
                escreverMensagem(`❌ Erro ao buscar consulta.`, 'chat-mensagens');
            }
        }

        // Buscar nome do médico e filial
        async function buscarDetalhesConsulta(consulta) {
            try {
                mostrarDigitando();
                const [medicoRes, filialRes] = await Promise.all([
                    fetch(`http://hospital-web.local/?rota=medico/buscar&id=${consulta.medico_id}`),
                    fetch(`http://hospital-web.local/?rota=filialHospital/buscar&id=${consulta.filial_hospital_id}`)
                ]);
                removerDigitando();

                const medico = await medicoRes.json();
                const filial = await filialRes.json();

                escreverMensagem(`
        📅 Sua consulta está agendada para <b>${formatarData(consulta.data_consulta)}</b>.<br><br>
        👨‍⚕️ Médico: <b>${medico.nome}</b><br><br>
        🏥 Filial: <b>${filial.nome}</b><br><br>
        Como podemos ajudar?<br><br>
        <div id="botoes-opcoes" class="mt-3">
            <button class="btn btn-success btn-sm me-2" onclick="tratarRespostaUsuario('1')">✅ Confirmar</button>
            <button class="btn btn-warning btn-sm me-2" onclick="tratarRespostaUsuario('2')">🔄 Remarcar</button>
            <button class="btn btn-danger btn-sm" onclick="tratarRespostaUsuario('3')">❌ Cancelar</button>
        </div>
                `, 'chat-mensagens', 10); // Um pouco mais rápido para listas
            } catch (error) {
                removerDigitando();
                escreverMensagem(`❌ Erro ao buscar detalhes da consulta.`, 'chat-mensagens');
            }
        }

        // Formatar data
        function formatarData(dataString) {
            const data = new Date(dataString);
            return data.toLocaleString('pt-BR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    });
    </script>
</body>
</html>