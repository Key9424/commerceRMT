<?php
// processaAnuncio.php
session_start();
require_once __DIR__ . '/../db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os valores do formulário
    $jogo = $_POST["jogo"];
    $tipo = $_POST["tipo"];
    $descricao = $_POST["descricao"];
    $preco = $_POST["preco"];
    $contato = $_POST["contato"];

    // Inicializa o nome da imagem como NULL
    $nomeImagem = null;

    // Processa o upload da imagem, se houver
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        // Pasta para upload
        $pastaUpload = '../public/uploads/';

        // Cria a pasta se ela não existir
        if (!is_dir($pastaUpload)) {
            mkdir($pastaUpload, 0777, true);
        }

        // Gera um nome único para o arquivo e define o caminho completo
        $nomeImagem = uniqid() . '_' . basename($_FILES['imagem']['name']);
        $caminhoCompleto = $pastaUpload . $nomeImagem;

        // Move o arquivo para a pasta de uploads
        if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoCompleto)) {
            $erro = "Erro ao fazer upload da imagem.";
        }
    }

    // Verifica se ocorreu algum erro no upload
    if (!isset($erro)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO anuncios (user_id, jogo, tipo, descricao, preco, contato, imagem, dataCriacao) VALUES (:user_id, :jogo, :tipo, :descricao, :preco, :contato, :imagem, NOW())");
            $stmt->execute([
                ':user_id'    => $_SESSION['user_id'],
                ':jogo'       => $jogo,
                ':tipo'       => $tipo,
                ':descricao'  => $descricao,
                ':preco'      => $preco,
                ':contato'    => $contato,
                ':imagem'     => $nomeImagem
            ]);
            // Redireciona para o dashboard após sucesso na publicação
            header("Location: dashboard.php");
            exit;
        } catch (PDOException $e) {
            $erro = 'Erro ao inserir anúncio: ' . $e->getMessage();
        }
    }
} else {
    $erro = "Método inválido.";
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processamento do Anúncio</title>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <header>
        <h1>Publicação de Anúncio</h1>
    </header>
    <section class="resultado">
        <?php if (isset($erro)): ?>
            <p style="color:red;"><?php echo $erro; ?></p>
        <?php else: ?>
            <p>Anúncio publicado com sucesso!</p>
        <?php endif; ?>
        <p><a href="dashboard.php">Voltar ao Dashboard</a></p>
    </section>
</body>

</html>