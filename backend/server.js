// server.js
const express = require('express');
const mongoose = require('mongoose');
const cors = require('cors');

const app = express();
const port = process.env.PORT || 3000;

// Middlewares
app.use(cors());
app.use(express.json()); // Para interpretar JSON do body

// Conexão com o MongoDB
// Substitua 'mongodb://localhost:27017/fatecgamer' pela sua string de conexão, se necessário.
mongoose.connect('mongodb://localhost:27017/fatecgamer', {
  useNewUrlParser: true,
  useUnifiedTopology: true
})
.then(() => console.log("Conectado ao MongoDB."))
.catch((err) => console.error("Erro ao conectar ao MongoDB:", err));

// Definição do Schema para um anúncio (produto)
const anuncioSchema = new mongoose.Schema({
  jogo: { type: String, required: true },
  tipo: { type: String, required: true },
  descricao: { type: String, required: true },
  preco: { type: Number, required: true },
  contato: { type: String, required: true },
  dataCriacao: { type: Date, default: Date.now }
});

const Anuncio = mongoose.model('Anuncio', anuncioSchema);

// Rota: Criar um novo anúncio
app.post('/anuncios', async (req, res) => {
  try {
    const { jogo, tipo, descricao, preco, contato } = req.body;
    const novoAnuncio = new Anuncio({ jogo, tipo, descricao, preco, contato });
    await novoAnuncio.save();
    res.status(201).json(novoAnuncio);
  } catch (error) {
    console.error(error);
    res.status(500).json({ message: "Erro ao publicar anúncio" });
  }
});

// Rota: Listar todos os anúncios
app.get('/anuncios', async (req, res) => {
  try {
    const anuncios = await Anuncio.find();
    res.json(anuncios);
  } catch (error) {
    console.error(error);
    res.status(500).json({ message: "Erro ao recuperar anúncios" });
  }
});

// Rota (Opcional): Listar anúncios filtrando por jogo
app.get('/anuncios/jogo/:nome', async (req, res) => {
  try {
    const anuncios = await Anuncio.find({ jogo: req.params.nome });
    res.json(anuncios);
  } catch (error) {
    console.error(error);
    res.status(500).json({ message: "Erro ao recuperar anúncios para o jogo especificado" });
  }
});

// Inicializa o servidor
app.listen(port, () => {
  console.log(`Servidor rodando na porta ${port}`);
});
