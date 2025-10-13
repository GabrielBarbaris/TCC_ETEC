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
CREATE TABLE tbproduto (
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

-- =======================
-- Produtos Bovinos (id_categoria = 1)
-- =======================
INSERT INTO tbproduto (cod_categoria, nome_produto, preco, peso_minimo, intervalo_peso, descricao, imagem_url, tipo_quantidade)
VALUES
(1, 'Picanha Bovina', 89.90, 0.50, 0.10, 'Corte nobre para churrasco e grelha.', 'img/uploads/picanha.WEBP', 'PESO'),
(1, 'Coxao Mole', 49.90, 0.50, 0.10, 'Carne ideal para bifes e assados.', 'img/uploads/coxaoMole.jpg', 'PESO'),
(1, 'Patinho Moido', 38.90, 0.50, 0.10, 'Carne moida leve e saborosa.', 'img/uploads/patinho.WEBP', 'PESO'),
(1, 'Fraldinha', 59.90, 0.60, 0.10, 'Corte suculento ideal para churrascos.', 'img/uploads/fraldinha.WEBP', 'PESO');

-- =======================
-- Produtos Aves (id_categoria = 2)
-- =======================
INSERT INTO tbproduto (cod_categoria, nome_produto, preco, peso_minimo, intervalo_peso, descricao, imagem_url, tipo_quantidade)
VALUES
(2, 'Peito de Frango', 22.90, 0.50, 0.10, 'Peito magro e versatil.', 'img/uploads/peito.WEBP', 'PESO'),
(2, 'Coxa e Sobrecoxa', 18.90, 1.00, 0.20, 'Corte ideal para assar.', 'img/uploads/coxaSobrecoxa.WEBP', 'PESO'),
(2, 'Asa Temperada', 19.90, 0.50, 0.10, 'Asinhas prontas para o churrasco.', 'img/uploads/asa.WEBP', 'PESO'),
(2, 'Frango Inteiro', 24.90, 1.50, 0.20, 'Frango fresco para preparo completo.', 'img/uploads/frango.WEBP', 'PESO');

-- =======================
-- Produtos Suinos (id_categoria = 3)
-- =======================
INSERT INTO tbproduto (cod_categoria, nome_produto, preco, peso_minimo, intervalo_peso, descricao, imagem_url, tipo_quantidade)
VALUES
(3, 'Lombo Suino', 32.90, 0.50, 0.10, 'Corte magro e macio.', 'img/uploads/lombo.WEBP', 'PESO'),
(3, 'Costelinha Suina', 29.90, 0.50, 0.10, 'Perfeita para churrasco.', 'img/uploads/costelinha.WEBP', 'PESO'),
(3, 'Pernil em Cubos', 27.90, 0.50, 0.10, 'Cubos ideais para refogados.', 'img/uploads/pernil.WEBP', 'PESO'),
(3, 'Bisteca Suina', 31.90, 0.50, 0.10, 'Tradicional bisteca para grelhar.', 'img/uploads/bisteca.WEBP', 'PESO');

-- =======================
-- Produtos Linguicas (id_categoria = 4)
-- =======================
INSERT INTO tbproduto (cod_categoria, nome_produto, preco, peso_minimo, intervalo_peso, descricao, imagem_url, tipo_quantidade)
VALUES
(4, 'Linguica Toscana', 24.90, 0.50, 0.10, 'Tradicional linguica artesanal.', 'img/uploads/toscana.WEBP', 'PESO'),
(4, 'Linguica de Frango', 22.90, 0.50, 0.10, 'Mais leve e saborosa.', 'img/uploads/lingFrango.WEBP', 'PESO'),
(4, 'Linguica Apimentada', 25.90, 0.50, 0.10, 'Com toque picante.', 'img/uploads/apimentada.WEBP', 'PESO'),
(4, 'Linguica Defumada', 26.90, 0.50, 0.10, 'Sabor marcante defumado.', 'img/uploads/lingDefumada.WEBP', 'PESO');

-- =======================
-- Produtos Embutidos (id_categoria = 5)
-- =======================
INSERT INTO tbproduto (cod_categoria, nome_produto, preco, peso_minimo, intervalo_peso, descricao, imagem_url, tipo_quantidade)
VALUES
(5, 'Presunto Fatiado', 8.90, 0.10, 0.05, 'Ideal para lanches.', 'img/uploads/presunto.WEBP', 'PESO'),
(5, 'Salame Italiano', 15.90, 0.20, 0.05, 'Sabor intenso artesanal.', 'img/uploads/salame.jpeg', 'PESO'),
(5, 'Mortadela Defumada', 9.90, 0.20, 0.05, 'Tradicional mortadela defumada.', 'img/uploads/mortadela.jpeg', 'PESO'),
(5, 'Peito de Peru', 12.90, 0.15, 0.05, 'Fatiado e leve.', 'img/uploads/peru.jpg', 'PESO');

-- =======================
-- Produtos Churrasco (id_categoria = 6)
-- =======================
INSERT INTO tbproduto (cod_categoria, nome_produto, preco, peso_minimo, intervalo_peso, descricao, imagem_url, tipo_quantidade)
VALUES
(6, 'Espetinho Misto', 7.90, 0.20, 0.05, 'Com carnes variadas.', 'img/uploads/espetinho.WEBP', 'UNIDADE'),
(6, 'Medalhao de Frango com Bacon', 9.90, 0.20, 0.05, 'Frango enrolado com bacon.', 'img/uploads/medalhao.WEBP', 'UNIDADE'),
(6, 'Coracao de Frango Temperado', 21.90, 0.50, 0.10, 'Pronto para grelha.', 'img/uploads/coracao.WEBP', 'PESO'),
(6, 'Espetinho de Linguica', 8.50, 0.20, 0.05, 'Espetinho artesanal de linguica.', 'img/uploads/lingEspetinho.WEBP', 'UNIDADE');

-- =======================
-- Kits (id_categoria = 7)
-- =======================
INSERT INTO tbproduto (cod_categoria, nome_produto, preco, peso_minimo, intervalo_peso, descricao, imagem_url, tipo_quantidade)
VALUES
(7, 'Kit Churrasco', 65.90, 1.50, 0.10, 'Kit completo para churrasco.', 'img/uploads/KitChurrasco.png', 'PESO'),
(7, 'Kit Semana', 59.90, 1.20, 0.10, 'Kit variado para o dia a dia.', 'img/uploads/KitChurrasco.png', 'PESO'),
(7, 'Kit Familia', 79.90, 2.00, 0.20, 'Ideal para familias grandes.', 'img/uploads/KitChurrasco.png', 'PESO'),
(7, 'Kit Economico', 49.90, 1.00, 0.10, 'Kit basico com cortes variados.', 'img/uploads/KitChurrasco.png', 'PESO');


		 
-- --------------------------------------------------------------------------------------------
-- Tabela: EstoqueProduto
CREATE TABLE tbEstoqueProduto (
    id_estoque INT AUTO_INCREMENT PRIMARY KEY,
    cod_produto INT NOT NULL,
    tipo_movimentacao ENUM('ENTRADA', 'SAIDA') NOT NULL,
    quantidade DECIMAL(10,2) NOT NULL,
    data_movimentacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    observacao VARCHAR(255),
    FOREIGN KEY (cod_produto) REFERENCES tbProduto(id_produto)
        ON DELETE CASCADE ON UPDATE CASCADE
);
-- ---------------------------------------------------------------------------------------------

-- Tabela: quantidade de corte do produto------------------------------------------------------
CREATE TABLE tbQuantidadeCorte(
	cod_produto INT NOT NULL,
 	cod_corte INT NOT NULL,
 	FOREIGN KEY (cod_produto) REFERENCES tbProduto(id_produto)
        ON DELETE CASCADE ON UPDATE CASCADE,
   FOREIGN KEY (cod_corte) REFERENCES tbcorte(id_corte)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- ===========================================
-- CATEGORIA: BOVINOS (id_categoria = 1)
-- ===========================================
-- 1: Picanha Bovina
INSERT INTO tbQuantidadeCorte (cod_produto, cod_corte) VALUES
(1, 5), -- peça
(1, 1), -- manta
(1, 2); -- bife

-- 2: Coxão Mole
INSERT INTO tbQuantidadeCorte (cod_produto, cod_corte) VALUES
(2, 2), -- bife
(2, 3), -- panela
(2, 5); -- peça

-- 3: Patinho Moído
INSERT INTO tbQuantidadeCorte (cod_produto, cod_corte) VALUES
(3, 4), -- moída
(3, 6), -- strogonoff
(3, 2); -- bife

-- 4: Fraldinha
INSERT INTO tbQuantidadeCorte (cod_produto, cod_corte) VALUES
(4, 1), -- manta
(4, 5), -- peça
(4, 7); -- tirinha


-- ===========================================
-- CATEGORIA: AVES (id_categoria = 2)
-- ===========================================
-- 5: Peito de Frango
INSERT INTO tbQuantidadeCorte (cod_produto, cod_corte) VALUES
(5, 2), -- bife
(5, 6), -- strogonoff
(5, 7); -- tirinha

-- 6: Coxa e Sobrecoxa
INSERT INTO tbQuantidadeCorte (cod_produto, cod_corte) VALUES
(6, 5), -- peça
(6, 3); -- panela

-- 7: Asa Temperada
INSERT INTO tbQuantidadeCorte (cod_produto, cod_corte) VALUES
(7, 9); -- espetinho

-- 8: Frango Inteiro
INSERT INTO tbQuantidadeCorte (cod_produto, cod_corte) VALUES
(8, 5); -- peça


-- ===========================================
-- CATEGORIA: SUÍNOS (id_categoria = 3)
-- ===========================================
-- 9: Lombo Suíno
INSERT INTO tbQuantidadeCorte (cod_produto, cod_corte) VALUES
(9, 5), -- peça
(9, 2), -- bife
(9, 7); -- tirinha

-- 10: Costelinha Suína
INSERT INTO tbQuantidadeCorte (cod_produto, cod_corte) VALUES
(10, 1), -- manta
(10, 5); -- peça

-- 11: Pernil em Cubos
INSERT INTO tbQuantidadeCorte (cod_produto, cod_corte) VALUES
(11, 3), -- panela
(11, 7); -- tirinha

-- 12: Bisteca Suína
INSERT INTO tbQuantidadeCorte (cod_produto, cod_corte) VALUES
(12, 2), -- bife
(12, 5); -- peça


-- ===========================================
-- CATEGORIA: LINGUIÇAS (id_categoria = 4)
-- ===========================================
-- 13: Linguiça Toscana
INSERT INTO tbQuantidadeCorte (cod_produto, cod_corte) VALUES
(13, 9); -- espetinho

-- 14: Linguiça de Frango
INSERT INTO tbQuantidadeCorte (cod_produto, cod_corte) VALUES
(14, 9); -- espetinho

-- 15: Linguiça Apimentada
INSERT INTO tbQuantidadeCorte (cod_produto, cod_corte) VALUES
(15, 9); -- espetinho

-- 16: Linguiça Defumada
INSERT INTO tbQuantidadeCorte (cod_produto, cod_corte) VALUES
(16, 9); -- espetinho


-- ===========================================
-- CATEGORIA: EMBUTIDOS (id_categoria = 5)
-- ===========================================
-- Não fazem cortes — são industrializados.


-- ===========================================
-- CATEGORIA: CHURRASCO (id_categoria = 6)
-- ===========================================
-- 21: Espetinho Misto


-- 23: Coração de Frango Temperado
INSERT INTO tbQuantidadeCorte (cod_produto, cod_corte) VALUES
(23, 5); -- peça




-- ===========================================
-- CATEGORIA: KITS (id_categoria = 7)
-- ===========================================
-- Kits são compostos por vários cortes, então associamos múltiplos tipos
-- 25: Kit Churrasco
INSERT INTO tbQuantidadeCorte (cod_produto, cod_corte) VALUES
(25, 1), -- manta
(25, 5), -- peça
(25, 9); -- espetinho

-- 26: Kit Semana
INSERT INTO tbQuantidadeCorte (cod_produto, cod_corte) VALUES
(26, 2), -- bife
(26, 3), -- panela
(26, 4); -- moída

-- 27: Kit Família
INSERT INTO tbQuantidadeCorte (cod_produto, cod_corte) VALUES
(27, 1), -- manta
(27, 2), -- bife
(27, 5); -- peça

-- 28: Kit Econômico
INSERT INTO tbQuantidadeCorte (cod_produto, cod_corte) VALUES
(28, 3), -- panela
(28, 4), -- moída
(28, 5); -- peça

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

