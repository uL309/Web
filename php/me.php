<?php
require __DIR__ . '/config.php';

// Return current user session info or null if not logged in
$user = $_SESSION['user'] ?? null;

json_response([
    'success' => true,
    'logged_in' => $user !== null,
    'user' => $user
]);
