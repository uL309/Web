<?php
require __DIR__ . '/config.php';

// Verifica se é admin
$user = $_SESSION['user'] ?? null;
if (!$user || !($user['is_admin'] ?? false)) {
    json_response(['success' => false, 'error' => 'Acesso negado. Apenas administradores.'], 403);
}

$pdo = db();

// CREATE - Adicionar novo produto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = read_json_body() ?? $_POST;
    
    $nome = trim($data['nome'] ?? '');
    $categoria = trim($data['categoria'] ?? '');
    $descricao = trim($data['descricao'] ?? '');
    $especificacoes = trim($data['especificacoes'] ?? '');
    $preco = (float)($data['preco'] ?? 0);
    $estoque = (int)($data['estoque'] ?? 0);
    $imagem = trim($data['imagem'] ?? '');
    
    if ($nome === '' || $preco <= 0) {
        json_response(['success' => false, 'error' => 'Nome e preço são obrigatórios'], 400);
    }
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO produto (nome, categoria, descricao, especificacoes, preco, estoque, imagem)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$nome, $categoria, $descricao, $especificacoes, $preco, $estoque, $imagem]);
        
        $produto_id = (int)$pdo->lastInsertId();
        
        json_response([
            'success' => true,
            'produto_id' => $produto_id,
            'message' => 'Produto criado com sucesso!'
        ]);
        
    } catch (Throwable $e) {
        json_response([
            'success' => false,
            'error' => 'Erro ao criar produto',
            'details' => getenv('APP_DEBUG') ? $e->getMessage() : null
        ], 500);
    }
}

// UPDATE - Atualizar produto existente
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = read_json_body();
    
    $produto_id = (int)($data['produto_id'] ?? 0);
    $nome = trim($data['nome'] ?? '');
    $categoria = trim($data['categoria'] ?? '');
    $descricao = trim($data['descricao'] ?? '');
    $especificacoes = trim($data['especificacoes'] ?? '');
    $preco = (float)($data['preco'] ?? 0);
    $estoque = (int)($data['estoque'] ?? 0);
    $imagem = trim($data['imagem'] ?? '');
    
    if ($produto_id <= 0 || $nome === '' || $preco <= 0) {
        json_response(['success' => false, 'error' => 'Dados inválidos'], 400);
    }
    
    try {
        $stmt = $pdo->prepare("
            UPDATE produto 
            SET nome = ?, categoria = ?, descricao = ?, especificacoes = ?, 
                preco = ?, estoque = ?, imagem = ?
            WHERE produto_id = ?
        ");
        $stmt->execute([$nome, $categoria, $descricao, $especificacoes, $preco, $estoque, $imagem, $produto_id]);
        
        json_response([
            'success' => true,
            'message' => 'Produto atualizado com sucesso!'
        ]);
        
    } catch (Throwable $e) {
        json_response([
            'success' => false,
            'error' => 'Erro ao atualizar produto',
            'details' => getenv('APP_DEBUG') ? $e->getMessage() : null
        ], 500);
    }
}

// DELETE - Remover produto
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = read_json_body();
    $produto_id = (int)($data['produto_id'] ?? 0);
    
    if ($produto_id <= 0) {
        json_response(['success' => false, 'error' => 'ID do produto inválido'], 400);
    }
    
    try {
        $stmt = $pdo->prepare("DELETE FROM produto WHERE produto_id = ?");
        $stmt->execute([$produto_id]);
        
        json_response([
            'success' => true,
            'message' => 'Produto removido com sucesso!'
        ]);
        
    } catch (Throwable $e) {
        json_response([
            'success' => false,
            'error' => 'Erro ao remover produto',
            'details' => getenv('APP_DEBUG') ? $e->getMessage() : null
        ], 500);
    }
}

json_response(['success' => false, 'error' => 'Método não permitido'], 405);
