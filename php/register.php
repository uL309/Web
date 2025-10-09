<?php
require __DIR__ . '/config.php';

require_method('POST');

$body = read_json_body();
if ($body === null) {
    $body = $_POST;
}

// Collect and trim inputs
$nome = trim((string)($body['nome'] ?? ''));
$cpf = preg_replace('/\D+/', '', (string)($body['cpf'] ?? ''));
$data_nascimento = trim((string)($body['data_nascimento'] ?? ''));
$telefone = preg_replace('/\D+/', '', (string)($body['telefone'] ?? ''));
$endereco = trim((string)($body['endereco'] ?? ''));
$email = trim((string)($body['email'] ?? ''));
$senha = (string)($body['senha'] ?? '');
$confirmar = (string)($body['confirmar_senha'] ?? '');

// Basic validations
$errors = [];
if ($nome === '') $errors['nome'] = 'Informe o nome.';
if ($cpf === '' || strlen($cpf) !== 11) $errors['cpf'] = 'CPF inválido.';
if ($data_nascimento === '') $errors['data_nascimento'] = 'Informe a data de nascimento.';
if ($endereco === '') $errors['endereco'] = 'Informe o endereço.';
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Email inválido.';
if ($senha === '' || strlen($senha) < 6) $errors['senha'] = 'A senha deve ter ao menos 6 caracteres.';
if ($senha !== $confirmar) $errors['confirmar_senha'] = 'As senhas não coincidem.';

if ($errors) {
    json_response(['success' => false, 'errors' => $errors], 422);
}

try {
    $pdo = db();

    // Uniqueness checks
    $stmt = $pdo->prepare('SELECT 1 FROM cliente WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        json_response(['success' => false, 'errors' => ['email' => 'Email já cadastrado.']], 409);
    }

    $stmt = $pdo->prepare('SELECT 1 FROM cliente WHERE cpf = ? LIMIT 1');
    $stmt->execute([$cpf]);
    if ($stmt->fetch()) {
        json_response(['success' => false, 'errors' => ['cpf' => 'CPF já cadastrado.']], 409);
    }

    // Hash password
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Insert
    $stmt = $pdo->prepare('INSERT INTO cliente (nome, cpf, endereco, telefone, email, senha, data_nascimento) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$nome, $cpf, $endereco, $telefone ?: null, $email, $senhaHash, $data_nascimento]);

    $id = (int)$pdo->lastInsertId();

    // Start session and return user info
    $_SESSION['user'] = [
        'id' => $id,
        'nome' => $nome,
        'email' => $email,
        'logged_in_at' => time(),
    ];

    json_response(['success' => true, 'user' => $_SESSION['user']]);
} catch (Throwable $e) {
    // Handle unique violations gracefully if DB constraints fire
    $msg = $e->getMessage();
    if (stripos($msg, 'Duplicate') !== false || stripos($msg, 'UNIQUE') !== false) {
        json_response(['success' => false, 'error' => 'Email ou CPF já cadastrado.'], 409);
    }

    json_response([
        'success' => false,
        'error' => 'Erro ao cadastrar cliente.',
        'details' => getenv('APP_DEBUG') ? $msg : null,
    ], 500);
}
