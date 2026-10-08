<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Tipo de Medicamento</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Cadastrar Tipo de Medicamento</h2>
        <form id="cadastroTipoMedicamento">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Tipo</label>
                <input type="text" id="nome" name="nome" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Cadastrar Tipo</button>
        </form>
        <div id="mensagem" class="mt-3"></div>
    </div>

    <script>
        document.getElementById("cadastroTipoMedicamento").addEventListener("submit", function(event) {
            event.preventDefault();
            let formData = new FormData(this);
            let jsonData = Object.fromEntries(formData);

            fetch("http://hospital-web.local/?rota=tipo-medicamento/criar", {
                method: "POST",
                body: JSON.stringify(jsonData),
                headers: { "Content-Type": "application/json" }
            })
            .then(response => response.json())
            .then(data => {
                if (data.mensagem) 
                {
                    document.getElementById("mensagem").innerHTML = `<div class='alert alert-success'>${data.mensagem}</div>`;
                    setTimeout(() => 
                    window.location.href = "?rota=listarTipos", 2000);
                } else if (data.erro) 
                {
                    document.getElementById("mensagem").innerHTML = `<div class='alert alert-danger'>${data.erro}</div>`;
                }
            })
            .catch(error => {
                console.error("Erro ao enviar requisição:", error);
                document.getElementById("mensagem").innerHTML = `<div class='alert alert-danger'>Erro ao cadastrar tipo de medicamento.</div>`;
            });
        });
    </script>
</body>
</html>
