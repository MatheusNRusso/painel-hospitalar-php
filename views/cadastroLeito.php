<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Leito</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #loader { 
        display: none; 
        text-align: center; 
        font-size: 18px; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Cadastrar Novo Leito</h2>
        <form id="cadastroLeito">
            <div class="mb-3">
                <label for="numero" class="form-label">Número do Leito</label>
                <input type="text" name="numero" id="numero" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="status_id" class="form-label">Status</label>
                <select name="status_id" id="status_id" class="form-control" required>
                    <option value="">Carregando...</option>
                    
                </select>
            </div>
            <div class="mb-3">
                <label for="tipo_id" class="form-label">Tipo de Leito</label>
                <select name="tipo_id" id="tipo_id" class="form-control" required>
                    <option value="">Carregando...</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="paciente_id" class="form-label">Paciente (opcional)</label>
                <input type="text" name="paciente_id" id="paciente_id" class="form-control">
            </div>
            
            <div class="mb-3">
                <label for="filial_hospital_id" class="form-label">Filial Hospital</label>
                <select name="filial_hospital_id" id="filial_hospital_id" class="form-control" required>
                    <option value="">Carregando...</option>
                    
                </select>
            </div>
            <div class="d-flex justify-content-between">
                 <a href="?rota=listarLeito" class="btn btn-outline-dark">⬅️ Voltar</a>
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
        // Ao carregar a página, busca os tipos de leito
        document.addEventListener("DOMContentLoaded", function() {
            carregarOpcoes("tipoLeito/listar", "tipo_id", "Selecione um tipo de leito");
            carregarOpcoes("filialHospital/listar", "filial_hospital_id", "Selecione uma filial");
            carregarOpcoes("tipoStatus/listar", "status_id", "Selecione um status");
        });
        // Função para carregar opções de select
        function carregarOpcoes(rota, elementoId, placeholder) {
            let select = document.getElementById(elementoId);
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
                        let option = document.createElement("option");
                        option.value = item.id;
                        option.textContent = item.nome;
                        select.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error(`❌ Erro ao carregar ${rota}:`, error);
                    document.getElementById("mensagem").innerHTML = `<div class='alert alert-danger'>Erro ao carregar ${rota}. Tente novamente.</div>`;
                });
        }


        document.getElementById("cadastroLeito").addEventListener("submit", function(event) {
            event.preventDefault();
            
            let formData = new FormData(this);
            let jsonData = Object.fromEntries(formData.entries());

            // Conversão dos campos para os tipos corretos
            jsonData.numero = parseInt(jsonData.numero);
            jsonData.status_id = parseInt(jsonData.status_id);
            jsonData.tipo_id = parseInt(jsonData.tipo_id);
            jsonData.filial_hospital_id = parseInt(jsonData.filial_hospital_id);

            if (!jsonData.paciente_id || jsonData.paciente_id.trim() === "") {
                jsonData.paciente_id = null;
            } else {
                jsonData.paciente_id = parseInt(jsonData.paciente_id);
            }


            
            document.getElementById("loader").style.display = "block";
            document.getElementById("mensagem").innerHTML = ""; // Limpa mensagens anteriores

            fetch("http://hospital-web.local/?rota=leito/criar", {
                method: "POST",
                body: JSON.stringify(jsonData),
                headers: { "Content-Type": "application/json" }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById("loader").style.display = "none";

                if (data.mensagem) {
                    document.getElementById("mensagem").innerHTML = `<div class='alert alert-success'>${data.mensagem}</div>`;
                } else if (data.erro) {
                    document.getElementById("mensagem").innerHTML = `<div class='alert alert-danger'>${data.erro}</div>`;
                }
            })
            .catch(error => {
                document.getElementById("loader").style.display = "none";
                console.error("❌ Erro ao adicionar ao estoque:", error);
                document.getElementById("mensagem").innerHTML = `<div class='alert alert-danger'>Erro ao adicionar medicamento ao estoque.</div>`;
            });
        });
    </script>
</body>
</html>
