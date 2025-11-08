-- Verifica estrutura das tabelas e dados necessários

USE loja_hardware;

-- 1. Verifica categorias existentes
SELECT 'CATEGORIAS:' as info;
SELECT categoria_id, nome, categoria_pai_id FROM categoria ORDER BY categoria_id;

-- 2. Verifica fornecedores existentes
SELECT '' as info;
SELECT 'FORNECEDORES:' as info;
SELECT fornecedor_id, nome FROM fornecedor ORDER BY fornecedor_id;

-- 3. Verifica se existe fornecedor padrão
SELECT '' as info;
SELECT 'FORNECEDOR PADRAO (ID=1):' as info;
SELECT COUNT(*) as existe FROM fornecedor WHERE fornecedor_id = 1;

-- 4. Se não existir fornecedor padrão, cria um
INSERT IGNORE INTO fornecedor (fornecedor_id, cnpj, nome, telefone, email, endereco)
VALUES (1, '00000000000001', 'TechDistribuidora', '11999999999', 'tech@distribuidora.com', 'São Paulo, SP');

-- 5. Verifica produtos existentes
SELECT '' as info;
SELECT 'TOTAL DE PRODUTOS:' as info;
SELECT COUNT(*) as total FROM produto;

-- 6. Mostra últimos 5 produtos
SELECT '' as info;
SELECT 'ULTIMOS 5 PRODUTOS:' as info;
SELECT produto_id, nome, sku, categoria_id, fornecedor_id, preco, estoque 
FROM produto 
ORDER BY produto_id DESC 
LIMIT 5;
