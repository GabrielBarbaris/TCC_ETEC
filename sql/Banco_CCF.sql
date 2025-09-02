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
    endereco TEXT,
    telefone VARCHAR(15) NOT NULL
);

-- Inserts: Usuario
INSERT INTO tbUsuario(nome, sobrenome, senha, tipo_usuario, endereco, telefone)
VALUES ("Casa de Carnes", "Fernandes", "123", "admin", '', "(17) 99201-8283");

INSERT INTO tbUsuario(nome, sobrenome, senha, tipo_usuario, endereco, telefone)
VALUES ("teste", "teste", "123", "cliente", '', "(11) 11111-1111");
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
(cod_categoria, nome_produto, preco, peso_minimo, peso_maximo, intervalo_peso, descricao, imagem_url, tipo_quantidade)
VALUES
(1, 'Kit Mistura', 27.90, 0.50, 1.20, 0.10, 'Kit de carnes variadas para o dia a dia.', 'img/kitMistura.png', 'PESO');

INSERT INTO tbProduto 
(cod_categoria, nome_produto, preco, peso_minimo, peso_maximo, intervalo_peso, descricao, imagem_url, tipo_quantidade)
VALUES
(1, 'Contra Filé', 69.90, 1.00, 2.00, 0.10, 'Corte bovino macio e saboroso, ideal para grelha.', 'img/contraFile.png', 'PESO');

INSERT INTO tbProduto 
(cod_categoria, nome_produto, preco, peso_minimo, peso_maximo, intervalo_peso, descricao, imagem_url, tipo_quantidade)
VALUES
(1, 'Carne Moída Bovina', 34.90, 0.50, 1.50, 0.10, 'Carne moída fresca, ideal para refogados e recheios.', 'img/carneMoida.png', 'PESO');

INSERT INTO tbProduto 
(cod_categoria, nome_produto, preco, peso_minimo, peso_maximo, intervalo_peso, descricao, imagem_url, tipo_quantidade)
VALUES
(1, 'Pão de Hambúrguer', 9.90, 0.20, 0.50, 0.10, 'Pão macio próprio para hambúrgueres artesanais.', 'img/pao.png', 'UNIDADE');

INSERT INTO tbProduto 
(cod_categoria, nome_produto, preco, peso_minimo, peso_maximo, intervalo_peso, descricao, imagem_url, tipo_quantidade)
VALUES
(1, 'Fraldinha Bovina', 59.90, 0.80, 1.20, 0.10, 'Carne bovina macia, ideal para churrasco e assados.', 'img/fraldinha.png', 'PESO');

INSERT INTO tbProduto 
(cod_categoria, nome_produto, preco, peso_minimo, peso_maximo, intervalo_peso, descricao, imagem_url, tipo_quantidade)
VALUES
(1, 'Hambúrguer Bovino Artesanal', 14.90, 0.15, 0.20, 0.05, 'Hambúrguer artesanal feito com carne selecionada.', 'img/hamburguer.png', 'UNIDADE');



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
    FOREIGN KEY (cod_usuario) REFERENCES tbUsuario(id_usuario)
        ON DELETE CASCADE ON UPDATE CASCADE
);
-- -------------------------------------------------------------------------------------------

-- Tabela: PedidoProduto (associação N:N)
CREATE TABLE tbPedidoProduto (
    cod_pedido INT NOT NULL,
    cod_produto INT NOT NULL,
    quantidade DECIMAL(10,2) NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    preco_total_prod DECIMAL(10,2) DEFAULT 0.00,
    PRIMARY KEY (cod_pedido, cod_produto),
    FOREIGN KEY (cod_pedido) REFERENCES tbPedido(id_pedido)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (cod_produto) REFERENCES tbProduto(id_produto)
        ON DELETE CASCADE ON UPDATE CASCADE
);
