<?php
require __DIR__ . '/config.php';

$pdo = db();

// GET: List reviews for a product
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $produto_id = (int)($_GET['produto_id'] ?? 0);
    if ($produto_id <= 0) {
        json_response(['success' => false, 'error' => 'Produto inválido'], 400);
    }
    
    $stmt = $pdo->prepare("
        SELECT 
            a.avaliacao_id, a.nota, a.comentario, a.data,
            c.nome as cliente_nome
        FROM avaliacao a
        JOIN cliente c ON a.cliente_id = c.cliente_id
        WHERE a.produto_id = ?
        ORDER BY a.data DESC
    ");
    $stmt->execute([$produto_id]);
    $reviews = $stmt->fetchAll();
    
    json_response(['success' => true, 'reviews' => $reviews]);
}

// POST: Add review
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_SESSION['user'] ?? null;
    if (!$user) {
        json_response(['success' => false, 'error' => 'Faça login para avaliar'], 401);
    }
    
    $data = read_json_body() ?? $_POST;
    $produto_id = (int)($data['produto_id'] ?? 0);
    $nota = (int)($data['nota'] ?? 0);
    $comentario = trim($data['comentario'] ?? '');
    
    if ($produto_id <= 0 || $nota < 1 || $nota > 5) {
        json_response(['success' => false, 'error' => 'Dados inválidos'], 400);
    }
    
    // Check if user bought this product
    $stmt = $pdo->prepare("
        SELECT 1 FROM pedido p
        JOIN item_pedido ip ON p.pedido_id = ip.pedido_id
        WHERE p.cliente_id = ? AND ip.produto_id = ?
        LIMIT 1
    ");
    $stmt->execute([$user['id'], $produto_id]);
    if (!$stmt->fetch()) {
        json_response(['success' => false, 'error' => 'Você precisa ter comprado este produto para avaliá-lo'], 403);
    }
    
    // Check if already reviewed
    $stmt = $pdo->prepare("SELECT 1 FROM avaliacao WHERE cliente_id = ? AND produto_id = ? LIMIT 1");
    $stmt->execute([$user['id'], $produto_id]);
    if ($stmt->fetch()) {
        json_response(['success' => false, 'error' => 'Você já avaliou este produto'], 409);
    }
    
    // Insert review
    $stmt = $pdo->prepare("
        INSERT INTO avaliacao (cliente_id, produto_id, nota, comentario, data)
        VALUES (?, ?, ?, ?, NOW())
    ");
    $stmt->execute([$user['id'], $produto_id, $nota, $comentario]);
    
    json_response(['success' => true, 'message' => 'Avaliação adicionada com sucesso']);
}

json_response(['success' => false, 'error' => 'Método não suportado'], 405);
