<?php
require_once __DIR__ . '/../db.php';
$pageTitle = "FatecGamer RMT - Início";
include 'header.php';

$stmt = $pdo->query("SELECT * FROM jogos ORDER BY id DESC");
$jogos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Banner Section -->
<section class="banner bg-dark py -5">
  <div class="container text-center">
    <h2 class="display-4">Compre e venda itens de jogos com confiança ⚔️</h2>
    <p class="lead">Segurança, mediação e suporte garantidos.</p>
  </div>
</section>

<!-- Jogos Disponíveis Section -->
<section class="jogos py-5">
  <div class="container">
    <h3 class="mb-4">Jogos Disponíveis</h3>
    <div class="jogos-carousel">
      <div class="jogos-track">
        <?php foreach ($jogos as $jogo): ?>
          <div class="card">
            <?php if (!empty($jogo['imagem'])): ?>
              <img src="../public/uploads/<?php echo htmlspecialchars($jogo['imagem']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($jogo['titulo']); ?>">
            <?php endif; ?>
            <div class="card-body text-center">
              <p class="card-text"><?php echo htmlspecialchars($jogo['titulo']); ?></p>
              <small><?php echo htmlspecialchars($jogo['descricao']); ?></small>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>