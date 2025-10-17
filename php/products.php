<?php
require __DIR__ . '/config.php';

require_method('GET');

$pdo = db();

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = min(50, max(1, (int)($_GET['limit'] ?? 12)));
$offset = ($page - 1) * $limit;

// Filters
$categoria_id = isset($_GET['categoria']) ? (int)$_GET['categoria'] : null;
$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$min_price = isset($_GET['min_price']) ? (float)$_GET['min_price'] : null;
$max_price = isset($_GET['max_price']) ? (float)$_GET['max_price'] : null;
$order_by = $_GET['order'] ?? 'nome';
$allowed_order = ['nome', 'preco', 'produto_id'];
if (!in_array($order_by, $allowed_order)) $order_by = 'nome';
$direction = strtoupper($_GET['dir'] ?? 'ASC') === 'DESC' ? 'DESC' : 'ASC';

// Build query
$where = ['1=1'];
$params = [];

if ($categoria_id) {
    $where[] = 'p.categoria_id = ?';
    $params[] = $categoria_id;
}

if ($search !== '') {
    $where[] = '(p.nome LIKE ? OR p.descricao LIKE ? OR p.fabricante LIKE ?)';
    $searchTerm = '%' . $search . '%';
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

if ($min_price !== null) {
    $where[] = 'p.preco >= ?';
    $params[] = $min_price;
}

if ($max_price !== null) {
    $where[] = 'p.preco <= ?';
    $params[] = $max_price;
}

$whereClause = implode(' AND ', $where);

// Count total
$countSql = "SELECT COUNT(*) as total FROM produto p WHERE $whereClause";
$stmt = $pdo->prepare($countSql);
$stmt->execute($params);
$total = (int)$stmt->fetchColumn();

// Get products
$sql = "SELECT 
    p.produto_id, p.nome, p.descricao, p.preco, p.estoque, p.imagem, p.fabricante,
    c.nome as categoria_nome
FROM produto p
LEFT JOIN categoria c ON p.categoria_id = c.categoria_id
WHERE $whereClause
ORDER BY p.{$order_by} {$direction}
LIMIT {$limit} OFFSET {$offset}";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

json_response([
    'success' => true,
    'products' => $products,
    'pagination' => [
        'page' => $page,
        'limit' => $limit,
        'total' => $total,
        'total_pages' => ceil($total / $limit)
    ]
]);
