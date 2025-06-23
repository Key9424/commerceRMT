document.addEventListener('DOMContentLoaded', () => {
  // Inicialmente carrega a página 1 sem filtros
  loadAnuncios(1);

  // Captura os elementos do filtro
  const aplicarFiltroBtn = document.getElementById('aplicar-filtro');
  const filtroSelect = document.getElementById('filtro-jogo');
  const searchInput = document.getElementById('search-keyword');
  const orderSelect = document.getElementById('order');

  aplicarFiltroBtn.addEventListener('click', () => {
    // Obtém os valores dos filtros
    const jogo = filtroSelect.value;
    const search = searchInput.value;
    const order = orderSelect.value;
    // Carrega a primeira página com os filtros selecionados
    loadAnuncios(1, jogo, search, order);
  });

  fetch('listarAnuncios.php')
    .then(res => res.json())
    .then(anuncios => {
      const lista = document.getElementById('lista-anuncios');
      lista.innerHTML = '';
      anuncios.forEach(anuncio => {
        const card = document.createElement('div');
        card.className = 'card';
        card.innerHTML = `
          <div class="card-body">
            <h5 class="card-title">${anuncio.jogo}</h5>
            <p class="card-text">${anuncio.descricao}</p>
            <p class="card-text"><strong>Preço:</strong> R$ ${anuncio.preco}</p>
            <small>${anuncio.dataCriacao}</small>
          </div>
        `;
        lista.appendChild(card);
      });
    })
    .catch(err => {
      console.error('Erro ao buscar anúncios:', err);
    });
});

async function loadAnuncios(page, jogo = '', search = '', order = '') {
  try {
    const limit = 10; // Quantidade de anúncios por página
    let url = `http://localhost/anuncios.php?page=${page}&limit=${limit}`;

    if (jogo) {
      url += `&jogo=${encodeURIComponent(jogo)}`;
    }
    if (search) {
      url += `&search=${encodeURIComponent(search)}`;
    }
    if (order) {
      url += `&order=${encodeURIComponent(order)}`;
    }

    const response = await fetch(url);
    if (!response.ok) {
      throw new Error('Erro ao carregar os anúncios');
    }

    // A resposta vem com "data", "total", "page", "limit" e "pages"
    const result = await response.json();
    const anuncios = result.data;

    // Preenche o container de anúncios
    const lista = document.getElementById('lista-anuncios');
    lista.innerHTML = '';

    if (anuncios && anuncios.length > 0) {
      anuncios.forEach(anuncio => {
        const card = document.createElement('div');
        card.classList.add('card');
        card.innerHTML = `
          <h4>${anuncio.jogo} - ${anuncio.tipo}</h4>
          <p>${anuncio.descricao.substring(0, 100)}...</p>
          <p><strong>Preço:</strong> R$ ${Number(anuncio.preco).toFixed(2)}</p>
          <a href="detalhes.html?id=${anuncio.id}">Ver detalhes</a>
        `;
        lista.appendChild(card);
      });
    } else {
      lista.innerHTML = '<p>Nenhum anúncio encontrado.</p>';
    }

    // Cria os controles de paginação
    const paginationContainer = document.getElementById('pagination');
    paginationContainer.innerHTML = '';

    for (let i = 1; i <= result.pages; i++) {
      const btn = document.createElement('button');
      btn.textContent = i;
      if (i === result.page) {
        btn.classList.add('active');
      }
      btn.addEventListener('click', () => loadAnuncios(i, jogo, search, order));
      paginationContainer.appendChild(btn);
    }

  } catch (error) {
    console.error("Erro ao carregar anúncios:", error);
    document.getElementById('lista-anuncios').innerHTML = '<p>Ocorreu um erro ao carregar os anúncios.</p>';
  }
}
