require('dotenv').config({ path: '../.env' }); // Carrega as variáveis de ambiente

const { GoogleGenerativeAI } = require("@google/generative-ai");

const API_KEY = process.env.GEMINI_API_KEY;

if (!API_KEY) {
  console.error("Erro: Variável de ambiente GEMINI_API_KEY não encontrada.");
} else {
  const genAI = new GoogleGenerativeAI(API_KEY);

  async function run() {
    const model = genAI.getGenerativeModel({ model: "gemini-1.5-flash" });

    const prompt =
      "Forneça as instruçoes para se implemetar um chatbot em um estoque com api genai";

    const result = await model.generateContent(prompt);
    const response = await result.response;
    const text = response.text();
    console.log(text);
  }

  run().catch((error) => console.error("Erro:", error));
}