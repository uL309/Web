# 🔍 Sistema de Busca e Filtros - CiberTech

## 📋 Visão Geral

O sistema de busca está completamente integrado ao e-commerce CiberTech, permitindo que os usuários pesquisem produtos por texto, filtrem por categorias, definam faixas de preço e ordenem os resultados.

## 🎯 Funcionalidades

### ✅ Busca por Texto
- Campo de busca presente em **todas as páginas** (header)
- Pesquisa por nome ou descrição do produto
- Redirecionamento automático para página de resultados
- Suporte para **Enter** no teclado

### ✅ Navegação por Categorias
- Links clicáveis no menu de navegação
- Categorias disponíveis:
  - Placas-mãe (ID: 7)
  - Processadores (ID: 6)
  - Placas de vídeo (ID: 5)
  - Memória RAM (ID: 8)
  - SSD / HD (ID: 13)
  - Monitores (ID: 3)
  - Periféricos (ID: 2)

### ✅ Filtros Avançados
- **Preço**: Slider de R$ 0 a R$ 10.000
- **Categorias**: Checkboxes para filtrar (apenas uma por vez)
- **Ordenação**: 
  - Nome (A-Z / Z-A)
  - Preço (Menor / Maior)

### ✅ Paginação
- 24 produtos por página
- Navegação: Primeira | Anterior | Próxima | Última
- Contador de resultados
- Scroll suave ao trocar de página

### ✅ Integração com Carrinho
- Botão "Adicionar ao Carrinho" em cada produto
- Feedback visual (✓ Adicionado)
- Verificação de estoque
- Atualização automática do carrinho

## 🗂️ Estrutura de Arquivos

```
Web/
├── busca.html              # Página dedicada de resultados
├── index.html              # Home com busca integrada
├── carrinho.html           # Carrinho (header com busca)
├── js/
│   ├── search-filters.js   # Lógica de busca na home
│   ├── search-results.js   # Lógica da página de resultados
│   └── header.js           # Integração de busca no header
└── php/
    └── products.php        # API REST para produtos
```

## 🚀 Como Usar

### 1️⃣ Busca por Texto (qualquer página)

```
1. Digite o termo no campo de busca do header
2. Clique em "Buscar" ou pressione Enter
3. Será redirecionado para: busca.html?q=termo
```

**Exemplo:**
```
Pesquisa: "RTX 4090"
URL: busca.html?q=RTX+4090
```

### 2️⃣ Busca por Categoria (qualquer página)

```
1. Clique em uma categoria no menu de navegação
2. Será redirecionado para: busca.html?categoria=ID
```

**Exemplo:**
```
Clique: "Placas de vídeo"
URL: busca.html?categoria=5
```

### 3️⃣ Busca na Home (index.html)

```
1. Use o campo de busca superior (redireciona para busca.html)
2. OU use os filtros laterais (atualiza a home dinamicamente)
3. OU clique nas categorias do menu
```

**Comportamento:**
- Campo de busca do header → Redireciona para `busca.html`
- Filtros laterais → Atualiza produtos na mesma página

### 4️⃣ Página de Resultados (busca.html)

**Filtros disponíveis:**
- ✅ Barra de busca (pesquisar novamente)
- ✅ Slider de preço máximo
- ✅ Checkboxes de categorias
- ✅ Dropdown de ordenação
- ✅ Botão "Limpar filtros"

**Navegação:**
- Paginação completa
- Contador de resultados
- Botão "Voltar ao Início"

## 🔗 Exemplos de URLs

### Busca Simples
```
busca.html?q=placa+de+video
```

### Categoria Específica
```
busca.html?categoria=5
```

### Busca + Filtro de Preço
```
busca.html?q=ryzen&max_price=1500
```

### Busca + Ordenação
```
busca.html?q=monitor&order=preco&dir=ASC
```

### Busca Completa
```
busca.html?q=ssd&categoria=13&max_price=500&order=preco&dir=ASC&page=1
```

## 📊 Parâmetros da URL

| Parâmetro   | Tipo   | Descrição                          | Exemplo         |
|-------------|--------|------------------------------------|-----------------|
| `q`         | string | Termo de busca                     | `rtx+4090`      |
| `categoria` | int    | ID da categoria                    | `5`             |
| `min_price` | float  | Preço mínimo                       | `500`           |
| `max_price` | float  | Preço máximo                       | `3000`          |
| `order`     | string | Campo de ordenação (nome/preco)    | `preco`         |
| `dir`       | string | Direção (ASC/DESC)                 | `ASC`           |
| `page`      | int    | Página atual                       | `2`             |
| `limit`     | int    | Produtos por página (padrão: 24)   | `24`            |

## 🎨 Integração com o Header

O arquivo `header.js` gerencia:

1. **Busca Global**
   - Adiciona event listeners ao campo de busca
   - Redireciona para `busca.html?q=termo`

2. **Navegação de Categorias**
   - Adiciona event listeners aos links de categoria
   - Redireciona para `busca.html?categoria=ID`

3. **Sessão do Usuário**
   - Verifica se está logado
   - Atualiza botões de ação (Login/Logout)

## 🔄 Fluxo de Dados

```
┌─────────────┐
│   Usuário   │
└──────┬──────┘
       │
       ├─ Digite termo ──→ header.js ──→ Redireciona busca.html?q=termo
       │
       ├─ Clique categoria ──→ header.js ──→ busca.html?categoria=ID
       │
       └─ Use filtros ──→ search-results.js ──→ php/products.php
                                                      ↓
                                              Retorna JSON
                                                      ↓
                                              Renderiza produtos
```

## ⚙️ Configuração do Backend

O arquivo `php/products.php` aceita:

```php
GET /php/products.php
Query Parameters:
  - q: termo de busca
  - categoria: ID da categoria
  - min_price: preço mínimo
  - max_price: preço máximo
  - order: campo (nome/preco)
  - dir: direção (ASC/DESC)
  - page: página atual
  - limit: itens por página
```

**Resposta:**
```json
{
  "success": true,
  "products": [...],
  "pagination": {
    "total": 42,
    "page": 1,
    "limit": 24,
    "total_pages": 2
  }
}
```

## 🧪 Testes

### Testar Busca
1. Acesse `index.html`
2. Digite "RTX" no campo de busca
3. Clique em "Buscar"
4. Verifique se redirecionou para `busca.html?q=RTX`

### Testar Categorias
1. Clique em "Placas de vídeo" no menu
2. Verifique se redirecionou para `busca.html?categoria=5`
3. Verifique se apenas placas de vídeo aparecem

### Testar Filtros
1. Em `busca.html`, ajuste o slider de preço
2. Marque uma categoria
3. Selecione uma ordenação
4. Verifique se os produtos atualizam

### Testar Paginação
1. Faça uma busca ampla (ex: "placa")
2. Verifique se mostra "Página 1 de X"
3. Clique em "Próxima"
4. Verifique se atualiza para página 2

### Testar Carrinho
1. Na página de resultados
2. Clique em "Adicionar ao Carrinho"
3. Verifique feedback "✓ Adicionado"
4. Acesse `carrinho.html` e confirme

## 📱 Responsividade

O sistema é totalmente responsivo:
- **Desktop**: Layout completo com sidebar
- **Tablet**: Layout adaptado
- **Mobile**: Sidebar empilhada, grid de 2 colunas

## 🔒 Segurança

- ✅ SQL Injection: Prepared statements
- ✅ XSS: Escape de HTML nos templates
- ✅ CSRF: Validação de sessão
- ✅ Input Validation: Validação de parâmetros

## 🚀 Performance

- ✅ Lazy Loading de imagens
- ✅ Cache de resultados (30s)
- ✅ Paginação para reduzir payload
- ✅ Debounce em inputs (opcional)

## 📝 Próximos Passos

- [ ] Adicionar filtro por fabricante
- [ ] Adicionar filtro por avaliação
- [ ] Adicionar ordenação por relevância
- [ ] Adicionar histórico de buscas
- [ ] Adicionar sugestões automáticas
- [ ] Adicionar busca por voz

## 🎓 Documentação Adicional

- [README.md](README.md) - Visão geral do projeto
- [API_DOCS.md](API_DOCS.md) - Documentação completa da API
- [QUICKSTART.md](QUICKSTART.md) - Guia de início rápido
- [CHECKLIST.md](CHECKLIST.md) - Lista de verificação

---

**Desenvolvido para o e-commerce CiberTech** 🚀
