<?php
// Test database connection
header('Content-Type: application/json');

$host = '127.0.0.1';
$port = '3306';
$user = 'root';
$pass = '';

echo json_encode([
    'testing_connection' => true,
    'host' => $host,
    'port' => $port,
    'user' => $user
]);

try {
    $pdo = new PDO("mysql:host={$host};port={$port};charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "\n" . json_encode(['connection' => 'success', 'message' => 'Connected to MySQL']);
    
    // List databases
    $stmt = $pdo->query("SHOW DATABASES");
    $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "\n" . json_encode(['databases' => $databases]);
    
    // Check if loja_hardware exists
    $hasLojaHardware = in_array('loja_hardware', $databases);
    echo "\n" . json_encode(['loja_hardware_exists' => $hasLojaHardware]);
    
} catch (Exception $e) {
    echo "\n" . json_encode(['error' => $e->getMessage()]);
}
