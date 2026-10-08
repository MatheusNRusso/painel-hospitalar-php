<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Consulta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
    <h2 class="mb-4">📋 Cadastrar Nova Consulta</h2>
    <form id="cadastroConsulta">
        <div class="mb-3">
            <label for="paciente_id" class="form-label">Paciente</label>
            <select id="paciente_id" name="paciente_id" class="form-select" required>
                <option value="">Carregando pacientes...</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="medico_id" class="form-label">Médico</label>
            <select id="medico_id" name="medico_id" class="form-select" required>
                <option value="">Carregando médicos...</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="filial_hospital_id" class="form-label">Filial Hospital</label>
            <select id="filial_hospital_id" name="filial_hospital_id" class="form-select" required>
                <option value="">Carregando filiais...</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="leito_id" class="form-label">Leito (opcional)</label>
            <select id="leito_id" name="leito_id" class="form-select">
                <option value="">Nenhum</option>
            </select>   
        </div>
        <div class="mb-3">
            <label for="data_consulta" class="form-label">Data da Consulta</label>
            <input type="datetime-local" id="data_consulta" name="data_consulta" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea id="descricao" name="descricao" class="form-control" rows="4" required></textarea>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select id="status" name="status" class="form-select" required>
                <option value="Agendada">Agendada</option>
                <option value="Realizada">Realizada</option>
                <option value="Cancelada">Cancelada</option>
            </select>
        </div>
        <div class="d-flex justify-content-between">
            <a href="?rota=listarConsultas" class="btn btn-outline-dark">⬅️ Voltar</a>
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
        carregarOpcoes("paciente/listar", "paciente_id", "Selecione um paciente");
        carregarOpcoes("medico/listar", "medico_id", "Selecione um médico");
        carregarOpcoes("filialHospital/listar", "filial_hospital_id", "Selecione uma filial");
        carregarOpcoes("leito/listarConsulta", "leito_id", "Selecione um leito (opcional)");
    
    });
    function carregarOpcoes(rota, elementoId, placeholder) {
        const select = document.getElementById(elementoId);
        select.innerHTML = `<option value="">Carregando...</option>`;

        fetch(`http://hospital-web.local/?rota=${rota}`)
            .then(res => {
                if (!res.ok) throw new Error(`Erro HTTP: ${res.status}`);
                return res.json();
            })
            .then(data => {
                if (!Array.isArray(data) || data.length === 0) {
                    select.innerHTML = `<option value="">Nenhuma opção disponível</option>`;
                    return;
                }

                select.innerHTML = `<option value="">${placeholder}</option>`;
                data.forEach(item => {
                    const opt = document.createElement("option");
                    opt.value = item.id;

                    if (elementoId === "leito_id") {
                        // Mostrando info completa para leito
                        const tipo = item.tipo_nome || "Tipo N/D";
                        const status = item.status_nome || "Status N/D";
                        const filial = item.filial_nome || "Filial N/D";
                        opt.textContent = `${tipo} - ${filial} - ${status}`;
                    } else {
                        opt.textContent = item.nome;
                    }

                    select.appendChild(opt);
                });
            })
            .catch(error => {
                console.error(`Erro ao carregar ${rota}:`, error);
                mostrarMensagem(`Erro ao carregar dados (${rota})`, "danger");
            });
    }


    document.getElementById("cadastroConsulta").addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const jsonData = Object.fromEntries(formData.entries());

        jsonData.paciente_id = parseInt(jsonData.paciente_id);
        jsonData.medico_id = parseInt(jsonData.medico_id);
        jsonData.filial_hospital_id = parseInt(jsonData.filial_hospital_id);
        jsonData.leito_id = parseInt(jsonData.leito_id);

        // Formatando data para MySQL
        const data = new Date(jsonData.data_consulta);
        jsonData.data_consulta = `${data.getFullYear()}-${String(data.getMonth() + 1).padStart(2, '0')}-${String(data.getDate()).padStart(2, '0')} ${String(data.getHours()).padStart(2, '0')}:${String(data.getMinutes()).padStart(2, '0')}:00`;

        document.getElementById("loader").style.display = "block";
        mostrarMensagem(""); // limpa mensagens

        fetch("http://hospital-web.local/?rota=consulta/criar", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(jsonData)
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById("loader").style.display = "none";
            if (data.mensagem) {
                mostrarMensagem(data.mensagem, "success");
                document.getElementById("cadastroConsulta").reset();
            } else {
                mostrarMensagem(data.erro || "Erro desconhecido ao cadastrar", "danger");
            }
        })
        .catch(err => {
            document.getElementById("loader").style.display = "none";
            console.error("Erro ao cadastrar:", err);
            mostrarMensagem("Erro ao cadastrar a consulta. Verifique os dados e tente novamente.", "danger");
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
