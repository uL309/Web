<?php
require __DIR__ . '/config.php';

// Alias para me.php - retorna informações do usuário logado
$user = $_SESSION['user'] ?? null;

json_response([
    'success' => true,
    'logged_in' => $user !== null,
    'user' => $user
]);
