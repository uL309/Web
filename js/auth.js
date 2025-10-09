(() => {
  function qs(sel, root = document) { return root.querySelector(sel); }
  function ce(tag, props = {}) { const el = document.createElement(tag); Object.assign(el, props); return el; }

  const form = qs('#login-form');
  if (!form) return;

  const emailInput = qs('#email');
  const passwordInput = qs('#password');
  const errorBox = qs('#login-error');
  const submitBtn = form.querySelector('button[type="submit"]');

  function setLoading(loading) {
    if (loading) {
      submitBtn.disabled = true;
      submitBtn.dataset._origText = submitBtn.textContent;
      submitBtn.textContent = 'Entrando…';
    } else {
      submitBtn.disabled = false;
      if (submitBtn.dataset._origText) submitBtn.textContent = submitBtn.dataset._origText;
    }
  }

  function showError(msg) {
    if (!errorBox) return;
    errorBox.textContent = msg;
    errorBox.style.display = 'block';
  }

  function clearError() {
    if (!errorBox) return;
    errorBox.textContent = '';
    errorBox.style.display = 'none';
  }

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    clearError();

    const email = (emailInput?.value || '').trim();
    const password = passwordInput?.value || '';
    if (!email || !password) {
      showError('Informe email e senha.');
      return;
    }

    setLoading(true);
    try {
      const res = await fetch('php/login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password })
      });
      const data = await res.json().catch(() => ({}));
      if (!res.ok || !data.success) {
        showError(data.error || 'Falha no login.');
        return;
      }

      // Redirect to homepage after login
      window.location.href = 'index.html';
    } catch (err) {
      showError('Erro de rede. Tente novamente.');
    } finally {
      setLoading(false);
    }
  });
})();
