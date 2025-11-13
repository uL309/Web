<?php
require __DIR__ . '/config.php';

// Este endpoint lida com atualizações de dados do usuário logado.
// Requer autenticação.
require_method('PUT');

$user = $_SESSION['user'] ?? null;
if (!$user) {
    json_response(['success' => false, 'error' => 'Acesso negado. Faça login.'], 401);
}

$data = read_json_body();
if ($data === null) {
    json_response(['success' => false, 'error' => 'Requisição JSON inválida.'], 400);
}

$action = $data['action'] ?? '';
$cliente_id = (int)$user['id'];
$pdo = db();

try {
    if ($action === 'update_info') {
        // Atualizar informações pessoais
        $nome = trim($data['nome'] ?? '');
        $telefone = preg_replace('/\D+/', '', (string)($data['telefone'] ?? ''));
        $endereco = trim($data['endereco'] ?? '');

        if (empty($nome) || empty($endereco)) {
            json_response(['success' => false, 'error' => 'Nome e Endereço são obrigatórios.'], 400);
        }

        $stmt = $pdo->prepare("UPDATE cliente SET nome = ?, telefone = ?, endereco = ? WHERE cliente_id = ?");
        $stmt->execute([$nome, $telefone ?: null, $endereco, $cliente_id]);

        // Atualiza a sessão
        $_SESSION['user']['nome'] = $nome;

        json_response(['success' => true, 'message' => 'Informações atualizadas.']);

    } elseif ($action === 'update_password') {
        // Atualizar senha
        $senha_antiga = $data['senha_antiga'] ?? '';
        $nova_senha = $data['nova_senha'] ?? '';

        if (empty($senha_antiga) || strlen($nova_senha) < 6) {
            json_response(['success' => false, 'error' => 'Senha antiga é obrigatória e a nova senha deve ter pelo menos 6 caracteres.'], 400);
        }

        // 1. Buscar hash da senha antiga
        $stmt = $pdo->prepare("SELECT senha FROM cliente WHERE cliente_id = ?");
        $stmt->execute([$cliente_id]);
        $hash_atual = $stmt->fetchColumn();

        if (!$hash_atual) {
             json_response(['success' => false, 'error' => 'Usuário não encontrado.'], 404);
        }

        // 2. Verificar senha antiga
        if (!password_verify($senha_antiga, $hash_atual)) {
            json_response(['success' => false, 'error' => 'Senha antiga incorreta.'], 403);
        }

        // 3. Atualizar para nova senha
        $novo_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE cliente SET senha = ? WHERE cliente_id = ?");
        $stmt->execute([$novo_hash, $cliente_id]);

        json_response(['success' => true, 'message' => 'Senha alterada com sucesso.']);

    } else {
        json_response(['success' => false, 'error' => 'Ação inválida.'], 400);
    }

} catch (Throwable $e) {
    json_response([
        'success' => false,
        'error' => 'Erro interno do servidor.',
        'details' => getenv('APP_DEBUG') ? $e->getMessage() : null
    ], 500);
}