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

-- Produtos Bovinos (id_categoria = 1)
INSERT INTO tbproduto (cod_categoria, nome_produto, preco, peso_minimo, intervalo_peso, descricao, imagem_url, tipo_quantidade)
VALUES
(1, 'Picanha Bovina', 89.90, 0.50, 0.10, 'Corte nobre de picanha, ideal para churrascos.', 'img/picanha.png', 'PESO'),
(1, 'Coxao Mole', 49.90, 0.50, 0.10, 'Carne macia, ótima para bifes e assados.', 'img/coxaomole.png', 'PESO'),
(1, 'Patinho Moido', 38.90, 0.50, 0.10, 'Carne moída de patinho, perfeita para receitas do dia a dia.', 'img/patinhomoido.png', 'PESO');

-- Produtos Aves (id_categoria = 2)
INSERT INTO tbproduto (cod_categoria, nome_produto, preco, peso_minimo, intervalo_peso, descricao, imagem_url, tipo_quantidade)
VALUES
(2, 'Coxa e Sobrecoxa de Frango', 18.90, 1.00, 0.20, 'Corte suculento, ideal para grelhar ou assar.', 'img/coxa_sobrecoxa.png', 'PESO'),
(2, 'Peito de Frango', 22.90, 0.50, 0.10, 'Corte magro e versátil, ideal para grelhados e filés.', 'img/peito_frango.png', 'PESO'),
(2, 'Asa Temperada', 19.90, 0.50, 0.10, 'Asinhas de frango já temperadas, prontas para o churrasco.', 'img/asa_temperada.png', 'PESO');

-- Produtos Suínos (id_categoria = 3)
INSERT INTO tbproduto (cod_categoria, nome_produto, preco, peso_minimo, intervalo_peso, descricao, imagem_url, tipo_quantidade)
VALUES
(3, 'Lombo Suino', 32.90, 0.50, 0.10, 'Carne suína magra, ideal para assados e recheios.', 'img/lombo_suino.png', 'PESO'),
(3, 'Costelinha Suina', 29.90, 0.50, 0.10, 'Costelinha suína saborosa, ótima para churrascos.', 'img/costelinha_suina.png', 'PESO'),
(3, 'Pernil em Cubos', 27.90, 0.50, 0.10, 'Cubos de pernil suíno, ideais para refogados e espetinhos.', 'img/pernil_cubos.png', 'PESO');

-- Produtos Linguiças (id_categoria = 4)
INSERT INTO tbproduto (cod_categoria, nome_produto, preco, peso_minimo, intervalo_peso, descricao, imagem_url, tipo_quantidade)
VALUES
(4, 'Linguiça Toscana', 24.90, 0.50, 0.10, 'Linguiça toscana artesanal, ideal para churrascos.', 'img/linguica_toscana.png', 'PESO'),
(4, 'Linguiça de Frango', 22.90, 0.50, 0.10, 'Linguiça leve e saborosa feita com carne de frango.', 'img/linguica_frango.png', 'PESO'),
(4, 'Linguiça Apimentada', 25.90, 0.50, 0.10, 'Linguiça artesanal com toque de pimenta.', 'img/linguica_apimentada.png', 'PESO');

-- Produtos Embutidos (id_categoria = 5)
INSERT INTO tbproduto (cod_categoria, nome_produto, preco, peso_minimo, intervalo_peso, descricao, imagem_url, tipo_quantidade)
VALUES
(5, 'Presunto Fatiado', 8.90, 0.10, 0.05, 'Presunto de qualidade, ideal para sanduíches e lanches.', 'img/presunto.png', 'PESO'),
(5, 'Salame Italiano', 15.90, 0.20, 0.05, 'Salame artesanal com sabor marcante.', 'img/salame.png', 'PESO'),
(5, 'Mortadela Defumada', 9.90, 0.20, 0.05, 'Mortadela tradicional com sabor defumado.', 'img/mortadela.png', 'PESO');

-- Produtos Churrasco (id_categoria = 6)
INSERT INTO tbproduto (cod_categoria, nome_produto, preco, peso_minimo, intervalo_peso, descricao, imagem_url, tipo_quantidade)
VALUES
(6, 'Espetinho Misto', 7.90, 0.20, 0.05, 'Espetinho com carnes variadas para churrasco.', 'img/espetinho_misto.png', 'UNIDADE'),
(6, 'Medalhao de Frango com Bacon', 9.90, 0.20, 0.05, 'Medalhão de frango enrolado com bacon.', 'img/medalhao_frango_bacon.png', 'UNIDADE'),
(6, 'Coracao de Frango Temperado', 21.90, 0.50, 0.10, 'Corações temperados prontos para grelha.', 'img/coracao_frango.png', 'PESO');

-- Kits (id_categoria = 7)
INSERT INTO tbproduto (cod_categoria, nome_produto, preco, peso_minimo, intervalo_peso, descricao, imagem_url, tipo_quantidade)
VALUES
(7, 'Kit Churrasco', 65.90, 1.50, 0.10, 'Kit completo com carnes e linguiças para o churrasco.', 'img/kit_churrasco.png', 'PESO'),
(7, 'Kit Semana', 59.90, 1.20, 0.10, 'Kit variado para refeições da semana.', 'img/kit_semana.png', 'PESO'),
(7, 'Kit Família', 79.90, 2.00, 0.20, 'Kit completo para famílias grandes.', 'img/kit_familia.png', 'PESO');

		 
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

