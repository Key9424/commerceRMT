<?php
session_start();
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
require_once 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    if (!isset($_SESSION["user_id"])) {
        http_response_code(401);
        echo json_encode(["message" => "Não autorizado. Faça login para postar anúncios."]);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['jogo'], $data['tipo'], $data['descricao'], $data['preco'], $data['contato'])) {
        http_response_code(400);
        echo json_encode(['message' => 'Dados incompletos']);
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO anuncios (jogo, tipo, descricao, preco, contato, dataCriacao) VALUES (:jogo, :tipo, :descricao, :preco, :contato, NOW())");
        $stmt->execute([
            ':jogo'      => $data['jogo'],
            ':tipo'      => $data['tipo'],
            ':descricao' => $data['descricao'],
            ':preco'     => $data['preco'],
            ':contato'   => $data['contato']
        ]);
        http_response_code(201);
        echo json_encode(['message' => 'Anúncio criado com sucesso']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Erro ao inserir anúncio', 'error' => $e->getMessage()]);
    }
    exit;
// (Código de POST permanece o mesmo)

} elseif ($method === 'GET') {
    // Parâmetros para paginação
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = ($page - 1) * $limit;
    
    // Parâmetros para filtro e busca
    $filtroJogo = isset($_GET['jogo']) ? trim($_GET['jogo']) : '';
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $order = isset($_GET['order']) ? trim($_GET['order']) : '';
    
    // Monta as cláusulas WHERE dinamicamente
    $whereClauses = [];
    $params = [];
    
    if (!empty($filtroJogo)) {
        $whereClauses[] = "jogo = :jogo";
        $params[':jogo'] = $filtroJogo;
    }
    
    if (!empty($search)) {
        // Aplica busca na descrição ou no nome do jogo
        $whereClauses[] = "(descricao LIKE :search OR jogo LIKE :search)";
        $params[':search'] = "%$search%";
    }
    
    $whereQuery = count($whereClauses) > 0 ? "WHERE " . implode(" AND ", $whereClauses) : "";
    
    // Define a ordenação
    $orderBy = "dataCriacao DESC"; // padrão: data recente primeiro
    if ($order === "priceAsc") {
        $orderBy = "preco ASC";
    } elseif ($order === "priceDesc") {
        $orderBy = "preco DESC";
    }
    
    try {
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM anuncios $whereQuery ORDER BY $orderBy LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);
        
        // Bind dos parâmetros dos filtros
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $anuncios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Recupera o total de registros (sem os limites)
        $stmtTotal = $pdo->query("SELECT FOUND_ROWS() AS total");
        $row = $stmtTotal->fetch(PDO::FETCH_ASSOC);
        $total = $row['total'];
        
        $responseData = [
            'data'  => $anuncios,
            'total' => (int)$total,
            'page'  => $page,
            'limit' => $limit,
            'pages' => ceil($total / $limit)
        ];
        echo json_encode($responseData);
    
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Erro ao buscar anúncios', 'error' => $e->getMessage()]);
    }
    exit;
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Método não permitido']);
}
?>
