<?php
// excluirAnuncio.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require_once __DIR__ . '/../db.php';

if (!isset($_GET['id'])) {
    echo "Anúncio não especificado.";
    exit;
}

$id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Verifica se o anúncio pertence ao usuário
$stmt = $pdo->prepare("SELECT * FROM anuncios WHERE id = :id AND user_id = :user_id");
$stmt->execute([':id' => $id, ':user_id' => $user_id]);
$anuncio = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$anuncio) {
    echo "Anúncio não encontrado ou acesso não autorizado.";
    exit;
}

// Se o anúncio possuir imagem, remove o arquivo
if (!empty($anuncio['imagem']) && file_exists('../public/uploads/' . $anuncio['imagem'])) {
    unlink('../public/uploads/' . $anuncio['imagem']);
}

// Remove o anúncio do banco de dados
$stmtDelete = $pdo->prepare("DELETE FROM anuncios WHERE id = :id AND user_id = :user_id");
$stmtDelete->execute([':id' => $id, ':user_id' => $user_id]);

header("Location: dashboard.php");
exit;
