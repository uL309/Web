<?php
require __DIR__ . '/config.php';

require_method('GET');

$user = $_SESSION['user'] ?? null;
if (!$user) {
    json_response(['success' => false, 'error' => 'Faça login para ver seus pedidos'], 401);
}

$pdo = db();
$cliente_id = $user['id'];

// Get specific order
if (isset($_GET['id'])) {
    $pedido_id = (int)$_GET['id'];
    
    // Get order
    $stmt = $pdo->prepare("
        SELECT 
            p.pedido_id, p.data_pedido, p.status, p.valor_total, p.frete,
            pg.tipo as forma_pagamento, pg.status as status_pagamento, pg.parcelas,
            e.data_envio, e.data_entrega, e.status_entrega, e.codigo_rastreamento, e.transportadora
        FROM pedido p
        LEFT JOIN pagamento pg ON p.pedido_id = pg.pedido_id
        LEFT JOIN entrega e ON p.pedido_id = e.pedido_id
        WHERE p.pedido_id = ? AND p.cliente_id = ?
        LIMIT 1
    ");
    $stmt->execute([$pedido_id, $cliente_id]);
    $order = $stmt->fetch();
    
    if (!$order) {
        json_response(['success' => false, 'error' => 'Pedido não encontrado'], 404);
    }
    
    // Get items
    $stmt = $pdo->prepare("
        SELECT 
            ip.quantidade, ip.preco_no_momento,
            pr.produto_id, pr.nome, pr.imagem
        FROM item_pedido ip
        JOIN produto pr ON ip.produto_id = pr.produto_id
        WHERE ip.pedido_id = ?
    ");
    $stmt->execute([$pedido_id]);
    $items = $stmt->fetchAll();
    
    json_response([
        'success' => true,
        'order' => $order,
        'items' => $items
    ]);
}

// List all orders
$stmt = $pdo->prepare("
    SELECT 
        p.pedido_id, p.data_pedido, p.status, p.valor_total,
        COUNT(ip.item_pedido_id) as total_items
    FROM pedido p
    LEFT JOIN item_pedido ip ON p.pedido_id = ip.pedido_id
    WHERE p.cliente_id = ?
    GROUP BY p.pedido_id
    ORDER BY p.data_pedido DESC
");
$stmt->execute([$cliente_id]);
$orders = $stmt->fetchAll();

json_response([
    'success' => true,
    'orders' => $orders
]);
