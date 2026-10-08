import google.generativeai as genai
import os
import requests
import json
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

# URL da API para obter os dados do estoque
URL_ESTOQUE = "http://hospital-web.local/?rota=estoque/gerar-relatorio"

def obter_dados_estoque():
    """Faz uma requisição à API do estoque e retorna os dados formatados para o relatório."""
    try:
        response = requests.get(URL_ESTOQUE)
        
        if response.status_code != 200:
            print(f"❌ Erro ao acessar a rota: {response.status_code}")
            return None
        
        dados = response.json()

        if "dados" not in dados:
            print("❌ Erro: Resposta inválida da API de estoque.")
            return None

        return dados["dados"]

    except requests.exceptions.RequestException as e:
        print(f"❌ Erro na requisição: {e}")
        return None

def gerar_relatorio():
    """Obtém os dados do estoque e gera o relatório usando a API do Gemini."""
    prompt = obter_dados_estoque()

    if prompt is None:
        print("❌ Não foi possível obter os dados do estoque.")
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
