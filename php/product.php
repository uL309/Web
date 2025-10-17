<?php
require __DIR__ . '/config.php';

require_method('GET');

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    json_response(['success' => false, 'error' => 'ID inválido'], 400);
}

$pdo = db();

// Get product details
$stmt = $pdo->prepare("
    SELECT 
        p.produto_id, p.nome, p.descricao, p.especificacoes, p.preco, 
        p.estoque, p.imagem, p.fabricante, p.sku,
        c.nome as categoria_nome,
        f.nome as fornecedor_nome
    FROM produto p
    LEFT JOIN categoria c ON p.categoria_id = c.categoria_id
    LEFT JOIN fornecedor f ON p.fornecedor_id = f.fornecedor_id
    WHERE p.produto_id = ?
    LIMIT 1
");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    json_response(['success' => false, 'error' => 'Produto não encontrado'], 404);
}

// Get reviews
$stmt = $pdo->prepare("
    SELECT 
        a.avaliacao_id, a.nota, a.comentario, a.data,
        c.nome as cliente_nome
    FROM avaliacao a
    JOIN cliente c ON a.cliente_id = c.cliente_id
    WHERE a.produto_id = ?
    ORDER BY a.data DESC
    LIMIT 50
");
$stmt->execute([$id]);
$reviews = $stmt->fetchAll();

// Calculate average rating
$avgStmt = $pdo->prepare("SELECT AVG(nota) as avg_rating, COUNT(*) as review_count FROM avaliacao WHERE produto_id = ?");
$avgStmt->execute([$id]);
$rating = $avgStmt->fetch();

json_response([
    'success' => true,
    'product' => $product,
    'reviews' => $reviews,
    'rating' => [
        'average' => $rating['avg_rating'] ? round((float)$rating['avg_rating'], 1) : 0,
        'count' => (int)$rating['review_count']
    ]
]);
