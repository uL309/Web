# Web
Repo para trabalho da matéria de Web.

## Backend de Login (PHP + MySQL)

Arquivos adicionados:
- `php/config.php` — Conexão PDO com MySQL, sessões e utilidades JSON.
- `php/login.php` — Endpoint POST para autenticação do usuário na tabela `cliente`.
- `php/logout.php` — Endpoint POST para encerrar a sessão.
- `js/auth.js` — Lógica no front-end para enviar o formulário de login via fetch.
 - `php/register.php` — Endpoint POST para cadastro de cliente com `password_hash` e validações.
 - `js/register.js` — Lida com o envio do formulário de registro.

Pré-requisitos:
- MySQL em execução (localhost) e banco criado a partir de `banco1.sql`.
- PHP instalado e acessível no PATH do Windows.

Configurar credenciais (opcional, para valores diferentes do padrão):
- Variáveis de ambiente suportadas: `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`.

Executar o script SQL:
1. Abra o MySQL (Workbench, CLI) e execute o conteúdo de `banco1.sql`.
2. Insira ao menos um cliente para teste. Exemplo com senha em texto simples (apenas dev):
	- email: `teste@example.com`
	- senha: `123456`

Recomendado: armazenar senhas com `password_hash`. Exemplo em PHP:
```php
password_hash('123456', PASSWORD_DEFAULT);
```

### Servir o projeto localmente

No Windows PowerShell, dentro da pasta `Web`:

```powershell
php -S localhost:8000
```

Abra http://localhost:8000/login.html e teste o login.

Observações:
- `php/login.php` aceita corpo JSON (usado pelo `js/auth.js`) e também `application/x-www-form-urlencoded`.
- Se já possuir senhas antigas em texto, o endpoint tenta verificar primeiro com `password_verify` (hash) e, se não for hash, faz comparação simples. Sugestão: migre para `password_hash`.

### Cadastro

- Acesse http://localhost:8000/registro_cliente.html para criar uma conta.
- Campos obrigatórios: nome, cpf, data de nascimento, endereço, email, senha e confirmação.
- O backend valida unicidade de email e CPF e armazena a senha com `password_hash`.

