// Header user session management
(() => {
  const actionsDiv = document.querySelector('.actions');
  if (!actionsDiv) return;

  async function checkSession() {
    try {
      const res = await fetch('php/me.php');
      const data = await res.json();
      
      if (data.logged_in && data.user) {
        // User is logged in - show personalized header
        const { nome, is_admin } = data.user;
        const firstName = nome.split(' ')[0];
        
        const adminButton = is_admin 
          ? '<a href="admin-produtos.html"><button class="icon-btn" aria-label="Admin" style="color: var(--primary);">⚙️ Admin</button></a>'
          : '';
        
        actionsDiv.innerHTML = `
          <a href="carrinho.html"><button class="icon-btn" aria-label="Carrinho">🛒 Carrinho</button></a>
          <a href="pedidos.html"><button class="icon-btn" aria-label="Meus Pedidos">📦 Pedidos</button></a>
          ${adminButton}
          <button class="icon-btn" id="user-menu-btn" aria-label="Perfil">👤 ${firstName}</button>
          <button class="icon-btn" id="logout-btn" aria-label="Sair" style="color: var(--muted)">Sair</button>
        `;
        
        document.getElementById('logout-btn')?.addEventListener('click', logout);
      } else {
        // Not logged in - show default buttons
        actionsDiv.innerHTML = `
          <a href="carrinho.html"><button class="icon-btn" aria-label="Carrinho">🛒 Carrinho</button></a>
          <a href="registro_cliente.html"><button class="icon-btn" aria-label="Registrar">Registrar</button></a>
          <a href="login.html"><button class="icon-btn" aria-label="Login">Entrar</button></a>
        `;
      }
    } catch (err) {
      console.error('Failed to check session:', err);
    }
  }

  async function logout() {
    try {
      await fetch('php/logout.php', { method: 'POST' });
      window.location.href = 'index.html';
    } catch (err) {
      console.error('Logout failed:', err);
    }
  }

  checkSession();
})();
