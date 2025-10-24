<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Admin - CiberTech</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #0a0f1e;
            color: #e6eef8;
        }
        .success { color: #10b981; margin: 20px 0; padding: 15px; background: #064e3b; border-radius: 8px; }
        .error { color: #ef4444; margin: 20px 0; padding: 15px; background: #7f1d1d; border-radius: 8px; }
        .info { color: #60a5fa; margin: 20px 0; padding: 15px; background: #1e3a8a; border-radius: 8px; }
        h1 { color: #60a5fa; }
        button {
            background: #60a5fa;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            margin: 10px 5px;
        }
        button:hover { background: #3b82f6; }
        input {
            padding: 10px;
            margin: 10px 0;
            width: 100%;
            background: #0b1020;
            border: 1px solid #334155;
            border-radius: 6px;
            color: #e6eef8;
        }
        label { display: block; margin: 10px 0 5px 0; font-weight: 600; }
    </style>
</head>
<body>
    <h1>🔧 Setup de Administrador</h1>
    
    <div class="info">
        <strong>Instruções:</strong>
        <ol>
            <li>Execute o SQL abaixo no phpMyAdmin ou MySQL Workbench</li>
            <li>Ou use o formulário abaixo para executar automaticamente</li>
            <li>Depois defina um usuário como admin</li>
        </ol>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require __DIR__ . '/php/config.php';
        
        $action = $_POST['action'] ?? '';
        $pdo = db();
        
        try {
            if ($action === 'add_column') {
                // Verifica se a coluna já existe
                $stmt = $pdo->query("SHOW COLUMNS FROM cliente LIKE 'is_admin'");
                if ($stmt->rowCount() > 0) {
                    echo '<div class="info">✓ A coluna is_admin já existe!</div>';
                } else {
                    $pdo->exec("ALTER TABLE cliente ADD COLUMN is_admin BOOLEAN DEFAULT FALSE AFTER data_nascimento");
                    echo '<div class="success">✓ Coluna is_admin adicionada com sucesso!</div>';
                }
            } elseif ($action === 'set_admin') {
                $email = trim($_POST['email'] ?? '');
                
                if ($email === '') {
                    echo '<div class="error">✗ Email é obrigatório</div>';
                } else {
                    $stmt = $pdo->prepare("UPDATE cliente SET is_admin = TRUE WHERE email = ?");
                    $stmt->execute([$email]);
                    
                    if ($stmt->rowCount() > 0) {
                        echo "<div class='success'>✓ Usuário $email agora é administrador!</div>";
                    } else {
                        echo "<div class='error'>✗ Usuário com email $email não encontrado</div>";
                    }
                }
            }
        } catch (Exception $e) {
            echo '<div class="error">✗ Erro: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    }
    ?>

    <h2>Passo 1: Adicionar Coluna is_admin</h2>
    <form method="POST">
        <input type="hidden" name="action" value="add_column">
        <button type="submit">Executar: Adicionar Coluna is_admin</button>
    </form>

    <h2>Passo 2: Definir Usuário como Admin</h2>
    <form method="POST">
        <input type="hidden" name="action" value="set_admin">
        <label for="email">Email do usuário que será admin:</label>
        <input type="email" id="email" name="email" placeholder="admin@exemplo.com" required>
        <button type="submit">Tornar Admin</button>
    </form>

    <hr style="margin: 40px 0; border-color: #334155;">

    <h2>SQL Manual (Alternativo)</h2>
    <pre style="background: #0b1020; padding: 15px; border-radius: 8px; overflow-x: auto; border: 1px solid #334155;">
USE loja_hardware;

-- Adiciona coluna is_admin
ALTER TABLE cliente ADD COLUMN is_admin BOOLEAN DEFAULT FALSE AFTER data_nascimento;

-- Define um usuário específico como admin (substitua o email)
UPDATE cliente SET is_admin = TRUE WHERE email = 'seu@email.com';

-- Verifica usuários admin
SELECT cliente_id, nome, email, is_admin FROM cliente WHERE is_admin = TRUE;
    </pre>

    <div style="margin-top: 30px; text-align: center;">
        <a href="index.html"><button>← Voltar para Home</button></a>
        <a href="admin-produtos.html"><button>Ir para Admin Produtos</button></a>
    </div>
</body>
</html>
