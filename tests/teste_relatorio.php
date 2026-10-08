<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Teste Relatório Modal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    .relatorio-content {
      font-family: Arial, sans-serif;
      line-height: 1.6;
      font-size: 1rem;
      color: #333;
      padding: 1rem;
      background: #f9f9f9;
      border-radius: 8px;
    }
    .relatorio-content strong {
      color: #0d6efd;
    }
    .relatorio-content ul {
      list-style-type: disc;
      padding-left: 1.5rem;
    }
    .relatorio-content p {
      margin-bottom: 0.75rem;
    }
    .relatorio-footer {
      margin-top: 1rem;
      font-size: 0.85rem;
      color: #6c757d;
      border-top: 1px solid #ddd;
      padding-top: 0.5rem;
    }
    .modal-content {
      border: none;
      border-radius: 10px;
    }
  </style>
</head>
<body class="p-4">

  <button id="btnGerarRelatorio" class="btn btn-primary">
    <i class="bi bi-graph-up"></i> Gerar Relatório
  </button>

  <div class="modal fade" id="modalRelatorio" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Relatório</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div id="conteudoRelatorio"></div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#btnGerarRelatorio').click(function() {
        const markdown = `
# Relatório de Pacientes

## 1. Introdução

Este relatório apresenta a análise de dados de pacientes.

## 2. Estrutura dos Dados

- **ID:** Identificador
- **NOME:** Nome
- **CPF:** CPF
- **DATA_NASCIMENTO:** Data de nascimento
- **PESO:** Peso

## 3. Conclusão

A coleta de dados deve ser aprimorada.

**Gerado em: ${new Date().toLocaleString('pt-BR')}**
        `;

        const html = marked.parse(markdown);
        $('#conteudoRelatorio').html(`<div class="relatorio-content">${html}</div>`);
        const modal = new bootstrap.Modal(document.getElementById('modalRelatorio'));
        modal.show();
      });
    });
  </script>

</body>
</html>
