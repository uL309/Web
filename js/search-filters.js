// Sistema de Pesquisa e Filtros - CiberTech E-commerce
(() => {
  // Elementos DOM
  const searchInput = document.querySelector('.search input[type="search"]');
  const searchBtn = document.querySelector('.search button');
  const productsContainer = document.querySelector('.products');
  const priceRange = document.querySelector('input[type="range"]');
  const priceLabel = document.querySelector('.filter label');
  const categoryCheckboxes = document.querySelectorAll('.filter input[type="checkbox"]');
  const categoryLinks = document.querySelectorAll('header nav a');

  // Estado dos filtros
  let currentFilters = {
    search: '',
    categoria: null,
    min_price: 0,
    max_price: 10000,
    order: 'nome',
    dir: 'ASC',
    page: 1,
    limit: 12
  };

  // Mapear categorias por nome
  const categoryMap = {
    'Placas-mãe': 7,
    'Processadores': 6,
    'Placas de vídeo': 5,
    'Memória RAM': 8,
    'SSD / HD': 13, // Pode ser 13 ou 14
    'Fontes': null, // Adicione o ID se existir
    'Monitores': 3,
    'Periféricos': 2,
    'SSD': 13,
    'HD': 14
  };

  // Formatar preço
  function formatPrice(value) {
    return 'R$ ' + parseFloat(value).toFixed(2).replace('.', ',');
  }

  // Construir query string
  function buildQueryString() {
    const params = new URLSearchParams();
    
    if (currentFilters.search) params.append('q', currentFilters.search);
    if (currentFilters.categoria) params.append('categoria', currentFilters.categoria);
    if (currentFilters.min_price > 0) params.append('min_price', currentFilters.min_price);
    if (currentFilters.max_price < 10000) params.append('max_price', currentFilters.max_price);
    params.append('order', currentFilters.order);
    params.append('dir', currentFilters.dir);
    params.append('page', currentFilters.page);
    params.append('limit', currentFilters.limit);
    
    return params.toString();
  }

  // Carregar produtos
  async function loadProducts() {
    if (!productsContainer) return;

    // Mostrar loading
    productsContainer.innerHTML = `
      <div style="grid-column: 1/-1; text-align: center; padding: 60px 0;">
        <p style="color: var(--muted);">Carregando produtos...</p>
      </div>
    `;

    try {
      const queryString = buildQueryString();
      const res = await fetch(`php/products.php?${queryString}`);
      const data = await res.json();

      if (!data.success) {
        throw new Error(data.error || 'Erro ao carregar produtos');
      }

      if (!data.products || data.products.length === 0) {
        productsContainer.innerHTML = `
          <div style="grid-column: 1/-1; text-align: center; padding: 60px 0;">
            <p style="color: var(--muted); margin-bottom: 16px;">Nenhum produto encontrado.</p>
            <button class="cta" onclick="location.reload()">Limpar Filtros</button>
          </div>
        `;
        return;
      }

      renderProducts(data.products);
      renderPagination(data.pagination);

    } catch (err) {
      console.error('Erro ao carregar produtos:', err);
      productsContainer.innerHTML = `
        <div style="grid-column: 1/-1; text-align: center; padding: 60px 0;">
          <p style="color: #ff3366;">Erro ao carregar produtos: ${err.message}</p>
          <button class="cta" onclick="location.reload()" style="margin-top: 16px;">Tentar Novamente</button>
        </div>
      `;
    }
  }

  // Renderizar produtos
  function renderProducts(products) {
    productsContainer.innerHTML = products.map(p => `
      <article class="card" aria-label="${p.nome}">
        <img src="${p.imagem || 'https://via.placeholder.com/400x300/1a1a2e/00ff88?text=Sem+Imagem'}" 
             alt="${p.nome}" 
             onerror="this.src='https://via.placeholder.com/400x300/1a1a2e/00ff88?text=Sem+Imagem'" />
        <a href="produto1.html?id=${p.produto_id}">
          <button class="icon-btn">${p.nome}</button>
        </a>
        <div>
          <span class="price">${formatPrice(p.preco)}</span>
        </div>
        <div style="font-size:13px;color:var(--muted);margin-top:6px">
          ${p.estoque > 0 ? `Em estoque (${p.estoque})` : 'Sem estoque'} • ${p.fabricante}
        </div>
        <button class="buy" 
                data-product-id="${p.produto_id}" 
                ${p.estoque === 0 ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : ''}>
          ${p.estoque > 0 ? 'Adicionar ao carrinho' : 'Indisponível'}
        </button>
      </article>
    `).join('');

    // Adiciona handlers para os botões
    attachCartHandlers();
  }

  // Renderizar paginação
  function renderPagination(pagination) {
    if (!pagination || pagination.total_pages <= 1) return;

    const paginationHTML = `
      <div style="grid-column: 1/-1; display: flex; justify-content: center; gap: 8px; margin-top: 24px;">
        ${pagination.page > 1 ? `
          <button class="buy" onclick="window.searchFilters.goToPage(${pagination.page - 1})">
            ← Anterior
          </button>
        ` : ''}
        
        <span style="padding: 12px; color: var(--muted);">
          Página ${pagination.page} de ${pagination.total_pages}
        </span>
        
        ${pagination.page < pagination.total_pages ? `
          <button class="buy" onclick="window.searchFilters.goToPage(${pagination.page + 1})">
            Próxima →
          </button>
        ` : ''}
      </div>
    `;
    
    productsContainer.insertAdjacentHTML('beforeend', paginationHTML);
  }

  // Adicionar produto ao carrinho
  async function addToCart(productId) {
    try {
      const res = await fetch('php/cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ produto_id: productId, quantidade: 1 })
      });

      const data = await res.json();

      if (!data.success) {
        alert(data.error || 'Erro ao adicionar ao carrinho');
        return false;
      }

      return true;
    } catch (err) {
      console.error('Erro ao adicionar ao carrinho:', err);
      alert('Erro ao adicionar produto. Tente novamente.');
      return false;
    }
  }

  // Configurar event listeners nos botões
  function attachCartHandlers() {
    document.querySelectorAll('.buy[data-product-id]').forEach(btn => {
      btn.addEventListener('click', async () => {
        const productId = btn.dataset.productId;
        if (!productId || btn.disabled) return;

        const originalText = btn.textContent;
        btn.disabled = true;
        btn.textContent = 'Adicionando...';

        const success = await addToCart(productId);

        if (success) {
          btn.textContent = '✓ Adicionado';
          btn.style.background = '#00cc88';
          setTimeout(() => {
            btn.textContent = originalText;
            btn.disabled = false;
            btn.style.background = '';
          }, 2000);
        } else {
          btn.textContent = originalText;
          btn.disabled = false;
        }
      });
    });
  }

  // Event listener: Pesquisa
  function handleSearch() {
    const term = searchInput?.value.trim() || '';
    currentFilters.search = term;
    currentFilters.page = 1;
    loadProducts();
  }

  if (searchBtn) {
    searchBtn.addEventListener('click', handleSearch);
  }

  if (searchInput) {
    searchInput.addEventListener('keypress', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
        handleSearch();
      }
    });
  }

  // Event listener: Slider de preço
  if (priceRange) {
    priceRange.addEventListener('input', (e) => {
      const value = parseInt(e.target.value);
      currentFilters.max_price = value;
      if (priceLabel) {
        priceLabel.textContent = `R$ 0 — R$ ${value.toLocaleString('pt-BR')}`;
      }
    });

    priceRange.addEventListener('change', () => {
      currentFilters.page = 1;
      loadProducts();
    });
  }

  // Event listener: Checkboxes de categoria
  categoryCheckboxes.forEach((checkbox, index) => {
    checkbox.addEventListener('change', () => {
      // Limpar outros checkboxes (apenas uma categoria por vez)
      categoryCheckboxes.forEach((cb, i) => {
        if (i !== index) cb.checked = false;
      });

      const categoryName = checkbox.parentElement.textContent.trim();
      currentFilters.categoria = checkbox.checked ? categoryMap[categoryName] || null : null;
      currentFilters.page = 1;
      loadProducts();
    });
  });

  // Event listener: Links de categoria no header
  categoryLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      const categoryName = link.textContent.trim();
      const categoryId = categoryMap[categoryName];
      
      if (categoryId) {
        currentFilters.categoria = categoryId;
        currentFilters.search = '';
        currentFilters.page = 1;
        
        // Limpar busca
        if (searchInput) searchInput.value = '';
        
        // Atualizar checkboxes
        categoryCheckboxes.forEach(cb => {
          cb.checked = cb.parentElement.textContent.trim() === categoryName;
        });
        
        loadProducts();
        
        // Scroll para produtos
        document.querySelector('.main')?.scrollIntoView({ behavior: 'smooth' });
      }
    });
  });

  // Funções públicas
  window.searchFilters = {
    goToPage: (page) => {
      currentFilters.page = page;
      loadProducts();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    },
    
    setOrder: (order, dir = 'ASC') => {
      currentFilters.order = order;
      currentFilters.dir = dir;
      currentFilters.page = 1;
      loadProducts();
    },
    
    clearFilters: () => {
      currentFilters = {
        search: '',
        categoria: null,
        min_price: 0,
        max_price: 10000,
        order: 'nome',
        dir: 'ASC',
        page: 1,
        limit: 12
      };
      
      if (searchInput) searchInput.value = '';
      if (priceRange) {
        priceRange.value = 10000;
        if (priceLabel) priceLabel.textContent = 'R$ 0 — R$ 10.000';
      }
      categoryCheckboxes.forEach(cb => cb.checked = false);
      
      loadProducts();
    }
  };

  // Inicializar
  document.addEventListener('DOMContentLoaded', () => {
    loadProducts();
    
    // Configurar valor inicial do slider
    if (priceRange && priceLabel) {
      priceLabel.textContent = `R$ 0 — R$ ${parseInt(priceRange.value).toLocaleString('pt-BR')}`;
    }
  });
})();
