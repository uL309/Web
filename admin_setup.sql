-- Este script adiciona os campos necessários à tabela `cliente`
-- para o fluxo de recuperação de senha.
-- Execute isso no seu banco de dados `loja_hardware`.

USE loja_hardware;

ALTER TABLE `cliente`
ADD COLUMN `reset_token` VARCHAR(64) NULL DEFAULT NULL AFTER `is_admin`,
ADD COLUMN `reset_expires` DATETIME NULL DEFAULT NULL AFTER `reset_token`;

-- Adiciona um índice para performance na busca do token
CREATE INDEX `idx_reset_token` ON `cliente` (`reset_token`);