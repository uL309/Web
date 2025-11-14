# 📄 Guia de Documentação HTML - CiberTech

> **Projeto:** Sistema E-commerce CiberTech - Hardware & Periféricos  
> **Tecnologia:** HTML5 + CSS3 + JavaScript Vanilla  
> **Última Atualização:** Janeiro 2025  
> **Documentação:** Estruturas HTML, componentes visuais e organização de templates

---

## 📑 Índice

1. [Visão Geral](#visão-geral)
2. [Arquivos Documentados - Lote 1](#arquivos-documentados---lote-1)
   - [index.html](#1-indexhtml)
   - [login.html](#2-loginhtml)
   - [registro_cliente.html](#3-registro_clientehtml)
   - [produto1.html](#4-produto1html)
   - [busca.html](#5-buscahtml)
3. [Arquivos Documentados - Lote 2](#arquivos-documentados---lote-2)
   - [carrinho.html](#6-carrinhohtml)
   - [checkout.html](#7-checkouthtml)
   - [pedidos.html](#8-pedidoshtml)
   - [pedido-detalhe.html](#9-pedido-detalhehtml)
   - [minha-conta.html](#10-minha-contahtml)
4. [Arquivos Documentados - Lote 3](#arquivos-documentados---lote-3)
   - [admin-produtos.html](#11-admin-produtoshtml)
   - [esqueci-senha.html](#12-esqueci-senhahtml)
   - [resetar-senha.html](#13-resetar-senhahtml)
   - [diagnostico-produtos.html](#14-diagnostico-produtoshtml)
   - [test-php.html](#15-test-phphtml)
5. [Componentes Compartilhados](#componentes-compartilhados)
6. [Padrões de Design](#padrões-de-design)
7. [Fluxos de Navegação](#fluxos-de-navegação)
8. [Estatísticas Finais](#estatísticas-finais)
9. [Conclusão](#conclusão)

---

## 🎯 Visão Geral

Este documento detalha a **estrutura HTML** do projeto CiberTech, um e-commerce especializado em hardware e periféricos. Os arquivos HTML seguem padrões semânticos modernos, acessibilidade (ARIA) e integração com APIs backend via JavaScript.

### Características Gerais:
- **HTML5 Semântico**: uso de tags `<header>`, `<main>`, `<section>`, `<article>`, `<aside>`, `<footer>`
- **Acessibilidade**: atributos ARIA (`role`, `aria-label`, `aria-live`)
- **Responsividade**: meta viewport configurado para mobile-first
- **SEO**: meta tags de descrição, títulos únicos por página
- **Font**: Google Fonts (Inter) para tipografia profissional
- **Estilo**: arquivo único `style.css` compartilhado

---

## 📁 Arquivos Documentados - Lote 1

### 1. **index.html**

#### 📋 Propósito
Página inicial (homepage) do e-commerce com catálogo de produtos, busca, filtros e navegação por categorias.

#### 🧩 Estrutura Principal

```html
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>CiberTech - Hardware & Periféricos</title>
  <meta name="description" content="Loja online de hardware..." />
  <link href="https://fonts.googleapis.com/..." rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
```

#### 🎨 Componentes Visuais

**1. Header (Cabeçalho Navegável)**
```html
<header>
  <div class="container topbar">
    <!-- Logo + Nome da marca -->
    <div class="brand">
      <div class="logo">CT</div>
      <a href="index.html">CiberTech</a>
      <div>Hardware • Periféricos • Promoções</div>
    </div>
    
    <!-- Busca Global -->
    <div class="search" role="search" aria-label="Busca de produtos">
      <input type="search" id="main-search" placeholder="Procure por..." />
      <button id="search-btn">Buscar</button>
    </div>
    
    <!-- Ações do Usuário -->
    <div class="actions">
      <a href="carrinho.html">🛒 Carrinho</a>
      <a href="pedidos.html">📦 Pedidos</a>
      <a href="admin-produtos.html" id="admin-btn" style="display:none">⚙️ Admin</a>
      <a href="registro_cliente.html">Registrar</a>
      <a href="login.html">Entrar</a>
      <a href="#">👤</a>
    </div>
  </div>
  
  <!-- Navegação por Categorias -->
  <nav id="category-nav">
    <a href="#" data-category="7">Placas-mãe</a>
    <a href="#" data-category="6">Processadores</a>
    <a href="#" data-category="5">Placas de vídeo</a>
    <!-- ... outras categorias ... -->
  </nav>
</header>
```

**Recursos:**
- Logo clicável retorna ao `index.html`
- Campo de busca (`#main-search`) controlado por `js/header.js`
- Botão Admin oculto por padrão (mostrado apenas para usuários autorizados)
- Links de categoria com atributo `data-category` para filtros dinâmicos

---

**2. Hero Section (Banner Principal)**
```html
<section class="hero">
  <div class="hero-card">
    <h1>Ofertas imperdíveis — componente certo, preço justo</h1>
    <p>Frete rápido, garantia e parcelas em até 12x...</p>
    <button class="cta">Ver Ofertas</button>
    
    <!-- Ícones de Categorias -->
    <div class="categories" aria-hidden="true">
      <div class="cat"><strong>GPU</strong><small>Placas de vídeo</small></div>
      <div class="cat"><strong>CPU</strong><small>Processadores</small></div>
      <!-- ... -->
    </div>
    
    <!-- Badges Promocionais -->
    <div>
      <div>ENVIO EXPRESSO - Entregas rápidas em todo PR</div>
      <div class="promo">30% OFF em peças selecionadas</div>
    </div>
  </div>
</section>
```

**Recursos:**
- CTA (Call to Action) destacado
- Grid de categorias com ícones
- Badges de frete e desconto

---

**3. Main Section (Produtos + Filtros)**
```html
<section class="main">
  <div>
    <!-- Cabeçalho com Ordenação -->
    <div style="display:flex; justify-content:space-between;">
      <h2>Produtos</h2>
      <select id="sort-select">
        <option value="nome-asc">Nome (A-Z)</option>
        <option value="preco-asc">Menor Preço</option>
        <!-- ... -->
      </select>
      <button onclick="window.searchFilters.clearFilters()">🔄 Limpar</button>
    </div>
    
    <!-- Grid de Produtos -->
    <div class="products" aria-live="polite">
      <article class="card" aria-label="Placa de Vídeo RTX 4070">
        <img src="https://http.cat/images/402.jpg" alt="Placa de vídeo" />
        <a href="produto1.html"><button>Placa de Vídeo RTX 4070 - 12GB</button></a>
        <div>
          <span class="price">R$ 3.499</span>
          <span class="old">R$ 4.199</span>
        </div>
        <div>Em estoque • Garantia 5 anos</div>
        <button class="buy">Adicionar ao carrinho</button>
      </article>
      <!-- Outros produtos... -->
    </div>
  </div>
  
  <!-- Sidebar de Filtros -->
  <aside class="sidebar" aria-label="Filtros">
    <h3>Filtros</h3>
    
    <!-- Filtro de Preço -->
    <div class="filter">
      <h4>Preço Máximo</h4>
      <input type="range" min="0" max="10000" value="10000" step="100" />
    </div>
    
    <!-- Filtro de Categorias -->
    <div class="filter">
      <h4>Categorias</h4>
      <label><input type="checkbox" /> Placas de vídeo</label>
      <label><input type="checkbox" /> Processadores</label>
      <!-- ... -->
    </div>
  </aside>
</section>
```

**Recursos:**
- `aria-live="polite"` para atualização dinâmica sem interromper leitores de tela
- Cards de produto com imagens placeholder (`http.cat`)
- Filtros de preço (range slider) e categorias (checkboxes)
- Botão "Limpar Filtros" chama `window.searchFilters.clearFilters()` (definido em `js/search-filters.js`)

---

**4. Footer (Rodapé)**
```html
<footer>
  <div class="container">
    <div class="footer-content">
      <div class="footer-left">
        <div>CiberTech</div>
        <div>© 2025 • Todos os direitos reservados</div>
      </div>
      <div class="footer-right">
        Pagamento seguro • Parcelamos em até 12x
        <a href="registrar_produto.html">Registrar produto</a>
      </div>
    </div>
  </div>
</footer>
```

---

**5. Scripts Carregados**
```html
<script src="js/header.js"></script>
<script src="js/search-filters.js"></script>
<script>
  // Event listener para ordenação
  document.addEventListener('DOMContentLoaded', () => {
    const sortSelect = document.getElementById('sort-select');
    sortSelect.addEventListener('change', (e) => {
      const [field, dir] = e.target.value.split('-');
      window.searchFilters.setOrder(field, dir.toUpperCase());
    });
  });
</script>
```

**Funcionalidades:**
- `header.js`: gerencia busca, navegação e autenticação
- `search-filters.js`: implementa filtros dinâmicos e ordenação
- Inline script: conecta dropdown de ordenação ao sistema de filtros

---

### 2. **login.html**

#### 📋 Propósito
Página de autenticação de usuários com validação de credenciais via backend PHP.

#### 🧩 Estrutura Principal

**1. Formulário de Login**
```html
<main class="container" style="max-width: 500px; margin-top: 48px;">
  <div class="hero-card">
    <h1 style="text-align: center;">Acessar sua Conta</h1>
    
    <!-- Mensagem de Erro -->
    <div id="login-error" role="alert" style="display:none; color:#ff6b6b;">
    </div>
    
    <form id="login-form">
      <!-- Email -->
      <div style="margin-bottom:16px;">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" 
               placeholder="seuemail@exemplo.com" required />
      </div>
      
      <!-- Senha -->
      <div style="margin-bottom:16px;">
        <label for="password">Senha</label>
        <input type="password" id="password" name="password" 
               placeholder="Sua senha" required />
      </div>
      
      <!-- Lembrar + Esqueceu Senha -->
      <div style="display:flex; justify-content:space-between;">
        <label><input type="checkbox" name="remember"> Lembrar-me</label>
        <a href="esqueci-senha.html">Esqueceu a senha?</a>
      </div>
      
      <!-- Botão de Envio -->
      <button type="submit" class="cta" style="width:100%;">Entrar</button>
      
      <!-- Link para Registro -->
      <div style="text-align: center; margin-top: 16px;">
        Não tem uma conta? <a href="registro_cliente.html">Crie uma agora</a>
      </div>
    </form>
  </div>
</main>
```

**Recursos:**
- Formulário com validação HTML5 (`required`, `type="email"`)
- Div de erro com `role="alert"` para acessibilidade
- Link para recuperação de senha (`esqueci-senha.html`)
- Link para criação de conta (`registro_cliente.html`)

---

**2. Scripts Carregados**
```html
<script src="js/header.js"></script>
<script src="js/auth.js"></script>
```

**Funcionalidades:**
- `auth.js`: gerencia envio do formulário, validação e sessão de usuário
- Previne recarregamento da página (`preventDefault`)
- Faz POST para `php/login.php` com credenciais
- Redireciona para `index.html` em caso de sucesso

---

### 3. **registro_cliente.html**

#### 📋 Propósito
Página de cadastro de novos usuários com formulário completo de dados pessoais.

#### 🧩 Estrutura Principal

**1. Formulário de Registro**
```html
<main class="container" style="max-width: 700px;">
  <div class="hero-card">
    <h1 style="text-align: center;">Crie sua Conta</h1>
    
    <div id="register-error" role="alert" style="display:none; color:#ff6b6b;">
    </div>
    
    <form id="register-form">
      <!-- Grid 2 Colunas -->
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
        
        <!-- Nome Completo -->
        <div>
          <label for="nome">Nome Completo</label>
          <input type="text" id="nome" name="nome" required />
        </div>
        
        <!-- CPF -->
        <div>
          <label for="cpf">CPF</label>
          <input type="text" id="cpf" name="cpf" required />
        </div>
        
        <!-- Data de Nascimento -->
        <div>
          <label for="data_nascimento">Data de Nascimento</label>
          <input type="date" id="data_nascimento" name="data_nascimento" required />
        </div>
        
        <!-- Telefone -->
        <div>
          <label for="telefone">Telefone</label>
          <input type="tel" id="telefone" name="telefone" />
        </div>
        
        <!-- Endereço (largura completa) -->
        <div style="grid-column: 1 / -1;">
          <label for="endereco">Endereço</label>
          <input type="text" id="endereco" name="endereco" required />
        </div>
        
        <!-- Email (largura completa) -->
        <div style="grid-column: 1 / -1;">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required />
        </div>
        
        <!-- Senha -->
        <div>
          <label for="senha">Senha</label>
          <input type="password" id="senha" name="senha" required />
        </div>
        
        <!-- Confirmar Senha -->
        <div>
          <label for="confirmar_senha">Confirmar Senha</label>
          <input type="password" id="confirmar_senha" name="confirmar_senha" required />
        </div>
      </div>
      
      <!-- Botão de Envio -->
      <button type="submit" class="cta" style="width:100%; margin-top: 24px;">
        Criar Conta
      </button>
      
      <!-- Link para Login -->
      <div style="text-align: center; margin-top: 16px;">
        Já possui uma conta? <a href="login.html">Faça login</a>
      </div>
    </form>
  </div>
</main>
```

**Recursos:**
- Layout em grid 2 colunas para otimizar espaço
- Validação de tipos HTML5 (`email`, `tel`, `date`)
- Confirmação de senha (validada no JavaScript)
- Campos obrigatórios marcados com `required`

---

**2. Scripts Carregados**
```html
<script src="js/register.js"></script>
```

**Funcionalidades:**
- `register.js`: valida formulário (senhas combinam, formato de CPF, etc.)
- Envia POST para `php/register.php` com dados do usuário
- Redireciona para `login.html` após registro bem-sucedido

---

### 4. **produto1.html**

#### 📋 Propósito
Página de detalhes de produto individual com especificações, avaliações e opção de compra.

#### 🧩 Estrutura Principal

**1. Seção de Detalhes do Produto**
```html
<section class="product-detail" style="display:flex; gap:24px;">
  <!-- Conteúdo carregado dinamicamente por JavaScript -->
  <div style="flex:1 1 320px; min-height: 300px; background: var(--card-bg);">
    <!-- Imagem do produto -->
  </div>
  <div style="flex:1 1 400px;">
    <h1 style="min-height: 40px;"><!-- Nome do produto --></h1>
    <div><!-- Preço --></div>
    <button class="buy" disabled>Carregando...</button>
  </div>
</section>
```

**Recursos:**
- Layout flexbox para imagem + informações
- Placeholder com skeleton loading (fundo cinza claro)
- Botão desabilitado até carregar dados

---

**2. Descrição do Produto**
```html
<section id="product-description">
  <h2>Descrição</h2>
  <p id="description-text" style="color:var(--muted); line-height:1.6">
    Carregando descrição...
  </p>
</section>
```

---

**3. Especificações Técnicas**
```html
<section id="product-specs">
  <h2>Especificações Técnicas</h2>
  <table id="specs-table" style="width:100%; border-collapse:collapse;">
    <tr style="border-bottom:1px solid rgba(255,255,255,0.1)">
      <td colspan="2" style="text-align:center; color:var(--muted)">
        Carregando especificações...
      </td>
    </tr>
  </table>
</section>
```

**Funcionalidade:**
- Tabela preenchida dinamicamente via JavaScript
- Formato de especificações: `"key: value | key: value"` (parsing no JS)

---

**4. Avaliações de Clientes**
```html
<section>
  <h2>Avaliações dos Clientes</h2>
  
  <!-- Mensagem de Feedback -->
  <div id="review-message" style="display: none; padding: 16px;">
  </div>
  
  <!-- Formulário de Avaliação -->
  <form id="review-form" style="background: var(--card-bg); padding: 24px;">
    <h3>Escreva sua avaliação:</h3>
    
    <!-- Nota (1-5 estrelas) -->
    <div>
      <label for="nota">Nota (1 a 5 estrelas)</label>
      <select id="nota" name="nota" required>
        <option value="">Selecione uma nota</option>
        <option value="5">★★★★★ (5 estrelas)</option>
        <option value="4">★★★★☆ (4 estrelas)</option>
        <option value="3">★★★☆☆ (3 estrelas)</option>
        <option value="2">★★☆☆☆ (2 estrelas)</option>
        <option value="1">★☆☆☆☆ (1 estrela)</option>
      </select>
    </div>
    
    <!-- Comentário -->
    <div>
      <label for="comentario">Comentário</label>
      <textarea id="comentario" name="comentario" 
                placeholder="Conte-nos sua experiência..." 
                rows="4" required></textarea>
    </div>
    
    <button class="cta" type="submit">Enviar Avaliação</button>
  </form>
  
  <!-- Lista de Avaliações -->
  <div class="reviews">
    <!-- Avaliações carregadas aqui -->
  </div>
</section>
```

**Recursos:**
- Formulário de avaliação com nota (select) e comentário (textarea)
- Mensagem de feedback (sucesso/erro) exibida após envio
- Lista de avaliações com média de estrelas

---

**5. JavaScript de Carregamento**
```javascript
// Pega ID do produto da URL
const urlParams = new URLSearchParams(window.location.search);
const productId = urlParams.get('id');

if (!productId) {
    alert('Produto não especificado.');
    window.location.href = 'index.html';
}

// Carrega produto do backend
async function loadProduct() {
  const res = await fetch(`php/product.php?id=${productId}`);
  const data = await res.json();
  
  if (!data.success) {
    alert('Produto não encontrado');
    window.location.href = 'index.html';
    return;
  }
  
  const product = data.product;
  // Atualiza DOM com dados do produto...
}

// Adiciona ao carrinho
async function addToCart(productId) {
  const res = await fetch('php/cart.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ produto_id: productId, quantidade: 1 })
  });
  // ...
}

// Handler do formulário de avaliação
reviewForm.addEventListener('submit', async (e) => {
  e.preventDefault();
  const payload = {
    produto_id: parseInt(productId),
    nota: parseInt(document.getElementById('nota').value),
    comentario: document.getElementById('comentario').value
  };
  
  const res = await fetch('php/reviews.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  });
  // ...
});

document.addEventListener('DOMContentLoaded', loadProduct);
```

**Funcionalidades:**
- **URL Parsing**: `?id=123` extraído de `URLSearchParams`
- **Fetch Assíncrono**: carrega dados do produto de `php/product.php`
- **Renderização Dinâmica**: atualiza HTML com dados JSON
- **Adicionar ao Carrinho**: POST para `php/cart.php`
- **Enviar Avaliação**: POST para `php/reviews.php`
- **Recarregamento**: após envio de avaliação, recarrega página (2s delay)

---

### 5. **busca.html**

#### 📋 Propósito
Página de resultados de busca com filtros avançados e ordenação de produtos.

#### 🧩 Estrutura Principal

**1. Cabeçalho de Resultados**
```html
<main class="container" style="margin-top: 24px;">
  <div style="margin-bottom: 24px;">
    <h1 id="search-title">Resultados da Busca</h1>
    <p id="search-info" style="color: var(--muted); font-size: 14px;">
      <!-- Info dinâmica: "Mostrando X resultados para 'Y'" -->
    </p>
  </div>
</main>
```

---

**2. Controles de Ordenação**
```html
<div style="display: flex; justify-content: space-between;">
  <div id="results-count" style="color: var(--muted);">
    <!-- Contador de resultados -->
  </div>
  
  <div style="display: flex; gap: 8px;">
    <label>Ordenar:</label>
    <select id="sort-select">
      <option value="nome-asc">Nome (A-Z)</option>
      <option value="nome-desc">Nome (Z-A)</option>
      <option value="preco-asc">Menor Preço</option>
      <option value="preco-desc">Maior Preço</option>
    </select>
  </div>
</div>
```

---

**3. Grid de Produtos**
```html
<div class="products" aria-live="polite">
  <!-- Produtos carregados dinamicamente via js/search-results.js -->
</div>
```

**Recursos:**
- `aria-live="polite"`: atualiza conteúdo sem interromper leitores de tela
- Produtos renderizados com base em query string (`?q=termo`)

---

**4. Sidebar de Filtros**
```html
<aside class="sidebar" aria-label="Filtros">
  <div style="display: flex; justify-content: space-between;">
    <h3>Filtros</h3>
    <button class="icon-btn" id="clear-filters">Limpar</button>
  </div>
  
  <!-- Filtro de Preço -->
  <div class="filter">
    <h4>Preço Máximo</h4>
    <label id="price-label">R$ 0 — R$ 10.000</label>
    <input type="range" id="price-range" min="0" max="10000" value="10000" step="100" />
  </div>
  
  <!-- Filtro de Categorias -->
  <div class="filter">
    <h4>Categorias</h4>
    <div id="category-filters">
      <label><input type="checkbox" data-category="5" /> Placas de vídeo</label>
      <label><input type="checkbox" data-category="6" /> Processadores</label>
      <!-- ... outras categorias ... -->
    </div>
  </div>
  
  <!-- Botão de Retorno -->
  <div class="filter">
    <button class="cta" onclick="window.location.href='index.html'" style="width: 100%;">
      ← Voltar ao Início
    </button>
  </div>
</aside>
```

**Recursos:**
- Range slider para filtro de preço
- Checkboxes de categoria com atributo `data-category`
- Botão "Limpar Filtros" reseta todos os filtros
- Botão de retorno ao índice

---

**5. Scripts Carregados**
```html
<script src="js/header.js"></script>
<script src="js/search-results.js"></script>
```

**Funcionalidades:**
- `search-results.js`: gerencia lógica de busca, filtros e ordenação
- Lê parâmetro `?q=` da URL para termo de busca
- Atualiza contador de resultados dinamicamente
- Aplica filtros de preço e categoria em tempo real

---

## � Arquivos Documentados - Lote 2

### 6. **carrinho.html**

#### 📋 Propósito
Página do carrinho de compras com visualização de itens, controle de quantidade e resumo do pedido.

#### 🧩 Estrutura Principal

**1. Estados de Carregamento**
```html
<!-- Estado: Carregando -->
<div id="cart-loading" style="text-align: center; padding: 60px 0;">
  <p style="color: var(--muted)">Carregando carrinho...</p>
</div>

<!-- Estado: Carrinho Vazio -->
<div id="cart-empty" style="display: none;">
  <p style="color: var(--muted);">Seu carrinho está vazio.</p>
  <a href="index.html"><button class="cta">Continuar Comprando</button></a>
</div>

<!-- Estado: Carrinho com Itens -->
<div id="cart-content" style="display: none;">
  <!-- Conteúdo do carrinho -->
</div>
```

**Recursos:**
- **3 estados visuais**: carregando, vazio, com produtos
- Transição automática entre estados via JavaScript
- Botão CTA para continuar comprando quando vazio

---

**2. Tabela de Itens do Carrinho**
```html
<section class="cart-items" style="overflow-x:auto">
  <table style="width:100%; border-collapse:collapse">
    <thead>
      <tr style="border-bottom:2px solid rgba(255,255,255,0.1)">
        <th style="text-align:left; padding:12px">Produto</th>
        <th style="padding:12px">Preço Unitário</th>
        <th style="padding:12px">Quantidade</th>
        <th style="padding:12px">Subtotal</th>
        <th style="padding:12px">Remover</th>
      </tr>
    </thead>
    <tbody id="cart-items-body">
      <!-- Linhas adicionadas dinamicamente -->
    </tbody>
  </table>
</section>
```

**Recursos:**
- Tabela responsiva com `overflow-x:auto`
- 5 colunas: Produto, Preço, Quantidade, Subtotal, Remover
- `<tbody>` preenchido via `js/cart.js`

---

**3. Resumo do Pedido**
```html
<section style="margin-top:24px; max-width:400px">
  <h2>Resumo do Pedido</h2>
  
  <!-- Subtotal -->
  <div style="display:flex; justify-content:space-between; padding:12px 0; border-bottom:1px solid rgba(255,255,255,0.1)">
    <span>Subtotal</span>
    <span id="cart-subtotal">R$ 0,00</span>
  </div>
  
  <!-- Frete -->
  <div style="display:flex; justify-content:space-between; padding:12px 0; border-bottom:1px solid rgba(255,255,255,0.1)">
    <span>Frete</span>
    <span id="cart-frete">R$ 0,00</span>
  </div>
  
  <!-- Total -->
  <div style="display:flex; justify-content:space-between; padding:12px 0; font-weight:800; font-size:18px">
    <span>Total</span>
    <span id="cart-total">R$ 0,00</span>
  </div>
  
  <!-- Botão de Checkout -->
  <a href="checkout.html">
    <button class="cta" style="width:100%; margin-top:12px">
      Finalizar Compra
    </button>
  </a>
</section>
```

**Recursos:**
- Cálculo dinâmico de subtotal + frete
- Total destacado em negrito (font-weight: 800)
- Botão verde (CTA) para checkout

---

**4. Scripts Carregados**
```html
<script src="js/header.js"></script>
<script src="js/cart.js?v=2025102401"></script>
```

**Funcionalidades (cart.js):**
- **Fetch Carrinho**: GET `php/cart.php` para listar itens
- **Atualizar Quantidade**: PUT `php/cart.php` com novo valor
- **Remover Item**: DELETE `php/cart.php` com `produto_id`
- **Calcular Totais**: soma preço × quantidade de todos os itens
- **Estados Visuais**: alterna entre loading/empty/content

---

### 7. **checkout.html**

#### 📋 Propósito
Página de finalização de compra com seleção de método de pagamento e endereço de entrega (simulado).

#### 🧩 Estrutura Principal

**1. Aviso de Simulação**
```html
<div style="padding: 16px; background: #064e3b; color: #10b981; border-radius: 8px; margin-bottom: 24px; text-align: center; font-weight: 600;">
  Esta é uma simulação. Nenhum dado de pagamento real é coletado ou processado.
</div>
```

**Recursos:**
- Banner verde destacado
- Informa que é ambiente de testes
- Evita confusão do usuário

---

**2. Resumo do Pedido (Sidebar)**
```html
<div class="sidebar">
  <div class="filter">
    <h2>Resumo do Pedido</h2>
    <ul id="summary-list" style="list-style:none; padding:0;">
      <li style="text-align: center; color: var(--muted); padding: 20px 0;">
        Carregando resumo...
      </li>
    </ul>
  </div>
</div>
```

**Funcionalidade:**
- Carrega itens do carrinho via `fetch('php/cart.php')`
- Exibe lista de produtos com quantidade
- Mostra subtotal, frete fixo (R$ 49,90) e total

---

**3. Seleção de Método de Pagamento**
```html
<div style="margin-bottom:20px;">
  <label style="font-weight:600">Método de Pagamento</label>
  <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px">
    
    <!-- Cartão de Crédito -->
    <label class="payment-option" style="padding:12px; border:2px solid rgba(255,255,255,0.1); border-radius:8px; cursor:pointer;">
      <input type="radio" name="payment_method" value="credit_card" checked>
      <span>💳 Cartão de Crédito (Sim)</span>
    </label>
    
    <!-- PIX -->
    <label class="payment-option">
      <input type="radio" name="payment_method" value="pix">
      <span>📱 PIX (Sim)</span>
    </label>
    
    <!-- Boleto -->
    <label class="payment-option">
      <input type="radio" name="payment_method" value="boleto">
      <span>🧾 Boleto (Sim)</span>
    </label>
  </div>
</div>
```

**Recursos:**
- 3 métodos: Cartão de Crédito, PIX, Boleto
- Radio buttons com ícones visuais
- Grid 2 colunas (responsivo)
- Padrão: Cartão de Crédito (`checked`)

---

**4. Campos de Pagamento (Simulados)**
```html
<!-- Parcelas (apenas para cartão) -->
<div id="installments-field" style="margin-bottom:12px;">
  <label for="installments">Número de parcelas</label>
  <select id="installments" name="installments">
    <option value="1">1x à vista (simulado)</option>
    <option value="2">2x (simulado)</option>
    <!-- ... até 12x ... -->
    <option value="12">12x (simulado)</option>
  </select>
</div>

<!-- Info PIX (oculto) -->
<div id="pix-info" style="display:none;">
  <p>Um código PIX seria gerado após a confirmação.</p>
</div>

<!-- Info Boleto (oculto) -->
<div id="boleto-info" style="display:none;">
  <p>Um boleto seria gerado após a confirmação.</p>
</div>
```

**Funcionalidade:**
- **Cartão**: exibe campo de parcelas
- **PIX/Boleto**: exibe mensagem informativa
- JavaScript controla visibilidade (`setupPaymentMethodToggle()`)

---

**5. Endereço de Entrega**
```html
<div style="margin-bottom:20px; border-top:1px solid rgba(255,255,255,0.1); padding-top:20px">
  <h3>Endereço de Entrega</h3>
  
  <!-- CEP -->
  <div style="margin-bottom:12px;">
    <label for="cep">CEP (Simulado)</label>
    <input type="text" id="cep" name="cep" placeholder="00000-000" maxlength="9" />
  </div>
  
  <!-- Endereço Completo -->
  <div style="margin-bottom:12px;">
    <label for="address">Endereço completo (Simulado)</label>
    <textarea id="address" name="address" placeholder="Rua, Número, Bairro..." rows="3"></textarea>
  </div>
</div>
```

**Recursos:**
- Campo CEP com máscara (maxlength: 9)
- Textarea para endereço completo
- Simulado (não valida CEP via API)

---

**6. JavaScript de Checkout**
```javascript
// Controla exibição dos campos conforme método de pagamento
function setupPaymentMethodToggle() {
  const radioButtons = document.querySelectorAll('input[name="payment_method"]');
  radioButtons.forEach(radio => {
    radio.addEventListener('change', (e) => {
      const method = e.target.value;
      // Esconde todos os painéis
      document.getElementById('pix-info').style.display = 'none';
      document.getElementById('boleto-info').style.display = 'none';
      document.getElementById('installments-field').style.display = 'none';
      
      // Exibe painel correto
      if (method === 'credit_card') {
        document.getElementById('installments-field').style.display = 'block';
      } else if (method === 'pix') {
        document.getElementById('pix-info').style.display = 'block';
      } else if (method === 'boleto') {
        document.getElementById('boleto-info').style.display = 'block';
      }
    });
  });
}

// Processa o checkout
async function processCheckout(e) {
  e.preventDefault();
  const form = e.target;
  
  const paymentMethodMap = {
    'credit_card': 'credito',
    'pix': 'pix',
    'boleto': 'boleto'
  };
  
  const formData = {
    endereco_entrega: form.address?.value || 'Endereço Simulado',
    cep: form.cep?.value || '00000-000',
    forma_pagamento: paymentMethodMap[form.payment_method.value],
    parcelas: form.installments?.value || '1',
    frete: 49.90
  };
  
  const res = await fetch('php/checkout.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(formData)
  });
  
  const data = await res.json();
  if (data.success) {
    alert('Pedido realizado! Número: ' + data.pedido_id);
    window.location.href = `pedido-detalhe.html?id=${data.pedido_id}`;
  }
}

document.getElementById('checkout-form').addEventListener('submit', processCheckout);
```

**Funcionalidades:**
- **Toggle de Métodos**: alterna campos visíveis (cartão/pix/boleto)
- **Validação**: verifica campos obrigatórios
- **Submit**: POST para `php/checkout.php` com dados do pedido
- **Redirecionamento**: após sucesso, vai para `pedido-detalhe.html?id=X`

---

### 8. **pedidos.html**

#### 📋 Propósito
Página de histórico de pedidos do cliente autenticado.

#### 🧩 Estrutura Principal

**1. Estados Visuais**
```html
<!-- Carregando -->
<div id="orders-loading" style="text-align: center; padding: 60px 0;">
  <p style="color: var(--muted)">Carregando pedidos...</p>
</div>

<!-- Lista de Pedidos -->
<div id="orders-list" style="display: none;"></div>

<!-- Nenhum Pedido -->
<div id="orders-empty" style="display: none;">
  <p style="color: var(--muted);">Você ainda não fez nenhum pedido.</p>
  <a href="index.html"><button class="cta">Começar a Comprar</button></a>
</div>
```

**Recursos:**
- 3 estados: carregando, lista, vazio
- Transição automática via JavaScript
- Botão CTA quando não há pedidos

---

**2. Scripts Carregados**
```html
<script src="js/header.js"></script>
<script src="js/orders.js"></script>
```

**Funcionalidades (orders.js):**
- **Fetch Pedidos**: GET `php/orders.php` retorna lista de pedidos
- **Renderização**: cria cards para cada pedido com:
  - Número do pedido
  - Data
  - Status (badge colorido)
  - Valor total
  - Botão "Ver Detalhes" → `pedido-detalhe.html?id=X`
- **Autenticação**: redireciona para login se não autenticado

---

**3. Exemplo de Card de Pedido (Renderizado)**
```html
<div style="background: var(--card-bg); padding: 24px; border-radius: 12px; margin-bottom: 16px;">
  <div style="display: flex; justify-content: space-between; align-items: center;">
    <div>
      <h3 style="margin: 0;">Pedido #123</h3>
      <p style="color: var(--muted); margin: 4px 0 0 0;">
        Realizado em 13/11/2025
      </p>
    </div>
    <span style="background: #34d399; color: #000; padding: 6px 12px; border-radius: 6px;">
      Entregue
    </span>
  </div>
  <div style="margin-top: 16px;">
    <strong>Total: R$ 3.548,90</strong>
  </div>
  <a href="pedido-detalhe.html?id=123">
    <button class="cta" style="margin-top: 12px;">Ver Detalhes</button>
  </a>
</div>
```

---

### 9. **pedido-detalhe.html**

#### 📋 Propósito
Página de detalhes de um pedido específico com informações completas de pagamento, entrega e itens.

#### 🧩 Estrutura Principal

**1. Estados de Carregamento**
```html
<!-- Carregando -->
<div id="loading" style="text-align: center; padding: 60px 0;">
  <p style="color: var(--muted)">Carregando detalhes do pedido...</p>
</div>

<!-- Detalhes do Pedido -->
<div id="order-details" style="display: none;"></div>

<!-- Erro -->
<div id="error-message" style="display: none;">
  <p style="color: var(--muted);">Pedido não encontrado.</p>
  <a href="pedidos.html"><button class="cta">Ver Todos os Pedidos</button></a>
</div>
```

---

**2. JavaScript de Carregamento**
```javascript
async function loadOrderDetails() {
  const urlParams = new URLSearchParams(window.location.search);
  const orderId = urlParams.get('id');
  
  if (!orderId) {
    showError();
    return;
  }
  
  const res = await fetch(`php/orders.php?id=${orderId}`);
  const data = await res.json();
  
  if (!data.success || !data.order) {
    showError();
    return;
  }
  
  renderOrderDetails(data.order, data.items);
}
```

**Funcionalidades:**
- Extrai `id` da URL (`?id=123`)
- Faz GET para `php/orders.php?id=X`
- Valida resposta e chama `renderOrderDetails()`

---

**3. Renderização de Detalhes**
```javascript
function renderOrderDetails(order, items) {
  container.innerHTML = `
    <!-- Cabeçalho -->
    <div style="display: flex; justify-content: space-between;">
      <h1>Pedido #${order.pedido_id}</h1>
      ${getStatusBadge(order.status)}
    </div>
    <p>Realizado em ${formatDate(order.data_pedido)}</p>
    
    <!-- Grid de Informações -->
    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 24px;">
      
      <!-- Card: Pagamento -->
      <div style="background: var(--card-bg); padding: 20px; border-radius: 12px;">
        <h3>Forma de Pagamento</h3>
        <p>${getPaymentMethodText(order.forma_pagamento)}</p>
        <p>${order.parcelas}x de R$ ${formatPrice(order.valor_total / order.parcelas)}</p>
      </div>
      
      <!-- Card: Entrega -->
      <div>
        <h3>Entrega</h3>
        <p>${order.status_entrega || 'Aguardando Processamento'}</p>
        ${order.codigo_rastreamento ? `<p>Rastreio: ${order.codigo_rastreamento}</p>` : ''}
      </div>
      
      <!-- Card: Valor Total -->
      <div>
        <h3>Valor Total</h3>
        <p style="font-size: 24px; font-weight: 800;">R$ ${formatPrice(order.valor_total)}</p>
        <p>Subtotal: R$ ${formatPrice(order.valor_total - order.frete)}</p>
        <p>Frete: R$ ${formatPrice(order.frete)}</p>
      </div>
    </div>
    
    <!-- Itens do Pedido -->
    <div style="background: var(--card-bg); padding: 24px; border-radius: 12px;">
      <h2>Itens do Pedido</h2>
      ${items.map(item => `
        <div style="display: flex; gap: 16px; padding: 16px 0; border-bottom: 1px solid var(--border-color);">
          <img src="${item.imagem}" style="width: 80px; height: 80px; border-radius: 8px;" />
          <div style="flex: 1;">
            <h3><a href="produto1.html?id=${item.produto_id}">${item.nome}</a></h3>
            <p>Quantidade: ${item.quantidade}</p>
            <p>Preço unitário: R$ ${formatPrice(item.preco_no_momento)}</p>
          </div>
          <div>
            <p style="font-weight: 700; font-size: 18px;">
              R$ ${formatPrice(item.preco_no_momento * item.quantidade)}
            </p>
          </div>
        </div>
      `).join('')}
    </div>
  `;
}
```

**Recursos:**
- **Grid 3 Colunas**: Pagamento, Entrega, Valor
- **Badges de Status**: coloridos (verde/amarelo/vermelho)
- **Lista de Itens**: com imagem, nome, quantidade, preço
- **Links Clicáveis**: produtos redirecionam para `produto1.html?id=X`

---

**4. Funções Auxiliares**
```javascript
// Formata data para PT-BR
function formatDate(dateStr) {
  const date = new Date(dateStr);
  return date.toLocaleDateString('pt-BR', { 
    day: '2-digit', 
    month: '2-digit', 
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
}

// Formata preço
function formatPrice(price) {
  return parseFloat(price).toFixed(2).replace('.', ',');
}

// Gera badge de status
function getStatusBadge(status) {
  const statusMap = {
    'Aguardando Pagamento': { color: '#fbbf24', text: 'Aguardando Pagamento' },
    'Pagamento Confirmado': { color: '#34d399', text: 'Pagamento Confirmado' },
    'Enviado': { color: '#818cf8', text: 'Enviado' },
    'Entregue': { color: '#10b981', text: 'Entregue' },
    'Cancelado': { color: '#ef4444', text: 'Cancelado' }
  };
  
  const info = statusMap[status] || { color: '#6b7280', text: status };
  return `<span style="background: ${info.color}; color: #000; padding: 4px 12px; border-radius: 6px;">
    ${info.text}
  </span>`;
}

// Converte código de pagamento
function getPaymentMethodText(method) {
  const methodMap = {
    'credito': 'Cartão de Crédito',
    'debito': 'Cartão de Débito',
    'pix': 'PIX',
    'boleto': 'Boleto Bancário'
  };
  return methodMap[method] || method;
}
```

---

### 10. **minha-conta.html**

#### 📋 Propósito
Página de gerenciamento de conta do usuário com atualização de dados pessoais e alteração de senha.

#### 🧩 Estrutura Principal

**1. Cabeçalho da Página**
```html
<div style="margin-bottom: 32px;">
  <h1 style="margin: 0 0 8px 0;">Minha Conta</h1>
  <p style="color: var(--muted); margin: 0;">
    Gerencie suas informações pessoais e segurança.
  </p>
</div>

<!-- Mensagem de Feedback -->
<div id="message-container" style="display: none; padding: 16px; margin-bottom: 24px; border-radius: 8px;">
</div>
```

**Recursos:**
- Título + descrição
- Container de mensagens (sucesso/erro) oculto por padrão

---

**2. Formulário de Dados Pessoais**
```html
<div style="background: var(--card-bg); padding: 32px; border-radius: 12px;">
  <h2>Dados Pessoais</h2>
  <form id="info-form">
    
    <!-- Nome -->
    <div style="margin-bottom: 16px;">
      <label for="nome" style="font-weight: 600;">Nome Completo</label>
      <input type="text" id="nome" name="nome" required />
    </div>
    
    <!-- Email (Readonly) -->
    <div style="margin-bottom: 16px;">
      <label for="email">Email (não pode ser alterado)</label>
      <input type="email" id="email" name="email" readonly 
             style="background: #0f1729; color: var(--muted); cursor: not-allowed;" />
    </div>
    
    <!-- Telefone -->
    <div style="margin-bottom: 16px;">
      <label for="telefone">Telefone</label>
      <input type="tel" id="telefone" name="telefone" />
    </div>
    
    <!-- Endereço -->
    <div style="margin-bottom: 24px;">
      <label for="endereco">Endereço</label>
      <textarea id="endereco" name="endereco" rows="3" required></textarea>
    </div>
    
    <button type="submit" class="cta" style="width: 100%;">
      Salvar Alterações
    </button>
  </form>
</div>
```

**Recursos:**
- Email readonly (não pode ser alterado por segurança)
- Campos: nome, telefone, endereço
- Validação HTML5 (`required`)
- Botão CTA para salvar

---

**3. Formulário de Alteração de Senha**
```html
<div style="background: var(--card-bg); padding: 32px; border-radius: 12px;">
  <h2>Alterar Senha</h2>
  <form id="password-form">
    
    <!-- Senha Antiga -->
    <div style="margin-bottom: 16px;">
      <label for="senha_antiga">Senha Antiga</label>
      <input type="password" id="senha_antiga" name="senha_antiga" required />
    </div>
    
    <!-- Nova Senha -->
    <div style="margin-bottom: 16px;">
      <label for="nova_senha">Nova Senha</label>
      <input type="password" id="nova_senha" name="nova_senha" required />
    </div>
    
    <!-- Confirmar Nova Senha -->
    <div style="margin-bottom: 24px;">
      <label for="confirmar_nova_senha">Confirmar Nova Senha</label>
      <input type="password" id="confirmar_nova_senha" name="confirmar_nova_senha" required />
    </div>
    
    <button type="submit" class="cta" style="width: 100%;">
      Alterar Senha
    </button>
  </form>
</div>
```

**Recursos:**
- 3 campos: senha antiga, nova, confirmação
- Validação de coincidência no JavaScript
- Botão CTA para alterar

---

**4. JavaScript de Gerenciamento**
```javascript
// Exibe mensagens de feedback
function showMessage(message, isError = false) {
  messageContainer.textContent = message;
  messageContainer.style.backgroundColor = isError ? '#7f1d1d' : '#064e3b';
  messageContainer.style.color = isError ? '#ef4444' : '#10b981';
  messageContainer.style.display = 'block';
  setTimeout(() => {
    messageContainer.style.display = 'none';
  }, 3000);
}

// Carrega dados do usuário
async function loadUserData() {
  const res = await fetch('php/me.php');
  const data = await res.json();
  
  if (data.logged_in && data.user) {
    document.getElementById('nome').value = data.user.nome || '';
    document.getElementById('email').value = data.user.email || '';
    // Telefone e endereço viriam de outro endpoint
  } else {
    window.location.href = 'login.html';
  }
}

// Atualiza informações
infoForm.addEventListener('submit', async (e) => {
  e.preventDefault();
  const payload = {
    action: 'update_info',
    nome: document.getElementById('nome').value,
    telefone: document.getElementById('telefone').value,
    endereco: document.getElementById('endereco').value
  };
  
  const res = await fetch('php/minha_conta.php', {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  });
  
  const data = await res.json();
  if (data.success) {
    showMessage('Dados atualizados com sucesso!');
  } else {
    showMessage(data.error, true);
  }
});

// Altera senha
passwordForm.addEventListener('submit', async (e) => {
  e.preventDefault();
  const novaSenha = document.getElementById('nova_senha').value;
  const confirmarSenha = document.getElementById('confirmar_nova_senha').value;
  
  if (novaSenha !== confirmarSenha) {
    showMessage('As novas senhas não coincidem.', true);
    return;
  }
  
  const payload = {
    action: 'update_password',
    senha_antiga: document.getElementById('senha_antiga').value,
    nova_senha: novaSenha
  };
  
  const res = await fetch('php/minha_conta.php', {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  });
  
  const data = await res.json();
  if (data.success) {
    showMessage('Senha alterada com sucesso!');
    passwordForm.reset();
  } else {
    showMessage(data.error, true);
  }
});

document.addEventListener('DOMContentLoaded', loadUserData);
```

**Funcionalidades:**
- **Carregar Dados**: GET `php/me.php` preenche formulário
- **Atualizar Info**: PUT `php/minha_conta.php` com `action: 'update_info'`
- **Alterar Senha**: PUT `php/minha_conta.php` com `action: 'update_password'`
- **Validação**: verifica se senhas coincidem antes de enviar
- **Feedback**: mensagens verde (sucesso) ou vermelho (erro)
- **Auto-hide**: mensagens desaparecem após 3 segundos

---

## �🔗 Componentes Compartilhados

Todos os arquivos do Lote 1 compartilham os seguintes elementos:

### 1. **Header (Cabeçalho)**
- Logo clicável → `index.html`
- Barra de busca global
- Ações do usuário (Carrinho, Pedidos, Admin, Login, Perfil)
- Navegação por categorias com `data-category`

### 2. **Footer (Rodapé)**
- Nome da marca + Copyright
- Links úteis (Registrar produto, Admin)
- Informações de pagamento

### 3. **Meta Tags**
```html
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<meta name="description" content="..." />
```

### 4. **Fontes e Estilos**
```html
<link href="https://fonts.googleapis.com/css2?family=Inter:..." rel="stylesheet">
<link rel="stylesheet" href="style.css">
```

### 5. **Scripts Base**
- `js/header.js`: gerencia navegação, busca e autenticação
- Scripts específicos por página (auth.js, register.js, search-results.js, etc.)

---

## 🎨 Padrões de Design

### Cores CSS (Variáveis)
```css
--card-bg: #05203b (fundo de cards)
--muted: rgba(255,255,255,0.6) (texto secundário)
--primary: #00ff88 (cor de destaque)
--border-color: rgba(255,255,255,0.1)
--brand: #00aaff (cor da marca)
```

### Classes Comuns
- `.hero-card`: card de destaque com padding e border-radius
- `.cta`: botão de call-to-action (verde/destaque)
- `.buy`: botão de adicionar ao carrinho
- `.icon-btn`: botão com ícone (transparente)
- `.card`: card de produto
- `.price`: preço atual (destaque)
- `.old`: preço antigo (riscado)
- `.filter`: seção de filtro na sidebar

### Layout Responsivo
- Grid de produtos: `display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));`
- Flexbox para header/footer: `display: flex; justify-content: space-between;`
- Container centralizado: `max-width: 1200px; margin: 0 auto;`

---

## 📊 Estatísticas do Lote 1

| Métrica | Valor |
|---------|-------|
| **Arquivos documentados** | 5 |
| **Total de linhas HTML** | ~800 |
| **Componentes únicos** | 15+ |
| **Scripts JS carregados** | 6 |
| **Endpoints PHP usados** | 5 |
| **Formulários** | 3 (login, registro, avaliação) |
| **Atributos ARIA** | 12+ |

---

## 📊 Estatísticas do Lote 2

| Métrica | Valor |
|---------|-------|
| **Arquivos documentados** | 5 |
| **Total de linhas HTML** | ~700 |
| **Componentes únicos** | 12+ |
| **Scripts JS carregados** | 4 |
| **Endpoints PHP usados** | 5 |
| **Formulários** | 3 (checkout, info, senha) |
| **Estados visuais** | 9 (loading, empty, content) |

---

## � Arquivos Documentados - Lote 3

### 11. **admin-produtos.html**

#### 📋 Propósito
Painel administrativo para gerenciamento completo de produtos (CRUD: Create, Read, Update, Delete).

#### 🧩 Funcionalidades Principais

**1. Controle de Acesso Admin**
- Verifica `is_admin` via `php/auth.php`
- Bloqueia não-administradores
- Exibe mensagem de acesso negado

**2. Formulário de Adição de Produto**
- 11 campos: nome, categoria, fabricante, descrição, especificações, SKU, preço, estoque, fornecedor, imagem
- Validação HTML5 completa
- Dropdown de categorias (futuro: dinâmico via API)

**3. Lista de Produtos**
- Renderização dinâmica via JavaScript
- Cards com imagem, nome, preço, estoque
- Botões: Editar e Excluir
- Função `safeText()` previne XSS

**4. Modal de Edição**
- Overlay fullscreen com z-index 1000
- Carrega dados do produto via `php/product.php?id=X`
- Formulário pré-preenchido
- Salva via PUT para `php/admin_products.php`

**5. Exclusão de Produto**
- Confirmação via `confirm()`
- DELETE para `php/admin_products.php`
- Recarrega lista após sucesso

---

### 12. **esqueci-senha.html**

#### 📋 Propósito
Solicitação de recuperação de senha via token único.

#### 🧩 Funcionalidades Principais

**1. Formulário Simples**
- Campo único: email
- Validação `type="email"`

**2. Segurança**
- Mensagem genérica (evita enumeração de emails)
- Token único gerado pelo backend

**3. Demonstração**
- Exibe link direto com token (apenas para teste)
- Em produção: envio por email real

**4. Endpoint**
- POST para `php/solicitar_reset.php`
- Retorna `token_demo` para ambiente de desenvolvimento

---

### 13. **resetar-senha.html**

#### 📋 Propósito
Redefinição de senha usando token de validação.

#### 🧩 Funcionalidades Principais

**1. Validação de Token**
- Extrai `token` e `email` da URL
- Bloqueia acesso se parâmetros ausentes

**2. Formulário de Nova Senha**
- 2 campos: nova_senha, confirmar_nova_senha
- Validação de coincidência no JavaScript

**3. Fluxo Completo**
```
esqueci-senha.html → email → token gerado → 
resetar-senha.html?token=X&email=Y → nova senha → 
php/confirmar_reset.php → login.html
```

**4. Endpoint**
- POST para `php/confirmar_reset.php`
- Valida token, atualiza senha, invalida token

---

### 14. **diagnostico-produtos.html**

#### 📋 Propósito
Ferramenta de diagnóstico e testes para desenvolvimento.

#### 🧩 Funcionalidades Principais

**1. Verificação de Autenticação**
- Testa `php/auth.php`
- Exibe JSON completo da resposta
- Badge: OK/ERRO

**2. Estrutura do Banco**
- Conta categorias, fornecedores, produtos
- Cards com valores em tempo real

**3. Teste de Criação**
- Cria produto de teste com timestamp único
- Exibe logs detalhados do processo
- Valida resposta do endpoint

**4. Teste de Validação**
- Envia dados inválidos propositalmente
- Verifica se endpoint retorna 400
- Confirma validação funcionando

**5. Sistema de Logs**
- Array persistente de logs
- Timestamp automático
- Cores por tipo (success/error/info)
- Botão para limpar

---

### 15. **test-php.html**

#### 📋 Propósito
Arquivo vazio (placeholder) para testes ad-hoc.

#### 🧩 Uso Sugerido
- Sandbox para experimentos
- Testes de endpoints isolados
- Interfaces temporárias de desenvolvimento

---

## 📊 Estatísticas do Lote 3

| Métrica | Valor |
|---------|-------|
| **Arquivos documentados** | 5 |
| **Total de linhas HTML** | ~600 (+ 1 vazio) |
| **Componentes únicos** | 8+ |
| **Scripts JS inline** | 4 arquivos |
| **Endpoints PHP usados** | 6 |
| **Formulários** | 4 (produto, edição, reset, nova senha) |
| **Modais** | 1 (edição de produto) |

---

## 🔜 Fluxos de Navegação

### 📦 Fluxo de Compra Completo
```
1. index.html (navegação/busca)
   ↓ [Clique em produto]
2. produto1.html?id=X (detalhes + avaliações)
   ↓ [Adicionar ao carrinho]
3. carrinho.html (revisão de itens)
   ↓ [Finalizar Compra]
4. checkout.html (pagamento + endereço)
   ↓ [Confirmar Pedido]
5. pedido-detalhe.html?id=Y (confirmação)
   ↓ [Ver Todos os Pedidos]
6. pedidos.html (histórico)
```

### 🔐 Fluxo de Autenticação
```
1. login.html
   ├─ [Esqueceu senha?] → esqueci-senha.html
   │                       ↓ [Email enviado]
   │                     resetar-senha.html?token=X
   │                       ↓ [Senha alterada]
   │                     login.html
   └─ [Não tem conta?] → registro_cliente.html
                           ↓ [Conta criada]
                         login.html
```

### ⚙️ Fluxo Administrativo
```
1. login.html (como admin)
   ↓ [Acesso autorizado]
2. admin-produtos.html
   ├─ Adicionar Produto → POST /admin_products.php
   ├─ Editar Produto → PUT /admin_products.php
   └─ Excluir Produto → DELETE /admin_products.php
```

### 🛠️ Fluxo de Diagnóstico
```
1. diagnostico-produtos.html
   ├─ Verificar Auth → GET /auth.php
   ├─ Verificar DB → GET /products.php
   ├─ Testar Criação → POST /admin_products.php
   └─ Testar Validação → POST /admin_products.php (dados inválidos)
```

---

## 📊 Estatísticas Finais

### Resumo Geral dos 15 Arquivos

| Categoria | Total |
|-----------|-------|
| **Páginas HTML** | 15 (1 vazia) |
| **Linhas de HTML** | ~2.100 |
| **Formulários** | 10 |
| **Scripts JavaScript** | 12 (8 externos + 4 inline) |
| **Endpoints PHP** | 16 únicos |
| **Componentes visuais** | 35+ |
| **Modais/Overlays** | 1 |
| **Estados de UI** | 15+ (loading/empty/content) |

### Distribuição por Tipo

**Lote 1 - Navegação (33%)**
- Catálogo, busca, autenticação, produtos

**Lote 2 - E-commerce (33%)**
- Carrinho, checkout, pedidos, conta

**Lote 3 - Admin/Utilitários (33%)**
- Gestão de produtos, recuperação de senha, diagnósticos

### Endpoints PHP Utilizados

| Endpoint | Métodos | Páginas que usam |
|----------|---------|------------------|
| `php/auth.php` | GET | Todas (via header.js) |
| `php/products.php` | GET | index.html, busca.html, admin-produtos.html |
| `php/product.php` | GET | produto1.html, admin-produtos.html |
| `php/cart.php` | GET, POST, PUT, DELETE | carrinho.html, checkout.html |
| `php/checkout.php` | POST | checkout.html |
| `php/orders.php` | GET | pedidos.html, pedido-detalhe.html |
| `php/reviews.php` | POST | produto1.html |
| `php/admin_products.php` | POST, PUT, DELETE | admin-produtos.html, diagnostico-produtos.html |
| `php/minha_conta.php` | PUT | minha-conta.html |
| `php/solicitar_reset.php` | POST | esqueci-senha.html |
| `php/confirmar_reset.php` | POST | resetar-senha.html |
| `php/me.php` | GET | minha-conta.html |
| `php/register.php` | POST | registro_cliente.html (via register.js) |
| `php/login.php` | POST | login.html (via auth.js) |

### Scripts JavaScript Externos

| Script | Páginas | Responsabilidades |
|--------|---------|-------------------|
| `js/header.js` | 14 páginas | Navegação, busca, auth |
| `js/cart.js` | carrinho.html | Gerenciar carrinho |
| `js/orders.js` | pedidos.html | Listar pedidos |
| `js/search-filters.js` | index.html, busca.html | Filtros e ordenação |
| `js/search-results.js` | busca.html | Resultados de busca |
| `js/auth.js` | login.html | Autenticação |
| `js/register.js` | registro_cliente.html | Cadastro |

---

## 📝 Conclusão

### Resumo do Projeto

Este guia documentou **15 páginas HTML** do sistema e-commerce **CiberTech**, especializado em hardware e periféricos. O projeto demonstra:

✅ **Arquitetura Moderna**
- HTML5 semântico
- Fetch API para comunicação assíncrona
- Componentização e reutilização

✅ **Acessibilidade (ARIA)**
- Atributos `role`, `aria-label`, `aria-live`
- Navegação por teclado
- Leitores de tela

✅ **Segurança**
- Função `safeText()` previne XSS
- Validação de tokens para reset de senha
- Controle de acesso admin
- Mensagens genéricas (evita enumeração)

✅ **Experiência do Usuário**
- Estados visuais claros (loading/empty/content)
- Feedback imediato (mensagens de sucesso/erro)
- Modais e overlays
- Responsividade mobile-first

✅ **Padrões de Desenvolvimento**
- Estrutura consistente entre páginas
- Nomenclatura clara de IDs e classes
- Comentários explicativos
- Separação de preocupações (HTML/CSS/JS)

### Melhorias Futuras Sugeridas

1. **Categorias Dinâmicas**: popular dropdown via `php/categories.php`
2. **Upload de Imagens**: substituir URLs por upload local
3. **Confirmações Não-Bloqueantes**: substituir `alert()` e `confirm()` por modais
4. **Validação de CPF**: máscara e validação no frontend
5. **Máscaras de Input**: CEP, telefone, preço
6. **Paginação**: para listas longas (produtos, pedidos)
7. **Busca Avançada**: filtros combinados, autocomplete
8. **PWA**: service workers, cache offline
9. **Testes Automatizados**: Jest, Cypress
10. **Internacionalização**: suporte a múltiplos idiomas

### Padrões Identificados

**Convenções de Nomenclatura:**
- IDs descritivos: `#cart-items-body`, `#message-container`
- Classes reutilizáveis: `.cta`, `.buy`, `.card`, `.hero-card`
- Prefixos por funcionalidade: `edit-*`, `cart-*`, `auth-*`

**Estrutura de Fetch:**
```javascript
try {
  const res = await fetch('endpoint');
  const data = await res.json();
  if (!data.success) throw new Error(data.error);
  // Processar sucesso
} catch (err) {
  // Tratar erro
}
```

**Estados de UI:**
```javascript
// 1. Loading
<div id="loading">Carregando...</div>

// 2. Empty
<div id="empty">Nenhum item encontrado</div>

// 3. Content
<div id="content"><!-- Dados renderizados --></div>
```

---

### Agradecimentos

Documentação criada por **GitHub Copilot** em **Novembro de 2025**.

Este guia serve como referência completa para desenvolvedores que trabalham ou darão manutenção no projeto CiberTech.

---

**📅 Data:** 13 de Novembro de 2025  
**🔄 Status:** COMPLETO (3/3 Lotes)  
**📄 Total de Páginas:** 15  
**📝 Total de Linhas:** ~2.100  
**👨‍💻 Documentado por:** GitHub Copilot

---

## �🔜 Próximos Lotes

**Lote 3 (Último):**
- `admin-produtos.html` - Painel administrativo
- `esqueci-senha.html` - Recuperação de senha
- `resetar-senha.html` - Redefinição de senha
- `diagnostico-produtos.html` - Diagnóstico de produtos
- `test-php.html` - Testes de integração PHP

---

## 📝 Conclusão Parcial (Lotes 1 + 2)

Até agora foram documentadas **10 páginas HTML** do sistema e-commerce CiberTech:

### 🎯 Lote 1 - Navegação e Catálogo (5 páginas)
1. **index.html**: Homepage com produtos, busca e filtros
2. **login.html**: Autenticação de usuários
3. **registro_cliente.html**: Cadastro de clientes
4. **produto1.html**: Detalhes de produto com avaliações
5. **busca.html**: Resultados de busca avançada

### 🛒 Lote 2 - E-commerce e Conta (5 páginas)
6. **carrinho.html**: Carrinho de compras com tabela de itens
7. **checkout.html**: Finalização de compra com métodos de pagamento
8. **pedidos.html**: Histórico de pedidos do cliente
9. **pedido-detalhe.html**: Detalhes completos de um pedido
10. **minha-conta.html**: Gerenciamento de conta (dados + senha)

### 🔑 Padrões Identificados

**Autenticação e Sessão:**
- `js/header.js` gerencia estado de login
- Redirecionamento automático para `login.html` quando não autenticado
- Endpoint `php/me.php` retorna dados do usuário logado

**Fluxo de Compra:**
```
index.html → produto1.html → carrinho.html → checkout.html → pedido-detalhe.html
```

**Estados Visuais Consistentes:**
- **Loading**: "Carregando..." (spinner/placeholder)
- **Empty**: "Nenhum item encontrado" + CTA
- **Content**: Dados renderizados dinamicamente

**Fetch API Pattern:**
```javascript
// GET: Listar dados
const res = await fetch('php/endpoint.php');
const data = await res.json();

// POST: Criar recurso
const res = await fetch('php/endpoint.php', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify(payload)
});

// PUT: Atualizar recurso
const res = await fetch('php/endpoint.php', {
  method: 'PUT',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify(payload)
});

// DELETE: Remover recurso
const res = await fetch('php/endpoint.php', {
  method: 'DELETE',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({ id })
});
```

Aguardando continuação com **Lote 3** para documentar as páginas administrativas e de recuperação de senha.

---

**📅 Última Atualização:** Novembro 2025  
**🔄 Status:** Lote 2/3 Concluído  
**👨‍💻 Documentado por:** GitHub Copilot
