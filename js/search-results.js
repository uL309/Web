// Sistema de Busca - Página de Resultados
(() => {
  const urlParams = new URLSearchParams(window.location.search);
  const searchInput = document.getElementById('main-search');
  const searchBtn = document.getElementById('search-btn');
  const productsContainer = document.querySelector('.products');
  const priceRange = document.getElementById('price-range');
  const priceLabel = document.getElementById('price-label');
  const sortSelect = document.getElementById('sort-select');
  const clearBtn = document.getElementById('clear-filters');
  const searchTitle = document.getElementById('search-title');
  const searchInfo = document.getElementById('search-info');
  const resultsCount = document.getElementById('results-count');
  const categoryCheckboxes = document.querySelectorAll('#category-filters input[type="checkbox"]');
  const categoryLinks = document.querySelectorAll('#category-nav a');

  // Estado
  let filters = {
    search: urlParams.get('q') || '',
    categoria: urlParams.get('categoria') || null,
    min_price: parseInt(urlParams.get('min_price')) || 0,
    max_price: parseInt(urlParams.get('max_price')) || 10000,
    order: urlParams.get('order') || 'nome',
    dir: urlParams.get('dir') || 'ASC',
    page: parseInt(urlParams.get('page')) || 1,
    limit: 24
  };

  // Inicializar UI
  if (searchInput && filters.search) {
    searchInput.value = filters.search;
    searchTitle.textContent = `Resultados para "${filters.search}"`;
  } else if (filters.categoria) {
    searchTitle.textContent = 'Produtos por Categoria';
  }

  if (priceRange) priceRange.value = filters.max_price;
  if (priceLabel) priceLabel.textContent = `R$ ${filters.min_price} — R$ ${filters.max_price.toLocaleString('pt-BR')}`;

  // Marcar categoria selecionada
  if (filters.categoria) {
    categoryCheckboxes.forEach(cb => {
      if (cb.dataset.category === filters.categoria) cb.checked = true;
    });
  }

  // Formatar preço
  function formatPrice(value) {
    return 'R$ ' + parseFloat(value).toFixed(2).replace('.', ',');
  }

  // Construir query
  function buildQuery() {
    const params = new URLSearchParams();
    if (filters.search) params.append('q', filters.search);
    if (filters.categoria) params.append('categoria', filters.categoria);
    if (filters.min_price > 0) params.append('min_price', filters.min_price);
    if (filters.max_price < 10000) params.append('max_price', filters.max_price);
    params.append('order', filters.order);
    params.append('dir', filters.dir);
    params.append('page', filters.page);
    params.append('limit', filters.limit);
    return params.toString();
  }

  // Atualizar URL
  function updateURL() {
    const query = buildQuery();
    window.history.replaceState({}, '', `busca.html?${query}`);
  }

  // Carregar produtos
  async function loadProducts() {
    if (!productsContainer) return;

    productsContainer.innerHTML = `
      <div style="grid-column: 1/-1; text-align: center; padding: 60px 0;">
        <p style="color: var(--muted);">Carregando...</p>
      </div>
    `;

    try {
      const res = await fetch(`php/products.php?${buildQuery()}`);
      const data = await res.json();

      if (!data.success) throw new Error(data.error || 'Erro ao carregar');

      if (!data.products || data.products.length === 0) {
        productsContainer.innerHTML = `
          <div style="grid-column: 1/-1; text-align: center; padding: 60px 0;">
            <p style="color: var(--muted); margin-bottom: 16px;">Nenhum produto encontrado.</p>
            <button class="cta" onclick="location.href='index.html'">Ver Todos os Produtos</button>
          </div>
        `;
        if (resultsCount) resultsCount.textContent = '0 produtos encontrados';
        return;
      }

      renderProducts(data.products);
      renderPagination(data.pagination);
      
      if (resultsCount) {
        resultsCount.textContent = `${data.pagination.total} produto${data.pagination.total !== 1 ? 's' : ''} encontrado${data.pagination.total !== 1 ? 's' : ''}`;
      }

      updateURL();

    } catch (err) {
      console.error('Erro:', err);
      productsContainer.innerHTML = `
        <div style="grid-column: 1/-1; text-align: center; padding: 60px 0;">
          <p style="color: #ff3366;">Erro: ${err.message}</p>
        </div>
      `;
    }
  }

  // Renderizar produtos
  function renderProducts(products) {
    productsContainer.innerHTML = products.map(p => `
      <article class="card">
        <img src="${p.imagem || 'https://via.placeholder.com/400x300/1a1a2e/00ff88?text=Produto'}" 
             alt="${p.nome}"
             onerror="this.src='https://via.placeholder.com/400x300/1a1a2e/00ff88?text=Produto'" />
        <a href="produto1.html?id=${p.produto_id}">
          <button class="icon-btn">${p.nome}</button>
        </a>
        <div><span class="price">${formatPrice(p.preco)}</span></div>
        <div style="font-size:13px;color:var(--muted);margin-top:6px">
          ${p.estoque > 0 ? `✓ Estoque: ${p.estoque}` : '✗ Sem estoque'}
        </div>
        <button class="buy" data-id="${p.produto_id}" ${p.estoque === 0 ? 'disabled' : ''}>
          ${p.estoque > 0 ? 'Adicionar ao Carrinho' : 'Indisponível'}
        </button>
      </article>
    `).join('');

    // Event listeners para adicionar ao carrinho
    document.querySelectorAll('.buy[data-id]').forEach(btn => {
      btn.addEventListener('click', async () => {
        const id = btn.dataset.id;
        const originalText = btn.textContent;
        btn.disabled = true;
        btn.textContent = 'Adicionando...';

        try {
          const res = await fetch('php/cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ produto_id: id, quantidade: 1 })
          });
          const data = await res.json();

          if (data.success) {
            btn.textContent = '✓ Adicionado';
            btn.style.background = '#00cc88';
            setTimeout(() => {
              btn.textContent = originalText;
              btn.disabled = false;
              btn.style.background = '';
            }, 2000);
          } else {
            alert(data.error || 'Erro ao adicionar');
            btn.textContent = originalText;
            btn.disabled = false;
          }
        } catch (err) {
          alert('Erro de rede');
          btn.textContent = originalText;
          btn.disabled = false;
        }
      });
    });
  }

  // Renderizar paginação
  function renderPagination(pagination) {
    if (!pagination || pagination.total_pages <= 1) return;

    const html = `
      <div style="grid-column: 1/-1; display: flex; justify-content: center; gap: 8px; margin-top: 24px; flex-wrap: wrap;">
        ${pagination.page > 1 ? `
          <button class="buy" onclick="goToPage(1)">Primeira</button>
          <button class="buy" onclick="goToPage(${pagination.page - 1})">← Anterior</button>
        ` : ''}
        
        <span style="padding: 12px; color: var(--muted);">
          Página ${pagination.page} de ${pagination.total_pages}
        </span>
        
        ${pagination.page < pagination.total_pages ? `
          <button class="buy" onclick="goToPage(${pagination.page + 1})">Próxima →</button>
          <button class="buy" onclick="goToPage(${pagination.total_pages})">Última</button>
        ` : ''}
      </div>
    `;
    
    productsContainer.insertAdjacentHTML('beforeend', html);
  }

  // Navegar página
  window.goToPage = (page) => {
    filters.page = page;
    loadProducts();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  // Event listeners
  if (searchBtn) {
    searchBtn.addEventListener('click', () => {
      filters.search = searchInput.value.trim();
      filters.page = 1;
      searchTitle.textContent = filters.search ? `Resultados para "${filters.search}"` : 'Todos os Produtos';
      loadProducts();
    });
  }

  if (searchInput) {
    searchInput.addEventListener('keypress', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
        searchBtn.click();
      }
    });
  }

  if (priceRange) {
    priceRange.addEventListener('input', (e) => {
      filters.max_price = parseInt(e.target.value);
      priceLabel.textContent = `R$ ${filters.min_price} — R$ ${filters.max_price.toLocaleString('pt-BR')}`;
    });

    priceRange.addEventListener('change', () => {
      filters.page = 1;
      loadProducts();
    });
  }

  categoryCheckboxes.forEach(cb => {
    cb.addEventListener('change', () => {
      // Apenas uma categoria
      categoryCheckboxes.forEach(other => {
        if (other !== cb) other.checked = false;
      });
      
      filters.categoria = cb.checked ? cb.dataset.category : null;
      filters.page = 1;
      loadProducts();
    });
  });

  categoryLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      const catId = link.dataset.category;
      filters.categoria = catId;
      filters.search = '';
      filters.page = 1;
      searchInput.value = '';
      searchTitle.textContent = link.textContent;
      
      categoryCheckboxes.forEach(cb => {
        cb.checked = cb.dataset.category === catId;
      });
      
      loadProducts();
    });
  });

  if (sortSelect) {
    sortSelect.addEventListener('change', (e) => {
      const [field, dir] = e.target.value.split('-');
      filters.order = field;
      filters.dir = dir.toUpperCase();
      filters.page = 1;
      loadProducts();
    });
  }

  if (clearBtn) {
    clearBtn.addEventListener('click', () => {
      filters = {
        search: '',
        categoria: null,
        min_price: 0,
        max_price: 10000,
        order: 'nome',
        dir: 'ASC',
        page: 1,
        limit: 24
      };
      
      searchInput.value = '';
      priceRange.value = 10000;
      priceLabel.textContent = 'R$ 0 — R$ 10.000';
      categoryCheckboxes.forEach(cb => cb.checked = false);
      sortSelect.value = 'nome-asc';
      searchTitle.textContent = 'Todos os Produtos';
      
      loadProducts();
    });
  }

  // Inicializar
  loadProducts();
})();
