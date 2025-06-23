<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
require_once __DIR__ . '/../db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
  http_response_code(400);
  echo json_encode(['message' => 'ID inválido']);
  exit;
}

try {
  $stmt = $pdo->prepare("SELECT * FROM anuncios WHERE id = :id");
  $stmt->execute([':id' => $id]);
  $anuncio = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($anuncio) {
    echo json_encode($anuncio);
  } else {
    http_response_code(404);
    echo json_encode(['message' => 'Anúncio não encontrado']);
  }
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['message' => 'Erro ao buscar anúncio', 'error' => $e->getMessage()]);
}
