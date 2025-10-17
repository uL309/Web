<?php
require __DIR__ . '/config.php';

$pdo = db();

// Helper: Get or create cart for current user/session
function get_or_create_cart(PDO $pdo): int {
    $user = $_SESSION['user'] ?? null;
    $cliente_id = $user ? $user['id'] : null;
    
    // If logged in, find cart by cliente_id
    if ($cliente_id) {
        $stmt = $pdo->prepare('SELECT carrinho_id FROM carrinho WHERE cliente_id = ? LIMIT 1');
        $stmt->execute([$cliente_id]);
        $cart = $stmt->fetch();
        if ($cart) return (int)$cart['carrinho_id'];
        
        // Create new cart for user
        $stmt = $pdo->prepare('INSERT INTO carrinho (cliente_id, data_criacao) VALUES (?, NOW())');
        $stmt->execute([$cliente_id]);
        return (int)$pdo->lastInsertId();
    }
    
    // Guest cart - use session
    if (isset($_SESSION['cart_id'])) {
        // Verify it exists
        $stmt = $pdo->prepare('SELECT carrinho_id FROM carrinho WHERE carrinho_id = ? AND cliente_id IS NULL LIMIT 1');
        $stmt->execute([$_SESSION['cart_id']]);
        if ($stmt->fetch()) return (int)$_SESSION['cart_id'];
    }
    
    // Create guest cart
    $stmt = $pdo->prepare('INSERT INTO carrinho (cliente_id, data_criacao) VALUES (NULL, NOW())');
    $stmt->execute();
    $cart_id = (int)$pdo->lastInsertId();
    $_SESSION['cart_id'] = $cart_id;
    return $cart_id;
}

// GET: List cart items
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $cart_id = get_or_create_cart($pdo);
    
    $stmt = $pdo->prepare("
        SELECT 
            ic.item_carrinho_id, ic.quantidade,
            p.produto_id, p.nome, p.preco, p.estoque, p.imagem
        FROM item_carrinho ic
        JOIN produto p ON ic.produto_id = p.produto_id
        WHERE ic.carrinho_id = ?
    ");
    $stmt->execute([$cart_id]);
    $items = $stmt->fetchAll();
    
    $total = array_reduce($items, fn($sum, $item) => $sum + ($item['preco'] * $item['quantidade']), 0);
    
    json_response([
        'success' => true,
        'cart_id' => $cart_id,
        'items' => $items,
        'total' => $total
    ]);
}

// POST: Add item to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = read_json_body() ?? $_POST;
    $produto_id = (int)($data['produto_id'] ?? 0);
    $quantidade = max(1, (int)($data['quantidade'] ?? 1));
    
    if ($produto_id <= 0) {
        json_response(['success' => false, 'error' => 'Produto inválido'], 400);
    }
    
    // Verify product exists and has stock
    $stmt = $pdo->prepare('SELECT estoque FROM produto WHERE produto_id = ?');
    $stmt->execute([$produto_id]);
    $product = $stmt->fetch();
    if (!$product) {
        json_response(['success' => false, 'error' => 'Produto não encontrado'], 404);
    }
    if ($product['estoque'] < $quantidade) {
        json_response(['success' => false, 'error' => 'Estoque insuficiente'], 400);
    }
    
    $cart_id = get_or_create_cart($pdo);
    
    // Check if already in cart
    $stmt = $pdo->prepare('SELECT item_carrinho_id, quantidade FROM item_carrinho WHERE carrinho_id = ? AND produto_id = ?');
    $stmt->execute([$cart_id, $produto_id]);
    $existing = $stmt->fetch();
    
    if ($existing) {
        // Update quantity
        $newQty = $existing['quantidade'] + $quantidade;
        if ($newQty > $product['estoque']) {
            json_response(['success' => false, 'error' => 'Estoque insuficiente'], 400);
        }
        $stmt = $pdo->prepare('UPDATE item_carrinho SET quantidade = ? WHERE item_carrinho_id = ?');
        $stmt->execute([$newQty, $existing['item_carrinho_id']]);
    } else {
        // Insert new item
        $stmt = $pdo->prepare('INSERT INTO item_carrinho (carrinho_id, produto_id, quantidade) VALUES (?, ?, ?)');
        $stmt->execute([$cart_id, $produto_id, $quantidade]);
    }
    
    json_response(['success' => true, 'message' => 'Produto adicionado ao carrinho']);
}

// PUT: Update item quantity
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = read_json_body() ?? [];
    $item_id = (int)($data['item_id'] ?? 0);
    $quantidade = max(1, (int)($data['quantidade'] ?? 1));
    
    $cart_id = get_or_create_cart($pdo);
    
    // Verify item belongs to cart and check stock
    $stmt = $pdo->prepare("
        SELECT ic.item_carrinho_id, p.estoque 
        FROM item_carrinho ic
        JOIN produto p ON ic.produto_id = p.produto_id
        WHERE ic.item_carrinho_id = ? AND ic.carrinho_id = ?
    ");
    $stmt->execute([$item_id, $cart_id]);
    $item = $stmt->fetch();
    
    if (!$item) {
        json_response(['success' => false, 'error' => 'Item não encontrado'], 404);
    }
    if ($quantidade > $item['estoque']) {
        json_response(['success' => false, 'error' => 'Estoque insuficiente'], 400);
    }
    
    $stmt = $pdo->prepare('UPDATE item_carrinho SET quantidade = ? WHERE item_carrinho_id = ?');
    $stmt->execute([$quantidade, $item_id]);
    
    json_response(['success' => true, 'message' => 'Quantidade atualizada']);
}

// DELETE: Remove item from cart
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = read_json_body() ?? [];
    $item_id = (int)($data['item_id'] ?? $_GET['item_id'] ?? 0);
    
    $cart_id = get_or_create_cart($pdo);
    
    $stmt = $pdo->prepare('DELETE FROM item_carrinho WHERE item_carrinho_id = ? AND carrinho_id = ?');
    $stmt->execute([$item_id, $cart_id]);
    
    json_response(['success' => true, 'message' => 'Item removido']);
}

json_response(['success' => false, 'error' => 'Método não suportado'], 405);
