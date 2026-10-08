<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Paciente</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Cadastrar Paciente</h2>
        <form id="cadastroPaciente">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Paciente</label>
                <input type="text" id="nome" name="nome" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="cpf" class="form-label">CPF</label>
                <input type="text" id="cpf" name="cpf" class="form-control" required maxlength="11" pattern="\d{11}" title="Digite apenas números (11 dígitos).">
            </div>
            <div class="mb-3">
                <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                <input type="date" id="data_nascimento" name="data_nascimento" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="peso" class="form-label">Peso do Paciente</label>
                <input type="number" step="0.01" id="peso" name="peso" class="form-control" required min="0">
            </div>

            <div class="d-flex justify-content-between">
                 <a href="?rota=listarPacientes" class="btn btn-outline-dark">⬅️ Voltar</a>
                 <div>
                    <button type="submit" class="btn btn-primary">💾 Cadastrar</button>
                    <a href="?rota=dashboard" class="btn btn-secondary">Cancelar</a>
                </div>
            </div>
            
        </form>
        <div id="mensagem" class="mt-3"></div>
    </div>
    <script>
        document.getElementById("cadastroPaciente").addEventListener("submit", function(event) {
            event.preventDefault();
            let formData = new FormData(this);
            let jsonData = Object.fromEntries(formData);

            fetch("http://hospital-web.local/?rota=paciente/criar", {
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
                if (data.mensagem) {
                    document.getElementById("mensagem").innerHTML = `<div class='alert alert-success'>${data.mensagem}</div>`;
                    setTimeout(() => location.reload(), 2000); // Recarrega a página após 2 segundos
                } else if (data.erro) {
                    document.getElementById("mensagem").innerHTML = `<div class='alert alert-danger'>${data.erro}</div>`;
                }
            })
            .catch(error => {
                console.error("Erro ao enviar requisição:", error);
                document.getElementById("mensagem").innerHTML = `<div class='alert alert-danger'>Erro ao cadastrar paciente. Tente novamente.</div>`;
            });
        });
    </script>
</body>
</html>