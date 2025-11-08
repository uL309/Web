# 🎯 Guia Rápido - Sistema de Busca Integrado

## ✅ INTEGRAÇÃO COMPLETA

O sistema de busca e filtros está **100% integrado** ao e-commerce CiberTech!

---

## 🔍 COMO FUNCIONA

### **1. Busca no Header (TODAS AS PÁGINAS)**

```
┌─────────────────────────────────────────┐
│  🔎 [Digite aqui...] [Buscar]          │
└─────────────────────────────────────────┘
                 ↓
        Redireciona para
         busca.html?q=termo
```

**Páginas com busca integrada:**
- ✅ `index.html` (Home)
- ✅ `carrinho.html` (Carrinho)
- ✅ `busca.html` (Resultados)
- ✅ Todas as outras páginas com header

---

### **2. Navegação por Categorias**

```
┌─────────────────────────────────────────────────────────┐
│ Placas-mãe | Processadores | Placas de vídeo | RAM ... │
└─────────────────────────────────────────────────────────┘
       ↓              ↓               ↓            ↓
  categoria=7   categoria=6     categoria=5  categoria=8
```

**Clique em qualquer categoria** → Vai para `busca.html?categoria=ID`

---

### **3. Página de Resultados (busca.html)**

```
┌──────────────────────────────────────────────┐
│ Resultados para "RTX 4090"                   │
│ 8 produtos encontrados                       │
│                                              │
│ Ordenar: [Nome A-Z ▼]                       │
│                                              │
│ ┌────────┐  ┌────────┐  ┌────────┐         │
│ │ Produto│  │ Produto│  │ Produto│  ...    │
│ │ R$5.999│  │ R$6.499│  │ R$7.299│         │
│ └────────┘  └────────┘  └────────┘         │
│                                              │
│      [← Anterior] Pág 1 de 2 [Próxima →]   │
└──────────────────────────────────────────────┘
```

**Filtros Laterais:**
- 💰 Preço Máximo: [────○─────] R$ 0 — R$ 10.000
- 📦 Categorias: ☐ Placas de vídeo ☐ Processadores ...
- 🧹 [Limpar Filtros]

---

## 🎮 TESTANDO O SISTEMA

### **Teste 1: Busca por Texto**
1. Acesse `index.html`
2. Digite **"RTX"** no campo de busca
3. Clique em **"Buscar"** (ou Enter)
4. ✅ Deve ir para `busca.html?q=RTX`

### **Teste 2: Busca por Categoria**
1. Clique em **"Placas de vídeo"** no menu
2. ✅ Deve ir para `busca.html?categoria=5`
3. ✅ Deve mostrar apenas placas de vídeo

### **Teste 3: Filtros**
1. Em `busca.html`, ajuste o **slider de preço**
2. Marque uma **categoria**
3. Selecione **"Menor Preço"** na ordenação
4. ✅ Produtos devem atualizar instantaneamente

### **Teste 4: Adicionar ao Carrinho**
1. Em qualquer resultado de busca
2. Clique em **"Adicionar ao Carrinho"**
3. ✅ Botão deve mudar para **"✓ Adicionado"**
4. Acesse `carrinho.html` e confirme

---

## 📂 ARQUIVOS MODIFICADOS/CRIADOS

### **Novos Arquivos:**
✅ `busca.html` - Página de resultados dedicada  
✅ `js/search-results.js` - Lógica da página de resultados  
✅ `js/search-filters.js` - Sistema de filtros na home  
✅ `BUSCA.md` - Documentação completa  
✅ `INTEGRACAO.md` - Este guia rápido  

### **Arquivos Atualizados:**
✅ `js/header.js` - Integração de busca global  
✅ `index.html` - IDs e categorias adicionados  
✅ `carrinho.html` - IDs e categorias adicionados  

---

## 🔗 URLS DISPONÍVEIS

### **Busca Simples**
```
busca.html?q=placa+de+video
```

### **Categoria**
```
busca.html?categoria=5
```

### **Busca + Preço**
```
busca.html?q=ryzen&max_price=1500
```

### **Busca + Ordenação**
```
busca.html?q=monitor&order=preco&dir=ASC
```

### **Busca Completa**
```
busca.html?q=ssd&categoria=13&max_price=500&order=preco&dir=ASC
```

---

## 🎯 FLUXO COMPLETO

```
Usuário → Header (index.html/carrinho.html/etc)
            ↓
      Digite "RTX 4090"
            ↓
    header.js captura evento
            ↓
 Redireciona busca.html?q=RTX+4090
            ↓
   search-results.js carrega
            ↓
     Faz fetch para php/products.php?q=RTX+4090
            ↓
       Recebe JSON com produtos
            ↓
     Renderiza na página
            ↓
  Usuário clica "Adicionar ao Carrinho"
            ↓
     Faz POST para php/cart.php
            ↓
    Feedback: "✓ Adicionado"
```

---

## ⚡ FEATURES PRINCIPAIS

✅ **Busca Global** - Funciona em todas as páginas  
✅ **Navegação por Categorias** - Menu clicável  
✅ **Filtros Avançados** - Preço, categorias, ordenação  
✅ **Paginação** - 24 produtos por página  
✅ **URL Persistente** - Compartilhe resultados  
✅ **Integração com Carrinho** - Adicione direto dos resultados  
✅ **Responsivo** - Funciona em mobile/tablet/desktop  
✅ **Performance** - Carregamento rápido  

---

## 🚀 PRÓXIMOS PASSOS

Para começar a usar:

1. **Certifique-se que o servidor está rodando:**
   ```powershell
   cd f:\weeeeeeeeeb\Web
   php -S localhost:8000
   ```

2. **Acesse no navegador:**
   ```
   http://localhost:8000/index.html
   ```

3. **Teste a busca:**
   - Digite algo no campo de busca
   - Clique nas categorias
   - Use os filtros em `busca.html`

---

## 📞 SUPORTE

Se algo não funcionar:

1. Abra o **Console do Navegador** (F12)
2. Verifique erros em vermelho
3. Certifique-se que o banco está populado:
   ```powershell
   .\install.ps1
   ```

---

**Sistema 100% Funcional e Integrado! 🎉**

Documentação completa em: [BUSCA.md](BUSCA.md)
