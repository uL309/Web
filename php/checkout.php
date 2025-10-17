<?php
require __DIR__ . '/config.php';

require_method('POST');

$user = $_SESSION['user'] ?? null;
if (!$user) {
    json_response(['success' => false, 'error' => 'Faça login para finalizar a compra'], 401);
}

$data = read_json_body() ?? $_POST;
$frete = max(0, (float)($data['frete'] ?? 0));
$forma_pagamento = trim($data['forma_pagamento'] ?? '');
$parcelas = max(1, (int)($data['parcelas'] ?? 1));

if (!in_array($forma_pagamento, ['credito', 'debito', 'pix', 'boleto'])) {
    json_response(['success' => false, 'error' => 'Forma de pagamento inválida'], 400);
}

$pdo = db();
$cliente_id = $user['id'];

// Get cart
$stmt = $pdo->prepare('SELECT carrinho_id FROM carrinho WHERE cliente_id = ? LIMIT 1');
$stmt->execute([$cliente_id]);
$cart = $stmt->fetch();

if (!$cart) {
    json_response(['success' => false, 'error' => 'Carrinho vazio'], 400);
}

$cart_id = $cart['carrinho_id'];

// Get cart items
$stmt = $pdo->prepare("
    SELECT ic.produto_id, ic.quantidade, p.preco, p.estoque, p.nome
    FROM item_carrinho ic
    JOIN produto p ON ic.produto_id = p.produto_id
    WHERE ic.carrinho_id = ?
");
$stmt->execute([$cart_id]);
$items = $stmt->fetchAll();

if (!$items) {
    json_response(['success' => false, 'error' => 'Carrinho vazio'], 400);
}

// Validate stock
foreach ($items as $item) {
    if ($item['quantidade'] > $item['estoque']) {
        json_response(['success' => false, 'error' => "Estoque insuficiente para {$item['nome']}"], 400);
    }
}

// Calculate total
$subtotal = array_reduce($items, fn($sum, $i) => $sum + ($i['preco'] * $i['quantidade']), 0);
$valor_total = $subtotal + $frete;

try {
    $pdo->beginTransaction();
    
    // Create order
    $stmt = $pdo->prepare("
        INSERT INTO pedido (cliente_id, data_pedido, status, valor_total, frete)
        VALUES (?, NOW(), 'Aguardando Pagamento', ?, ?)
    ");
    $stmt->execute([$cliente_id, $valor_total, $frete]);
    $pedido_id = (int)$pdo->lastInsertId();
    
    // Insert order items and update stock
    $stmtItem = $pdo->prepare("
        INSERT INTO item_pedido (pedido_id, produto_id, quantidade, preco_no_momento)
        VALUES (?, ?, ?, ?)
    ");
    $stmtStock = $pdo->prepare("UPDATE produto SET estoque = estoque - ? WHERE produto_id = ?");
    
    foreach ($items as $item) {
        $stmtItem->execute([$pedido_id, $item['produto_id'], $item['quantidade'], $item['preco']]);
        $stmtStock->execute([$item['quantidade'], $item['produto_id']]);
    }
    
    // Create payment record
    $stmt = $pdo->prepare("
        INSERT INTO pagamento (pedido_id, tipo, valor, data_pagamento, status, parcelas)
        VALUES (?, ?, ?, NOW(), 'Pendente', ?)
    ");
    $stmt->execute([$pedido_id, $forma_pagamento, $valor_total, $parcelas]);
    $pagamento_id = (int)$pdo->lastInsertId();
    
    // Create delivery record
    $codigo_rastreamento = 'BR' . str_pad($pedido_id, 10, '0', STR_PAD_LEFT) . strtoupper(substr(md5(uniqid()), 0, 4));
    $stmt = $pdo->prepare("
        INSERT INTO entrega (pedido_id, status_entrega, codigo_rastreamento, transportadora)
        VALUES (?, 'Aguardando Processamento', ?, 'Correios')
    ");
    $stmt->execute([$pedido_id, $codigo_rastreamento]);
    
    // Clear cart
    $pdo->prepare('DELETE FROM item_carrinho WHERE carrinho_id = ?')->execute([$cart_id]);
    
    $pdo->commit();
    
    json_response([
        'success' => true,
        'pedido_id' => $pedido_id,
        'pagamento_id' => $pagamento_id,
        'codigo_rastreamento' => $codigo_rastreamento,
        'message' => 'Pedido realizado com sucesso!'
    ]);
    
} catch (Throwable $e) {
    $pdo->rollBack();
    json_response([
        'success' => false,
        'error' => 'Erro ao processar pedido',
        'details' => getenv('APP_DEBUG') ? $e->getMessage() : null
    ], 500);
}
