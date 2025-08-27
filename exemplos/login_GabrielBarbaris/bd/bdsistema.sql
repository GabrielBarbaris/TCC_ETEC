create database bdsistema_GabrielBarbaris;

use bdsistema_GabrielBarbaris;

create table tbusuario(
    id_usuario int not NULL primary key auto_increment,
    nome_usuario varchar (45) not null,
    email_usuario varchar (25) not null unique,
    senha_usuario VARCHAR (20) not null,
    tipo_usuario int not null
);
/*tipo de usuario
    1- usuario administrador
    0- usuario comum
*/

INSERT INTO tbusuario (nome_usuario,email_usuario,senha_usuario,tipo_usuario)
VALUES("Jeferson","jeferson@gmail.com","mmm",1),
		("Barbaris","mg3games@gmail.com","biel134_GTi",1),
		("Kaique","reimberg@gmail.com","macaco02",0),
		("João","Joãoaugusto@gmail.com","jjj",0);