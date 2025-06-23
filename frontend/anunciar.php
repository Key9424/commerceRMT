<?php
// anunciar.php
session_start();
if (!isset($_SESSION["user_id"])) {
   header("Location: login.php");
   exit;
}
?>
<?php
$pageTitle = "FatecGamer RMT - Anunciar Produto";
include 'header.php';
?>
<h1>Anunciar Produto - FatecGamer RMT</h1>
<section class="formulario">
   <h2>Publique seu Anúncio</h2>
   <!-- Formulário com enctype para permitir upload de arquivos -->
   <div class="formAnuncio">
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
   </div>
</section>
<?php include 'footer.php'; ?>
</body>

</html>