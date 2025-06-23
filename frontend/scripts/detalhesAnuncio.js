// detalhesAnuncio.js
document.addEventListener('DOMContentLoaded', async () => {
  const container = document.getElementById('detalhes-anuncio');

  // Obtém o ID do anúncio pela URL (ex: detalhes.html?id=123)
  const params = new URLSearchParams(window.location.search);
  const id = params.get('id');

  if (!id) {
    container.innerHTML = '<p>ID do anúncio não informado.</p>';
    return;
  }

  try {
    // Faz a requisição para o endpoint individual
    const response = await fetch(`http://localhost/anuncio.php?id=${id}`);
    if (!response.ok) throw new Error('Erro ao buscar dados do anúncio');

    const anuncio = await response.json();

    // Se o campo "imagem" estiver definido, utiliza-o; caso contrário, usa uma imagem padrão.
    let imagemHTML = '';
    if (anuncio.imagem) {
      // Assume que a imagem foi salva na pasta "uploads"
      imagemHTML = `<img src="../public/uploads/${anuncio.imagem}" alt="${anuncio.jogo}" />`;
    } else {
      // Imagem padrão
      imagemHTML = `<img src="../public/imagens/default.jpg" alt="${anuncio.jogo}" />`;
    }

    // Cria uma URL configurada para o WhatsApp (confira o formato do número de telefone em 'contato')
    // Aqui se assume que 'contato' armazena o número no formato internacional, ex: 5511999998888
    const whatsappURL = `https://api.whatsapp.com/send?phone=${encodeURIComponent(anuncio.contato)}&text=${encodeURIComponent('Olá, estou interessado no anúncio de ' + anuncio.jogo + ' - ' + anuncio.tipo)}`;

    container.innerHTML = `
      ${imagemHTML}
      <h2>${anuncio.jogo} - ${anuncio.tipo}</h2>
      <p><strong>Descrição:</strong> ${anuncio.descricao}</p>
      <p><strong>Preço:</strong> R$ ${Number(anuncio.preco).toFixed(2)}</p>
      <p><strong>Contato:</strong> ${anuncio.contato}</p>
      <p><strong>Publicado em:</strong> ${new Date(anuncio.dataCriacao).toLocaleDateString()}</p>
      <a href="${whatsappURL}" target="_blank" class="btn-contato">Entrar em Contato via WhatsApp</a>
    `;
  } catch (err) {
    console.error(err);
    container.innerHTML = '<p>Erro ao carregar o anúncio.</p>';
  }
});
