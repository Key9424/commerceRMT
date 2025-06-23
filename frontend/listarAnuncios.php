<?php
require_once __DIR__ . '/../db.php';
$stmt = $pdo->query("SELECT * FROM anuncios ORDER BY dataCriacao DESC");
$anuncios = $stmt->fetchAll(PDO::FETCH_ASSOC);
header('Content-Type: application/json');
echo json_encode($anuncios);
