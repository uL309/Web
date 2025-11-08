<?php
require __DIR__ . '/config.php';

// Inicia sessão se ainda não foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se é admin
$user = $_SESSION['user'] ?? null;
if (!$user || !($user['is_admin'] ?? false)) {
    json_response(['success' => false, 'error' => 'Acesso negado. Apenas administradores.'], 403);
}

$pdo = db();

// CREATE - Adicionar novo produto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = read_json_body() ?? $_POST;
    
    // Log dos dados recebidos (apenas em desenvolvimento)
    error_log('POST Data: ' . json_encode($data));
    
    $nome = trim($data['nome'] ?? '');
    $categoria_id = (int)($data['categoria_id'] ?? 0);
    $fabricante = trim($data['fabricante'] ?? '');
    $descricao = trim($data['descricao'] ?? '');
    $especificacoes = trim($data['especificacoes'] ?? '');
    $sku = trim($data['sku'] ?? '');
    $preco = (float)($data['preco'] ?? 0);
    $estoque = (int)($data['estoque'] ?? 0);
    $fornecedor_id = (int)($data['fornecedor_id'] ?? 1);
    $imagem = trim($data['imagem'] ?? '');
    
    // Validações detalhadas
    $errors = [];
    if ($nome === '') $errors[] = 'Nome é obrigatório';
    if ($categoria_id <= 0) $errors[] = 'Categoria inválida';
    if ($sku === '') $errors[] = 'SKU é obrigatório';
    if ($preco <= 0) $errors[] = 'Preço deve ser maior que zero';
    if ($fabricante === '') $errors[] = 'Fabricante é obrigatório';
    
    if (!empty($errors)) {
        json_response([
            'success' => false, 
            'error' => 'Dados inválidos',
            'details' => $errors
        ], 400);
    }
    
    try {
        // Verifica se o SKU já existe
        $checkSku = $pdo->prepare("SELECT produto_id FROM produto WHERE sku = ?");
        $checkSku->execute([$sku]);
        if ($checkSku->fetch()) {
            json_response([
                'success' => false,
                'error' => 'SKU já existe. Use um código único.'
            ], 400);
        }
        
        // Verifica se a categoria existe
        $checkCat = $pdo->prepare("SELECT categoria_id FROM categoria WHERE categoria_id = ?");
        $checkCat->execute([$categoria_id]);
        if (!$checkCat->fetch()) {
            json_response([
                'success' => false,
                'error' => "Categoria ID {$categoria_id} não existe no banco de dados"
            ], 400);
        }
        
        $stmt = $pdo->prepare("
            INSERT INTO produto (nome, categoria_id, fabricante, descricao, especificacoes, 
                                preco, estoque, sku, imagem, fornecedor_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $result = $stmt->execute([
            $nome, $categoria_id, $fabricante, $descricao, $especificacoes, 
            $preco, $estoque, $sku, $imagem, $fornecedor_id
        ]);
        
        if (!$result) {
            json_response([
                'success' => false,
                'error' => 'Falha ao executar INSERT',
                'details' => $stmt->errorInfo()
            ], 500);
        }
        
        $produto_id = (int)$pdo->lastInsertId();
        
        json_response([
            'success' => true,
            'produto_id' => $produto_id,
            'message' => 'Produto criado com sucesso!'
        ]);
        
    } catch (PDOException $e) {
        error_log('Erro SQL: ' . $e->getMessage());
        
        // Detecta erro de chave duplicada
        if ($e->getCode() == 23000) {
            json_response([
                'success' => false,
                'error' => 'SKU duplicado. Use um código único.',
                'details' => $e->getMessage()
            ], 400);
        }
        
        json_response([
            'success' => false,
            'error' => 'Erro ao criar produto no banco de dados',
            'details' => $e->getMessage()
        ], 500);
        
    } catch (Throwable $e) {
        error_log('Erro geral: ' . $e->getMessage());
        json_response([
            'success' => false,
            'error' => 'Erro ao criar produto',
            'details' => $e->getMessage()
        ], 500);
    }
}

// UPDATE - Atualizar produto existente
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = read_json_body();
    
    $produto_id = (int)($data['produto_id'] ?? 0);
    $nome = trim($data['nome'] ?? '');
    $categoria_id = (int)($data['categoria_id'] ?? 0);
    $fabricante = trim($data['fabricante'] ?? '');
    $descricao = trim($data['descricao'] ?? '');
    $especificacoes = trim($data['especificacoes'] ?? '');
    $sku = trim($data['sku'] ?? '');
    $preco = (float)($data['preco'] ?? 0);
    $estoque = (int)($data['estoque'] ?? 0);
    $fornecedor_id = (int)($data['fornecedor_id'] ?? 1);
    $imagem = trim($data['imagem'] ?? '');
    
    if ($produto_id <= 0 || $nome === '' || $preco <= 0 || $categoria_id <= 0 || $sku === '') {
        json_response(['success' => false, 'error' => 'Dados inválidos'], 400);
    }
    
    try {
        $stmt = $pdo->prepare("
            UPDATE produto 
            SET nome = ?, categoria_id = ?, fabricante = ?, descricao = ?, 
                especificacoes = ?, preco = ?, estoque = ?, sku = ?, 
                imagem = ?, fornecedor_id = ?
            WHERE produto_id = ?
        ");
        $stmt->execute([$nome, $categoria_id, $fabricante, $descricao, $especificacoes, 
                       $preco, $estoque, $sku, $imagem, $fornecedor_id, $produto_id]);
        
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
