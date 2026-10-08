<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Medicamento ao Estoque</title>
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
        <h2>Adicionar Medicamento ao Estoque</h2>
        <form id="cadastroEstoque">
            <div class="mb-3">
                <label for="filial_hospital_id" class="form-label">Filial Hospital</label>
                <select id="filial_hospital_id" name="filial_hospital_id" class="form-control" required>
                    <option value="">Carregando...</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="medicamento_id" class="form-label">Medicamento</label>
                <select id="medicamento_id" name="medicamento_id" class="form-control" required>
                    <option value="">Carregando...</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="quantidade" class="form-label">Quantidade</label>
                <input type="number" id="quantidade" name="quantidade" class="form-control" min="1" required>
            </div>
            <div class="mb-3">
                <label for="quantidade_minima" class="form-label">Quantidade Mínima</label>
                <input type="number" id="quantidade_minima" name="quantidade_minima" class="form-control" min="1" required>
            </div>
            <div class="d-flex justify-content-between">
                 <a href="?rota=listarEstoque" class="btn btn-outline-dark">⬅️ Voltar</a>
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
        document.addEventListener("DOMContentLoaded", function() {
            carregarOpcoes("medicamento/listarJSON", "medicamento_id", "Selecione um medicamento");
            carregarOpcoes("filialHospital/listar", "filial_hospital_id", "Selecione uma filial");
        });

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

        document.getElementById("cadastroEstoque").addEventListener("submit", function(event) {
            event.preventDefault();
            let formData = new FormData(this);
            let jsonData = Object.fromEntries(formData.entries()); // Garante um objeto JSON correto

            
            jsonData.filial_hospital_id = parseInt(jsonData.filial_hospital_id);
            jsonData.medicamento_id = parseInt(jsonData.medicamento_id);
            jsonData.quantidade = parseInt(jsonData.quantidade);
            jsonData.quantidade_minima = parseInt(jsonData.quantidade_minima);
            
            document.getElementById("loader").style.display = "block";
            document.getElementById("mensagem").innerHTML = ""; // Limpa mensagens anteriores

            fetch("http://hospital-web.local/?rota=estoque/adicionarItem", {
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
