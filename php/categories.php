<?php
require __DIR__ . '/config.php';

require_method('GET');

$pdo = db();

// Get all categories with hierarchy
$stmt = $pdo->query("
    SELECT 
        categoria_id, nome, categoria_pai_id
    FROM categoria
    ORDER BY categoria_pai_id, nome
");
$categories = $stmt->fetchAll();

json_response([
    'success' => true,
    'categories' => $categories
]);
