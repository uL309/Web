(() => {
  const loadingEl = document.getElementById('orders-loading');
  const listEl = document.getElementById('orders-list');
  const emptyEl = document.getElementById('orders-empty');

  const statusColors = {
    'Aguardando Pagamento': '#ff9500',
    'Pagamento Confirmado': '#00cc88',
    'Em Separação': '#0088ff',
    'Enviado': '#00aa88',
    'Entregue': '#00ff88',
    'Cancelado': '#ff3366'
  };

  function formatDate(dateStr) {
    if (!dateStr) return '-';
    const d = new Date(dateStr);
    return d.toLocaleDateString('pt-BR') + ' ' + d.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
  }

  function formatPrice(value) {
    return 'R$ ' + parseFloat(value).toFixed(2).replace('.', ',');
  }

  async function loadOrders() {
    try {
      const res = await fetch('php/orders.php');
      const data = await res.json();

      loadingEl.style.display = 'none';

      if (!data.success) {
        // Not logged in
        if (res.status === 401) {
          window.location.href = 'login.html';
          return;
        }
        throw new Error(data.error || 'Erro ao carregar pedidos');
      }

      if (!data.orders || data.orders.length === 0) {
        emptyEl.style.display = 'block';
        return;
      }

      listEl.style.display = 'block';
      listEl.innerHTML = data.orders.map(order => `
        <div class="hero-card" style="margin-bottom: 24px; cursor: pointer;" onclick="window.location.href='pedido-detalhe.html?id=${order.pedido_id}'">
          <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px;">
            <div>
              <div style="font-weight: 700; font-size: 18px;">Pedido #${order.pedido_id}</div>
              <div style="color: var(--muted); font-size: 14px;">${formatDate(order.data_pedido)}</div>
            </div>
            <div style="text-align: right;">
              <div style="font-weight: 700; font-size: 18px; color: var(--brand);">${formatPrice(order.valor_total)}</div>
              <div style="color: ${statusColors[order.status] || 'var(--muted)'}; font-weight: 600; font-size: 14px;">${order.status}</div>
            </div>
          </div>
          <div style="color: var(--muted); font-size: 14px;">
            ${order.total_items} item${order.total_items !== 1 ? 's' : ''}
          </div>
        </div>
      `).join('');

    } catch (err) {
      loadingEl.innerHTML = `<p style="color: #ff3366;">Erro ao carregar pedidos: ${err.message}</p>`;
    }
  }

  loadOrders();
})();
