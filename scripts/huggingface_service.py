from transformers import AutoModelForCausalLM, AutoTokenizer, pipeline
import sys

# Modelo
##modelo = "gpt2"

modelo = "t5-small"
gerador = pipeline("text2text-generation", model=modelo, tokenizer=modelo)


# Carregar tokenizer e modelo
tokenizer = AutoTokenizer.from_pretrained(modelo)
modelo = AutoModelForCausalLM.from_pretrained(modelo)

# Criar pipeline
##gerador = pipeline("text-generation", model=modelo, tokenizer=tokenizer)

# Verificar se o prompt foi fornecido
if len(sys.argv) < 2:
    print("❌ Erro: Nenhum prompt fornecido!")
    sys.exit(1)

prompt = sys.argv[1]

# Gerar texto com ajustes de temperatura e truncamento
resultado = gerador(prompt, max_length=200, num_return_sequences=1, temperature=0.7, top_p=0.9, truncation=True)

# Exibir saída
print(resultado[0]['generated_text'])
