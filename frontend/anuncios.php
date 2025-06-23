<?php
$pageTitle = "FatecGamer RMT - Anuncios";
include 'header.php';
?>

<body>
  <!-- Área de Filtros e Busca -->
  <div class="formAnuncio">
    <section class="filtro">
      <label for="filtro-jogo">Filtrar por Jogo:</label>
      <select id="filtro-jogo">
        <option value="">Todos</option>
        <option value="Tibia">Tibia</option>
        <option value="World of Warcraft">World of Warcraft</option>
        <option value="Albion Online">Albion Online</option>
      </select>
      <br>
      <label for="search-keyword">Buscar:</label>
      <input type="text" id="search-keyword" placeholder="Digite as palavras-chave">
      <br>
      <label for="order">Ordenar por:</label>
      <select id="order">
        <option value="">Data Recente</option>
        <option value="priceAsc">Preço - Crescente</option>
        <option value="priceDesc">Preço - Decrescente</option>
      </select>
      <br>
      <button id="aplicar-filtro">Aplicar Filtro</button>
    </section>
  </div>
  <section class="anuncios">
    <h2>Anúncios Publicados</h2>
    <div id="lista-anuncios" class="grid">
      <!-- Os cards dos anúncios serão inseridos aqui dinamicamente -->
    </div>
    <!-- Container para os controles de paginação -->
    <div id="pagination" class="pagination"></div>
  </section>

  <?php include 'footer.php'; ?>

  <script src="scripts/listarAnuncios.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>