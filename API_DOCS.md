# Documentação de APIs - CiberTech E-commerce

Todas as respostas são em formato JSON.

## 🔐 Autenticação

### POST `/php/login.php`
Autenticar usuário.

**Request:**
```json
{
  "email": "joao@teste.com",
  "senha": "teste123"
}
```

**Response (200):**
```json
{
  "success": true,
  "user": {
    "id": 1,
    "nome": "João Silva",
    "email": "joao@teste.com",
    "logged_in_at": 1697500000
  }
}
```

---

### POST `/php/register.php`
Cadastrar novo cliente.

**Request:**
```json
{
  "nome": "Maria Santos",
  "cpf": "12345678901",
  "data_nascimento": "1995-03-20",
  "telefone": "11999887766",
  "endereco": "Rua ABC, 123",
  "email": "maria@example.com",
  "senha": "senha123",
  "confirmar_senha": "senha123"
}
```

**Response (200):**
```json
{
  "success": true,
  "user": {
    "id": 2,
    "nome": "Maria Santos",
    "email": "maria@example.com",
    "logged_in_at": 1697500000
  }
}
```

---

### POST `/php/logout.php`
Encerrar sessão.

**Response (200):**
```json
{
  "success": true,
  "message": "Logout realizado com sucesso."
}
```

---

### GET `/php/me.php`
Obter informações do usuário logado.

**Response (200):**
```json
{
  "success": true,
  "logged_in": true,
  "user": {
    "id": 1,
    "nome": "João Silva",
    "email": "joao@teste.com",
    "logged_in_at": 1697500000
  }
}
```

---

## 📦 Produtos

### GET `/php/products.php`
Listar produtos com filtros e paginação.

**Query Params:**
- `page` (int): Número da página (padrão: 1)
- `limit` (int): Itens por página (padrão: 12, max: 50)
- `categoria` (int): ID da categoria
- `q` (string): Termo de busca
- `min_price` (float): Preço mínimo
- `max_price` (float): Preço máximo
- `order` (string): Campo de ordenação (`nome`, `preco`, `produto_id`)
- `dir` (string): Direção (`ASC` ou `DESC`)

**Example:** `/php/products.php?categoria=5&order=preco&dir=ASC&limit=12`

**Response (200):**
```json
{
  "success": true,
  "products": [
    {
      "produto_id": 1,
      "nome": "NVIDIA RTX 4090 24GB",
      "descricao": "Placa de vídeo top...",
      "preco": "14999.90",
      "estoque": 15,
      "imagem": "https://...",
      "fabricante": "NVIDIA",
      "categoria_nome": "Placas de Vídeo"
    }
  ],
  "pagination": {
    "page": 1,
    "limit": 12,
    "total": 18,
    "total_pages": 2
  }
}
```

---

### GET `/php/product.php?id={produto_id}`
Obter detalhes de um produto específico.

**Response (200):**
```json
{
  "success": true,
  "product": {
    "produto_id": 1,
    "nome": "NVIDIA RTX 4090 24GB",
    "descricao": "...",
    "especificacoes": "GPU: AD102 | 24GB GDDR6X...",
    "preco": "14999.90",
    "estoque": 15,
    "imagem": "https://...",
    "fabricante": "NVIDIA",
    "sku": "GPU-RTX4090-24GB",
    "categoria_nome": "Placas de Vídeo",
    "fornecedor_nome": "TechDistribuidora LTDA"
  },
  "reviews": [
    {
      "avaliacao_id": 1,
      "nota": 5,
      "comentario": "Excelente!",
      "data": "2025-10-17 10:30:00",
      "cliente_nome": "João Silva"
    }
  ],
  "rating": {
    "average": 4.8,
    "count": 12
  }
}
```

---

### GET `/php/categories.php`
Listar todas as categorias.

**Response (200):**
```json
{
  "success": true,
  "categories": [
    {
      "categoria_id": 1,
      "nome": "Componentes",
      "categoria_pai_id": null
    },
    {
      "categoria_id": 5,
      "nome": "Placas de Vídeo",
      "categoria_pai_id": 1
    }
  ]
}
```

---

## 🛒 Carrinho

### GET `/php/cart.php`
Obter itens do carrinho atual.

**Response (200):**
```json
{
  "success": true,
  "cart_id": 1,
  "items": [
    {
      "item_carrinho_id": 1,
      "quantidade": 2,
      "produto_id": 1,
      "nome": "NVIDIA RTX 4090 24GB",
      "preco": "14999.90",
      "estoque": 15,
      "imagem": "https://..."
    }
  ],
  "total": 29999.80
}
```

---

### POST `/php/cart.php`
Adicionar produto ao carrinho.

**Request:**
```json
{
  "produto_id": 1,
  "quantidade": 2
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Produto adicionado ao carrinho"
}
```

---

### PUT `/php/cart.php`
Atualizar quantidade de um item.

**Request:**
```json
{
  "item_id": 1,
  "quantidade": 3
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Quantidade atualizada"
}
```

---

### DELETE `/php/cart.php`
Remover item do carrinho.

**Request:**
```json
{
  "item_id": 1
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Item removido"
}
```

---

## 💳 Checkout e Pedidos

### POST `/php/checkout.php`
Finalizar compra e criar pedido.

**Request:**
```json
{
  "frete": 49.90,
  "forma_pagamento": "credito",
  "parcelas": 3
}
```

**Formas de pagamento:** `credito`, `debito`, `pix`, `boleto`

**Response (200):**
```json
{
  "success": true,
  "pedido_id": 1,
  "pagamento_id": 1,
  "codigo_rastreamento": "BR0000000001ABCD",
  "message": "Pedido realizado com sucesso!"
}
```

---

### GET `/php/orders.php`
Listar todos os pedidos do cliente logado.

**Response (200):**
```json
{
  "success": true,
  "orders": [
    {
      "pedido_id": 1,
      "data_pedido": "2025-10-17 14:30:00",
      "status": "Enviado",
      "valor_total": "15049.80",
      "total_items": 2
    }
  ]
}
```

---

### GET `/php/orders.php?id={pedido_id}`
Obter detalhes de um pedido específico.

**Response (200):**
```json
{
  "success": true,
  "order": {
    "pedido_id": 1,
    "data_pedido": "2025-10-17 14:30:00",
    "status": "Enviado",
    "valor_total": "15049.80",
    "frete": "49.90",
    "forma_pagamento": "credito",
    "status_pagamento": "Aprovado",
    "parcelas": 3,
    "data_envio": "2025-10-18 10:00:00",
    "data_entrega": null,
    "status_entrega": "Em Trânsito",
    "codigo_rastreamento": "BR0000000001ABCD",
    "transportadora": "Correios"
  },
  "items": [
    {
      "quantidade": 2,
      "preco_no_momento": "14999.90",
      "produto_id": 1,
      "nome": "NVIDIA RTX 4090 24GB",
      "imagem": "https://..."
    }
  ]
}
```

---

## ⭐ Avaliações

### GET `/php/reviews.php?produto_id={id}`
Listar avaliações de um produto.

**Response (200):**
```json
{
  "success": true,
  "reviews": [
    {
      "avaliacao_id": 1,
      "nota": 5,
      "comentario": "Produto excelente!",
      "data": "2025-10-17 12:00:00",
      "cliente_nome": "João Silva"
    }
  ]
}
```

---

### POST `/php/reviews.php`
Adicionar avaliação (apenas para produtos comprados).

**Request:**
```json
{
  "produto_id": 1,
  "nota": 5,
  "comentario": "Produto excelente, recomendo!"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Avaliação adicionada com sucesso"
}
```

---

## ❌ Erros Comuns

### 400 - Bad Request
```json
{
  "success": false,
  "error": "Dados inválidos"
}
```

### 401 - Unauthorized
```json
{
  "success": false,
  "error": "Faça login para continuar"
}
```

### 403 - Forbidden
```json
{
  "success": false,
  "error": "Você não tem permissão"
}
```

### 404 - Not Found
```json
{
  "success": false,
  "error": "Recurso não encontrado"
}
```

### 409 - Conflict
```json
{
  "success": false,
  "errors": {
    "email": "Email já cadastrado"
  }
}
```

### 422 - Unprocessable Entity
```json
{
  "success": false,
  "errors": {
    "nome": "Informe o nome",
    "email": "Email inválido"
  }
}
```

### 500 - Internal Server Error
```json
{
  "success": false,
  "error": "Erro interno do servidor",
  "details": "..." // apenas se APP_DEBUG está ativo
}
```

---

## 🔧 Configuração Adicional

### Debug Mode
Para ver detalhes de erros em desenvolvimento, defina:
```powershell
$env:APP_DEBUG="1"
```

### CORS (se frontend estiver em outro domínio)
Adicione em `php/config.php`:
```php
header('Access-Control-Allow-Origin: http://seu-frontend.com');
header('Access-Control-Allow-Credentials: true');
```
