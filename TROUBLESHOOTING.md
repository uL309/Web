# 🔧 Guia de Troubleshooting - CiberTech E-commerce

## ✅ Correções Aplicadas

As seguintes integrações foram adicionadas/corrigidas:

### 📄 **index.html**
- ✅ Adicionado `js/header.js` para gerenciar sessão do usuário
- ✅ Produtos agora carregam dinamicamente via `php/products.php`
- ✅ Botões "Adicionar ao carrinho" integrados com `php/cart.php`

### 📄 **produto1.html**
- ✅ Produto carrega dinamicamente via `php/product.php?id=X`
- ✅ Suporta URL query parameter (`?id=1`, `?id=2`, etc)
- ✅ Botão integrado com backend para adicionar ao carrinho

### 📄 **carrinho.html**
- ✅ Já estava com `js/cart.js` integrado
- ✅ Carrega itens via `php/cart.php` (GET)
- ✅ Atualiza quantidade via PUT
- ✅ Remove itens via DELETE

### 📄 **checkout.html**
- ✅ Adicionado script para carregar resumo do carrinho
- ✅ Integrado com `php/checkout.php` para processar pedidos
- ✅ Redirecionamento para página de pedidos após sucesso

### 📄 **login.html** e **registro_cliente.html**
- ✅ Já estavam integrados com `js/auth.js` e `js/register.js`

---

## 🚀 Como Testar

### 1️⃣ **Verificar se Apache e MySQL estão rodando**

Abra o XAMPP Control Panel e certifique-se que:
- ✅ **Apache** está com status "Running" (verde)
- ✅ **MySQL** está com status "Running" (verde)

Se não estiverem rodando, clique em "Start" para cada um.

---

### 2️⃣ **Verificar se o banco de dados está configurado**

1. Acesse: `http://localhost/phpmyadmin`
2. Verifique se existe o banco `loja_hardware`
3. Se não existir, execute o arquivo `banco1.sql` ou `populate_db.sql`

---

### 3️⃣ **Acessar o site corretamente**

⚠️ **IMPORTANTE**: Acesse via servidor HTTP, NÃO via arquivo local!

✅ **CORRETO**:
```
http://localhost/index.html
```

❌ **ERRADO** (não funcionará):
```
file:///C:/xampp/htdocs/index.html
```

---

### 4️⃣ **Abrir o Console do Navegador para Debug**

1. Abra o site: `http://localhost/index.html`
2. Pressione **F12** ou **Ctrl+Shift+I** (Chrome/Edge) para abrir DevTools
3. Clique na aba **Console**
4. Recarregue a página (**F5** ou **Ctrl+R**)

#### **O que verificar no Console:**

✅ **Se tudo estiver funcionando**, você verá:
- Nenhum erro em vermelho
- Requisições para `/php/products.php` com status 200
- Lista de produtos sendo exibida

❌ **Se houver erros**, você verá algo como:

**Erro de CORS:**
```
Access to fetch at 'http://localhost/php/products.php' from origin 'null' has been blocked by CORS
```
➡️ **Solução**: Você está acessando via `file://`. Use `http://localhost/`

**Erro 404:**
```
GET http://localhost/php/products.php 404 (Not Found)
```
➡️ **Solução**: O arquivo PHP não existe ou o caminho está errado

**Erro 500:**
```
GET http://localhost/php/products.php 500 (Internal Server Error)
```
➡️ **Solução**: Erro no código PHP. Verifique `php/config.php` se as credenciais do banco estão corretas

**Erro de conexão:**
```
Failed to fetch
TypeError: Failed to fetch
```
➡️ **Solução**: Apache não está rodando. Inicie o Apache no XAMPP

---

### 5️⃣ **Verificar a aba Network (Rede)**

1. No DevTools, clique na aba **Network** (Rede)
2. Recarregue a página (**F5**)
3. Procure por requisições para `/php/products.php`, `/php/cart.php`, etc.

#### **O que verificar:**

- **Status**: Deve ser `200 OK`
- **Response**: Clique na requisição e veja a aba "Response" - deve retornar JSON
- **Headers**: Verifique se `Content-Type: application/json`

**Exemplo de resposta esperada** (`php/products.php`):
```json
{
  "success": true,
  "products": [
    {
      "produto_id": 1,
      "nome": "RTX 4070",
      "preco": "3499.00",
      "estoque": 10,
      "imagem": "..."
    }
  ],
  "pagination": {
    "page": 1,
    "total": 15
  }
}
```

---

### 6️⃣ **Testar funcionalidades específicas**

#### **Carregar Produtos na Home**
1. Acesse: `http://localhost/index.html`
2. Os produtos devem aparecer automaticamente
3. Se não aparecerem, verifique o console (F12)

#### **Adicionar ao Carrinho**
1. Clique em "Adicionar ao carrinho" em qualquer produto
2. O botão deve mudar para "Adicionando..." e depois "Adicionado ✓"
3. Se não funcionar, verifique se você está logado (pode precisar de sessão)

#### **Ver Carrinho**
1. Acesse: `http://localhost/carrinho.html`
2. Deve mostrar "Carregando carrinho..." e depois os itens
3. Se mostrar "Carrinho vazio", adicione produtos primeiro

#### **Login**
1. Acesse: `http://localhost/login.html`
2. Tente fazer login com um usuário cadastrado
3. Deve redirecionar para `index.html` após sucesso

---

## 🔍 Problemas Comuns e Soluções

### ❌ Produtos não aparecem na home

**Causa**: API não está retornando dados ou banco vazio

**Solução**:
1. Verifique se o banco `loja_hardware` tem produtos cadastrados:
   ```sql
   SELECT * FROM produto;
   ```
2. Se estiver vazio, execute `populate_db.sql`
3. Verifique no console se há erros

---

### ❌ Erro "Falha ao conectar no banco de dados"

**Causa**: Credenciais do banco incorretas em `php/config.php`

**Solução**:
1. Abra `php/config.php`
2. Verifique as linhas:
   ```php
   $host = getenv('DB_HOST') ?: '127.0.0.1';
   $name = getenv('DB_NAME') ?: 'loja_hardware';
   $user = getenv('DB_USER') ?: 'root';
   $pass = getenv('DB_PASS') ?: '';  // ← Pode precisar de senha
   ```
3. Se você configurou senha no MySQL, altere:
   ```php
   $pass = getenv('DB_PASS') ?: 'sua_senha_aqui';
   ```

---

### ❌ Carrinho não carrega itens

**Causa**: Sessão não iniciada ou carrinho vazio

**Solução**:
1. Tente adicionar um produto primeiro
2. Verifique se você está acessando via `http://localhost/` (não `file://`)
3. Verifique o console para erros de API

---

### ❌ Checkout não funciona

**Causa**: API de checkout pode precisar de autenticação

**Solução**:
1. Faça login primeiro em `http://localhost/login.html`
2. Certifique-se que há itens no carrinho
3. Verifique o console para mensagens de erro

---

## 📋 Checklist Final

Antes de reportar problemas, verifique:

- [ ] Apache está rodando no XAMPP
- [ ] MySQL está rodando no XAMPP
- [ ] Banco `loja_hardware` existe e tem dados
- [ ] Acessando via `http://localhost/` (não `file://`)
- [ ] Console do navegador não mostra erros (F12)
- [ ] Aba Network mostra status 200 nas requisições PHP
- [ ] Credenciais do banco em `php/config.php` estão corretas

---

## 📞 Debug Avançado

Se ainda houver problemas, colete as seguintes informações:

1. **Screenshot do Console** (F12 → Console)
2. **Screenshot da aba Network** mostrando requisições PHP
3. **Conteúdo da resposta** de uma requisição que falhou (clique na requisição → Response)
4. **Versão do PHP**: Execute no terminal:
   ```bash
   C:\xampp\php\php.exe -v
   ```

---

## ✅ Tudo Funcionando?

Se tudo estiver ok, você deve conseguir:

1. ✅ Ver produtos na home carregados do banco
2. ✅ Adicionar produtos ao carrinho
3. ✅ Ver carrinho com itens e totais
4. ✅ Fazer login/registro
5. ✅ Processar checkout
6. ✅ Ver pedidos realizados

**Divirta-se testando o e-commerce! 🚀**
