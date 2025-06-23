<?php
// editarAnuncio.php
session_start();
if (!isset($_SESSION['user_id'])) {
   header("Location: login.php");
   exit;
}
require_once __DIR__ . '/../db.php';

$user_id = $_SESSION['user_id'];
if (!isset($_GET['id'])) {
   echo "Anúncio não especificado.";
   exit;
}
$id = intval($_GET['id']);

// Verifica se o anúncio pertence ao usuário
$stmt = $pdo->prepare("SELECT * FROM anuncios WHERE id = :id AND user_id = :user_id");
$stmt->execute([':id' => $id, ':user_id' => $user_id]);
$anuncio = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$anuncio) {
   echo "Anúncio não encontrado ou acesso não autorizado.";
   exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $jogo = $_POST["jogo"];
   $tipo = $_POST["tipo"];
   $descricao = $_POST["descricao"];
   $preco = $_POST["preco"];
   $contato = $_POST["contato"];

   // Mantém a imagem atual por padrão
   $nomeImagem = $anuncio['imagem'];
   // Verifica se uma nova imagem foi enviada
   if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
      $pastaUpload = '../public/uploads/';
      if (!is_dir($pastaUpload)) {
         mkdir($pastaUpload, 0777, true);
      }
      $nomeImagemNovo = uniqid() . '_' . basename($_FILES['imagem']['name']);
      $caminhoCompleto = $pastaUpload . $nomeImagemNovo;
      if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoCompleto)) {
         $nomeImagem = $nomeImagemNovo;
         // Opcional: remover a imagem antiga, se existir
         if (!empty($anuncio['imagem']) && file_exists($pastaUpload . $anuncio['imagem'])) {
            unlink($pastaUpload . $anuncio['imagem']);
         }
      }
   }

   // Atualiza o anúncio no banco
   $stmtUpdate = $pdo->prepare("UPDATE anuncios SET jogo = :jogo, tipo = :tipo, descricao = :descricao, preco = :preco, contato = :contato, imagem = :imagem WHERE id = :id AND user_id = :user_id");
   $stmtUpdate->execute([
      ':jogo'      => $jogo,
      ':tipo'      => $tipo,
      ':descricao' => $descricao,
      ':preco'     => $preco,
      ':contato'   => $contato,
      ':imagem'    => $nomeImagem,
      ':id'        => $id,
      ':user_id'   => $user_id
   ]);

   header("Location: dashboard.php");
   exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Editar Anúncio - FatecGamer RMT</title>
   <link rel="stylesheet" href="styles/style.css">
</head>

<body>
   <header>
      <h1>Editar Anúncio</h1>
      <nav>
         <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="logout.php">Logout</a></li>
         </ul>
      </nav>
   </header>
   <section class="formAnuncio">
      <h2>Editar Anúncio</h2>
      <form action="editarAnuncio.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
         <label for="jogo">Jogo:</label>
         <select id="jogo" name="jogo" required>
            <option value="">Selecione</option>
            <option value="Tibia" <?php echo ($anuncio['jogo'] == 'Tibia') ? 'selected' : ''; ?>>Tibia</option>
            <option value="World of Warcraft" <?php echo ($anuncio['jogo'] == 'World of Warcraft') ? 'selected' : ''; ?>>World of Warcraft</option>
            <option value="Albion Online" <?php echo ($anuncio['jogo'] == 'Albion Online') ? 'selected' : ''; ?>>Albion Online</option>
         </select>

         <label for="tipo">Tipo de Produto:</label>
         <input type="text" id="tipo" name="tipo" value="<?php echo htmlspecialchars($anuncio['tipo']); ?>" required>

         <label for="descricao">Descrição:</label>
         <textarea id="descricao" name="descricao" required><?php echo htmlspecialchars($anuncio['descricao']); ?></textarea>

         <label for="preco">Preço (R$):</label>
         <input type="number" id="preco" name="preco" step="0.01" value="<?php echo $anuncio['preco']; ?>" required>

         <label for="contato">Contato (WhatsApp):</label>
         <input type="text" id="contato" name="contato" value="<?php echo htmlspecialchars($anuncio['contato']); ?>" required>

         <p>Imagem atual:</p>
         <?php if (!empty($anuncio['imagem'])): ?>
            <img src="uploads/<?php echo $anuncio['imagem']; ?>" alt="Imagem do anúncio" style="max-width:200px;">
         <?php else: ?>
            <p>Sem imagem</p>
         <?php endif; ?>

         <label for="imagem">Alterar Imagem:</label>
         <input type="file" id="imagem" name="imagem" accept="image/*">

         <button type="submit">Atualizar Anúncio</button>
      </form>
   </section>
   <footer>
      <p>&copy; 2025 FatecGamer RMT</p>
   </footer>
</body>

</html>