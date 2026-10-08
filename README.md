# **Sistema Hospitalar - Gestão de Pacientes, Medicamentos e Consultas**  

## 📌 **Descrição do Projeto**  
O **Sistema Hospitalar** é uma aplicação web desenvolvida em **PHP** para gerenciamento eficiente de hospitais, incluindo **cadastro de pacientes, médicos, medicamentos, estoque, consultas e receitas**. O sistema oferece uma interface intuitiva para administração hospitalar, controle de estoque e gerenciamento de leitos.  

## 🚀 **Principais Funcionalidades**  
✅ **Autenticação de Usuários** (Login seguro com hash de senha)  
✅ **Cadastro e Gerenciamento** de Pacientes, Médicos, Medicamentos e Estoque  
✅ **Controle de Estoque** com validação de quantidade mínima  
✅ **Prescrição de Receitas e Agendamento de Consultas**  
✅ **Sistema de Rotas Dinâmico** para facilitar o acesso às funcionalidades  
✅ **Dashboard Interativo** para monitoramento das informações do hospital  

## 🛠 **Tecnologias Utilizadas**  
- **Back-end:** PHP 8, MySQL/MariaDB, POO, PDO  
- **Front-end:** HTML5, CSS3, Bootstrap, JavaScript (AJAX)  
- **Segurança:** Hash de senha, Sessões PHP, Proteção contra SQL Injection  
- **Gerenciamento de Dependências:** Composer  

## 📂 **Estrutura do Projeto**  
```
hospital-web-app/
│── public/               # Arquivos acessíveis pelo navegador (index.php, formulários, login)
│── routes/               # Arquivos de rotas (definição de endpoints)
│── scripts/              # Scripts SQL para criar e popular o banco de dados
│── src/                  
│   ├── Config/           # Configuração do banco de dados (Database.php)
│   ├── Controllers/      # Controladores para cada entidade (Paciente, Medico, Estoque, etc.)
│   ├── Core/             # Sistema de roteamento (Router.php)
│   ├── Models/           # Modelos do banco de dados
│   ├── Services/         # Serviços auxiliares
│── tests/                # Testes de conexão e APIs
│── views/                # Arquivos visuais (futuro dashboard)
│── .env                  # Configurações sensíveis (exemplo: credenciais do BD)
│── composer.json         # Dependências do projeto
│── README.md             # Documentação do projeto
```

## 🔧 **Como Instalar e Executar o Projeto**  

### 1️⃣ **Pré-requisitos**  
Antes de começar, certifique-se de ter instalado:  
- **PHP 8** ou superior  
- **Composer** (Gerenciador de dependências do PHP)  
- **MySQL/MariaDB**  
- **Servidor Apache (XAMPP, WAMP, Laragon, etc.)**  

### 2️⃣ **Clonar o Repositório**  
```bash
git clone https://github.com/seuusuario/hospital-web-app.git
cd hospital-web-app
```

### 3️⃣ **Instalar as Dependências**  
```bash
composer install
```

### 4️⃣ **Configurar as Variáveis de Ambiente**  
Renomeie o arquivo `.env.example` para `.env` e configure as credenciais do banco de dados:  
```
DB_HOST=localhost
DB_NAME=hospital
DB_USER=root
DB_PASS=senha
DB_CHARSET=utf8
```

### 5️⃣ **Criar o Banco de Dados**  
```bash
mysql -u root -p < scripts/criar_banco.sql
```

### 6️⃣ **Iniciar o Servidor**  
```bash
php -S localhost:8000 -t public
```
Agora, abra **http://localhost:8000** no navegador e faça login! 🎉  

## 🔍 **Testando a API**  
Você pode testar as rotas do sistema via **Postman** ou `cURL`.  

Exemplo: Listar pacientes  
```bash
curl -X GET "http://localhost:8000/?rota=paciente/listar"
```

## 📌 **Futuras Melhorias**  
🔹 Implementar API RESTful com JWT  
🔹 Criar logs de atividades dos usuários  
🔹 Melhorar a interface gráfica com Vue.js ou React  

## 📜 **Licença**  
Este projeto é de código aberto e está sob a **Licença MIT**.  

---

### 📢 **Contribuição**  
Caso queira contribuir, fique à vontade para abrir **Issues** e **Pull Requests**.  

Se gostou do projeto, deixe uma ⭐ no repositório!  

---  

