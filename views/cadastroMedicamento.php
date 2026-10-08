<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Medicamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">📦 Cadastrar Novo Medicamento</h2>

    <!-- Formulário de cadastro -->
    <form id="formMedicamento">
        <!-- Nome do medicamento -->
        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Medicamento</label>
            <input type="text" class="form-control" id="nome" name="nome" required placeholder="Ex: Dipirona">
        </div>

        <!-- Tipo de medicamento (carregado dinamicamente) -->
        <div class="mb-3">
            <label for="tipo_id" class="form-label">Tipo de Medicamento</label>
            <select id="tipo_id" name="tipo_id" class="form-select" required>
                <option value="">Selecione um tipo</option>
            </select>
        </div>

        <!-- Doses por caixa -->
        <div class="mb-3">
            <label for="doses_por_caixa" class="form-label">Doses por Caixa</label>
            <input type="number" class="form-control" id="doses_por_caixa" name="doses_por_caixa" min="1" required placeholder="Ex: 20">
        </div>

        <!-- Preço por caixa -->
        <div class="mb-3">
            <label for="preco_por_caixa" class="form-label">Preço por Caixa (R$)</label>
            <input type="number" step="0.01" min="0" class="form-control" id="preco_por_caixa" name="preco_por_caixa" required placeholder="Ex: 15.99">
        </div>

        <!-- Botões -->
        <div class="d-flex justify-content-between align-items-center">
            <a href="?rota=listarMedicamentos" class="btn btn-outline-dark">⬅️ Voltar</a>
            <div>
                <button type="submit" class="btn btn-primary">💾 Cadastrar</button>
                <a href="?rota=dashboard" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </form>

    <!-- Mensagens de sucesso ou erro -->
    <div id="mensagem" class="mt-4"></div>
</div>

<!-- Script para manipulação do formulário de cadastro de medicamento -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const tipoSelect = document.getElementById("tipo_id");
        const mensagemDiv = document.getElementById("mensagem");

        // 🔄 Carrega dinamicamente os tipos de medicamento no select
        fetch("http://hospital-web.local/?rota=tipo-medicamento/listarJSON")
            .then(response => {
                if (!response.ok) throw new Error("Erro ao buscar tipos de medicamento.");
                return response.json();
            })
            .then(data => {
                if (!Array.isArray(data) || data.length === 0) {
                    tipoSelect.innerHTML = `<option value="">Nenhum tipo disponível</option>`;
                    return;
                }

                tipoSelect.innerHTML = `<option value="">Selecione o tipo</option>`;
                data.forEach(tipo => {
                    const option = document.createElement("option");
                    option.value = tipo.id;
                    option.textContent = tipo.nome;
                    tipoSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error("❌ Erro no carregamento:", error);
                mensagemDiv.innerHTML = `
                    <div class="alert alert-danger">
                        Não foi possível carregar os tipos de medicamento.<br>Detalhes: ${error.message}
                    </div>`;
            });

        // 📝 Lida com o envio do formulário de cadastro de medicamento
        document.getElementById("formMedicamento").addEventListener("submit", event => {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const jsonData = Object.fromEntries(formData.entries());

            // 🔍 Conversão dos campos numéricos
            jsonData.tipo_id = parseInt(jsonData.tipo_id);
            jsonData.doses_por_caixa = parseInt(jsonData.doses_por_caixa);
            jsonData.preco_por_caixa = parseFloat(jsonData.preco_por_caixa);

            mensagemDiv.innerHTML = ""; // Limpa mensagens anteriores

            // 📤 Envia os dados em formato JSON para o backend
            fetch("http://hospital-web.local/?rota=medicamento/criar", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(jsonData)
            })
            .then(response => response.json().catch(() => {
                throw new Error("Resposta inválida do servidor.");
            }))
            .then(data => {
                if (data.mensagem) {
                    mensagemDiv.innerHTML = `
                        <div class="alert alert-success">${data.mensagem}</div>`;

                    // ⏳ Redireciona para a listagem após 3 segundos
                    setTimeout(() => {
                        window.location.href = "?rota=listarMedicamentos";
                    }, 3000);

                } else if (data.erro) {
                    mensagemDiv.innerHTML = `
                        <div class="alert alert-danger">${data.erro}</div>`;
                }
            })
            .catch(error => {
                console.error("❌ Erro ao cadastrar:", error);
                mensagemDiv.innerHTML = `
                    <div class="alert alert-danger">
                        Ocorreu um erro ao cadastrar o medicamento.<br>Detalhes: ${error.message}
                    </div>`;
            });
        });
    });
</script>

</body>
</html>
