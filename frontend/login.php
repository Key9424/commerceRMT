<?php
// login.php
session_start();
require_once '../database/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    // Busca o usuário no banco
    $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = :username");
    $stmt->execute(["username" => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Credenciais inválidas.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - FatecGamer RMT</title>
  <link rel="stylesheet" href="styles/style.css">
</head>
<body>
  <header>
    <h1>Login no FatecGamer RMT</h1>
    <nav>
      <ul>
        <li><a href="index.html">Início</a></li>
        <li><a href="register.php">Registrar</a></li>
      </ul>
    </nav>
  </header>
  <section class="formulario">
    <h2>Login</h2>
    <?php if(isset($error)): ?>
      <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="post" action="login.php" autocomplete="on">
      <label for="username">Nome de usuário:</label>
      <input type="text" id="username" name="username" required placeholder="Digite seu usuário" autofocus>
      
      <label for="password">Senha:</label>
      <input type="password" id="password" name="password" required placeholder="Digite sua senha">
      
      <button type="submit">Entrar</button>
      <p style="margin-top:10px;">
        <a href="register.php">Não tem conta? Cadastre-se</a>
      </p>
    </form>
  </section>
  <footer>
    <p>&copy; 2025 FatecGamer RMT</p>
  </footer>
</body>
</html>
