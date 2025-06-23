<?php
// login.php
session_start();
require_once __DIR__ . '/../db.php';

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
<?php
$pageTitle = "FatecGamer RMT - Login";
include 'header.php';
?>

<body>
  <section class="formAnuncio">
    <h2>Login</h2>
    <?php if (isset($error)): ?>
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
  <?php include 'footer.php'; ?>
  <script>
    function togglePassword() {
      const pwd = document.getElementById('password');
      pwd.type = pwd.type === 'password' ? 'text' : 'password';
    }
  </script>
</body>

</html>