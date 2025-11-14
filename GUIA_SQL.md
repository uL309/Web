# 📊 GUIA SQL - CiberTech E-commerce

> **Documentação Completa do Banco de Dados**
> 
> Sistema de gerenciamento de banco de dados para e-commerce de hardware e periféricos.

---

## 📑 Índice

1. [Visão Geral](#visão-geral)
2. [Schema Principal](#schema-principal)
3. [Tabelas do Sistema](#tabelas-do-sistema)
4. [Relacionamentos](#relacionamentos)
5. [Scripts de Manutenção](#scripts-de-manutenção)
6. [População de Dados](#população-de-dados)
7. [Índices e Performance](#índices-e-performance)
8. [Boas Práticas](#boas-práticas)

---

## 🎯 Visão Geral

### Estatísticas do Banco de Dados

```
📊 Total de Tabelas: 11
📊 Total de Scripts: 5
📊 Relacionamentos (FK): 15
📊 Índices Customizados: 1
📊 Categorias Base: 4
📊 Subcategorias: 9
📊 Produtos de Exemplo: 17
```

### Arquivos SQL do Projeto

| Arquivo | Propósito | Tipo |
|---------|-----------|------|
| `banco1.sql` | Schema principal do banco | DDL |
| `populate_db.sql` | Dados de exemplo/seed | DML |
| `admin_setup.sql` | Campos de recuperação de senha | DDL (ALTER) |
| `verificar_estrutura.sql` | Verificação de integridade | DQL |
| `fix_categories.sql` | Correção de IDs de categorias | DML |

---

## 🗄️ Schema Principal

### Nome do Banco de Dados

```sql
CREATE DATABASE IF NOT EXISTS loja_hardware;
USE loja_hardware;
```

**Características:**
- Nome: `loja_hardware`
- Charset: UTF-8 (padrão)
- Collation: utf8mb4_general_ci (recomendado)
- Engine: InnoDB (todas as tabelas)

---

## 📋 Tabelas do Sistema

### 1. 👤 CLIENTE

**Descrição:** Armazena informações dos clientes cadastrados no e-commerce.

```sql
CREATE TABLE IF NOT EXISTS cliente(
    cliente_id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(11) UNIQUE NOT NULL,
    endereco TEXT NOT NULL,
    telefone VARCHAR(11),
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(100) NOT NULL,
    data_nascimento DATE NOT NULL,
    reset_token VARCHAR(64) NULL DEFAULT NULL,
    reset_expires DATETIME NULL DEFAULT NULL
);
```

**Campos:**

| Campo | Tipo | Restrições | Descrição |
|-------|------|------------|-----------|
| `cliente_id` | INT | PK, AUTO_INCREMENT | Identificador único |
| `nome` | VARCHAR(100) | NOT NULL | Nome completo |
| `cpf` | VARCHAR(11) | UNIQUE, NOT NULL | CPF sem formatação |
| `endereco` | TEXT | NOT NULL | Endereço completo |
| `telefone` | VARCHAR(11) | - | Telefone sem formatação |
| `email` | VARCHAR(100) | UNIQUE, NOT NULL | Email único |
| `senha` | VARCHAR(100) | NOT NULL | Hash da senha (bcrypt) |
| `data_nascimento` | DATE | NOT NULL | Data de nascimento |
| `reset_token` | VARCHAR(64) | NULL | Token de recuperação de senha |
| `reset_expires` | DATETIME | NULL | Expiração do token |

**Índices:**
- PRIMARY KEY: `cliente_id`
- UNIQUE: `cpf`, `email`
- INDEX: `idx_reset_token` (para performance em recuperação de senha)

**Exemplo de Registro:**
```sql
INSERT INTO cliente (nome, cpf, endereco, telefone, email, senha, data_nascimento) 
VALUES (
    'João Silva', 
    '12345678901', 
    'Rua Teste, 123 - São Paulo/SP', 
    '11999887766', 
    'joao@teste.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    '1990-05-15'
);
```

**Regras de Negócio:**
- ✅ CPF deve ser único e conter exatamente 11 dígitos
- ✅ Email deve ser único e válido
- ✅ Senha armazenada como hash bcrypt (PHP `password_hash()`)
- ✅ Token de reset expira após período definido (geralmente 1 hora)

---

### 2. 🏷️ CATEGORIA

**Descrição:** Sistema hierárquico de categorias e subcategorias de produtos.

```sql
CREATE TABLE IF NOT EXISTS categoria(
    categoria_id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    categoria_pai_id INT,
    FOREIGN KEY (categoria_pai_id) REFERENCES categoria(categoria_id)
);
```

**Campos:**

| Campo | Tipo | Restrições | Descrição |
|-------|------|------------|-----------|
| `categoria_id` | INT | PK, AUTO_INCREMENT | Identificador único |
| `nome` | VARCHAR(100) | NOT NULL | Nome da categoria |
| `categoria_pai_id` | INT | FK (self), NULL | Referência à categoria pai |

**Relacionamentos:**
- **Self-referencing**: `categoria_pai_id` → `categoria(categoria_id)`

**Estrutura Hierárquica:**

```
Componentes (1)
├── Placas de Vídeo (5)
├── Processadores (6)
├── Placas-mãe (7)
└── Memória RAM (8)

Periféricos (2)
├── Teclados (10)
├── Mouses (11)
└── Headsets (12)

Monitores (3)

Armazenamento (4)
├── SSD (13)
└── HD (14)
```

**IDs Fixos (Sincronização com Frontend):**
```sql
-- Categorias principais
(1, 'Componentes', NULL)
(2, 'Periféricos', NULL)
(3, 'Monitores', NULL)
(4, 'Armazenamento', NULL)

-- Subcategorias
(5, 'Placas de Vídeo', 1)
(6, 'Processadores', 1)
(7, 'Placas-mãe', 1)
(8, 'Memória RAM', 1)
(10, 'Teclados', 2)
(11, 'Mouses', 2)
(12, 'Headsets', 2)
(13, 'SSD', 4)
(14, 'HD', 4)
```

**⚠️ IMPORTANTE:** Os IDs das categorias são fixos e devem corresponder aos valores hardcoded no JavaScript do frontend.

---

### 3. 🏭 FORNECEDOR

**Descrição:** Informações dos fornecedores de produtos.

```sql
CREATE TABLE IF NOT EXISTS fornecedor(
    fornecedor_id INT PRIMARY KEY AUTO_INCREMENT,
    cnpj VARCHAR(14) UNIQUE NOT NULL,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(11) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    endereco TEXT NOT NULL
);
```

**Campos:**

| Campo | Tipo | Restrições | Descrição |
|-------|------|------------|-----------|
| `fornecedor_id` | INT | PK, AUTO_INCREMENT | Identificador único |
| `cnpj` | VARCHAR(14) | UNIQUE, NOT NULL | CNPJ sem formatação |
| `nome` | VARCHAR(100) | NOT NULL | Razão social |
| `telefone` | VARCHAR(11) | NOT NULL | Telefone comercial |
| `email` | VARCHAR(100) | UNIQUE, NOT NULL | Email comercial |
| `endereco` | TEXT | NOT NULL | Endereço completo |

**Índices:**
- PRIMARY KEY: `fornecedor_id`
- UNIQUE: `cnpj`, `email`

**Fornecedor Padrão:**
```sql
INSERT IGNORE INTO fornecedor (fornecedor_id, cnpj, nome, telefone, email, endereco)
VALUES (
    1, 
    '00000000000001', 
    'TechDistribuidora', 
    '11999999999', 
    'tech@distribuidora.com', 
    'São Paulo, SP'
);
```

**Regras de Negócio:**
- ✅ CNPJ deve ser único e conter 14 dígitos
- ✅ Fornecedor com ID=1 é criado como padrão se não existir

---

### 4. 📦 PRODUTO

**Descrição:** Catálogo completo de produtos disponíveis no e-commerce.

```sql
CREATE TABLE IF NOT EXISTS produto(
    produto_id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(200) NOT NULL,
    descricao TEXT NOT NULL,
    especificacoes TEXT NOT NULL,
    fabricante VARCHAR(100) NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    estoque INT NOT NULL,
    sku VARCHAR(100) UNIQUE NOT NULL,
    imagem TEXT,
    categoria_id INT,
    fornecedor_id INT,
    FOREIGN KEY (categoria_id) REFERENCES categoria(categoria_id),
    FOREIGN KEY (fornecedor_id) REFERENCES fornecedor(fornecedor_id)
);
```

**Campos:**

| Campo | Tipo | Restrições | Descrição |
|-------|------|------------|-----------|
| `produto_id` | INT | PK, AUTO_INCREMENT | Identificador único |
| `nome` | VARCHAR(200) | NOT NULL | Nome do produto |
| `descricao` | TEXT | NOT NULL | Descrição marketing |
| `especificacoes` | TEXT | NOT NULL | Specs técnicas |
| `fabricante` | VARCHAR(100) | NOT NULL | Marca/fabricante |
| `preco` | DECIMAL(10,2) | NOT NULL | Preço unitário |
| `estoque` | INT | NOT NULL | Quantidade disponível |
| `sku` | VARCHAR(100) | UNIQUE, NOT NULL | Código único (Stock Keeping Unit) |
| `imagem` | TEXT | - | URL da imagem |
| `categoria_id` | INT | FK | Referência à categoria |
| `fornecedor_id` | INT | FK | Referência ao fornecedor |

**Relacionamentos:**
- **N:1** com `categoria` via `categoria_id`
- **N:1** com `fornecedor` via `fornecedor_id`

**Índices:**
- PRIMARY KEY: `produto_id`
- UNIQUE: `sku`
- FOREIGN KEY: `categoria_id`, `fornecedor_id`

**Exemplo de Produto:**
```sql
INSERT INTO produto (nome, descricao, especificacoes, fabricante, preco, estoque, sku, imagem, categoria_id, fornecedor_id) 
VALUES (
    'NVIDIA RTX 4090 24GB',
    'Placa de vídeo top de linha para jogos e renderização',
    'GPU: AD102 | 24GB GDDR6X | 384-bit | 2520 MHz Boost',
    'NVIDIA',
    14999.90,
    15,
    'GPU-RTX4090-24GB',
    'https://via.placeholder.com/400x300/1a1a2e/00ff88?text=RTX+4090',
    5,  -- Categoria: Placas de Vídeo
    1   -- Fornecedor: TechDistribuidora
);
```

**Regras de Negócio:**
- ✅ SKU deve ser único no sistema
- ✅ Preço com 2 casas decimais
- ✅ Estoque não pode ser negativo
- ✅ Imagem usa placeholders se não definida

---

### 5. 🛒 CARRINHO

**Descrição:** Carrinhos de compras ativos dos clientes.

```sql
CREATE TABLE IF NOT EXISTS carrinho(
    carrinho_id INT PRIMARY KEY AUTO_INCREMENT,
    data_criacao DATETIME NOT NULL,
    cliente_id INT,
    FOREIGN KEY (cliente_id) REFERENCES cliente(cliente_id)
);
```

**Campos:**

| Campo | Tipo | Restrições | Descrição |
|-------|------|------------|-----------|
| `carrinho_id` | INT | PK, AUTO_INCREMENT | Identificador único |
| `data_criacao` | DATETIME | NOT NULL | Timestamp de criação |
| `cliente_id` | INT | FK | Referência ao cliente |

**Relacionamentos:**
- **N:1** com `cliente` via `cliente_id`
- **1:N** com `item_carrinho`

**Regras de Negócio:**
- ✅ Cada cliente pode ter apenas 1 carrinho ativo
- ✅ Carrinho persiste até checkout ou expiração
- ✅ Data de criação registrada automaticamente

---

### 6. 🛍️ ITEM_CARRINHO

**Descrição:** Itens individuais dentro de cada carrinho.

```sql
CREATE TABLE IF NOT EXISTS item_carrinho(
    item_carrinho_id INT PRIMARY KEY AUTO_INCREMENT,
    quantidade INT NOT NULL,
    carrinho_id INT,
    produto_id INT,
    FOREIGN KEY (carrinho_id) REFERENCES carrinho(carrinho_id),
    FOREIGN KEY (produto_id) REFERENCES produto(produto_id)
);
```

**Campos:**

| Campo | Tipo | Restrições | Descrição |
|-------|------|------------|-----------|
| `item_carrinho_id` | INT | PK, AUTO_INCREMENT | Identificador único |
| `quantidade` | INT | NOT NULL | Quantidade do produto |
| `carrinho_id` | INT | FK | Referência ao carrinho |
| `produto_id` | INT | FK | Referência ao produto |

**Relacionamentos:**
- **N:1** com `carrinho` via `carrinho_id`
- **N:1** com `produto` via `produto_id`

**Regras de Negócio:**
- ✅ Quantidade deve ser > 0
- ✅ Não armazena preço (obtido do produto em tempo real)
- ✅ Um produto pode aparecer apenas uma vez por carrinho

---

### 7. 📝 PEDIDO

**Descrição:** Registros de pedidos finalizados pelos clientes.

```sql
CREATE TABLE IF NOT EXISTS pedido(
    pedido_id INT PRIMARY KEY AUTO_INCREMENT,
    data_pedido DATETIME NOT NULL,
    status VARCHAR(100) NOT NULL,
    valor_total DECIMAL(10,2) NOT NULL,
    frete DECIMAL(10,2) NOT NULL,
    cliente_id INT,
    FOREIGN KEY (cliente_id) REFERENCES cliente(cliente_id)
);
```

**Campos:**

| Campo | Tipo | Restrições | Descrição |
|-------|------|------------|-----------|
| `pedido_id` | INT | PK, AUTO_INCREMENT | Identificador único |
| `data_pedido` | DATETIME | NOT NULL | Timestamp do pedido |
| `status` | VARCHAR(100) | NOT NULL | Status atual |
| `valor_total` | DECIMAL(10,2) | NOT NULL | Total (produtos + frete) |
| `frete` | DECIMAL(10,2) | NOT NULL | Valor do frete |
| `cliente_id` | INT | FK | Referência ao cliente |

**Relacionamentos:**
- **N:1** com `cliente` via `cliente_id`
- **1:N** com `item_pedido`
- **1:1** com `pagamento`
- **1:1** com `entrega`

**Status Possíveis:**
```
- pendente
- aguardando_pagamento
- pago
- em_separacao
- em_transporte
- entregue
- cancelado
```

**Regras de Negócio:**
- ✅ Valor total = soma dos itens + frete
- ✅ Status inicial: "pendente"
- ✅ Data do pedido registrada no momento da criação

---

### 8. 📋 ITEM_PEDIDO

**Descrição:** Itens individuais que compõem cada pedido.

```sql
CREATE TABLE IF NOT EXISTS item_pedido(
    item_pedido_id INT PRIMARY KEY AUTO_INCREMENT,
    quantidade INT NOT NULL,
    preco_no_momento DECIMAL(10,2) NOT NULL,
    pedido_id INT,
    produto_id INT,
    FOREIGN KEY (pedido_id) REFERENCES pedido(pedido_id),
    FOREIGN KEY (produto_id) REFERENCES produto(produto_id)
);
```

**Campos:**

| Campo | Tipo | Restrições | Descrição |
|-------|------|------------|-----------|
| `item_pedido_id` | INT | PK, AUTO_INCREMENT | Identificador único |
| `quantidade` | INT | NOT NULL | Quantidade comprada |
| `preco_no_momento` | DECIMAL(10,2) | NOT NULL | Preço do produto no checkout |
| `pedido_id` | INT | FK | Referência ao pedido |
| `produto_id` | INT | FK | Referência ao produto |

**Relacionamentos:**
- **N:1** com `pedido` via `pedido_id`
- **N:1** com `produto` via `produto_id`

**⚠️ IMPORTANTE:** O campo `preco_no_momento` congela o preço do produto no momento da compra, garantindo histórico preciso mesmo se o preço do produto mudar posteriormente.

---

### 9. 💳 PAGAMENTO

**Descrição:** Informações de pagamento dos pedidos.

```sql
CREATE TABLE IF NOT EXISTS pagamento(
    pagamento_id INT PRIMARY KEY AUTO_INCREMENT,
    tipo VARCHAR(100) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    data_pagamento DATETIME NOT NULL,
    status VARCHAR(100) NOT NULL,
    parcelas INT NOT NULL,
    pedido_id INT,
    FOREIGN KEY (pedido_id) REFERENCES pedido(pedido_id)
);
```

**Campos:**

| Campo | Tipo | Restrições | Descrição |
|-------|------|------------|-----------|
| `pagamento_id` | INT | PK, AUTO_INCREMENT | Identificador único |
| `tipo` | VARCHAR(100) | NOT NULL | Forma de pagamento |
| `valor` | DECIMAL(10,2) | NOT NULL | Valor pago |
| `data_pagamento` | DATETIME | NOT NULL | Timestamp do pagamento |
| `status` | VARCHAR(100) | NOT NULL | Status do pagamento |
| `parcelas` | INT | NOT NULL | Número de parcelas |
| `pedido_id` | INT | FK | Referência ao pedido |

**Relacionamentos:**
- **1:1** com `pedido` via `pedido_id`

**Tipos de Pagamento:**
```
- credito
- debito
- pix
- boleto
```

**Status de Pagamento:**
```
- pendente
- aprovado
- recusado
- estornado
```

**Regras de Negócio:**
- ✅ Valor deve corresponder ao `valor_total` do pedido
- ✅ Parcelas = 1 para débito, PIX e boleto
- ✅ Parcelas ≥ 1 para crédito

---

### 10. 🚚 ENTREGA

**Descrição:** Informações de entrega e rastreamento dos pedidos.

```sql
CREATE TABLE IF NOT EXISTS entrega(
    entrega_id INT PRIMARY KEY AUTO_INCREMENT,
    data_envio DATETIME,
    data_entrega DATETIME,
    status_entrega VARCHAR(100) NOT NULL,
    codigo_rastreamento VARCHAR(100) UNIQUE NOT NULL,
    transportadora VARCHAR(100) NOT NULL,
    pedido_id INT,
    FOREIGN KEY (pedido_id) REFERENCES pedido(pedido_id)
);
```

**Campos:**

| Campo | Tipo | Restrições | Descrição |
|-------|------|------------|-----------|
| `entrega_id` | INT | PK, AUTO_INCREMENT | Identificador único |
| `data_envio` | DATETIME | NULL | Data de postagem |
| `data_entrega` | DATETIME | NULL | Data de entrega efetiva |
| `status_entrega` | VARCHAR(100) | NOT NULL | Status atual |
| `codigo_rastreamento` | VARCHAR(100) | UNIQUE, NOT NULL | Código de rastreio |
| `transportadora` | VARCHAR(100) | NOT NULL | Nome da transportadora |
| `pedido_id` | INT | FK | Referência ao pedido |

**Relacionamentos:**
- **1:1** com `pedido` via `pedido_id`

**Status de Entrega:**
```
- preparando
- postado
- em_transito
- saiu_para_entrega
- entregue
- tentativa_falha
- devolvido
```

**Regras de Negócio:**
- ✅ Código de rastreamento único
- ✅ `data_envio` preenchida quando status = "postado"
- ✅ `data_entrega` preenchida quando status = "entregue"

---

### 11. ⭐ AVALIACAO

**Descrição:** Avaliações e comentários dos clientes sobre produtos.

```sql
CREATE TABLE IF NOT EXISTS avaliacao(
    avaliacao_id INT PRIMARY KEY AUTO_INCREMENT,
    nota INT CHECK (nota >= 1 AND nota <= 5),
    comentario TEXT,
    data DATETIME,
    cliente_id INT,
    produto_id INT,
    FOREIGN KEY (cliente_id) REFERENCES cliente(cliente_id),
    FOREIGN KEY (produto_id) REFERENCES produto(produto_id)
);
```

**Campos:**

| Campo | Tipo | Restrições | Descrição |
|-------|------|------------|-----------|
| `avaliacao_id` | INT | PK, AUTO_INCREMENT | Identificador único |
| `nota` | INT | CHECK (1-5) | Nota de 1 a 5 estrelas |
| `comentario` | TEXT | NULL | Comentário opcional |
| `data` | DATETIME | - | Timestamp da avaliação |
| `cliente_id` | INT | FK | Referência ao cliente |
| `produto_id` | INT | FK | Referência ao produto |

**Relacionamentos:**
- **N:1** com `cliente` via `cliente_id`
- **N:1** com `produto` via `produto_id`

**Constraint:**
- ✅ Nota deve estar entre 1 e 5 (CHECK constraint)

**Exemplo:**
```sql
INSERT INTO avaliacao (cliente_id, produto_id, nota, comentario, data) 
VALUES (
    1, 
    1, 
    5, 
    'Placa de vídeo excepcional! Roda tudo no máximo em 4K sem esforço.', 
    NOW()
);
```

---

## 🔗 Relacionamentos

### Diagrama de Relacionamentos (Textual)

```
CLIENTE (1)
├──> CARRINHO (N) - Um cliente pode ter vários carrinhos
├──> PEDIDO (N) - Um cliente pode fazer vários pedidos
└──> AVALIACAO (N) - Um cliente pode avaliar vários produtos

CATEGORIA (1)
├──> CATEGORIA (N) - Categoria pode ter subcategorias (self-referencing)
└──> PRODUTO (N) - Uma categoria possui vários produtos

FORNECEDOR (1)
└──> PRODUTO (N) - Um fornecedor fornece vários produtos

PRODUTO (1)
├──> ITEM_CARRINHO (N) - Um produto pode estar em vários carrinhos
├──> ITEM_PEDIDO (N) - Um produto pode estar em vários pedidos
└──> AVALIACAO (N) - Um produto pode ter várias avaliações

CARRINHO (1)
└──> ITEM_CARRINHO (N) - Um carrinho contém vários itens

PEDIDO (1)
├──> ITEM_PEDIDO (N) - Um pedido contém vários itens
├──> PAGAMENTO (1) - Um pedido tem um pagamento
└──> ENTREGA (1) - Um pedido tem uma entrega
```

### Mapeamento de Foreign Keys

| Tabela | Campo FK | Referencia | Tipo |
|--------|----------|------------|------|
| `categoria` | `categoria_pai_id` | `categoria(categoria_id)` | 1:N (self) |
| `produto` | `categoria_id` | `categoria(categoria_id)` | N:1 |
| `produto` | `fornecedor_id` | `fornecedor(fornecedor_id)` | N:1 |
| `carrinho` | `cliente_id` | `cliente(cliente_id)` | N:1 |
| `item_carrinho` | `carrinho_id` | `carrinho(carrinho_id)` | N:1 |
| `item_carrinho` | `produto_id` | `produto(produto_id)` | N:1 |
| `pedido` | `cliente_id` | `cliente(cliente_id)` | N:1 |
| `item_pedido` | `pedido_id` | `pedido(pedido_id)` | N:1 |
| `item_pedido` | `produto_id` | `produto(produto_id)` | N:1 |
| `pagamento` | `pedido_id` | `pedido(pedido_id)` | 1:1 |
| `entrega` | `pedido_id` | `pedido(pedido_id)` | 1:1 |
| `avaliacao` | `cliente_id` | `cliente(cliente_id)` | N:1 |
| `avaliacao` | `produto_id` | `produto(produto_id)` | N:1 |

**Total de Foreign Keys:** 15

---

## 🔧 Scripts de Manutenção

### 1. admin_setup.sql

**Propósito:** Adicionar campos para sistema de recuperação de senha.

**Arquivo:** `admin_setup.sql`

```sql
USE loja_hardware;

ALTER TABLE `cliente`
ADD COLUMN `reset_token` VARCHAR(64) NULL DEFAULT NULL AFTER `is_admin`,
ADD COLUMN `reset_expires` DATETIME NULL DEFAULT NULL AFTER `reset_token`;

CREATE INDEX `idx_reset_token` ON `cliente` (`reset_token`);
```

**Quando Executar:**
- ✅ Após criar o schema inicial
- ✅ Antes de implementar funcionalidade "Esqueci minha senha"

**Mudanças:**
- Adiciona campo `reset_token` (64 caracteres)
- Adiciona campo `reset_expires` (timestamp de expiração)
- Cria índice para otimizar buscas por token

---

### 2. verificar_estrutura.sql

**Propósito:** Script de diagnóstico e verificação de integridade.

**Arquivo:** `verificar_estrutura.sql`

```sql
USE loja_hardware;

-- Verifica categorias existentes
SELECT categoria_id, nome, categoria_pai_id 
FROM categoria 
ORDER BY categoria_id;

-- Verifica fornecedores existentes
SELECT fornecedor_id, nome 
FROM fornecedor 
ORDER BY fornecedor_id;

-- Verifica se existe fornecedor padrão
SELECT COUNT(*) as existe 
FROM fornecedor 
WHERE fornecedor_id = 1;

-- Se não existir fornecedor padrão, cria um
INSERT IGNORE INTO fornecedor (fornecedor_id, cnpj, nome, telefone, email, endereco)
VALUES (1, '00000000000001', 'TechDistribuidora', '11999999999', 'tech@distribuidora.com', 'São Paulo, SP');

-- Verifica total de produtos
SELECT COUNT(*) as total 
FROM produto;

-- Mostra últimos 5 produtos
SELECT produto_id, nome, sku, categoria_id, fornecedor_id, preco, estoque 
FROM produto 
ORDER BY produto_id DESC 
LIMIT 5;
```

**Quando Executar:**
- ✅ Após popular banco de dados
- ✅ Após fazer alterações no schema
- ✅ Para debug de problemas de dados

**Output Esperado:**
```
CATEGORIAS: Lista de todas as categorias
FORNECEDORES: Lista de todos os fornecedores
FORNECEDOR PADRAO (ID=1): 1 (existe) ou 0 (criado automaticamente)
TOTAL DE PRODUTOS: Número total
ULTIMOS 5 PRODUTOS: Últimos produtos cadastrados
```

---

### 3. fix_categories.sql

**Propósito:** Corrigir IDs de categorias para sincronizar com frontend.

**Arquivo:** `fix_categories.sql`

**⚠️ ATENÇÃO:** Este script **DELETA TODAS AS CATEGORIAS** e recria com IDs fixos.

```sql
USE loja_hardware;

-- Desabilitar verificação de FK temporariamente
SET FOREIGN_KEY_CHECKS = 0;

-- Deletar categorias existentes
DELETE FROM categoria;

-- Resetar auto_increment
ALTER TABLE categoria AUTO_INCREMENT = 1;

-- Recriar categorias com IDs corretos
INSERT INTO categoria (categoria_id, nome, categoria_pai_id) VALUES
(1, 'Componentes', NULL),
(2, 'Periféricos', NULL),
(3, 'Monitores', NULL),
(4, 'Armazenamento', NULL),
(5, 'Placas de Vídeo', 1),
(6, 'Processadores', 1),
(7, 'Placas-mãe', 1),
(8, 'Memória RAM', 1),
(10, 'Teclados', 2),
(11, 'Mouses', 2),
(12, 'Headsets', 2),
(13, 'SSD', 4),
(14, 'HD', 4);

-- Reabilitar verificação de FK
SET FOREIGN_KEY_CHECKS = 1;
```

**Quando Executar:**
- ✅ Se os IDs das categorias não correspondem ao frontend
- ✅ Após importar dados de sistema legado
- ✅ Para resetar categorias ao padrão

**⚠️ CUIDADO:**
- Deleta TODAS as categorias
- Produtos ficam sem categoria (FK null)
- Execute ANTES de popular produtos

---

## 📊 População de Dados

### populate_db.sql

**Propósito:** Popular banco com dados de exemplo para desenvolvimento/teste.

**Arquivo:** `populate_db.sql`

#### Dados Inseridos:

**1. Categorias:** 4 principais + 9 subcategorias (13 total)

**2. Fornecedores:** 3 fornecedores

```sql
(1) TechDistribuidora LTDA - CNPJ: 12345678000190
(2) HardwareMax Importadora - CNPJ: 98765432000111
(3) PerifericosBR - CNPJ: 11122233000144
```

**3. Produtos:** 17 produtos de exemplo

| Categoria | Quantidade | Exemplos |
|-----------|------------|----------|
| Placas de Vídeo | 3 | RTX 4090, RX 7900 XTX, RTX 4070 Ti |
| Processadores | 3 | i9-14900K, Ryzen 9 7950X, i5-14600K |
| Memória RAM | 2 | Corsair Vengeance 32GB DDR5, Kingston Fury 16GB DDR4 |
| Placas-mãe | 2 | ASUS ROG Z790-E, MSI MAG B650 |
| SSDs | 2 | Samsung 990 PRO 2TB, WD Black SN850X 1TB |
| Periféricos | 3 | Logitech G Pro X, Razer BlackWidow V4, HyperX Cloud III |
| Monitores | 2 | LG UltraGear 27" 240Hz, Samsung Odyssey G9 49" |

**4. Cliente de Teste:**

```
Nome: João Silva
Email: joao@teste.com
Senha: teste123
CPF: 12345678901
```

**5. Avaliações:** 3 avaliações de exemplo

#### Quando Executar:

- ✅ Em ambiente de desenvolvimento
- ✅ Para testes de funcionalidades
- ✅ Após criar schema inicial

#### ⚠️ NÃO EXECUTAR EM PRODUÇÃO

---

## 🚀 Índices e Performance

### Índices Existentes

#### Índices Primários (AUTO_INCREMENT)

Todas as tabelas possuem PRIMARY KEY com AUTO_INCREMENT:

```sql
cliente(cliente_id)
categoria(categoria_id)
fornecedor(fornecedor_id)
produto(produto_id)
carrinho(carrinho_id)
item_carrinho(item_carrinho_id)
pedido(pedido_id)
item_pedido(item_pedido_id)
pagamento(pagamento_id)
entrega(entrega_id)
avaliacao(avaliacao_id)
```

#### Índices UNIQUE

Garantem unicidade de valores:

```sql
cliente(cpf)
cliente(email)
fornecedor(cnpj)
fornecedor(email)
produto(sku)
entrega(codigo_rastreamento)
```

#### Índices de Foreign Keys

MySQL/InnoDB cria índices automaticamente para FKs:

```sql
categoria(categoria_pai_id)
produto(categoria_id)
produto(fornecedor_id)
carrinho(cliente_id)
item_carrinho(carrinho_id)
item_carrinho(produto_id)
pedido(cliente_id)
item_pedido(pedido_id)
item_pedido(produto_id)
pagamento(pedido_id)
entrega(pedido_id)
avaliacao(cliente_id)
avaliacao(produto_id)
```

#### Índices Customizados

**1. idx_reset_token (cliente)**

```sql
CREATE INDEX `idx_reset_token` ON `cliente` (`reset_token`);
```

**Propósito:** Otimizar busca por token de recuperação de senha.

**Query Otimizada:**
```sql
SELECT * FROM cliente WHERE reset_token = 'abc123...';
```

---

### Recomendações de Performance

#### Índices Adicionais Sugeridos:

**1. Índice Composto em Pedido**

```sql
CREATE INDEX idx_cliente_status ON pedido(cliente_id, status);
```

**Benefício:** Acelera consultas de pedidos por cliente e status.

```sql
-- Query otimizada
SELECT * FROM pedido 
WHERE cliente_id = 1 
AND status = 'em_transporte';
```

**2. Índice em Data de Pedido**

```sql
CREATE INDEX idx_data_pedido ON pedido(data_pedido DESC);
```

**Benefício:** Ordenação rápida por data recente.

```sql
-- Query otimizada
SELECT * FROM pedido 
ORDER BY data_pedido DESC 
LIMIT 10;
```

**3. Índice Composto em Avaliação**

```sql
CREATE INDEX idx_produto_nota ON avaliacao(produto_id, nota);
```

**Benefício:** Cálculo rápido de média de notas por produto.

```sql
-- Query otimizada
SELECT AVG(nota) as media 
FROM avaliacao 
WHERE produto_id = 1;
```

**4. Índice em Status de Entrega**

```sql
CREATE INDEX idx_status_entrega ON entrega(status_entrega);
```

**Benefício:** Filtrar entregas por status rapidamente.

```sql
-- Query otimizada
SELECT COUNT(*) 
FROM entrega 
WHERE status_entrega = 'em_transito';
```

---

### Otimização de Queries

#### Query 1: Produtos por Categoria (com subcategorias)

```sql
-- Sem JOIN recursivo (aproximação)
SELECT p.* 
FROM produto p
WHERE p.categoria_id IN (5, 6, 7, 8)  -- IDs das subcategorias de Componentes
ORDER BY p.nome;
```

**Otimização:** Usar lista fixa de IDs de subcategorias.

#### Query 2: Pedidos com Detalhes

```sql
SELECT 
    p.pedido_id,
    p.data_pedido,
    p.status,
    p.valor_total,
    pg.tipo as forma_pagamento,
    e.status_entrega,
    e.codigo_rastreamento
FROM pedido p
LEFT JOIN pagamento pg ON p.pedido_id = pg.pedido_id
LEFT JOIN entrega e ON p.pedido_id = e.pedido_id
WHERE p.cliente_id = 1
ORDER BY p.data_pedido DESC;
```

**Índices Usados:** `pedido(cliente_id)`, `pagamento(pedido_id)`, `entrega(pedido_id)`

#### Query 3: Produtos Mais Vendidos

```sql
SELECT 
    pr.produto_id,
    pr.nome,
    SUM(ip.quantidade) as total_vendido
FROM item_pedido ip
JOIN produto pr ON ip.produto_id = pr.produto_id
GROUP BY pr.produto_id, pr.nome
ORDER BY total_vendido DESC
LIMIT 10;
```

**Otimização:** Criar índice `item_pedido(produto_id, quantidade)`.

#### Query 4: Média de Avaliações por Produto

```sql
SELECT 
    p.produto_id,
    p.nome,
    AVG(a.nota) as media_avaliacoes,
    COUNT(a.avaliacao_id) as total_avaliacoes
FROM produto p
LEFT JOIN avaliacao a ON p.produto_id = a.produto_id
GROUP BY p.produto_id, p.nome
HAVING total_avaliacoes > 0
ORDER BY media_avaliacoes DESC;
```

**Índices Usados:** `avaliacao(produto_id, nota)` (sugerido).

---

## ✅ Boas Práticas

### 1. Segurança

#### ✅ Senhas

```php
// PHP - SEMPRE usar password_hash()
$senha_hash = password_hash($senha, PASSWORD_BCRYPT);

// Verificação
if (password_verify($senha_digitada, $senha_hash)) {
    // Login válido
}
```

**❌ NUNCA:**
- Armazenar senhas em texto plano
- Usar MD5 ou SHA1 para senhas
- Usar criptografia reversível

#### ✅ SQL Injection

```php
// Usar Prepared Statements
$stmt = $conn->prepare("SELECT * FROM cliente WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
```

**❌ NUNCA:**
```php
// Interpolação direta - VULNERÁVEL!
$query = "SELECT * FROM cliente WHERE email = '$email'";
```

---

### 2. Integridade de Dados

#### ✅ Transações

```sql
START TRANSACTION;

-- Criar pedido
INSERT INTO pedido (data_pedido, status, valor_total, frete, cliente_id) 
VALUES (NOW(), 'pendente', 1599.90, 50.00, 1);

SET @pedido_id = LAST_INSERT_ID();

-- Adicionar itens
INSERT INTO item_pedido (quantidade, preco_no_momento, pedido_id, produto_id) 
VALUES (1, 1549.90, @pedido_id, 1);

-- Deduzir estoque
UPDATE produto SET estoque = estoque - 1 WHERE produto_id = 1;

COMMIT;
```

**Benefício:** Garante que todas as operações são executadas ou nenhuma.

#### ✅ Constraints

```sql
-- CHECK constraint em nota
CREATE TABLE avaliacao(
    ...
    nota INT CHECK (nota >= 1 AND nota <= 5),
    ...
);
```

---

### 3. Performance

#### ✅ LIMIT em Queries

```sql
-- SEMPRE usar LIMIT em listagens
SELECT * FROM produto ORDER BY nome LIMIT 20;
```

#### ✅ Índices em Colunas de Busca

```sql
-- Se busca por fabricante é frequente
CREATE INDEX idx_fabricante ON produto(fabricante);
```

#### ✅ Evitar SELECT *

```sql
-- ❌ Evitar
SELECT * FROM produto;

-- ✅ Preferir
SELECT produto_id, nome, preco, imagem FROM produto;
```

---

### 4. Manutenção

#### ✅ Backup Regular

```bash
# Backup completo
mysqldump -u root -p loja_hardware > backup_loja_$(date +%Y%m%d).sql

# Backup apenas schema
mysqldump -u root -p --no-data loja_hardware > schema_loja.sql

# Backup apenas dados
mysqldump -u root -p --no-create-info loja_hardware > dados_loja.sql
```

#### ✅ Monitoramento

```sql
-- Verificar tamanho das tabelas
SELECT 
    table_name AS `Tabela`,
    round(((data_length + index_length) / 1024 / 1024), 2) AS `Tamanho (MB)`
FROM information_schema.TABLES
WHERE table_schema = 'loja_hardware'
ORDER BY (data_length + index_length) DESC;
```

#### ✅ Limpeza de Dados

```sql
-- Deletar carrinhos antigos (> 30 dias sem atividade)
DELETE FROM item_carrinho 
WHERE carrinho_id IN (
    SELECT carrinho_id FROM carrinho 
    WHERE data_criacao < DATE_SUB(NOW(), INTERVAL 30 DAY)
);

DELETE FROM carrinho 
WHERE data_criacao < DATE_SUB(NOW(), INTERVAL 30 DAY);
```

---

## 📐 Modelagem de Dados

### Normalização

O banco de dados está na **3ª Forma Normal (3NF)**:

✅ **1NF:** Todos os campos são atômicos (sem arrays ou listas)
✅ **2NF:** Não há dependências parciais (todas as colunas dependem da PK completa)
✅ **3NF:** Não há dependências transitivas

**Exemplo de 3NF:**

```
❌ NÃO NORMALIZADO:
pedido(pedido_id, cliente_nome, cliente_email, cliente_cpf, ...)

✅ NORMALIZADO:
pedido(pedido_id, cliente_id, ...)
cliente(cliente_id, nome, email, cpf, ...)
```

---

### Design Patterns Aplicados

#### 1. **Congelamento de Preço**

`item_pedido.preco_no_momento` armazena o preço no checkout:

```sql
INSERT INTO item_pedido (quantidade, preco_no_momento, pedido_id, produto_id)
SELECT 1, p.preco, @pedido_id, p.produto_id
FROM produto p
WHERE p.produto_id = 1;
```

**Benefício:** Histórico preciso mesmo se o preço mudar.

#### 2. **Soft Delete (Sugestão)**

Adicionar campo `ativo` em vez de deletar registros:

```sql
ALTER TABLE produto ADD COLUMN ativo BOOLEAN DEFAULT TRUE;

-- "Deletar" produto
UPDATE produto SET ativo = FALSE WHERE produto_id = 1;

-- Listar apenas ativos
SELECT * FROM produto WHERE ativo = TRUE;
```

#### 3. **Hierarquia de Categorias**

Self-referencing FK permite árvore infinita:

```sql
SELECT 
    c1.nome as categoria_pai,
    c2.nome as subcategoria
FROM categoria c1
LEFT JOIN categoria c2 ON c1.categoria_id = c2.categoria_pai_id
WHERE c1.categoria_pai_id IS NULL;
```

---

## 🎓 Queries Úteis

### 1. Relatório de Vendas por Categoria

```sql
SELECT 
    c.nome as categoria,
    COUNT(DISTINCT p.pedido_id) as total_pedidos,
    SUM(ip.quantidade) as total_unidades,
    SUM(ip.preco_no_momento * ip.quantidade) as valor_total
FROM categoria c
JOIN produto pr ON c.categoria_id = pr.categoria_id
JOIN item_pedido ip ON pr.produto_id = ip.produto_id
JOIN pedido p ON ip.pedido_id = p.pedido_id
WHERE p.status NOT IN ('cancelado')
GROUP BY c.categoria_id, c.nome
ORDER BY valor_total DESC;
```

### 2. Top 10 Clientes

```sql
SELECT 
    cl.cliente_id,
    cl.nome,
    cl.email,
    COUNT(p.pedido_id) as total_pedidos,
    SUM(p.valor_total) as valor_total_gasto
FROM cliente cl
JOIN pedido p ON cl.cliente_id = p.cliente_id
WHERE p.status NOT IN ('cancelado')
GROUP BY cl.cliente_id, cl.nome, cl.email
ORDER BY valor_total_gasto DESC
LIMIT 10;
```

### 3. Produtos com Estoque Baixo

```sql
SELECT 
    produto_id,
    nome,
    estoque,
    preco
FROM produto
WHERE estoque < 10 AND estoque > 0
ORDER BY estoque ASC;
```

### 4. Pedidos Pendentes de Entrega

```sql
SELECT 
    p.pedido_id,
    cl.nome as cliente,
    p.data_pedido,
    e.status_entrega,
    e.codigo_rastreamento,
    e.transportadora
FROM pedido p
JOIN cliente cl ON p.cliente_id = cl.cliente_id
LEFT JOIN entrega e ON p.pedido_id = e.pedido_id
WHERE e.status_entrega IN ('em_transito', 'postado', 'saiu_para_entrega')
ORDER BY p.data_pedido ASC;
```

### 5. Produtos Sem Avaliação

```sql
SELECT 
    p.produto_id,
    p.nome,
    p.preco
FROM produto p
LEFT JOIN avaliacao a ON p.produto_id = a.produto_id
WHERE a.avaliacao_id IS NULL
ORDER BY p.nome;
```

---

## 🔄 Fluxo de Dados Completo

### Jornada do Cliente

```
1. CADASTRO
   └─> INSERT INTO cliente

2. NAVEGAÇÃO
   └─> SELECT FROM produto WHERE categoria_id = ?

3. ADICIONAR AO CARRINHO
   ├─> INSERT INTO carrinho (se não existe)
   └─> INSERT INTO item_carrinho

4. CHECKOUT
   ├─> START TRANSACTION
   ├─> INSERT INTO pedido
   ├─> INSERT INTO item_pedido (copia de item_carrinho com preco_no_momento)
   ├─> UPDATE produto SET estoque = estoque - quantidade
   ├─> DELETE FROM item_carrinho
   ├─> INSERT INTO pagamento
   ├─> INSERT INTO entrega
   └─> COMMIT

5. ACOMPANHAMENTO
   └─> SELECT FROM pedido JOIN entrega

6. AVALIAÇÃO
   └─> INSERT INTO avaliacao
```

---

## 📝 Checklist de Instalação

### Setup Inicial

```sql
☑️ 1. Executar banco1.sql (schema principal)
☑️ 2. Executar admin_setup.sql (campos de reset)
☑️ 3. Executar populate_db.sql (dados de exemplo)
☑️ 4. Verificar com verificar_estrutura.sql
☑️ 5. Se necessário, executar fix_categories.sql
```

### Verificação

```sql
-- Verificar tabelas criadas
SHOW TABLES;

-- Verificar estrutura
DESCRIBE cliente;
DESCRIBE produto;
DESCRIBE pedido;

-- Verificar dados
SELECT COUNT(*) FROM categoria;  -- Deve retornar 13
SELECT COUNT(*) FROM produto;    -- Deve retornar 17
SELECT COUNT(*) FROM fornecedor; -- Deve retornar 3
```

---

## 🎯 Conclusão

Este banco de dados foi projetado para suportar um e-commerce completo de hardware e periféricos, com:

✅ **11 tabelas** inter-relacionadas
✅ **15 foreign keys** garantindo integridade referencial
✅ **Normalização** até 3ª Forma Normal
✅ **Segurança** com hashing de senhas e tokens de reset
✅ **Performance** com índices estratégicos
✅ **Rastreabilidade** completa de pedidos e entregas
✅ **Histórico** preservado com preços congelados
✅ **Escalabilidade** com hierarquia de categorias flexível

**Próximos Passos Recomendados:**

1. Implementar índices adicionais sugeridos
2. Criar views para queries complexas frequentes
3. Implementar stored procedures para operações críticas
4. Configurar backups automáticos
5. Implementar auditoria de alterações (trigger logs)
6. Adicionar campo `ativo` para soft delete

---

**Documentação Gerada:** CiberTech E-commerce Database
**Versão:** 1.0
**Última Atualização:** 2024
**Autor:** Sistema de Documentação Automatizada
