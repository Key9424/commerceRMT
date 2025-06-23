<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once __DIR__ . '/../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  // Validação simples
  if (empty($username) || empty($email) || empty($password)) {
    $error = "Por favor, preencha todos os campos.";
  } elseif ($password !== $confirm_password) {
    $error = "As senhas não conferem.";
  } else {
    // Verifica se o nome de usuário ou email já existem
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
    $stmt->execute(["username" => $username, "email" => $email]);
    if ($stmt->rowCount() > 0) {
      $error = "Nome de usuário ou email já cadastrados.";
    } else {
      // Insere o novo usuário com senha criptografada
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
      if ($stmt->execute(["username" => $username, "email" => $email, "password" => $hashed_password])) {
        $_SESSION["user_id"] = $pdo->lastInsertId();
        $_SESSION["username"] = $username;
        header("Location: dashboard.php");
        exit;
      } else {
        $errorInfo = $stmt->errorInfo();
        $error = "Erro ao cadastrar usuário: " . $errorInfo[2];
      }
    }
  }
}

$pageTitle = "FatecGamer RMT - Registro";
include 'header.php';
?>

<body>
  <section class="formAnuncio">
    <h2>Registrar</h2>
    <?php if (isset($error)): ?>
      <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="post" action="register.php">
      <label for="username">Nome de usuário:</label>
      <input type="text" id="username" name="username" required>

      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required>

      <label for="password">Senha:</label>
      <input type="password" id="password" name="password" required>

      <label for="confirm_password">Confirme a Senha:</label>
      <input type="password" id="confirm_password" name="confirm_password" required>

      <button type="submit">Registrar</button>
    </form>
  </section>
  <?php include 'footer.php'; ?>
</body>

</html>