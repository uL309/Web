-- Popular banco de dados com dados de exemplo para teste do e-commerce

USE loja_hardware;

-- Categorias (usando IDs específicos para corresponder ao frontend)
-- Categorias principais
INSERT INTO categoria (categoria_id, nome, categoria_pai_id) VALUES
(1, 'Componentes', NULL),
(2, 'Periféricos', NULL),
(3, 'Monitores', NULL),
(4, 'Armazenamento', NULL);

-- Subcategorias com IDs específicos
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

-- Fornecedores
INSERT INTO fornecedor (cnpj, nome, telefone, email, endereco) VALUES
('12345678000190', 'TechDistribuidora LTDA', '11987654321', 'contato@techdist.com.br', 'Av. Paulista, 1000 - São Paulo/SP'),
('98765432000111', 'HardwareMax Importadora', '11976543210', 'vendas@hardwaremax.com.br', 'Rua dos Componentes, 500 - São Paulo/SP'),
('11122233000144', 'PerifericosBR', '11965432109', 'comercial@perifericosbr.com.br', 'Rua Augusta, 250 - São Paulo/SP');

-- Produtos
INSERT INTO produto (nome, descricao, especificacoes, fabricante, preco, estoque, sku, imagem, categoria_id, fornecedor_id) VALUES
-- Placas de Vídeo
('NVIDIA RTX 4090 24GB', 'Placa de vídeo top de linha para jogos e renderização', 'GPU: AD102 | 24GB GDDR6X | 384-bit | 2520 MHz Boost', 'NVIDIA', 14999.90, 15, 'GPU-RTX4090-24GB', 'https://via.placeholder.com/400x300/1a1a2e/00ff88?text=RTX+4090', 5, 1),
('AMD Radeon RX 7900 XTX', 'Placa de vídeo de alto desempenho', 'GPU: Navi 31 | 24GB GDDR6 | 384-bit | 2500 MHz Boost', 'AMD', 8999.90, 20, 'GPU-RX7900XTX', 'https://via.placeholder.com/400x300/1a1a2e/ff0088?text=RX+7900', 5, 1),
('NVIDIA RTX 4070 Ti 12GB', 'Ótima relação custo-benefício para jogos em 4K', 'GPU: AD104 | 12GB GDDR6X | 192-bit | 2610 MHz Boost', 'NVIDIA', 5499.90, 30, 'GPU-RTX4070TI', 'https://via.placeholder.com/400x300/1a1a2e/00ff88?text=RTX+4070Ti', 5, 2),

-- Processadores
('Intel Core i9-14900K', 'Processador de 24 núcleos para máximo desempenho', 'Socket LGA 1700 | 24 cores (8P+16E) | 3.2GHz base | 6.0GHz turbo | 36MB cache', 'Intel', 3899.90, 25, 'CPU-I9-14900K', 'https://via.placeholder.com/400x300/1a1a2e/0088ff?text=i9+14900K', 6, 1),
('AMD Ryzen 9 7950X', 'Processador de 16 núcleos para alta performance', 'Socket AM5 | 16 cores | 4.5GHz base | 5.7GHz boost | 64MB cache', 'AMD', 3299.90, 30, 'CPU-R9-7950X', 'https://via.placeholder.com/400x300/1a1a2e/ff0088?text=Ryzen+9', 6, 1),
('Intel Core i5-14600K', 'Processador intermediário com ótimo desempenho', 'Socket LGA 1700 | 14 cores (6P+8E) | 3.5GHz base | 5.3GHz turbo | 24MB cache', 'Intel', 1899.90, 40, 'CPU-I5-14600K', 'https://via.placeholder.com/400x300/1a1a2e/0088ff?text=i5+14600K', 6, 2),

-- Memória RAM
('Corsair Vengeance RGB 32GB (2x16GB) DDR5', 'Memória de alta velocidade com RGB', 'DDR5 6000MHz | CL30 | 1.35V | RGB Customizável', 'Corsair', 899.90, 50, 'RAM-CORS-32GB-DDR5', 'https://via.placeholder.com/400x300/1a1a2e/ff8800?text=Corsair+RAM', 8, 2),
('Kingston Fury Beast 16GB (2x8GB) DDR4', 'Memória DDR4 de alto desempenho', 'DDR4 3200MHz | CL16 | 1.35V | Dissipador de calor', 'Kingston', 399.90, 80, 'RAM-KING-16GB-DDR4', 'https://via.placeholder.com/400x300/1a1a2e/8800ff?text=Kingston+RAM', 8, 2),

-- Placas-mãe
('ASUS ROG Strix Z790-E Gaming', 'Placa-mãe premium para Intel 14ª geração', 'Socket LGA 1700 | DDR5 | PCIe 5.0 | WiFi 6E | 2.5Gb LAN | RGB', 'ASUS', 2899.90, 18, 'MB-ASUS-Z790E', 'https://via.placeholder.com/400x300/1a1a2e/ff0088?text=ASUS+Z790', 7, 1),
('MSI MAG B650 TOMAHAWK', 'Placa-mãe para AMD Ryzen 7000', 'Socket AM5 | DDR5 | PCIe 5.0 | 2.5Gb LAN | RGB', 'MSI', 1599.90, 25, 'MB-MSI-B650', 'https://via.placeholder.com/400x300/1a1a2e/ff0088?text=MSI+B650', 7, 1),

-- SSDs
('Samsung 990 PRO 2TB NVMe', 'SSD NVMe Gen 4 de altíssima velocidade', 'M.2 NVMe | PCIe 4.0 | 7450 MB/s leitura | 6900 MB/s escrita', 'Samsung', 1299.90, 45, 'SSD-SAM-990PRO-2TB', 'https://via.placeholder.com/400x300/1a1a2e/00ff88?text=990+PRO', 13, 2),
('WD Black SN850X 1TB', 'SSD NVMe para gamers', 'M.2 NVMe | PCIe 4.0 | 7300 MB/s leitura | 6300 MB/s escrita', 'Western Digital', 799.90, 60, 'SSD-WD-SN850X-1TB', 'https://via.placeholder.com/400x300/1a1a2e/00ff88?text=WD+Black', 13, 2),

-- Periféricos
('Logitech G Pro X Superlight', 'Mouse gamer sem fio ultra leve', 'Sensor HERO 25K | 25.600 DPI | 60g | Bateria 70h | Wireless', 'Logitech', 799.90, 35, 'MOUSE-LOG-GPRO', 'https://via.placeholder.com/400x300/1a1a2e/ffff00?text=G+Pro', 11, 3),
('Razer BlackWidow V4 Pro', 'Teclado mecânico premium', 'Switch Green | RGB | Apoio de pulso | Comandos programáveis | USB', 'Razer', 1299.90, 28, 'KB-RAZ-BWV4PRO', 'https://via.placeholder.com/400x300/1a1a2e/00ff88?text=BlackWidow', 10, 3),
('HyperX Cloud III', 'Headset gamer com áudio espacial', '7.1 Surround | Driver 53mm | Noise Cancelling | USB', 'HyperX', 649.90, 40, 'HEAD-HX-CLOUD3', 'https://via.placeholder.com/400x300/1a1a2e/ff0088?text=Cloud+III', 12, 3),

-- Monitores
('LG UltraGear 27" 240Hz', 'Monitor gamer IPS com alta taxa de atualização', '27" IPS | 2560x1440 | 240Hz | 1ms | G-Sync | HDR400', 'LG', 2499.90, 22, 'MON-LG-27-240HZ', 'https://via.placeholder.com/400x300/1a1a2e/0088ff?text=LG+27', 3, 2),
('Samsung Odyssey G9 49"', 'Monitor ultra-wide curvo para imersão total', '49" VA Curvo | 5120x1440 | 240Hz | 1ms | G-Sync | HDR1000', 'Samsung', 7999.90, 8, 'MON-SAM-G9-49', 'https://via.placeholder.com/400x300/1a1a2e/0088ff?text=Odyssey+G9', 3, 2);

-- Cliente de teste (senha: teste123)
INSERT INTO cliente (nome, cpf, endereco, telefone, email, senha, data_nascimento) VALUES
('João Silva', '12345678901', 'Rua Teste, 123 - São Paulo/SP', '11999887766', 'joao@teste.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1990-05-15');

-- Avaliações de exemplo
INSERT INTO avaliacao (cliente_id, produto_id, nota, comentario, data) VALUES
(1, 1, 5, 'Placa de vídeo excepcional! Roda tudo no máximo em 4K sem esforço.', NOW()),
(1, 4, 5, 'Processador muito rápido, compilação de código ficou muito mais ágil!', NOW()),
(1, 13, 4, 'Mouse excelente, mas poderia ter mais botões programáveis.', NOW());
