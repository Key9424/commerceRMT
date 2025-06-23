<?php 
  // Define o título da página para personalização do <title>
  $pageTitle = "FatecGamer RMT - Jogos";
  // Simulação de autenticação de admin. Em uma aplicação real, isso viria da sessão de usuário.
  $isAdmin = true;
  include 'header.php'; 
?>

<div class="container">
    <h1 class="mb-4">Jogos Disponíveis</h1>

    <!-- Painel de Admin (visível apenas para admin) -->
    <div id="adminPanel" class="admin-panel mb-4 <?php echo ($isAdmin) ? 'active' : 'd-none'; ?>">
        <h2>Adicionar Novo Jogo</h2>
        <input type="text" id="gameTitle" class="form-control mb-2" placeholder="Título do Jogo" />
        <textarea id="gameDesc" class="form-control mb-2" placeholder="Descrição do Jogo"></textarea>
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
    // Simulação de dados de jogos de exemplo.
    const games = [
        { title: "Futebol 2024", desc: "Simulação realista de partidas de futebol." },
        { title: "Corrida Extrema", desc: "Dispute corridas em pistas radicais." },
        { title: "Quebra-cabeça Lógico", desc: "Desafie sua mente com puzzles inteligentes." }
    ];

    function renderGames() {
        const grid = document.getElementById('gamesGrid');
        grid.innerHTML = '';
        games.forEach(game => {
            const card = document.createElement('div');
            // Utiliza as classes do Bootstrap para criar um card responsivo em uma coluna de 4 colunas (col-md-4)
            card.className = 'col-md-4 mb-3';
            card.innerHTML = `
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">${game.title}</h5>
                        <p class="card-text">${game.desc}</p>
                    </div>
                </div>
            `;
            grid.appendChild(card);
        });
    }

    function addGame() {
        const title = document.getElementById('gameTitle').value.trim();
        const desc = document.getElementById('gameDesc').value.trim();
        if (title && desc) {
            games.push({ title, desc });
            renderGames();
            document.getElementById('gameTitle').value = '';
            document.getElementById('gameDesc').value = '';
        }
    }

    // Renderiza os jogos de exemplo ao carregar a página.
    renderGames();
</script>
