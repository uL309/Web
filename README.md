# CiberTech - Sistema de E-commerce Completo

Projeto de loja online de hardware e periféricos com backend PHP e MySQL.

## 🚀 Funcionalidades Implementadas

### 📦 Módulos Principais

1. **Autenticação e Autorização**
   - Login de usuário com sessão PHP
   - Cadastro de cliente com validação
   - Logout e gerenciamento de sessão
   - Hash seguro de senhas (`password_hash`)

2. **Catálogo de Produtos**
   - Listagem de produtos com paginação
   - Filtros por categoria, preço e busca
   - Detalhes do produto com especificações
   - Sistema de avaliações (apenas para quem comprou)

3. **Carrinho de Compras**
   - Adicionar/remover produtos
   - Atualizar quantidades
   - Carrinho persistente (sessão/usuário)
   - Validação de estoque em tempo real

4. **Checkout e Pagamentos**
   - Finalização de pedido
   - Cálculo de frete
   - Múltiplas formas de pagamento (crédito, débito, PIX, boleto)
   - Parcelamento
   - Geração automática de código de rastreamento

5. **Gestão de Pedidos**
   - Histórico de pedidos do cliente
   - Acompanhamento de status
   - Detalhes de entrega e rastreamento

6. **Sistema de Avaliações**
   - Clientes podem avaliar produtos comprados
   - Nota de 1 a 5 estrelas + comentário
   - Exibição de média de avaliações

## 📁 Estrutura de Arquivos

### Backend (PHP)
```
php/
├── config.php       # Configuração, conexão DB, sessões, helpers
├── login.php        # Autenticação
├── register.php     # Cadastro de cliente
├── logout.php       # Encerrar sessão
├── me.php           # Info do usuário logado
├── products.php     # Listar produtos (com filtros)
├── product.php      # Detalhes de um produto
├── categories.php   # Listar categorias
├── cart.php         # Gerenciar carrinho (GET/POST/PUT/DELETE)
├── checkout.php     # Processar pedido
├── orders.php       # Listar/detalhar pedidos do cliente
└── reviews.php      # Adicionar/listar avaliações
```

### Frontend (JavaScript)
```
js/
├── header.js        # Gerenciamento de sessão no header
├── auth.js          # Login do usuário
├── register.js      # Cadastro de cliente
├── cart.js          # Carrinho de compras dinâmico
└── orders.js        # Listagem de pedidos
```

### Páginas HTML
```
├── index.html               # Página inicial
├── login.html               # Login
├── registro_cliente.html    # Cadastro
├── carrinho.html            # Carrinho
├── checkout.html            # Finalização de compra
├── pedidos.html             # Meus pedidos
├── produto1.html            # Detalhes do produto
└── style.css                # Estilos globais
```

### Banco de Dados
```
├── banco1.sql       # Schema do banco (estrutura)
└── populate_db.sql  # Dados de exemplo (produtos, categorias, etc.)
```

## ⚙️ Configuração e Instalação

### Pré-requisitos
- PHP 7.4+ (recomendado PHP 8.0+)
- MySQL 5.7+ ou MariaDB 10.3+
- Servidor web (Apache/Nginx) ou PHP built-in server

### Passo 1: Configurar o Banco de Dados

1. Crie o banco e estrutura:
```sql
mysql -u root -p < banco1.sql
```

2. Popule com dados de exemplo:
```sql
mysql -u root -p < populate_db.sql
```

### Passo 2: Configurar Credenciais (Opcional)

Por padrão, o sistema usa:
- **Host:** 127.0.0.1
- **Porta:** 3306
- **Banco:** loja_hardware
- **Usuário:** root
- **Senha:** (vazio)

Para alterar, defina variáveis de ambiente antes de iniciar o servidor:
```powershell
$env:DB_HOST="127.0.0.1"
$env:DB_PORT="3306"
$env:DB_NAME="loja_hardware"
$env:DB_USER="root"
$env:DB_PASS="sua_senha"
```

### Passo 3: Iniciar o Servidor

No Windows PowerShell, dentro da pasta `Web`:

```powershell
php -S localhost:8000
```

Acesse: http://localhost:8000

## 🧪 Testando o Sistema

### Usuário de Teste
Após executar `populate_db.sql`, você terá:
- **Email:** joao@teste.com
- **Senha:** teste123
- **Nome:** João Silva

### Fluxo de Teste Completo

1. **Cadastro**
   - Acesse http://localhost:8000/registro_cliente.html
   - Preencha os dados e crie uma conta
   - Você será autenticado automaticamente

2. **Navegar e Adicionar ao Carrinho**
   - Navegue pelos produtos na página inicial
   - Clique em um produto para ver detalhes
   - Adicione ao carrinho

3. **Finalizar Compra**
   - Acesse http://localhost:8000/carrinho.html
   - Revise os itens
   - Clique em "Finalizar Compra"
   - Escolha forma de pagamento
   - Confirme o pedido

4. **Acompanhar Pedidos**
   - Acesse http://localhost:8000/pedidos.html
   - Veja histórico e detalhes

5. **Avaliar Produto**
   - Após comprar, avalie o produto na página de detalhes

## 🔒 Segurança Implementada

- ✅ Senhas com hash `password_hash()` (bcrypt)
- ✅ Prepared statements (previne SQL injection)
- ✅ Validação de entrada no backend
- ✅ Verificação de estoque antes de finalizar
- ✅ Sessões com configuração segura
- ✅ Autenticação necessária para ações críticas

## 📊 Estrutura do Banco de Dados

### Tabelas Principais
- `cliente` - Dados dos usuários
- `produto` - Catálogo de produtos
- `categoria` - Categorias (com hierarquia)
- `fornecedor` - Fornecedores de produtos
- `carrinho` / `item_carrinho` - Carrinho de compras
- `pedido` / `item_pedido` - Pedidos realizados
- `pagamento` - Registros de pagamento
- `entrega` - Rastreamento de entregas
- `avaliacao` - Avaliações de produtos

## 🛠️ Tecnologias Utilizadas

- **Backend:** PHP 8.0+, PDO MySQL
- **Frontend:** HTML5, CSS3, JavaScript (Vanilla)
- **Banco de Dados:** MySQL 8.0
- **Autenticação:** Sessões PHP nativas
- **Arquitetura:** REST-like API (JSON responses)

## 📝 Observações

- Todos os endpoints retornam JSON
- O carrinho funciona tanto para usuários logados quanto convidados
- Apenas clientes que compraram podem avaliar produtos
- O estoque é atualizado automaticamente ao finalizar compra
- Códigos de rastreamento são gerados automaticamente