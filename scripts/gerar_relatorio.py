import google.generativeai as genai
import os
import sys

from dotenv import load_dotenv

load_dotenv(dotenv_path='../.env')

api_key = os.getenv("GEMINI_API_KEY")

if api_key is None:
    print("Erro: Variável de ambiente GEMINI_API_KEY não encontrada.")
else:
    genai.configure(api_key=api_key)

def gerar_realatorio(prompt):
    try:
        model = genai.GenerativeModel('gemini-1.5-flash')
        response = model.generate_content(prompt)
        print(response.text)

    except Exception as e:
        print(f"Erro: {e}")

if __name__ == "__main__":
    if len(sys.argv) > 1:
        prompt = sys.argv[1]
        gerar_realatorio(prompt)
    else:
        print("Erro: Nenhum prompt fornecido como argumento de linha de comando.")