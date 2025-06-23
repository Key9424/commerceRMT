<?php
// login.php
session_start();
require_once '../database/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

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
  <style>
    .msg-erro { color: #fff; background: #c0392b; padding: 0.7em 1em; border-radius: 4px; margin-bottom: 1em; }
    .show-hide { cursor: pointer; color: #00ffcc; margin-left: 8px; }
    .formulario input[type="text"], .formulario input[type="password"] { width: 100%; max-width: 350px; }
  </style>
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
      <div class="msg-erro" role="alert"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="post" action="login.php" autocomplete="on">
      <label for="username">Nome de usuário:</label>
      <input type="text" id="username" name="username" required placeholder="Digite seu usuário" autofocus
        value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">

      <label for="password">Senha:</label>
      <div style="position:relative;max-width:350px;">
        <input type="password" id="password" name="password" required placeholder="Digite sua senha" aria-label="Senha">
        <span class="show-hide" onclick="togglePassword()" tabindex="0" aria-label="Mostrar ou ocultar senha">👁️</span>
      </div>

      <button type="submit" id="btn-login">Entrar</button>
      <p style="margin-top:10px;">
        <a href="register.php">Não tem conta? Cadastre-se</a>
      </p>
    </form>
  </section>
  <footer>
    <p>&copy; 2025 FatecGamer RMT</p>
  </footer>
  <script>
    function togglePassword() {
      const pwd = document.getElementById('password');
      pwd.type = pwd.type === 'password' ? 'text' : 'password';
    }
  </script>
</body>
</html>
