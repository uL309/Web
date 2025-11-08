-- Script de Correção - Ajustar IDs das Categorias
-- Execute este script se você já tem dados no banco

USE loja_hardware;

-- Desabilitar verificação de chave estrangeira temporariamente
SET FOREIGN_KEY_CHECKS = 0;

-- Deletar categorias existentes
DELETE FROM categoria;

-- Resetar auto_increment
ALTER TABLE categoria AUTO_INCREMENT = 1;

-- Recriar categorias com IDs corretos
-- Categorias principais
INSERT INTO categoria (categoria_id, nome, categoria_pai_id) VALUES
(1, 'Componentes', NULL),
(2, 'Periféricos', NULL),
(3, 'Monitores', NULL),
(4, 'Armazenamento', NULL);

-- Subcategorias com IDs específicos que correspondem ao frontend
INSERT INTO categoria (categoria_id, nome, categoria_pai_id) VALUES
(5, 'Placas de Vídeo', 1),
(6, 'Processadores', 1),
(7, 'Placas-mãe', 1),
(8, 'Memória RAM', 1),
(10, 'Teclados', 2),
(11, 'Mouses', 2),
(12, 'Headsets', 2),
(13, 'SSD', 4),
(14, 'HD', 4);

-- Atualizar categoria_id dos produtos para os novos IDs
-- Se você já tem produtos, ajuste conforme necessário:

-- Placas de Vídeo: categoria_id = 5
-- Processadores: categoria_id = 6
-- Placas-mãe: categoria_id = 7
-- Memória RAM: categoria_id = 8
-- Teclados: categoria_id = 10
-- Mouses: categoria_id = 11
-- Headsets: categoria_id = 12
-- SSD: categoria_id = 13
-- HD: categoria_id = 14
-- Monitores: categoria_id = 3

-- Reabilitar verificação de chave estrangeira
SET FOREIGN_KEY_CHECKS = 1;

-- Verificar categorias
SELECT categoria_id, nome, categoria_pai_id FROM categoria ORDER BY categoria_id;

-- Verificar produtos por categoria
SELECT 
    c.categoria_id,
    c.nome as categoria,
    COUNT(p.produto_id) as total_produtos
FROM categoria c
LEFT JOIN produto p ON c.categoria_id = p.categoria_id
GROUP BY c.categoria_id, c.nome
ORDER BY c.categoria_id;
