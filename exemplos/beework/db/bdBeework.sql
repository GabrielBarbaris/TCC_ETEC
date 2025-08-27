CREATE DATABASE bdBeework;
USE bdBeework;

CREATE TABLE tbUsuario (
    idUsuario INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) NOT NULL UNIQUE,
    cpfUsuario VARCHAR(14) UNIQUE, 
    senha VARCHAR(100) NOT NULL,
    nomeUsuario VARCHAR(100),
    telefone VARCHAR(20) UNIQUE,
    fotoUsuario VARCHAR(255),
    tipoUsuario INT NOT NULL DEFAULT 0, 
    biografiaUsuario VARCHAR(255),
    cep VARCHAR(255)
) ENGINE=INNODB;

CREATE TABLE tbCategoria (
    idCategoria INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    descricaoCategoria VARCHAR(100),
    nomeCategoria VARCHAR(100)
) ENGINE=INNODB;

CREATE TABLE tbServico (
    idServico INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    descricao VARCHAR(255),
    codCategoria INT NOT NULL,
    mediaValor DECIMAL(10,2),
    tituloServico VARCHAR(100),
    dataHora DATE,
    fotoServico VARCHAR(255),
    codUsuario INT NOT NULL,
    FOREIGN KEY (codCategoria) REFERENCES tbCategoria(idCategoria)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (codUsuario) REFERENCES tbUsuario(idUsuario)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB;

CREATE TABLE tbAvaliacao (
    idAvaliacao INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    dataHora DATE,
    comentarioAvaliacao VARCHAR(255),
    codUsuario INT NOT NULL, 
    codServico INT NOT NULL,
    anonimo BOOLEAN,
    estrelas INT,
    FOREIGN KEY (codUsuario) REFERENCES tbUsuario(idUsuario)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (codServico) REFERENCES tbServico(idServico)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB;

insert into tbUsuario (email, cpfUsuario, senha, nomeUsuario, telefone, fotoUsuario, tipoUsuario, biografiaUsuario, cep)
values ('juniogr.moura@gmail.com', '518.090.728-40', 'Ju171227*', 'Junio Moura', '(17) 99107-9194', 'foto.jpg', 0, 'Desenvolvedor de Sistemas', '15047-259'),
('deborah22rex@gmail.com', '512.924.068-52', '11235813', 'Henrique Paglione', '(17) 99229-6648', 'foto.jpg', 1, 'Churrasqueiro', '15115-000'),
('kaique.reimberg@icloud.com', '528.611.568-30', '@Kaique2504', 'Kaique Reimberg', '(17) 99621-1509', 'foto.jpg', 2, 'Administrador BeeWork', '15140-000');