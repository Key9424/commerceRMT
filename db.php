<?php
// db.php
$host = 'localhost';
$dbname = 'fatecgamer';
$user = 'root';     // ajuste conforme sua configuração
$pass = '';         // ajuste conforme sua configuração

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>
