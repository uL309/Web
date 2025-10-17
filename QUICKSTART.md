# 🚀 Guia de Início Rápido - CiberTech E-commerce

## Opção 1: Instalação Automática (Recomendado)

### Windows PowerShell (como Administrador):

```powershell
cd f:\weeeeeeeeeb\Web
.\install.ps1
```

O script irá:
1. Verificar PHP e MySQL
2. Configurar credenciais do banco
3. Criar schema e popular dados de exemplo
4. Iniciar servidor automaticamente
5. Abrir navegador

---

## Opção 2: Instalação Manual

### 1. Criar o Banco de Dados

```powershell
# Executar schema
mysql -u root -p < banco1.sql

# Popular com dados de exemplo
mysql -u root -p loja_hardware < populate_db.sql
```

### 2. Configurar Credenciais (se necessário)

```powershell
$env:DB_HOST="127.0.0.1"
$env:DB_PORT="3306"
$env:DB_NAME="loja_hardware"
$env:DB_USER="root"
$env:DB_PASS="sua_senha"
```

### 3. Iniciar Servidor

```powershell
cd f:\weeeeeeeeeb\Web
php -S localhost:8000
```

### 4. Acessar

Abra o navegador em: **http://localhost:8000**

---

## 📋 Teste Rápido (5 minutos)

### 1. Cadastro (1 min)
- Acesse http://localhost:8000/registro_cliente.html
- Preencha o formulário
- Você será logado automaticamente

### 2. Navegue pelos Produtos (1 min)
- Volte para a página inicial
- Veja os produtos cadastrados
- Clique em um para ver detalhes

### 3. Adicione ao Carrinho (1 min)
- Na página do produto, clique "Adicionar ao Carrinho"
- Vá para http://localhost:8000/carrinho.html
- Atualize quantidades se quiser

### 4. Finalize a Compra (1 min)
- Clique em "Finalizar Compra"
- Escolha forma de pagamento
- Confirme o pedido

### 5. Veja seus Pedidos (1 min)
- Clique em "📦 Pedidos" no menu
- Veja detalhes do seu pedido
- Note o código de rastreamento

---

## 🧪 Conta de Teste Pré-configurada

Se você executou `populate_db.sql`:

```
Email: joao@teste.com
Senha: teste123
```

Essa conta já possui:
- ✅ Cadastro completo
- ✅ 3 avaliações de produtos
- ✅ Pronto para fazer pedidos

---

## 🎯 Fluxos de Teste

### Teste 1: Usuário Novo
```
1. Criar conta → 2. Navegar → 3. Adicionar ao carrinho → 4. Comprar → 5. Ver pedido
```

### Teste 2: Usuário Existente
```
1. Login → 2. Ver histórico → 3. Avaliar produto → 4. Nova compra
```

### Teste 3: Carrinho de Convidado
```
1. Adicionar ao carrinho sem login → 2. Fazer login → 3. Carrinho mantido → 4. Comprar
```

---

## 📦 Produtos Disponíveis (após populate_db.sql)

| Categoria | Produtos |
|-----------|----------|
| **Placas de Vídeo** | RTX 4090, RX 7900 XTX, RTX 4070 Ti |
| **Processadores** | i9-14900K, Ryzen 9 7950X, i5-14600K |
| **Memória RAM** | Corsair 32GB DDR5, Kingston 16GB DDR4 |
| **Placas-mãe** | ASUS Z790-E, MSI B650 |
| **SSDs** | Samsung 990 PRO 2TB, WD Black 1TB |
| **Periféricos** | Logitech G Pro, Razer BlackWidow, HyperX Cloud III |
| **Monitores** | LG UltraGear 27", Samsung Odyssey G9 |

Total: **18 produtos** cadastrados

---

## 🔍 Endpoints para Testar Diretamente

### Listar Produtos
```
GET http://localhost:8000/php/products.php
GET http://localhost:8000/php/products.php?categoria=5
GET http://localhost:8000/php/products.php?q=RTX
```

### Produto Específico
```
GET http://localhost:8000/php/product.php?id=1
```

### Ver Carrinho
```
GET http://localhost:8000/php/cart.php
```

### Adicionar ao Carrinho (use Postman/Insomnia)
```
POST http://localhost:8000/php/cart.php
Content-Type: application/json

{
  "produto_id": 1,
  "quantidade": 2
}
```

### Fazer Pedido (após login)
```
POST http://localhost:8000/php/checkout.php
Content-Type: application/json

{
  "frete": 49.90,
  "forma_pagamento": "credito",
  "parcelas": 3
}
```

---

## ⚠️ Problemas Comuns

### Erro: "Connection refused"
**Solução:** Verifique se o MySQL está rodando
```powershell
Get-Service -Name MySQL*
# Se parado:
Start-Service MySQL80  # ou o nome do seu serviço
```

### Erro: "Access denied"
**Solução:** Verifique usuário/senha do MySQL
```powershell
mysql -u root -p
# Teste se consegue conectar
```

### Porta 8000 em uso
**Solução:** Use outra porta
```powershell
php -S localhost:8080
# Então acesse http://localhost:8080
```

### Produtos não aparecem
**Solução:** Execute populate_db.sql
```powershell
mysql -u root -p loja_hardware < populate_db.sql
```

### Erro "Class 'PDO' not found"
**Solução:** Ative extensão PDO no php.ini
```ini
extension=pdo_mysql
```

---

## 📚 Documentação Completa

- **README.md** - Visão geral e instalação
- **API_DOCS.md** - Documentação completa das APIs
- **CHECKLIST.md** - Funcionalidades e melhorias futuras
- **banco1.sql** - Schema do banco
- **populate_db.sql** - Dados de exemplo

---

## 🎓 Próximos Passos

1. ✅ Familiarize-se com a estrutura do projeto
2. ✅ Teste todas as funcionalidades
3. ✅ Explore o código PHP e JavaScript
4. ✅ Leia a documentação das APIs
5. ✅ Implemente melhorias do CHECKLIST.md
6. ✅ Customize o design (style.css)
7. ✅ Adicione novos produtos no banco
8. ✅ Crie suas próprias funcionalidades!

---

## 💡 Dicas

- Use **Ctrl+Shift+I** (DevTools) para debugar JavaScript
- Verifique o **Console** do navegador para erros
- Use **Network tab** para ver chamadas de API
- Adicione `$env:APP_DEBUG="1"` para ver erros detalhados no PHP
- Consulte `API_DOCS.md` para exemplos de requisições

---

## 🤝 Suporte

Se encontrar problemas:

1. Verifique o Console do navegador (F12)
2. Verifique logs do PHP no terminal
3. Confirme que MySQL está rodando
4. Revise README.md e API_DOCS.md
5. Teste endpoints diretamente (Postman/Insomnia)

**Bom desenvolvimento! 🚀**
