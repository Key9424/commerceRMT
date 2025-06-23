<?php
require_once __DIR__ . '/../db.php';
// Define o título da página para personalização do <title>
$pageTitle = "FatecGamer RMT - Jogos";
// Simulação de autenticação de admin. Em uma aplicação real, isso viria da sessão de usuário.
$isAdmin = true;
include 'header.php';

// Busca jogos do banco
$stmt = $pdo->query("SELECT * FROM jogos ORDER BY id DESC");
$jogos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="formAnuncio container mt-5">
    <h1 class="mb-4">Jogos Disponíveis</h1>

    <!-- Painel de Admin (visível apenas para admin) -->
    <div id="adminPanel" class="admin-panel mb-4 <?php echo ($isAdmin) ? 'active' : 'd-none'; ?>">
        <h2>Adicionar Novo Jogo</h2>
        <input type="text" id="gameTitle" class="form-control mb-2" placeholder="Título do Jogo" />
        <textarea id="gameDesc" class="form-control mb-2" placeholder="Descrição do Jogo"></textarea>
        <input type="file" id="gameImg" class="form-control mb-2" accept="image/*" />
        <button class="btn btn-primary" onclick="addGame()">Adicionar Jogo</button>
    </div>

    <!-- Grade de Jogos -->
    <div id="gamesGrid" class="row">
        <!-- Os jogos serão inseridos aqui via JavaScript -->
    </div>
</div>

<?php include 'footer.php'; ?>

<!-- Scripts personalizados -->
<script>
    const games = <?php echo json_encode($jogos); ?>.map(j => ({
        title: j.titulo,
        desc: j.descricao,
        img: j.imagem
    }));

    function renderGames() {
        const grid = document.getElementById('gamesGrid');
        grid.innerHTML = '';
        games.forEach((game, index) => {
            const imgTag = game.img ? `<img src="../public/uploads/${game.img}" class="card-img-top" alt="${game.title}">` : '';
            const card = document.createElement('div');
            card.className = 'col-md-4 mb-3';
            card.innerHTML = `
                <div class="card h-100 shadow-sm">
                    ${imgTag}
                    <div class="card-body">
                        <h5 class="card-title">${game.title}</h5>
                        <p class="card-text">${game.desc}</p>
                        <button class="btn btn-danger btn-sm" onclick="deleteGame(${index})">Excluir</button>
                    </div>
                </div>
            `;
            grid.appendChild(card);
        });
    }

    function addGame() {
        const title = document.getElementById('gameTitle').value.trim();
        const desc = document.getElementById('gameDesc').value.trim();
        const imgInput = document.getElementById('gameImg');
        const imgFile = imgInput.files[0];

        if (title && desc && imgFile) {
            const formData = new FormData();
            formData.append('title', title);
            formData.append('desc', desc);
            formData.append('img', imgFile);

            fetch('add_jogo.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        games.unshift({
                            title,
                            desc,
                            img: data.img // retorna o nome do arquivo salvo
                        });
                        renderGames();
                        document.getElementById('gameTitle').value = '';
                        document.getElementById('gameDesc').value = '';
                        imgInput.value = '';
                    } else {
                        alert('Erro ao adicionar jogo!');
                    }
                });
        } else {
            alert('Preencha todos os campos e selecione uma imagem!');
        }
    }

    function deleteGame(index) {
        games.splice(index, 1);
        renderGames();
    }

    // Renderiza os jogos de exemplo ao carregar a página.
    renderGames();
</script>