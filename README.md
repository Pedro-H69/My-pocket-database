create database Mypocket;

use mypocket;


CREATE TABLE transacoes (
id INT AUTO_INCREMENT PRIMARY KEY,
tipo ENUM('receita', 'despesa') NOT NULL,
descricao VARCHAR(150) NOT NULL,
valor DECIMAL(10,2) NOT NULL,
data_transacao DATE NOT NULL
);
