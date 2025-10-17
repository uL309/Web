(() => {
  const loadingEl = document.getElementById('cart-loading');
  const emptyEl = document.getElementById('cart-empty');
  const contentEl = document.getElementById('cart-content');
  const itemsBody = document.getElementById('cart-items-body');
  const subtotalEl = document.getElementById('cart-subtotal');
  const freteEl = document.getElementById('cart-frete');
  const totalEl = document.getElementById('cart-total');

  const FRETE = 49.90; // Simulação de frete fixo

  function formatPrice(value) {
    return 'R$ ' + parseFloat(value).toFixed(2).replace('.', ',');
  }

  async function loadCart() {
    try {
      const res = await fetch('php/cart.php');
      const data = await res.json();

      loadingEl.style.display = 'none';

      if (!data.success || !data.items || data.items.length === 0) {
        emptyEl.style.display = 'block';
        return;
      }

      contentEl.style.display = 'block';
      renderCart(data.items, data.total);

    } catch (err) {
      loadingEl.innerHTML = `<p style="color: #ff3366;">Erro ao carregar carrinho: ${err.message}</p>`;
    }
  }

  function renderCart(items, subtotal) {
    itemsBody.innerHTML = items.map(item => `
      <tr style="border-bottom:1px solid rgba(255,255,255,0.1)" data-item-id="${item.item_carrinho_id}">
        <td style="padding:12px">
          <div style="display: flex; align-items: center; gap: 12px;">
            ${item.imagem ? `<img src="${item.imagem}" style="width: 60px; height: 45px; object-fit: cover; border-radius: 4px;" alt="${item.nome}">` : ''}
            <span>${item.nome}</span>
          </div>
        </td>
        <td style="padding:12px; text-align: center;">${formatPrice(item.preco)}</td>
        <td style="padding:12px; text-align: center;">
          <input type="number" value="${item.quantidade}" min="1" max="${item.estoque}" 
                 class="qty-input" data-item-id="${item.item_carrinho_id}"
                 style="width:60px;padding:4px;border-radius:6px;border:1px solid rgba(255,255,255,0.2);background:#0b1020;color:#e6eef8; text-align: center;">
        </td>
        <td style="padding:12px; text-align: center;" class="item-subtotal">${formatPrice(item.preco * item.quantidade)}</td>
        <td style="padding:12px; text-align: center;">
          <button class="remove-btn" data-item-id="${item.item_carrinho_id}" 
                  style="background:#ff3b00; border: 0; padding: 6px 12px; border-radius: 6px; cursor: pointer; color: white;">
            ❌
          </button>
        </td>
      </tr>
    `).join('');

    updateTotals(subtotal);

    // Event listeners
    document.querySelectorAll('.qty-input').forEach(input => {
      input.addEventListener('change', (e) => updateQuantity(e.target));
    });

    document.querySelectorAll('.remove-btn').forEach(btn => {
      btn.addEventListener('click', (e) => removeItem(e.target.dataset.itemId));
    });
  }

  function updateTotals(subtotal) {
    const frete = subtotal > 0 ? FRETE : 0;
    const total = subtotal + frete;

    subtotalEl.textContent = formatPrice(subtotal);
    freteEl.textContent = formatPrice(frete);
    totalEl.textContent = formatPrice(total);
  }

  async function updateQuantity(input) {
    const item_id = parseInt(input.dataset.itemId);
    const quantidade = parseInt(input.value);

    if (quantidade < 1) {
      input.value = 1;
      return;
    }

    try {
      const res = await fetch('php/cart.php', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ item_id, quantidade })
      });

      const data = await res.json();
      if (!data.success) {
        alert(data.error || 'Erro ao atualizar quantidade');
        loadCart(); // Reload to get correct state
        return;
      }

      // Reload cart to update all totals
      loadCart();
    } catch (err) {
      alert('Erro ao atualizar quantidade');
      loadCart();
    }
  }

  async function removeItem(item_id) {
    if (!confirm('Deseja remover este item do carrinho?')) return;

    try {
      const res = await fetch('php/cart.php', {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ item_id: parseInt(item_id) })
      });

      const data = await res.json();
      if (!data.success) {
        alert(data.error || 'Erro ao remover item');
        return;
      }

      loadCart();
    } catch (err) {
      alert('Erro ao remover item');
    }
  }

  loadCart();
})();
