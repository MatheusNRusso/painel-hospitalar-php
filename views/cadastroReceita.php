<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Receita</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
        <h2>Cadastrar Receita</h2>
        <form id="cadastroReceita">
            <div class="mb-3">
                <label for="paciente_id" class="form-label">ID do Paciente</label>
                <select id="paciente_id" name="paciente_id" class="form-select" required>
                    <option value="">Selecione o Paciente</option>
                    <!-- Aqui você pode adicionar opções dinamicamente com JavaScript -->
                </select>
            </div>
            <div class="mb-3">
                <label for="medico_id" class="form-label">ID do Médico</label>
                <select id="medico_id" name="medico_id" class="form-select" required>
                    <option value="">Selecione o Médico</option>
                    <!-- Aqui você pode adicionar opções dinamicamente com JavaScript -->
                </select>
                  
            </div>
            <div class="mb-3">
                <label for="medicamento_id" class="form-label">ID do Medicamento</label>
                <select id="medicamento_id" name="medicamento_id" class="form-select" required maxlength="11" pattern="\d{11}" title="Digite apenas números (11 dígitos).">
                    <option value="">Selecione o Medicamento</option>
                    <!-- Aqui você pode adicionar opções dinamicamente com JavaScript -->
                </select>
            </div>
            <div class="mb-3">
                <label for="dose" class="form-label">Dose</label>
                <input type="number" id="dose" name="dose" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="data_prescricao" class="form-label">Data de Pescrição</label>
                <input type="datetime-local" id="data_prescricao" name="data_prescricao" class="form-control" required>
            </div>
            <div class="d-flex justify-content-between">
                <a href="?rota=listarReceitas" class="btn btn-outline-dark">⬅️ Voltar</a>
                <div>
                    <button type="submit" class="btn btn-primary">💾 Cadastrar</button>
                    <a href="?rota=dashboard" class="btn btn-secondary">Cancelar</a>
                </div>
            </div>
        </form>
        <div id="loader" class="mt-3">⏳ Processando...</div>
        <div id="mensagem" class="mt-3" role="alert" aria-live="assertive"></div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            carregarOpcoes("paciente/listar", "paciente_id", "Selecione o Paciente");
            carregarOpcoes("medico/listar", "medico_id", "Selecione o Médico");
            carregarOpcoes("medicamento/listarJSON", "medicamento_id", "Selecione o Medicamento");
        })
        // Função para carregar os pacientes
        function carregarOpcoes(rota, selectId, mensagem)
        {
            const select = document.getElementById(selectId);
            select.innerHTML = `<option value="">${mensagem}</option>`;
            fetch(`http://hospital-web.local/?rota=${rota}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Erro na requisição: " + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    data.forEach(item => {
                        const option = document.createElement("option");
                        option.value = item.id;
                        option.textContent = item.nome || item.nome_completo || item.nome_medicamento;
                        select.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error("Erro ao carregar opções:", error);
                    select.innerHTML = `<option value="">Erro ao carregar opções</option>`;
                });
        }



        document.getElementById("cadastroReceita").addEventListener("submit", function(event) {
            event.preventDefault();
            const  formData = new FormData(this);
            const jsonData = Object.fromEntries(formData);
           
            jsonData.paciente_id = parseInt(jsonData.paciente_id);
            jsonData.medicamento_id = parseInt(jsonData.medicamento_id);
            jsonData.medico_id = parseInt(jsonData.medico_id);
            jsonData.dose = parseInt(jsonData.dose);
            
            // Formato data para MySQL
            const data = new Date(jsonData.data_prescricao);
            
            // Formata a data para o formato YYYY-MM-DD HH:MM:SS
            jsonData.data_prescricao = `${data.getFullYear()}-${String(data.getMonth() + 1).padStart(2, '0')}-${String(data.getDate()).padStart(2, '0')} ${String(data.getHours()).padStart(2, '0')}:${String(data.getMinutes()).padStart(2, '0')}:00`;
            
            document.getElementById("loader").style.display = "block";
            mostrarMensagem(""); // limpa mensagens
            fetch("http://hospital-web.local/?rota=receita/criar", {
                method: "POST",
                body: JSON.stringify(jsonData),
                headers: { "Content-Type": "application/json" }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Erro na requisição: " + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                document.getElementById("loader").style.display = "none";

                if (data.mensagem) 
                {
                    mostrarMensagem(data.mensagem, "success");
                    document.getElementById("cadastroReceita").reset();
                } 
                else 
                {
                    mostrarMensagem(data.erro || "Erro desconhecido ao cadastrar receita!", "danger");
                }
                setTimeout(() => {
                    window.location.href = "?rota=listarReceitas";
                }, 2000);
            })
            .catch(error => {
                document.getElementById("loader").style.display = "none";
                console.error("Erro ao enviar requisição:", error);
                document.getElementById("mensagem").innerHTML = `<div class='alert alert-danger'>Erro ao cadastrar receita. Tente novamente.</div>`;
            });
        });
        function mostrarMensagem(mensagem, tipo = "") {
            const div = document.getElementById("mensagem");
            if (!mensagem) {
                div.innerHTML = "";
                return;
            }
            div.innerHTML = `<div class="alert alert-${tipo}">${mensagem}</div>`;
        }
    </script>
</body>
</html>