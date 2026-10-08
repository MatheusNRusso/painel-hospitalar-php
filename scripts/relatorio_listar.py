import google.generativeai as genai
import os
import requests
import json
import sys
from dotenv import load_dotenv

# Carregar variáveis de ambiente
load_dotenv(dotenv_path='../.env')

# Configurar API Key do Gemini
api_key = os.getenv("GEMINI_API_KEY")

if api_key is None:
    print("Erro: Variável de ambiente GEMINI_API_KEY não encontrada.")
    exit(1)
else:
    genai.configure(api_key=api_key)

# Verificar se a tabela foi passada como argumento
if len(sys.argv) < 2:
    print("❌ Erro: Nenhuma tabela especificada. Use: python relatorio_service.py <nome_tabela>")
    exit(1)

# Captura o nome da tabela da linha de comando
tabela = sys.argv[1]

# URL dinâmica para buscar os dados da tabela especificada
URL_RELATORIO = f"http://hospital-web.local/?rota=relatorio/listar/{tabela}"

def obter_dados_relatorio():
    """Faz uma requisição à API do sistema e retorna os dados formatados para o relatório."""
    try:
        response = requests.get(URL_RELATORIO)

        if response.status_code != 200:
            print(f"❌ Erro ao acessar a rota: {response.status_code}")
            return None

        dados = response.json()

        if "dados" not in dados:
            print("❌ Erro: Resposta inválida da API.")
            return None

        return dados["dados"]

    except requests.exceptions.RequestException as e:
        print(f"❌ Erro na requisição: {e}")
        return None

def gerar_relatorio():
    """Obtém os dados da tabela e gera o relatório usando a API do Gemini."""
    prompt = obter_dados_relatorio()

    if prompt is None:
        print("❌ Não foi possível obter os dados da tabela.")
        return

    try:
        model = genai.GenerativeModel('gemini-1.5-flash')
        response = model.generate_content(contents=prompt)

        if hasattr(response, "text"):
            print("\n📊 **Relatório Gerado:**\n")
            print(response.text)
        else:
            print("❌ Erro: Resposta inesperada da API")

    except Exception as e:
        print(f"❌ Erro ao gerar relatório: {e}")

# Executa automaticamente quando chamado pelo terminal
if __name__ == "__main__":
    gerar_relatorio()
