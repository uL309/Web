(() => {
  const form = document.querySelector('#register-form');
  if (!form) return;

  const field = (id) => document.getElementById(id);
  const showError = (msg) => {
    const box = document.getElementById('register-error');
    if (!box) return;
    box.textContent = msg;
    box.style.display = 'block';
  };
  const clearError = () => {
    const box = document.getElementById('register-error');
    if (!box) return;
    box.textContent = '';
    box.style.display = 'none';
  };
  const setLoading = (loading) => {
    const btn = form.querySelector('button[type="submit"]');
    if (!btn) return;
    if (loading) {
      btn.disabled = true;
      btn.dataset._origText = btn.textContent;
      btn.textContent = 'Criando…';
    } else {
      btn.disabled = false;
      if (btn.dataset._origText) btn.textContent = btn.dataset._origText;
    }
  };

  function normalizeDigits(v) { return (v || '').replace(/\D+/g, ''); }

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    clearError();

    const payload = {
      nome: field('nome')?.value?.trim() || '',
      cpf: normalizeDigits(field('cpf')?.value),
      data_nascimento: field('data_nascimento')?.value || '',
      telefone: normalizeDigits(field('telefone')?.value),
      endereco: field('endereco')?.value?.trim() || '',
      email: field('email')?.value?.trim() || '',
      senha: field('senha')?.value || '',
      confirmar_senha: field('confirmar_senha')?.value || '',
    };

    // Simple client validations
    if (!payload.nome || !payload.cpf || !payload.data_nascimento || !payload.endereco || !payload.email || !payload.senha || !payload.confirmar_senha) {
      showError('Preencha todos os campos obrigatórios.');
      return;
    }
    if (payload.senha !== payload.confirmar_senha) {
      showError('As senhas não coincidem.');
      return;
    }

    setLoading(true);
    try {
      const res = await fetch('php/register.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
      const data = await res.json().catch(() => ({}));

      if (!res.ok || !data.success) {
        const msg = data?.error || (data?.errors && Object.values(data.errors)[0]) || 'Não foi possível criar a conta.';
        showError(msg);
        return;
      }

      // Go to homepage or login
      window.location.href = 'index.html';
    } catch (err) {
      showError('Erro de rede. Tente novamente.');
    } finally {
      setLoading(false);
    }
  });
})();
