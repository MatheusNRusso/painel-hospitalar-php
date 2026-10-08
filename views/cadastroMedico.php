<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Médico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #loader {
            display: none;
            text-align: center;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Cadastrar Novo Médico</h2>
        <form id="cadastroMedico">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Médico</label>
                <input type="text" name="nome" id="nome" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="crm" class="form-label">CRM</label>
                <input type="text" name="crm" id="crm" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="especialidade_id" class="form-label">Especialidade</label>
                <select name="especialidade_id" id="especialidade_id" class="form-control" required>
                    <option value="">Carregando...</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="filial_hospital_id" class="form-label">Filial Hospital</label>
                <select name="filial_hospital_id" id="filial_hospital_id" class="form-control" required>
                    <option value="">Carregando...</option>
                </select>
            </div>

            <div class="d-flex justify-content-between">
                <a href="?rota=listarMedicos" class="btn btn-outline-dark">⬅️ Voltar</a>
                <div>
                    <button type="submit" class="btn btn-primary">💾 Cadastrar</button>
                    <a href="?rota=dashboard" class="btn btn-secondary">Cancelar</a>
                </div>
            </div>
        </form>

        <div id="loader" class="mt-3">⏳ Processando...</div>
        <div id="mensagem" class="mt-3"></div>
    </div>

    <script>
        // Ao carregar a página, buscar as filiais hospitalares disponíveis
        document.addEventListener("DOMContentLoaded", function () {
            carregarOpcoes("filialHospital/listar", "filial_hospital_id", "Selecione uma filial");
            carregarOpcoes("especialidade/listar", "especialidade_id", "Selecione uma especialidade");
        });

        // Função reutilizável para carregar opções em selects
        function carregarOpcoes(rota, elementoId, placeholder) {
            const select = document.getElementById(elementoId);
            select.innerHTML = `<option value="">Carregando...</option>`;

            fetch(`http://hospital-web.local/?rota=${rota}`)
                .then(response => {
                    if (!response.ok) throw new Error(`Erro HTTP! Status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    if (!Array.isArray(data) || data.length === 0) {
                        select.innerHTML = `<option value="">Nenhuma opção disponível</option>`;
                        return;
                    }

                    select.innerHTML = `<option value="">${placeholder}</option>`;
                    data.forEach(item => {
                        const option = document.createElement("option");
                        option.value = item.id;
                        option.textContent = item.nome;
                        select.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error(`❌ Erro ao carregar ${rota}:`, error);
                    document.getElementById("mensagem").innerHTML =
                        `<div class='alert alert-danger'>Erro ao carregar ${rota}. Tente novamente.</div>`;
                });
        }

        // Manipula o envio do formulário
        document.getElementById("cadastroMedico").addEventListener("submit", function (event) {
            event.preventDefault();

            const formData = new FormData(this);
            const jsonData = Object.fromEntries(formData.entries());

            // Conversões de tipo
            jsonData.nome = jsonData.nome.trim();
            jsonData.crm = String(jsonData.crm);
            jsonData.especialidade_id = parseInt(jsonData.especialidade_id);
            jsonData.filial_hospital_id = parseInt(jsonData.filial_hospital_id);
          

            document.getElementById("loader").style.display = "block";
            document.getElementById("mensagem").innerHTML = "";

            fetch("http://hospital-web.local/?rota=medico/criar", {
                method: "POST",
                body: JSON.stringify(jsonData),
                headers: { "Content-Type": "application/json" }
            })
                .then(response => response.json())
                .then(data => {
                    document.getElementById("loader").style.display = "none";

                    if (data.mensagem) {
                        document.getElementById("mensagem").innerHTML =
                            `<div class='alert alert-success'>${data.mensagem}</div>`;
                    } else if (data.erro) {
                        document.getElementById("mensagem").innerHTML =
                            `<div class='alert alert-danger'>${data.erro}</div>`;
                    }
                })
                .catch(error => {
                    document.getElementById("loader").style.display = "none";
                    console.error("❌ Erro ao cadastrar médico:", error);
                    document.getElementById("mensagem").innerHTML =
                        `<div class='alert alert-danger'>Erro ao cadastrar médico. Tente novamente.</div>`;
                });
        });
    </script>
</body>
</html>
