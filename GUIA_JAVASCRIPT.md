# 📘 Guia Completo de JavaScript - CiberTech

## Índice
1. [Visão Geral](#visão-geral)
2. [Header.js - Sistema de Navegação](#headerjs)
3. [Search-Results.js - Sistema de Busca](#search-resultsjs)
4. [Search-Filters.js - Filtros de Produtos](#search-filtersjs)
5. [Admin-Produtos.html - Gerenciamento de Produtos](#admin-produtos)
6. [Diagnóstico-Produtos.html - Sistema de Testes](#diagnóstico)
7. [Padrões e Boas Práticas](#padrões)

---

## Visão Geral

O projeto utiliza **JavaScript Vanilla** (puro, sem frameworks) com padrões modernos:
- ✅ **Async/Await** para operações assíncronas
- ✅ **Fetch API** para requisições HTTP
- ✅ **ES6+** (arrow functions, template literals, destructuring)
- ✅ **Event Delegation** para performance
- ✅ **Session/Local Storage** para cache
- ✅ **Componentes reutilizáveis**

---

## Header.js

**Localização:** `js/header.js`  
**Função:** Sistema de busca e navegação por categorias

### 🔍 Código Explicado

```javascript
document.addEventListener('DOMContentLoaded', () => {
```
**O que faz:** Aguarda o DOM ser completamente carregado antes de executar o código.  
**Por quê:** Garante que todos os elementos HTML existam antes de tentar manipulá-los.

---

### Navegação por Categorias

```javascript
const nav = document.querySelector('header nav');
if (nav) {
  nav.addEventListener('click', (e) => {
    e.preventDefault();
```

**Explicação:**
1. `querySelector('header nav')` - Busca o elemento `<nav>` dentro do `<header>`
2. `addEventListener('click', ...)` - Escuta TODOS os cliques dentro do nav (Event Delegation)
3. `e.preventDefault()` - Impede o comportamento padrão do link (`<a href="#">`)

**Vantagem:** Em vez de adicionar listeners individuais a cada link, usa-se apenas um listener no elemento pai.

---

```javascript
const link = e.target.closest('a');
if (!link) return;
```

**Explicação:**
1. `e.target` - Elemento que foi clicado
2. `closest('a')` - Busca o elemento `<a>` mais próximo (suporta cliques em elementos filhos)
3. `if (!link) return` - Se não clicou em um link, para a execução

**Exemplo prático:**
```html
<a href="#">
  <span>Processadores</span> <!-- Clicar aqui também funciona -->
</a>
```

---

```javascript
const text = link.textContent.trim().toLowerCase();
const categoryMap = {
  'placas-mãe': 7,
  'processadores': 6,
  'placas de vídeo': 5,
  'memória ram': 8,
  'ssd / hd': 13,
  // ...
};
```

**Explicação:**
1. `textContent.trim()` - Pega o texto do link e remove espaços
2. `toLowerCase()` - Converte para minúsculas (case-insensitive)
3. `categoryMap` - Mapeia nome da categoria para ID do banco de dados

**Por quê usar IDs?** O banco de dados usa IDs numéricos para categorias (mais eficiente que texto).

---

```javascript
const categoryId = categoryMap[text];
if (categoryId) {
  window.location.href = `busca.html?categoria=${categoryId}`;
}
```

**Explicação:**
1. Busca o ID correspondente ao texto clicado
2. Se existir, redireciona para `busca.html` com parâmetro `?categoria=ID`
3. A página `busca.html` vai ler esse parâmetro e filtrar os produtos

**Exemplo de URL gerada:** `busca.html?categoria=6` (Processadores)

---

### Sistema de Busca

```javascript
const searchContainer = document.querySelector('.search');
const searchInput = searchContainer?.querySelector('input[type="search"]');
const searchButton = searchContainer?.querySelector('button');
```

**Explicação:**
1. `querySelector('.search')` - Busca o container de busca
2. `?.` (Optional Chaining) - Se o elemento não existir, retorna `undefined` em vez de erro
3. Busca input e botão dentro do container

---

```javascript
function performSearch() {
  const query = searchInput.value.trim();
  if (query.length === 0) {
    alert('Digite algo para buscar');
    return;
  }
  window.location.href = `busca.html?q=${encodeURIComponent(query)}`;
}
```

**Explicação detalhada:**

1. **`searchInput.value.trim()`**
   - Pega o texto digitado
   - Remove espaços no início/fim

2. **`if (query.length === 0)`**
   - Valida se há algo digitado
   - Mostra alerta se vazio

3. **`encodeURIComponent(query)`**
   - Codifica caracteres especiais para URL
   - Exemplo: "RTX 4090" → "RTX%204090"

4. **`window.location.href`**
   - Redireciona para página de busca
   - Exemplo: `busca.html?q=RTX%204090`

---

```javascript
searchButton?.addEventListener('click', performSearch);

searchInput?.addEventListener('keypress', (e) => {
  if (e.key === 'Enter') {
    performSearch();
  }
});
```

**Explicação:**
1. **Primeiro listener:** Busca ao clicar no botão
2. **Segundo listener:** Busca ao pressionar Enter no input
3. `e.key === 'Enter'` - Detecta tecla Enter

**UX (Experiência do Usuário):** Usuário pode buscar de duas formas (clique OU Enter).

---

## Search-Results.js

**Localização:** `js/search-results.js`  
**Função:** Exibe resultados de busca e gerencia paginação

### 📊 Código Explicado

```javascript
const urlParams = new URLSearchParams(window.location.search);
const query = urlParams.get('q') || '';
const categoria = urlParams.get('categoria') || '';
```

**Explicação:**
1. `URLSearchParams` - API nativa para ler parâmetros da URL
2. `window.location.search` - Retorna `?q=texto&categoria=5`
3. `get('q')` - Extrai o valor do parâmetro
4. `|| ''` - Se não existir, usa string vazia

**Exemplo:**
- URL: `busca.html?q=RTX&categoria=5`
- `query` = "RTX"
- `categoria` = "5"

---

### Construção da URL da API

```javascript
let apiUrl = 'php/products.php?limit=12';

if (query) {
  apiUrl += `&search=${encodeURIComponent(query)}`;
}
if (categoria) {
  apiUrl += `&categoria=${encodeURIComponent(categoria)}`;
}
```

**Explicação:**
1. URL base com limite de 12 produtos por página
2. Adiciona parâmetros condicionalmente
3. `&` - Operador de concatenação de parâmetros

**URLs geradas:**
- Busca simples: `php/products.php?limit=12&search=RTX`
- Por categoria: `php/products.php?limit=12&categoria=5`
- Busca + categoria: `php/products.php?limit=12&search=RTX&categoria=5`

---

### Fetch Assíncrono

```javascript
async function loadResults() {
  try {
    const res = await fetch(apiUrl);
    const data = await res.json();
```

**Explicação:**

1. **`async function`**
   - Permite usar `await` dentro da função
   - Retorna uma Promise automaticamente

2. **`await fetch(apiUrl)`**
   - Faz requisição HTTP GET
   - Aguarda a resposta do servidor
   - `res` contém status, headers, body

3. **`await res.json()`**
   - Converte resposta de JSON para objeto JavaScript
   - Aguarda conversão assíncrona

**Exemplo de resposta:**
```json
{
  "success": true,
  "products": [...],
  "total": 45,
  "page": 1,
  "totalPages": 4
}
```

---

### Tratamento de Erros

```javascript
if (!data.success || !data.products) {
  container.innerHTML = '<p style="text-align:center;">Nenhum resultado encontrado.</p>';
  return;
}
```

**Explicação:**
1. Verifica se a requisição teve sucesso
2. Verifica se há produtos na resposta
3. Se falhar, mostra mensagem e para execução (`return`)

---

### Renderização Dinâmica

```javascript
container.innerHTML = data.products.map(p => `
  <a href="produto.html?id=${p.produto_id}" class="product-card">
    <img src="${p.imagem || 'https://via.placeholder.com/300'}" alt="${p.nome}">
    <div class="product-info">
      <h3>${p.nome}</h3>
      <p class="price">R$ ${parseFloat(p.preco).toFixed(2).replace('.', ',')}</p>
      <p class="estoque">Em estoque: ${p.estoque} unidades</p>
    </div>
  </a>
`).join('');
```

**Explicação detalhada:**

1. **`data.products.map(p => ...)`**
   - Itera sobre cada produto
   - `p` = produto atual
   - Retorna um array de strings HTML

2. **Template Literals** (\`...\`)
   - Permite variáveis com `${}`
   - Suporta múltiplas linhas

3. **`p.imagem || 'https://...'`**
   - Se não houver imagem, usa placeholder
   - Operador OR (`||`) retorna primeiro valor "truthy"

4. **`parseFloat(p.preco).toFixed(2)`**
   - Converte para número decimal
   - Fixa 2 casas decimais (8999.9 → 8999.90)

5. **`.replace('.', ',')`**
   - Troca ponto por vírgula (padrão BR)
   - 8999.90 → 8999,90

6. **`.join('')`**
   - Une o array em uma única string
   - Remove as vírgulas entre elementos

---

### Paginação

```javascript
function renderPagination(currentPage, totalPages) {
  const pagination = document.getElementById('pagination');
  if (totalPages <= 1) {
    pagination.innerHTML = '';
    return;
  }
```

**Explicação:**
1. Se houver apenas 1 página, não mostra paginação
2. `innerHTML = ''` - Limpa o conteúdo

---

```javascript
let buttons = '';
for (let i = 1; i <= totalPages; i++) {
  const isActive = i === currentPage ? 'class="active"' : '';
  buttons += `<button ${isActive} onclick="goToPage(${i})">${i}</button>`;
}
```

**Explicação:**

1. **Loop de 1 até totalPages**
   - Cria um botão para cada página

2. **Operador ternário** (`? :`)
   - Se `i === currentPage`: adiciona classe "active"
   - Senão: string vazia

3. **`onclick="goToPage(${i})"`**
   - Atributo inline que chama função global
   - Passa número da página como argumento

**HTML gerado:**
```html
<button onclick="goToPage(1)">1</button>
<button class="active" onclick="goToPage(2)">2</button>
<button onclick="goToPage(3)">3</button>
```

---

```javascript
function goToPage(page) {
  urlParams.set('page', page);
  window.location.search = urlParams.toString();
}
```

**Explicação:**
1. `urlParams.set('page', page)` - Adiciona/atualiza parâmetro `page`
2. `toString()` - Converte para string de query (`?q=RTX&page=2`)
3. `window.location.search = ...` - Atualiza URL e recarrega página

**Fluxo:**
- Usuário clica em "Página 2"
- URL muda de `busca.html?q=RTX` para `busca.html?q=RTX&page=2`
- Página recarrega com novos resultados

---

## Search-Filters.js

**Localização:** `js/search-filters.js`  
**Função:** Filtros de categoria, preço e ordenação

### 🎛️ Código Explicado

```javascript
const filters = {
  categoria: urlParams.get('categoria') || '',
  minPrice: urlParams.get('minPrice') || '',
  maxPrice: urlParams.get('maxPrice') || '',
  sort: urlParams.get('sort') || 'relevancia'
};
```

**Explicação:**
1. Cria objeto com estado dos filtros
2. Lê valores da URL ou usa padrões
3. Centraliza estado da aplicação

---

### Checkbox de Categorias

```javascript
document.querySelectorAll('.category-filter').forEach(checkbox => {
  const categoryId = checkbox.dataset.category;
  
  if (filters.categoria === categoryId) {
    checkbox.checked = true;
  }
```

**Explicação:**

1. **`querySelectorAll('.category-filter')`**
   - Seleciona TODOS os checkboxes de categoria
   - Retorna NodeList (similar a array)

2. **`.forEach(checkbox => ...)`**
   - Itera sobre cada checkbox

3. **`checkbox.dataset.category`**
   - Lê atributo `data-category` do HTML
   - HTML: `<input data-category="5">`
   - JS: `dataset.category` = "5"

4. **Marca checkbox se estiver ativo**
   - `checked = true` - Marca visualmente

---

```javascript
checkbox.addEventListener('change', () => {
  if (checkbox.checked) {
    urlParams.set('categoria', categoryId);
  } else {
    urlParams.delete('categoria');
  }
  urlParams.delete('page');
  window.location.search = urlParams.toString();
});
```

**Explicação:**

1. **Event: `change`**
   - Dispara quando checkbox muda de estado

2. **Se marcado:** Adiciona categoria à URL
3. **Se desmarcado:** Remove categoria da URL

4. **`urlParams.delete('page')`**
   - Remove paginação ao mudar filtro
   - Sempre volta para página 1

5. **Atualiza URL**
   - Recarrega página com novos filtros

---

### Filtro de Preço

```javascript
document.getElementById('apply-price').addEventListener('click', () => {
  const min = document.getElementById('min-price').value;
  const max = document.getElementById('max-price').value;
  
  if (min) urlParams.set('minPrice', min);
  else urlParams.delete('minPrice');
  
  if (max) urlParams.set('maxPrice', max);
  else urlParams.delete('maxPrice');
```

**Explicação:**

1. Lê valores dos inputs
2. **Se tiver valor:** Adiciona à URL
3. **Se vazio:** Remove da URL
4. Permite filtrar por:
   - Apenas mínimo
   - Apenas máximo
   - Ambos
   - Nenhum

---

### Ordenação

```javascript
document.getElementById('sort-select').addEventListener('change', (e) => {
  const sortValue = e.target.value;
  urlParams.set('sort', sortValue);
  urlParams.delete('page');
  window.location.search = urlParams.toString();
});
```

**Explicação:**
1. `e.target.value` - Valor da opção selecionada no `<select>`
2. Atualiza parâmetro `sort` na URL
3. Volta para página 1
4. Recarrega com nova ordenação

**Opções de ordenação:**
- `relevancia` - Padrão
- `preco_asc` - Menor preço
- `preco_desc` - Maior preço
- `nome_asc` - A-Z
- `nome_desc` - Z-A

---

## Admin-Produtos

**Localização:** `admin-produtos.html` (JavaScript inline)  
**Função:** CRUD completo de produtos

### 🔐 Verificação de Acesso

```javascript
async function checkAdminAccess() {
  try {
    const res = await fetch('php/auth.php');
    const data = await res.json();
    
    if (!data.success || !data.user || !data.user.is_admin) {
      document.getElementById('access-denied').style.display = 'block';
      return false;
    }
```

**Explicação:**

1. **Fetch assíncrono** para `php/auth.php`
2. **Resposta esperada:**
   ```json
   {
     "success": true,
     "user": {
       "is_admin": true,
       "nome": "Admin"
     }
   }
   ```

3. **Validação em cadeia:**
   - `data.success` - Requisição funcionou?
   - `data.user` - Usuário existe?
   - `data.user.is_admin` - É administrador?

4. **Se falhar:** Mostra mensagem de acesso negado

---

### Criar Produto (POST)

```javascript
async function handleSubmit(e) {
  e.preventDefault();
  
  const form = e.target;
  const submitBtn = form.querySelector('button[type="submit"]');
  
  submitBtn.disabled = true;
  submitBtn.textContent = 'Salvando...';
```

**Explicação:**

1. **`e.preventDefault()`**
   - Impede envio padrão do formulário
   - Evita reload da página

2. **`e.target`**
   - Referência ao `<form>` que disparou o evento

3. **Desabilita botão**
   - Previne duplo clique
   - Mostra feedback visual ("Salvando...")

---

```javascript
const formData = {
  nome: form.nome.value,
  categoria_id: parseInt(form.categoria_id.value),
  fabricante: form.fabricante.value,
  descricao: form.descricao.value,
  especificacoes: form.especificacoes.value,
  sku: form.sku.value,
  preco: parseFloat(form.preco.value),
  estoque: parseInt(form.estoque.value) || 0,
  fornecedor_id: parseInt(form.fornecedor_id.value) || 1,
  imagem: form.imagem.value
};
```

**Explicação:**

1. **`form.nome.value`**
   - Acessa input pelo atributo `name`
   - HTML: `<input name="nome">`

2. **Conversões de tipo:**
   - `parseInt()` - String → Inteiro
   - `parseFloat()` - String → Decimal
   - `|| 0` - Se NaN, usa valor padrão

3. **Por quê converter?**
   - Inputs retornam sempre strings
   - Backend espera números
   - JSON precisa de tipos corretos

---

```javascript
const res = await fetch('php/admin_products.php', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify(formData)
});
```

**Explicação:**

1. **`method: 'POST'`**
   - Define método HTTP
   - POST = criar recurso

2. **`headers: { 'Content-Type': 'application/json' }`**
   - Informa ao servidor que está enviando JSON
   - Backend usa isso para decodificar

3. **`JSON.stringify(formData)`**
   - Converte objeto JS para string JSON
   - Objeto: `{nome: "RTX"}`
   - JSON: `'{"nome":"RTX"}'`

---

```javascript
const data = await res.json();

if (!data.success) {
  alert(data.error || 'Erro ao adicionar produto');
  return;
}

alert('Produto adicionado com sucesso!');
form.reset();
loadProducts();
```

**Explicação:**

1. **Converte resposta para objeto**
2. **Verifica sucesso**
   - Se falhar: mostra erro
   - `return` para execução

3. **Se sucesso:**
   - Mostra confirmação
   - `form.reset()` - Limpa formulário
   - `loadProducts()` - Recarrega lista

---

### Editar Produto (PUT)

```javascript
async function openEditModal(produtoId) {
  try {
    const res = await fetch(`php/product.php?id=${produtoId}`);
    const data = await res.json();
    
    if (!data.success || !data.product) {
      alert('Erro ao carregar produto');
      return;
    }
    
    const p = data.product;
```

**Explicação:**

1. **Busca dados do produto específico**
   - Endpoint: `php/product.php?id=123`

2. **Valida resposta**
   - Verifica `success` e `product`

3. **`const p = data.product`**
   - Alias para facilitar acesso

---

```javascript
document.getElementById('edit-produto-id').value = p.produto_id;
document.getElementById('edit-nome').value = p.nome;
document.getElementById('edit-categoria').value = p.categoria_id;
document.getElementById('edit-fabricante').value = p.fabricante || '';
```

**Explicação:**

1. **Preenche cada campo do formulário**
2. **`p.fabricante || ''`**
   - Se valor for `null`, usa string vazia
   - Evita mostrar "null" no input

---

```javascript
document.getElementById('edit-modal').style.display = 'block';
document.body.style.overflow = 'hidden';
```

**Explicação:**

1. **Mostra modal**
   - Muda display de `none` para `block`

2. **Bloqueia scroll do body**
   - `overflow: hidden` - Previne scroll da página de fundo
   - Melhora UX do modal

---

```javascript
async function handleEditSubmit(e) {
  e.preventDefault();
  const form = e.target;
  
  const formData = {
    produto_id: parseInt(form.produto_id.value),
    nome: form.nome.value,
    // ... outros campos
  };
  
  const res = await fetch('php/admin_products.php', {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(formData)
  });
```

**Explicação:**

1. **Muito similar ao POST**
2. **Diferenças:**
   - `method: 'PUT'` - Atualiza recurso existente
   - Inclui `produto_id` no body
   - Backend identifica produto a atualizar

---

### Deletar Produto (DELETE)

```javascript
async function deleteProduct(produtoId) {
  if (!confirm('Tem certeza que deseja excluir este produto?')) {
    return;
  }
```

**Explicação:**

1. **`confirm()`**
   - Mostra diálogo nativo do navegador
   - Retorna `true` (OK) ou `false` (Cancelar)

2. **Confirmação dupla**
   - Previne exclusão acidental
   - Boa prática de UX

---

```javascript
const res = await fetch('php/admin_products.php', {
  method: 'DELETE',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({ produto_id: produtoId })
});
```

**Explicação:**

1. **`method: 'DELETE'`**
   - Método HTTP para remover recurso

2. **Body com apenas ID**
   - Outros dados não são necessários
   - Backend usa ID para localizar registro

3. **Backend executa:**
   ```sql
   DELETE FROM produto WHERE produto_id = ?
   ```

---

### Modal - Fechar ao Clicar Fora

```javascript
document.getElementById('edit-modal').addEventListener('click', (e) => {
  if (e.target.id === 'edit-modal') {
    closeEditModal();
  }
});
```

**Explicação:**

1. **Event listener no modal inteiro**
2. **`e.target.id === 'edit-modal'`**
   - Verifica se clicou no backdrop (fundo escuro)
   - Não no conteúdo interno

3. **Comportamento:**
   - Clicar fora = fecha
   - Clicar dentro = não fecha

**Estrutura HTML:**
```html
<div id="edit-modal"> <!-- CLICAR AQUI FECHA -->
  <div> <!-- CLICAR AQUI NÃO FECHA -->
    Conteúdo do modal
  </div>
</div>
```

---

### Event Listeners Iniciais

```javascript
document.getElementById('product-form').addEventListener('submit', handleSubmit);
document.getElementById('edit-product-form').addEventListener('submit', handleEditSubmit);

document.addEventListener('DOMContentLoaded', checkAdminAccess);
```

**Explicação:**

1. **Registra listeners nos formulários**
   - Formulário de criar
   - Formulário de editar

2. **`DOMContentLoaded`**
   - Executa quando DOM está pronto
   - Chama `checkAdminAccess()` automaticamente
   - Carrega produtos se for admin

---

## Diagnóstico-Produtos

**Localização:** `diagnostico-produtos.html`  
**Função:** Testes automatizados da API

### 🧪 Sistema de Logs

```javascript
const logs = [];

function addLog(message, type = 'info') {
  const timestamp = new Date().toLocaleTimeString('pt-BR');
  logs.push({ timestamp, message, type });
  
  const logsDiv = document.getElementById('system-logs');
  const entry = document.createElement('div');
  entry.className = `log-entry ${type}`;
  entry.textContent = `[${timestamp}] ${message}`;
  logsDiv.insertBefore(entry, logsDiv.firstChild);
}
```

**Explicação:**

1. **Array `logs`**
   - Armazena histórico completo
   - Útil para debug

2. **`new Date().toLocaleTimeString('pt-BR')`**
   - Cria timestamp formatado (ex: "14:32:15")
   - Formato brasileiro

3. **`createElement('div')`**
   - Cria elemento dinamicamente
   - Mais performático que `innerHTML` em loops

4. **`insertBefore(entry, firstChild)`**
   - Adiciona no TOPO da lista
   - Logs mais recentes aparecem primeiro

---

### Teste de Criação

```javascript
async function testProductCreation() {
  const testProduct = {
    nome: `TESTE GPU ${Date.now()}`,
    categoria_id: 5,
    fabricante: 'NVIDIA',
    sku: `TEST-GPU-${Date.now()}`,
    preco: 8999.99,
    estoque: 10,
    fornecedor_id: 1,
    imagem: 'https://via.placeholder.com/400'
  };
```

**Explicação:**

1. **`Date.now()`**
   - Timestamp em millisegundos (ex: 1699893456789)
   - Garante nome/SKU únicos
   - Evita erro de duplicação

2. **Dados válidos**
   - Testa caminho feliz (sucesso)

---

```javascript
try {
  const res = await fetch('php/admin_products.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(testProduct)
  });
  
  const data = await res.json();
  
  logsDiv.innerHTML += `<div class="log-entry ${data.success ? 'success' : 'error'}">` +
    `Status: ${res.status}\n` +
    `Resposta: ${JSON.stringify(data, null, 2)}</div>`;
```

**Explicação:**

1. **Requisição POST**
2. **Captura resposta completa**
   - `res.status` - Código HTTP (200, 400, 500)
   - `data` - Body da resposta

3. **`JSON.stringify(data, null, 2)`**
   - Converte objeto para JSON formatado
   - `null` - Sem replacer function
   - `2` - Indentação de 2 espaços

**Output:**
```json
{
  "success": true,
  "produto_id": 123,
  "message": "Produto criado!"
}
```

---

### Teste de Validação

```javascript
async function testWithInvalidData() {
  const invalidProduct = {
    nome: '',
    categoria_id: 0,
    sku: '',
    preco: -100
  };
  
  // ... requisição
  
  if (res.status === 400 && !data.success) {
    addLog('✓ Validação funcionando corretamente', 'success');
  }
}
```

**Explicação:**

1. **Dados inválidos propositalmente**
   - Nome vazio
   - Categoria 0 (inválida)
   - SKU vazio
   - Preço negativo

2. **Espera status 400 (Bad Request)**
   - Se retornar 400 = validação funciona
   - Se retornar 200 = validação falhou

3. **Teste negativo**
   - Importante testar falhas
   - Garante que validações estão ativas

---

## Padrões e Boas Práticas

### 1. Async/Await vs Promises

**❌ Evite (Promises encadeadas):**
```javascript
fetch('api.php')
  .then(res => res.json())
  .then(data => {
    console.log(data);
  })
  .catch(err => console.error(err));
```

**✅ Prefira (Async/Await):**
```javascript
async function fetchData() {
  try {
    const res = await fetch('api.php');
    const data = await res.json();
    console.log(data);
  } catch (err) {
    console.error(err);
  }
}
```

**Vantagens:**
- Código mais legível
- Fácil de debugar
- Melhor tratamento de erros

---

### 2. Validação Client-Side

```javascript
// ✅ Sempre valide antes de enviar
if (nome === '' || preco <= 0) {
  alert('Preencha todos os campos');
  return;
}
```

**Por quê:**
- Feedback imediato ao usuário
- Reduz requisições desnecessárias
- Melhora UX

**⚠️ Importante:** Sempre valide no backend também (segurança).

---

### 3. Template Literals

**❌ Evite (concatenação):**
```javascript
const html = '<div class="product">' +
  '<h3>' + produto.nome + '</h3>' +
  '<p>R$ ' + produto.preco + '</p>' +
  '</div>';
```

**✅ Prefira (template literals):**
```javascript
const html = `
  <div class="product">
    <h3>${produto.nome}</h3>
    <p>R$ ${produto.preco}</p>
  </div>
`;
```

**Vantagens:**
- Mais legível
- Menos erros
- Suporta múltiplas linhas

---

### 4. Event Delegation

**❌ Evite (listener individual):**
```javascript
document.querySelectorAll('.btn').forEach(btn => {
  btn.addEventListener('click', handleClick);
});
```

**✅ Prefira (delegation):**
```javascript
document.getElementById('container').addEventListener('click', (e) => {
  if (e.target.classList.contains('btn')) {
    handleClick(e);
  }
});
```

**Vantagens:**
- Um listener em vez de N
- Funciona com elementos dinâmicos
- Melhor performance

---

### 5. Destructuring

**❌ Evite:**
```javascript
const nome = data.product.nome;
const preco = data.product.preco;
const estoque = data.product.estoque;
```

**✅ Prefira:**
```javascript
const { nome, preco, estoque } = data.product;
```

**Vantagens:**
- Código mais conciso
- Menos repetição

---

### 6. Optional Chaining

**❌ Evite (verificações manuais):**
```javascript
if (data && data.user && data.user.is_admin) {
  // ...
}
```

**✅ Prefira:**
```javascript
if (data?.user?.is_admin) {
  // ...
}
```

**Vantagens:**
- Mais legível
- Evita erros "Cannot read property of undefined"

---

### 7. Nullish Coalescing

**❌ Evite (`||` com valores falsy):**
```javascript
const estoque = produto.estoque || 0; // BUG: 0 é falsy!
```

**✅ Prefira (`??` apenas para null/undefined):**
```javascript
const estoque = produto.estoque ?? 0;
```

**Diferença:**
- `||` - Retorna se falsy (0, '', false, null, undefined)
- `??` - Retorna apenas se null/undefined

---

### 8. Try/Catch em Async

**❌ Evite (sem tratamento):**
```javascript
async function fetchData() {
  const res = await fetch('api.php'); // Pode lançar erro!
  const data = await res.json();
}
```

**✅ Prefira:**
```javascript
async function fetchData() {
  try {
    const res = await fetch('api.php');
    const data = await res.json();
    return data;
  } catch (err) {
    console.error('Erro:', err);
    alert('Falha ao carregar dados');
    return null;
  }
}
```

---

### 9. Debounce em Busca

**Problema:** Buscar a cada tecla digitada = muitas requisições

**✅ Solução:**
```javascript
let debounceTimer;

searchInput.addEventListener('input', (e) => {
  clearTimeout(debounceTimer);
  
  debounceTimer = setTimeout(() => {
    performSearch(e.target.value);
  }, 300); // Aguarda 300ms após parar de digitar
});
```

**Vantagem:** Reduz requisições de 20+ para 1-2.

---

### 10. Loading States

**✅ Sempre mostre feedback:**
```javascript
submitBtn.disabled = true;
submitBtn.textContent = 'Salvando...';

try {
  await fetch('api.php', { ... });
  submitBtn.textContent = 'Salvo!';
} catch {
  submitBtn.textContent = 'Erro!';
} finally {
  setTimeout(() => {
    submitBtn.disabled = false;
    submitBtn.textContent = 'Salvar';
  }, 2000);
}
```

---

## 🎓 Conceitos Avançados

### Closures

```javascript
function createCounter() {
  let count = 0; // Variável privada
  
  return {
    increment: () => ++count,
    decrement: () => --count,
    getCount: () => count
  };
}

const counter = createCounter();
counter.increment(); // 1
counter.increment(); // 2
counter.getCount();  // 2
```

**Uso no projeto:** Encapsular estado (ex: logs, filtros).

---

### Arrow Functions vs Function

**Arrow (=>):**
```javascript
const sum = (a, b) => a + b;

// Não tem 'this' próprio
const obj = {
  method: () => {
    console.log(this); // Window (não obj)
  }
};
```

**Function:**
```javascript
function sum(a, b) {
  return a + b;
}

// Tem 'this' próprio
const obj = {
  method: function() {
    console.log(this); // obj
  }
};
```

**Quando usar arrow:** Callbacks, funções simples  
**Quando usar function:** Métodos de objetos, construtores

---

### LocalStorage vs SessionStorage

```javascript
// LocalStorage - persiste até limpar manualmente
localStorage.setItem('user', JSON.stringify({ nome: 'João' }));
const user = JSON.parse(localStorage.getItem('user'));

// SessionStorage - limpa ao fechar aba
sessionStorage.setItem('cart', JSON.stringify(items));
```

**Uso no projeto:**
- LocalStorage: Carrinho, preferências
- SessionStorage: Filtros temporários

---

### Fetch API - Opções Avançadas

```javascript
const res = await fetch('api.php', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' + token
  },
  body: JSON.stringify(data),
  credentials: 'include', // Envia cookies
  mode: 'cors', // Cross-origin
  cache: 'no-cache' // Não usar cache
});

// Verificar status HTTP
if (!res.ok) {
  throw new Error(`HTTP ${res.status}: ${res.statusText}`);
}
```

---

## 📚 Recursos de Aprendizado

### Documentação Oficial
- [MDN Web Docs](https://developer.mozilla.org/pt-BR/docs/Web/JavaScript) - Referência completa
- [JavaScript.info](https://javascript.info/) - Tutorial moderno
- [ECMAScript Spec](https://tc39.es/ecma262/) - Especificação oficial

### Ferramentas
- **Chrome DevTools** - F12 para debugar
- **Console.log()** - Debug básico
- **Debugger** - Breakpoints no código
- **Network Tab** - Inspecionar requisições

### Debugging

```javascript
// 1. Console.log básico
console.log('Valor:', variavel);

// 2. Console.table para objetos
console.table(produtos);

// 3. Debugger statement
function problematica() {
  debugger; // Pausa execução aqui
  const resultado = calcular();
  return resultado;
}

// 4. Try/catch com stack trace
try {
  fazerAlgo();
} catch (err) {
  console.error('Erro:', err.message);
  console.error('Stack:', err.stack);
}
```

---

## 🚀 Performance Tips

### 1. Minimize Reflows
```javascript
// ❌ Lento (3 reflows)
element.style.width = '100px';
element.style.height = '100px';
element.style.background = 'red';

// ✅ Rápido (1 reflow)
element.style.cssText = 'width:100px; height:100px; background:red;';
```

### 2. Use DocumentFragment
```javascript
// ❌ Lento (N manipulações do DOM)
produtos.forEach(p => {
  container.appendChild(createCard(p));
});

// ✅ Rápido (1 manipulação)
const fragment = document.createDocumentFragment();
produtos.forEach(p => {
  fragment.appendChild(createCard(p));
});
container.appendChild(fragment);
```

### 3. Evite Query Selectors em Loops
```javascript
// ❌ Lento
for (let i = 0; i < 100; i++) {
  document.getElementById('container').innerHTML += '<div></div>';
}

// ✅ Rápido
const container = document.getElementById('container');
let html = '';
for (let i = 0; i < 100; i++) {
  html += '<div></div>';
}
container.innerHTML = html;
```

---

## ✅ Checklist de Qualidade

Antes de fazer commit:

- [ ] Código está indentado corretamente?
- [ ] Variáveis têm nomes descritivos?
- [ ] Funções fazem UMA coisa?
- [ ] Há tratamento de erros (try/catch)?
- [ ] Validações client-side estão implementadas?
- [ ] Há feedback visual (loading, sucesso, erro)?
- [ ] Console.log de debug foram removidos?
- [ ] Código funciona em diferentes navegadores?
- [ ] Não há memory leaks (event listeners não removidos)?

---

## 🎯 Próximos Passos

1. **Adicionar TypeScript** - Tipagem estática
2. **Implementar Service Worker** - Cache offline
3. **Adicionar testes** - Jest ou Vitest
4. **Lazy Loading** - Carregar imagens sob demanda
5. **Virtual Scrolling** - Performance em listas grandes
6. **Web Components** - Componentes reutilizáveis
7. **State Management** - Gerenciar estado global

---

**Autor:** Sistema CiberTech  
**Última atualização:** 13/11/2025  
**Versão:** 1.0
