SHOW DATABASES;
CREATE DATABASE db_batuki;
use db_batuki;

create table usuario (
email varchar(80) NOT NULL PRIMARY KEY,
senha varchar(80) NOT NULL UNIQUE INDEX,
nome_usuario varchar(80) NOT NULL,
origem varchar(80) NOT NULL,
data_cadastro datetime);