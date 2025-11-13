<?php
require __DIR__ . '/config.php';

require_method('POST');

$data = read_json_body();
$email = trim($data['email'] ?? '');

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Silenciosamente tem sucesso, para não revelar emails cadastrados
    json_response(['success' => true]);
}

$pdo = db();

try {
    // 1. Verificar se o email existe
    $stmt = $pdo->prepare("SELECT cliente_id FROM cliente WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // 2. Gerar token seguro
        $token = bin2hex(random_bytes(32));
        
        // 3. Definir expiração (ex: 1 hora)
        $expires = date('Y-m-d H:i:s', time() + 3600); // 1 hora
        
        // 4. Salvar token no banco
        $stmt = $pdo->prepare("UPDATE cliente SET reset_token = ?, reset_expires = ? WHERE cliente_id = ?");
        $stmt->execute([$token, $expires, $user['cliente_id']]);

        // 5. Enviar email (SIMULAÇÃO)
        // Em um app real, você usaria uma biblioteca como PHPMailer:
        // $link = "https://seusite.com/resetar-senha.html?token=$token&email=" . urlencode($email);
        // send_email($email, "Recupere sua senha", "Clique aqui: $link");
        
        // **NÃO FAÇA ISSO EM PRODUÇÃO!**
        // Retornando o token apenas para fins de demonstração,
        // já que não podemos enviar emails.
        json_response(['success' => true, 'token_demo' => $token]);
    } else {
        // Email não encontrado, mas retornamos sucesso para segurança.
        json_response(['success' => true]);
    }
} catch (Throwable $e) {
    // Loga o erro, mas não informa o usuário
    error_log("Erro em solicitar_reset.php: " . $e->getMessage());
    json_response(['success' => true]); // Resposta genérica
}