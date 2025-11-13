<?php
require __DIR__ . '/config.php';

require_method('POST');

$data = read_json_body();
$token = trim($data['token'] ?? '');
$email = trim($data['email'] ?? '');
$nova_senha = $data['nova_senha'] ?? '';

if (empty($token) || empty($email) || strlen($nova_senha) < 6) {
    json_response(['success' => false, 'error' => 'Token, email e uma nova senha (mín. 6 caracteres) são obrigatórios.'], 400);
}

$pdo = db();

try {
    // 1. Buscar usuário pelo token, email e verificar se não expirou
    $stmt = $pdo->prepare("
        SELECT cliente_id 
        FROM cliente 
        WHERE email = ? 
          AND reset_token = ? 
          AND reset_expires > NOW()
        LIMIT 1
    ");
    $stmt->execute([$email, $token]);
    $user = $stmt->fetch();

    if (!$user) {
        json_response(['success' => false, 'error' => 'Token inválido ou expirado.'], 400);
    }

    $cliente_id = (int)$user['cliente_id'];

    // 2. Atualizar a senha e limpar os campos de reset
    $novo_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("
        UPDATE cliente 
        SET senha = ?, reset_token = NULL, reset_expires = NULL 
        WHERE cliente_id = ?
    ");
    $stmt->execute([$novo_hash, $cliente_id]);

    json_response(['success' => true, 'message' => 'Senha redefinida com sucesso.']);

} catch (Throwable $e) {
    json_response([
        'success' => false,
        'error' => 'Erro interno do servidor.',
        'details' => getenv('APP_DEBUG') ? $e->getMessage() : null
    ], 500);
}