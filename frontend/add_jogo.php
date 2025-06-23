<?php
require_once __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['title'] ?? '');
    $descricao = trim($_POST['desc'] ?? '');
    $imgName = '';

    if ($titulo && $descricao && isset($_FILES['img'])) {
        $img = $_FILES['img'];
        $ext = pathinfo($img['name'], PATHINFO_EXTENSION);
        $imgName = uniqid('jogo_') . '.' . $ext;
        $dest = __DIR__ . '/../public/uploads/' . $imgName;
        move_uploaded_file($img['tmp_name'], $dest);

        $stmt = $pdo->prepare("INSERT INTO jogos (titulo, descricao, imagem) VALUES (?, ?, ?)");
        $stmt->execute([$titulo, $descricao, $imgName]);
        echo json_encode(['success' => true, 'img' => $imgName]);
        exit;
    }
}
echo json_encode(['success' => false]);
