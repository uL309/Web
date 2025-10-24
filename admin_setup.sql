-- Adiciona campo is_admin na tabela cliente
USE loja_hardware;

ALTER TABLE cliente ADD COLUMN is_admin BOOLEAN DEFAULT FALSE AFTER data_nascimento;

-- Define o primeiro cliente como admin (ajuste o ID conforme necessário)
-- UPDATE cliente SET is_admin = TRUE WHERE cliente_id = 1;
