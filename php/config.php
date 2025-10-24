<?php
// Basic PHP configuration and database connection for the CiberTech site

// Enable strict errors during development
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Allow same-origin only; adjust if you host frontend elsewhere
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Start session (only once)
if (session_status() === PHP_SESSION_NONE) {
    // You can tune cookie params here (secure, httponly). For local dev over HTTP keep secure=false.
    session_set_cookie_params([
        'lifetime' => 0,           // session cookie
        'path' => '/',
        'domain' => '',
        'secure' => false,         // set true when using HTTPS
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

/**
 * Returns a shared PDO instance connected to MySQL.
 */
function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $name = getenv('DB_NAME') ?: 'loja_hardware';
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASS') ?: 'mlucas65';
    $port = getenv('DB_PORT') ?: '3306';

    $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (Throwable $e) {
        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'error' => 'Falha ao conectar no banco de dados. Verifique as credenciais e se o MySQL está em execução.',
            'details' => getenv('APP_DEBUG') ? $e->getMessage() : null,
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    return $pdo;
}

/** Send a JSON response with proper headers and status code */
function json_response(array $payload, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

/** Ensure request is a specific HTTP method */
function require_method(string $method): void {
    if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') !== strtoupper($method)) {
        json_response(['success' => false, 'error' => 'Método não permitido'], 405);
    }
}

/** Read JSON body (returns array or null if not JSON) */
function read_json_body(): ?array {
    $ct = $_SERVER['CONTENT_TYPE'] ?? $_SERVER['HTTP_CONTENT_TYPE'] ?? '';
    if (stripos($ct, 'application/json') === false) {
        return null;
    }
    $raw = file_get_contents('php://input');
    if ($raw === false || $raw === '') return [];
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

/** Utility to check if a stored password is a known hash format */
function is_password_hash(string $hash): bool {
    // Common PHP password_hash formats: bcrypt ($2y$, $2a$, $2b$) and argon2 variants
    return preg_match('/^\$(2y|2a|2b)\$|^\$argon2(id|i)\$/', $hash) === 1;
}
