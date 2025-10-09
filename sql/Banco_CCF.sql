-- Criando o banco de dados
CREATE DATABASE CasaDeCarnes_Fernandes;
USE CasaDeCarnes_Fernandes;

-- Tabela: Usuario
CREATE TABLE tbUsuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    sobrenome VARCHAR(100) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    tipo_usuario ENUM('cliente', 'admin') NOT NULL DEFAULT 'cliente',
    telefone VARCHAR(15) NOT NULL
);

-- Inserts: Usuario
INSERT INTO tbUsuario(nome, sobrenome, senha, tipo_usuario, telefone)
VALUES ("Casa de Carnes", "Fernandes", "123", "admin", "(17) 99201-8283");

INSERT INTO tbUsuario(nome, sobrenome, senha, tipo_usuario, telefone)
VALUES ("teste", "teste", "123", "cliente", "(11) 11111-1111");
-- -----------------------------------------------------------------------------------------

-- Tabela: Categoria
CREATE TABLE tbCategoria (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nome_categoria VARCHAR(50) NOT NULL
);

-- Inserts: Categoria
INSERT INTO tbCategoria(nome_categoria)
VALUES ("Bovinos"),
		 ("Aves"),
		 ("Suinos"),
		 ("Linguicas"),
		 ("embutidos"),
		 ("churrasco"),
		 ("kits");
-- ------------------------------------------------------------------------------------------

-- Tabela: Corte
CREATE TABLE tbcorte (
	id_corte INT AUTO_INCREMENT PRIMARY KEY,
	nome_corte VARCHAR(50) NOT null
);

INSERT INTO tbcorte(nome_corte)
VALUES ("manta"),
		 ("bife"),
		 ("panela"),
		 ("moida"),
		 ("peça"),
		 ("strogonoff"),
		 ("tirinha"),
		 ("medalhão"),
		 ("espetinho");
-- -----------------------------------------------------------------------------------------

-- Tabela: Produto
CREATE TABLE tbProduto (
    id_produto INT AUTO_INCREMENT PRIMARY KEY,
    cod_categoria INT NOT NULL,
    nome_produto VARCHAR(100) NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    peso_minimo DECIMAL(10,2) NOT NULL,
    intervalo_peso DECIMAL(10,2) NOT NULL,
    descricao TEXT,
    imagem_url VARCHAR(255),
    tipo_quantidade ENUM('UNIDADE', 'PESO') NOT NULL,
    FOREIGN KEY (cod_categoria) REFERENCES tbCategoria(id_categoria)
        ON DELETE CASCADE ON UPDATE CASCADE
);
INSERT INTO tbProduto 
(cod_categoria, nome_produto, preco, peso_minimo, intervalo_peso, descricao, imagem_url, tipo_quantidade)
VALUES
(1, 'Kit Mistura', 27.90, 0.50, 0.10, 'Kit de carnes variadas para o dia a dia.', 'img/kitMistura.png', 'PESO');

INSERT INTO tbProduto 
(cod_categoria, nome_produto, preco, peso_minimo, intervalo_peso, descricao, imagem_url, tipo_quantidade)
VALUES
(1, 'Contra Filé', 69.90, 1.00, 0.10, 'Corte bovino macio e saboroso, ideal para grelha.', 'img/contraFile.png', 'PESO');

INSERT INTO tbProduto 
(cod_categoria, nome_produto, preco, peso_minimo, intervalo_peso, descricao, imagem_url, tipo_quantidade)
VALUES
(1, 'Carne Moída Bovina', 34.90, 0.50, 0.10, 'Carne moída fresca, ideal para refogados e recheios.', 'img/carneMoida.png', 'PESO');

INSERT INTO tbProduto 
(cod_categoria, nome_produto, preco, peso_minimo, intervalo_peso, descricao, imagem_url, tipo_quantidade)
VALUES
(1, 'Pão de Hambúrguer', 9.90, 0.20, 0.10, 'Pão macio próprio para hambúrgueres artesanais.', 'img/pao.png', 'UNIDADE');

INSERT INTO tbProduto 
(cod_categoria, nome_produto, preco, peso_minimo, intervalo_peso, descricao, imagem_url, tipo_quantidade)
VALUES
(1, 'Fraldinha Bovina', 59.90, 0.80, 0.10, 'Carne bovina macia, ideal para churrasco e assados.', 'img/fraldinha.png', 'PESO');

INSERT INTO tbProduto 
(cod_categoria, nome_produto, preco, peso_minimo, intervalo_peso, descricao, imagem_url, tipo_quantidade)
VALUES
(1, 'Hambúrguer Bovino Artesanal', 14.90, 0.15, 0.05, 'Hambúrguer artesanal feito com carne selecionada.', 'img/hamburguer.png', 'UNIDADE');




-- --------------------------------------------------------------------------------------------

-- Tabela: quantidade de corte do produto------------------------------------------------------
CREATE TABLE tbQuantidadeCorte(
	cod_produto INT NOT NULL,
 	cod_corte INT NOT NULL,
 	FOREIGN KEY (cod_produto) REFERENCES tbProduto(id_produto)
        ON DELETE CASCADE ON UPDATE CASCADE,
   FOREIGN KEY (cod_corte) REFERENCES tbcorte(id_corte)
        ON DELETE CASCADE ON UPDATE CASCADE
);
-- --------------------------------------------------------------------------------------------


-- Tabela: Pedido
CREATE TABLE tbPedido (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    cod_usuario INT NOT NULL,
    data_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    tipo_pedido ENUM('ENTREGA', 'RETIRADA') NOT NULL,
    horario_retirada TIME,
    status ENUM('PENDENTE', 'PRONTO', 'ENTREGUE') NOT NULL DEFAULT 'PENDENTE',
    forma_pagamento VARCHAR(50) NOT NULL,
    preco_total DECIMAL(10,2) DEFAULT 0.00,
    notificado BOOLEAN DEFAULT FALSE,
    endereco TEXT NOT NULL,
    FOREIGN KEY (cod_usuario) REFERENCES tbUsuario(id_usuario)
        ON DELETE CASCADE ON UPDATE CASCADE
);
-- -------------------------------------------------------------------------------------------

-- Tabela: PedidoProduto (itens do pedido)
CREATE TABLE tbPedidoProduto (
    id_pedido_produto INT AUTO_INCREMENT PRIMARY KEY,
    cod_pedido INT NOT NULL,
    cod_produto INT NOT NULL,
    cod_corte INT NULL,
    quantidade DECIMAL(10,2) NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    preco_total_prod DECIMAL(10,2) DEFAULT 0.00,
    observacao VARCHAR(120),
    INDEX idx_tbPedidoProduto_pedido (cod_pedido),
    INDEX idx_tbPedidoProduto_produto (cod_produto),
    INDEX idx_tbPedidoProduto_corte (cod_corte),
    CONSTRAINT fk_tbPedidoProduto_pedido FOREIGN KEY (cod_pedido) REFERENCES tbPedido(id_pedido)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_tbPedidoProduto_produto FOREIGN KEY (cod_produto) REFERENCES tbProduto(id_produto)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_tbPedidoProduto_corte FOREIGN KEY (cod_corte) REFERENCES tbcorte(id_corte)
        ON DELETE SET NULL ON UPDATE CASCADE
);

-- Produto 1: Kit Mistura
INSERT INTO tbQuantidadeCorte (cod_produto, cod_corte) VALUES
(1, 1),  -- manta
(1, 3),  -- panela
(1, 4);  -- moida

-- Produto 2: Contra Filé
INSERT INTO tbQuantidadeCorte (cod_produto, cod_corte) VALUES
(2, 2),  -- bife
(2, 5),  -- peça
(2, 7);  -- tirinha

-- Produto 3: Carne Moída Bovina
INSERT INTO tbQuantidadeCorte (cod_produto, cod_corte) VALUES
(3, 4),  -- moida
(3, 3),  -- panela
(3, 1);  -- manta

-- Produto 4: Pão de Hambúrguer
INSERT INTO tbQuantidadeCorte (cod_produto, cod_corte) VALUES
(4, 8),  -- medalhão
(4, 9),  -- espetinho
(4, 7);  -- tirinha

-- Produto 5: Fraldinha Bovina
INSERT INTO tbQuantidadeCorte (cod_produto, cod_corte) VALUES
(5, 2),  -- bife
(5, 5),  -- peça
(5, 1);  -- manta

-- Produto 6: Hambúrguer Bovino Artesanal
INSERT INTO tbQuantidadeCorte (cod_produto, cod_corte) VALUES
(6, 8),  -- medalhão
(6, 7),  -- tirinha
(6, 9);  -- espetinho
