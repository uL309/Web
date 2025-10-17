# 🧪 Exemplos de Uso da API - CiberTech E-commerce

Coleção de comandos para testar a API usando PowerShell ou curl.

---

## 🔐 Autenticação

### Cadastrar Novo Usuário

**PowerShell:**
```powershell
$body = @{
    nome = "Maria Silva"
    cpf = "98765432109"
    data_nascimento = "1995-06-15"
    telefone = "11998877665"
    endereco = "Rua Exemplo, 456 - São Paulo/SP"
    email = "maria@example.com"
    senha = "senha123"
    confirmar_senha = "senha123"
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://localhost:8000/php/register.php" `
    -Method Post `
    -ContentType "application/json" `
    -Body $body `
    -SessionVariable session
```

**curl:**
```bash
curl -X POST http://localhost:8000/php/register.php \
  -H "Content-Type: application/json" \
  -d '{
    "nome": "Maria Silva",
    "cpf": "98765432109",
    "data_nascimento": "1995-06-15",
    "telefone": "11998877665",
    "endereco": "Rua Exemplo, 456 - São Paulo/SP",
    "email": "maria@example.com",
    "senha": "senha123",
    "confirmar_senha": "senha123"
  }' \
  -c cookies.txt
```

---

### Login

**PowerShell:**
```powershell
$body = @{
    email = "joao@teste.com"
    senha = "teste123"
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://localhost:8000/php/login.php" `
    -Method Post `
    -ContentType "application/json" `
    -Body $body `
    -SessionVariable session
```

**curl:**
```bash
curl -X POST http://localhost:8000/php/login.php \
  -H "Content-Type: application/json" \
  -d '{
    "email": "joao@teste.com",
    "senha": "teste123"
  }' \
  -c cookies.txt
```

---

### Ver Sessão Atual

**PowerShell:**
```powershell
Invoke-RestMethod -Uri "http://localhost:8000/php/me.php" `
    -WebSession $session
```

**curl:**
```bash
curl http://localhost:8000/php/me.php -b cookies.txt
```

---

### Logout

**PowerShell:**
```powershell
Invoke-RestMethod -Uri "http://localhost:8000/php/logout.php" `
    -Method Post `
    -WebSession $session
```

**curl:**
```bash
curl -X POST http://localhost:8000/php/logout.php -b cookies.txt
```

---

## 📦 Produtos

### Listar Todos os Produtos

**PowerShell:**
```powershell
Invoke-RestMethod -Uri "http://localhost:8000/php/products.php"
```

**curl:**
```bash
curl http://localhost:8000/php/products.php
```

---

### Buscar Produtos por Termo

**PowerShell:**
```powershell
Invoke-RestMethod -Uri "http://localhost:8000/php/products.php?q=RTX"
```

**curl:**
```bash
curl "http://localhost:8000/php/products.php?q=RTX"
```

---

### Filtrar por Categoria

**PowerShell:**
```powershell
# Categoria 5 = Placas de Vídeo
Invoke-RestMethod -Uri "http://localhost:8000/php/products.php?categoria=5"
```

**curl:**
```bash
curl "http://localhost:8000/php/products.php?categoria=5"
```

---

### Filtrar por Faixa de Preço

**PowerShell:**
```powershell
Invoke-RestMethod -Uri "http://localhost:8000/php/products.php?min_price=1000&max_price=5000"
```

**curl:**
```bash
curl "http://localhost:8000/php/products.php?min_price=1000&max_price=5000"
```

---

### Ordenar por Preço

**PowerShell:**
```powershell
# ASC = menor para maior
Invoke-RestMethod -Uri "http://localhost:8000/php/products.php?order=preco&dir=ASC"

# DESC = maior para menor
Invoke-RestMethod -Uri "http://localhost:8000/php/products.php?order=preco&dir=DESC"
```

**curl:**
```bash
curl "http://localhost:8000/php/products.php?order=preco&dir=ASC"
```

---

### Paginação

**PowerShell:**
```powershell
# Página 1, 12 itens
Invoke-RestMethod -Uri "http://localhost:8000/php/products.php?page=1&limit=12"

# Página 2
Invoke-RestMethod -Uri "http://localhost:8000/php/products.php?page=2&limit=12"
```

**curl:**
```bash
curl "http://localhost:8000/php/products.php?page=1&limit=12"
```

---

### Detalhes de um Produto

**PowerShell:**
```powershell
Invoke-RestMethod -Uri "http://localhost:8000/php/product.php?id=1"
```

**curl:**
```bash
curl "http://localhost:8000/php/product.php?id=1"
```

---

### Listar Categorias

**PowerShell:**
```powershell
Invoke-RestMethod -Uri "http://localhost:8000/php/categories.php"
```

**curl:**
```bash
curl http://localhost:8000/php/categories.php
```

---

## 🛒 Carrinho

### Ver Carrinho

**PowerShell:**
```powershell
Invoke-RestMethod -Uri "http://localhost:8000/php/cart.php" `
    -WebSession $session
```

**curl:**
```bash
curl http://localhost:8000/php/cart.php -b cookies.txt
```

---

### Adicionar Produto ao Carrinho

**PowerShell:**
```powershell
$body = @{
    produto_id = 1
    quantidade = 2
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://localhost:8000/php/cart.php" `
    -Method Post `
    -ContentType "application/json" `
    -Body $body `
    -WebSession $session
```

**curl:**
```bash
curl -X POST http://localhost:8000/php/cart.php \
  -H "Content-Type: application/json" \
  -d '{
    "produto_id": 1,
    "quantidade": 2
  }' \
  -b cookies.txt
```

---

### Atualizar Quantidade

**PowerShell:**
```powershell
$body = @{
    item_id = 1
    quantidade = 5
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://localhost:8000/php/cart.php" `
    -Method Put `
    -ContentType "application/json" `
    -Body $body `
    -WebSession $session
```

**curl:**
```bash
curl -X PUT http://localhost:8000/php/cart.php \
  -H "Content-Type: application/json" \
  -d '{
    "item_id": 1,
    "quantidade": 5
  }' \
  -b cookies.txt
```

---

### Remover Item

**PowerShell:**
```powershell
$body = @{
    item_id = 1
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://localhost:8000/php/cart.php" `
    -Method Delete `
    -ContentType "application/json" `
    -Body $body `
    -WebSession $session
```

**curl:**
```bash
curl -X DELETE http://localhost:8000/php/cart.php \
  -H "Content-Type: application/json" \
  -d '{
    "item_id": 1
  }' \
  -b cookies.txt
```

---

## 💳 Checkout

### Finalizar Compra

**PowerShell:**
```powershell
$body = @{
    frete = 49.90
    forma_pagamento = "credito"
    parcelas = 3
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://localhost:8000/php/checkout.php" `
    -Method Post `
    -ContentType "application/json" `
    -Body $body `
    -WebSession $session
```

**curl:**
```bash
curl -X POST http://localhost:8000/php/checkout.php \
  -H "Content-Type: application/json" \
  -d '{
    "frete": 49.90,
    "forma_pagamento": "credito",
    "parcelas": 3
  }' \
  -b cookies.txt
```

**Formas de pagamento válidas:**
- `credito`
- `debito`
- `pix`
- `boleto`

---

## 📦 Pedidos

### Listar Meus Pedidos

**PowerShell:**
```powershell
Invoke-RestMethod -Uri "http://localhost:8000/php/orders.php" `
    -WebSession $session
```

**curl:**
```bash
curl http://localhost:8000/php/orders.php -b cookies.txt
```

---

### Detalhes de um Pedido

**PowerShell:**
```powershell
Invoke-RestMethod -Uri "http://localhost:8000/php/orders.php?id=1" `
    -WebSession $session
```

**curl:**
```bash
curl "http://localhost:8000/php/orders.php?id=1" -b cookies.txt
```

---

## ⭐ Avaliações

### Listar Avaliações de um Produto

**PowerShell:**
```powershell
Invoke-RestMethod -Uri "http://localhost:8000/php/reviews.php?produto_id=1"
```

**curl:**
```bash
curl "http://localhost:8000/php/reviews.php?produto_id=1"
```

---

### Adicionar Avaliação

**PowerShell:**
```powershell
$body = @{
    produto_id = 1
    nota = 5
    comentario = "Produto excelente! Superou minhas expectativas."
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://localhost:8000/php/reviews.php" `
    -Method Post `
    -ContentType "application/json" `
    -Body $body `
    -WebSession $session
```

**curl:**
```bash
curl -X POST http://localhost:8000/php/reviews.php \
  -H "Content-Type: application/json" \
  -d '{
    "produto_id": 1,
    "nota": 5,
    "comentario": "Produto excelente! Superou minhas expectativas."
  }' \
  -b cookies.txt
```

---

## 🧪 Script Completo de Teste (PowerShell)

```powershell
# 1. Cadastrar usuário
Write-Host "1. Cadastrando usuário..." -ForegroundColor Yellow
$register = @{
    nome = "Teste API"
    cpf = "11122233344"
    data_nascimento = "1990-01-01"
    telefone = "11999999999"
    endereco = "Rua API, 123"
    email = "testeapi@example.com"
    senha = "api123"
    confirmar_senha = "api123"
} | ConvertTo-Json

$user = Invoke-RestMethod -Uri "http://localhost:8000/php/register.php" `
    -Method Post -ContentType "application/json" -Body $register -SessionVariable session
Write-Host "✓ Usuário criado: $($user.user.nome)" -ForegroundColor Green

# 2. Listar produtos
Write-Host "`n2. Listando produtos..." -ForegroundColor Yellow
$products = Invoke-RestMethod -Uri "http://localhost:8000/php/products.php?limit=5"
Write-Host "✓ Encontrados $($products.products.Count) produtos" -ForegroundColor Green
$firstProduct = $products.products[0]
Write-Host "  - Primeiro produto: $($firstProduct.nome) - R$ $($firstProduct.preco)" -ForegroundColor Gray

# 3. Adicionar ao carrinho
Write-Host "`n3. Adicionando ao carrinho..." -ForegroundColor Yellow
$addCart = @{
    produto_id = $firstProduct.produto_id
    quantidade = 1
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://localhost:8000/php/cart.php" `
    -Method Post -ContentType "application/json" -Body $addCart -WebSession $session
Write-Host "✓ Produto adicionado ao carrinho" -ForegroundColor Green

# 4. Ver carrinho
Write-Host "`n4. Visualizando carrinho..." -ForegroundColor Yellow
$cart = Invoke-RestMethod -Uri "http://localhost:8000/php/cart.php" -WebSession $session
Write-Host "✓ Carrinho tem $($cart.items.Count) item(s) - Total: R$ $($cart.total)" -ForegroundColor Green

# 5. Finalizar compra
Write-Host "`n5. Finalizando compra..." -ForegroundColor Yellow
$checkout = @{
    frete = 49.90
    forma_pagamento = "credito"
    parcelas = 1
} | ConvertTo-Json

$order = Invoke-RestMethod -Uri "http://localhost:8000/php/checkout.php" `
    -Method Post -ContentType "application/json" -Body $checkout -WebSession $session
Write-Host "✓ Pedido #$($order.pedido_id) criado!" -ForegroundColor Green
Write-Host "  Rastreamento: $($order.codigo_rastreamento)" -ForegroundColor Gray

# 6. Ver pedidos
Write-Host "`n6. Listando pedidos..." -ForegroundColor Yellow
$orders = Invoke-RestMethod -Uri "http://localhost:8000/php/orders.php" -WebSession $session
Write-Host "✓ Cliente tem $($orders.orders.Count) pedido(s)" -ForegroundColor Green

Write-Host "`n✅ Teste completo finalizado!" -ForegroundColor Green
```

---

## 🔍 Dicas de Debug

### Ver Response Headers
**PowerShell:**
```powershell
$response = Invoke-WebRequest -Uri "http://localhost:8000/php/products.php"
$response.Headers
```

### Ver Status Code
**PowerShell:**
```powershell
$response.StatusCode
```

### Salvar Response em Arquivo
**PowerShell:**
```powershell
Invoke-RestMethod -Uri "http://localhost:8000/php/products.php" | ConvertTo-Json -Depth 10 | Out-File products.json
```

### Pretty Print JSON
**PowerShell:**
```powershell
Invoke-RestMethod -Uri "http://localhost:8000/php/products.php" | ConvertTo-Json -Depth 10
```

---

## 📚 Mais Recursos

- **Postman Collection:** Importe esses exemplos no Postman
- **Insomnia:** Alternativa ao Postman
- **VS Code REST Client:** Use a extensão REST Client
- **Bruno:** Cliente API open-source

**Happy Testing! 🧪**
