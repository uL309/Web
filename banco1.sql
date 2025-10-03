create database if not exists loja_hardware; use loja_hardware; 

create table if not EXISTS cliente( cliente_id int primary key auto_increment, nome varchar(100) not null, cpf varchar(11) unique not null, endereco text not null, telefone varchar(11), email varchar(100) unique not null, senha varchar(100) not null, data_nascimento date not null ); 

create table if not EXISTS categoria( categoria_id int primary key auto_increment, nome varchar(100) not null, categoria_pai_id int, foreign key (categoria_pai_id) references categoria(categoria_id) ); 

create table if not EXISTS fornecedor( fornecedor_id int PRIMARY KEY AUTO_INCREMENT, cnpj varchar(14) unique not null, nome varchar(100) not null, telefone varchar(11) not null, email varchar(100) unique not null, endereco text not null ); 

create table if not EXISTS produto( produto_id int PRIMARY KEY AUTO_INCREMENT, nome varchar(200) not null, descricao text not null, especificacoes text not null, fabricante varchar(100) not null, preco decimal(10,2) not null, estoque int not null, sku varchar(100) unique not null, imagem text, categoria_id int, fornecedor_id int, foreign key (categoria_id) references categoria(categoria_id), foreign key (fornecedor_id) REFERENCES fornecedor(fornecedor_id) ); 

create table if not EXISTS pedido( pedido_id int PRIMARY KEY AUTO_INCREMENT, data_pedido datetime not null, status varchar(100) not null, valor_total decimal(10,2) not null, frete decimal(10,2) not null, cliente_id int, foreign key (cliente_id) references cliente(cliente_id) ); 

create table if not EXISTS pagamento( pagamento_id int PRIMARY key AUTO_INCREMENT, tipo varchar(100) not null, valor decimal(10,2) not null, data_pagamento datetime not null, status varchar(100) not null, parcelas int not null, pedido_id int, FOREIGN KEY (pedido_id) REFERENCES pedido(pedido_id) ); 

create table if not exists entrega( entrega_id int PRIMARY key AUTO_INCREMENT, data_envio datetime, data_entrega datetime, status_entrega varchar(100) not null, codigo_rastreamento varchar(100) unique not null, transportadora varchar(100) not null, pedido_id int, FOREIGN key (pedido_id) REFERENCES pedido(pedido_id) ); 

create table if not exists item_pedido( item_pedido_id int PRIMARY key AUTO_INCREMENT, quantidade int not null, preco_no_momento decimal(10,2) not null, pedido_id int, produto_id int, FOREIGN key (pedido_id) REFERENCES pedido(pedido_id), FOREIGN key (produto_id) REFERENCES produto(produto_id) ); 

create table if not exists carrinho( carrinho_id int PRIMARY key AUTO_INCREMENT, data_criacao datetime not null, cliente_id int, foreign key (cliente_id) references cliente(cliente_id) ); 

create table if not exists item_carrinho( item_carrinho_id int PRIMARY key AUTO_INCREMENT, quantidade int not null, carrinho_id int, produto_id int, foreign key (carrinho_id) references carrinho(carrinho_id), FOREIGN key (produto_id) REFERENCES produto(produto_id) ); 

create table if not exists avaliacao( avaliacao_id int PRIMARY key AUTO_INCREMENT, nota int check (nota >= 1 and nota <= 5), comentario text, data datetime, cliente_id int, produto_id int, foreign key (cliente_id) references cliente(cliente_id), FOREIGN key (produto_id) REFERENCES produto(produto_id) ) 