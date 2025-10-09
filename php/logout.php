<?php
require __DIR__ . '/config.php';

require_method('POST');

// Clear session data
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
session_destroy();

json_response(['success' => true, 'message' => 'Logout realizado com sucesso.']);
