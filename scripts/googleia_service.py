import google.generativeai as genai
import os
import requests
import json
import sys
from dotenv import load_dotenv
from datetime import datetime

# Carregar variáveis de ambiente
load_dotenv(dotenv_path='../.env')

# Configurar API Key do Gemini
api_key = os.getenv("GEMINI_API_KEY")

if api_key is None:
    print("Erro: Variável de ambiente GEMINI_API_KEY não encontrada.")
    exit(1)
else:
    genai.configure(api_key=api_key)

# Captura o nome da tabela da linha de comando
tabela = sys.argv[1]

# URL dinâmica para buscar os dados da tabela especificada
URL_RELATORIO = f"http://hospital-web.local/?rota=relatorio/listar/{tabela}"

# URL da API para obter os dados do estoque
#URL_ESTOQUE = "http://hospital-web.local/?rota=relatorio/listar"

def obter_dados_estoque():
    """Faz uma requisição à API do estoque e retorna os dados formatados para o relatório."""
    try:
        response = requests.get(URL_RELATORIO)
        
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
    dados_api = obter_dados_estoque()

    if dados_api is None:
        print("❌ Não foi possível obter os dados do estoque.")
        return

    try:
        prompt = f"""
        {dados_api}

        **⚠️ Instruções Estritas de Formatação:**

        1. ✅ Responda **EXCLUSIVAMENTE** com o conteúdo em **Markdown puro**, bem formatado.

        2. ❌ **NÃO inclua qualquer data ou hora** no conteúdo, seja no rodapé ou em qualquer parte do texto.

        3. ✅ Use títulos com `#`, listas com `-` ou `*`, negrito com `**` e itálico com `_` conforme o padrão Markdown.

        4. ❌ **Não insira** textos automáticos como:
        - Datas como "Gerado em..." ou "Impresso em..."
        - Menções como "hoje", "ontem" ou datas relativas.
        - Disclaimers ou avisos do modelo.

        5. ✅ O conteúdo deve estar limpo, direto e organizado, adequado para ser processado por um parser Markdown como `marked.js`.

        6. ✅ Mantenha a estrutura analítica padrão, incluindo seções como:
        - Introdução
        - Estrutura dos Dados
        - Análise dos Dados
        - Sugestões de Melhorias
        - Conclusão
        """

        model = genai.GenerativeModel('gemini-1.5-flash')
        response = model.generate_content(contents=prompt)

        if hasattr(response, "text"):
            print(response.text)
        else:
            print("❌ Erro: Resposta inesperada da API")

    except Exception as e:
        print(f"❌ Erro ao gerar relatório: {e}")


# Executa automaticamente quando chamado pelo terminal
if __name__ == "__main__":
    gerar_relatorio()
