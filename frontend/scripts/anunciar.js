document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('anuncio-form');
  
  form.addEventListener('submit', async (event) => {
    event.preventDefault();
    
    const anuncio = {
      jogo: document.getElementById('jogo').value,
      tipo: document.getElementById('tipo').value,
      descricao: document.getElementById('descricao').value,
      preco: Number(document.getElementById('preco').value),
      contato: document.getElementById('contato').value
    };
    
    try {
      const response = await fetch('http://localhost/anuncios.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(anuncio)
      });
      
      const result = await response.json();
      
      if (response.ok) {
        alert('Anúncio publicado com sucesso!');
        form.reset();
      } else {
        alert('Erro ao publicar anúncio: ' + result.message);
      }
    } catch (error) {
      console.error('Erro:', error);
      alert('Erro ao publicar anúncio');
    }
  });
});
