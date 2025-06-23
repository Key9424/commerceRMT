<?php
// dashboard.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require_once __DIR__ . '/../db.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM anuncios WHERE user_id = :user_id ORDER BY dataCriacao DESC");
$stmt->execute([':user_id' => $user_id]);
$anuncios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - FatecGamer RMT</title>
    <link rel="stylesheet" href="styles/style.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #ccc;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        .action-btn {
            padding: 4px 8px;
            text-decoration: none;
            background-color: #007BFF;
            color: #fff;
            border-radius: 4px;
            margin-right: 4px;
        }

        .action-btn.delete {
            background-color: #dc3545;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>
    <section>
        <h2>Meus Anúncios</h2>
        <?php if (count($anuncios) > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Jogo</th>
                    <th>Tipo</th>
                    <th>Preço</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
                <?php foreach ($anuncios as $anuncio): ?>
                    <tr>
                        <td><?php echo $anuncio['id']; ?></td>
                        <td><?php echo $anuncio['jogo']; ?></td>
                        <td><?php echo $anuncio['tipo']; ?></td>
                        <td>R$ <?php echo number_format($anuncio['preco'], 2, ',', '.'); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($anuncio['dataCriacao'])); ?></td>
                        <td>
                            <a class="action-btn" href="editarAnuncio.php?id=<?php echo $anuncio['id']; ?>">Editar</a>
                            <a class="action-btn delete" href="excluirAnuncio.php?id=<?php echo $anuncio['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este anúncio?');">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Você ainda não possui anúncios publicados.</p>
        <?php endif; ?>
    </section>
    <?php include 'footer.php'; ?>
</body>

</html>