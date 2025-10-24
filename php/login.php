<?php
require __DIR__ . '/config.php';

require_method('POST');

// Accept both JSON and form-encoded bodies
$data = read_json_body();
if ($data === null) {
    $data = $_POST;
}

$email = trim($data['email'] ?? '');
$password = (string)($data['password'] ?? '');

if ($email === '' || $password === '') {
    json_response(['success' => false, 'error' => 'Email e senha são obrigatórios.'], 400);
}

try {
    $pdo = db();
    $stmt = $pdo->prepare('SELECT cliente_id, nome, email, senha, is_admin FROM cliente WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        json_response(['success' => false, 'error' => 'Credenciais inválidas.'], 401);
    }

    $stored = $user['senha'];
    $ok = false;
    if (is_password_hash($stored)) {
        $ok = password_verify($password, $stored);
    } else {
        // Fallback for legacy plaintext passwords (not recommended). Consider migrating to password_hash.
        $ok = hash_equals($stored, $password);
    }

    if (!$ok) {
        json_response(['success' => false, 'error' => 'Credenciais inválidas.'], 401);
    }

    // Auth success -> set session
    $_SESSION['user'] = [
        'id' => (int)$user['cliente_id'],
        'nome' => $user['nome'],
        'email' => $user['email'],
        'is_admin' => (bool)$user['is_admin'],
        'logged_in_at' => time(),
    ];

    json_response(['success' => true, 'user' => $_SESSION['user']]);
} catch (Throwable $e) {
    json_response([
        'success' => false,
        'error' => 'Erro ao processar login.',
        'details' => getenv('APP_DEBUG') ? $e->getMessage() : null,
    ], 500);
}
