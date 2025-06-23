<?php
// anunciar.php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Anunciar Produto - FatecGamer RMT</title>
   <link rel="stylesheet" href="styles/style.css">
</head>
<body>
   <header>
      <h1>Anunciar Produto - FatecGamer RMT</h1>
      <nav>
         <ul>
            <li><a href="index.html">Início</a></li>
            <li><a href="dashboard.php">Dashboard</a></li>
         </ul>
      </nav>
   </header>
   <section class="formulario">
      <h2>Publique seu Anúncio</h2>
      <!-- Formulário com enctype para permitir upload de arquivos -->
      <form action="processaAnuncio.php" method="post" enctype="multipart/form-data">
         <label for="jogo">Jogo:</label>
         <select id="jogo" name="jogo" required>
            <option value="">Selecione</option>
            <option value="Tibia">Tibia</option>
            <option value="World of Warcraft">World of Warcraft</option>
            <option value="Albion Online">Albion Online</option>
         </select>

         <label for="tipo">Tipo de Produto:</label>
         <input type="text" id="tipo" name="tipo" placeholder="Conta, Gold, Item..." required>

         <label for="descricao">Descrição:</label>
         <textarea id="descricao" name="descricao" placeholder="Detalhes do item..." required></textarea>

         <label for="preco">Preço (R$):</label>
         <input type="number" id="preco" name="preco" step="0.01" required>

         <label for="contato">Contato (WhatsApp):</label>
         <input type="text" id="contato" name="contato" placeholder="Número com DDI e DDD" required>

         <label for="imagem">Imagem do Produto:</label>
         <input type="file" id="imagem" name="imagem" accept="image/*">

         <button type="submit">Publicar Anúncio</button>
      </form>
   </section>
   <footer>
      <p>&copy; 2025 FatecGamer RMT</p>
   </footer>
</body>
</html>
