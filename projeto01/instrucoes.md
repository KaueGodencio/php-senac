## Crianção do banco de dados 

CREATE DATABASE IF NOT EXISTS projeto_db -- cria o banco de dados caso ele não exista
	DEFAULT CHARACTER SET utf8mb4 		 -- definir o charset moderno (aceita emojis e acentos)
	COLLATE utf8mb4_general_ci;          -- define collation (cria regras de ordenação)

## Comando para excluir o banco de dados OBS: É excecutado no servidor e não direto no banco de dados

## comando para apagar banco de dados

DROP DATABASE IF EXISTES projeto_db  


PDO PHP Data Objects = forma do PHP se conecta com o banco de dados


## comando para criar a tabela com coluna no banco de dados. 

CREATE TABLE IF NOT EXISTS cadastros( -- criar a tabela se não existir
    id INT PRIMARY KEY AUTO_INCREMENT, -- chave primaria numérica, auto incremento
    nome VARCHAR(120) NOT NULL, -- nome: texto até 120 caracteres, obrigatorio  
    email VARCHAR(16) NOT NULL, -- email: texto até 160, obrigatorio
    telefone VARCHAR(25) NOT NULL, -- telefone: texto permite traços/espaçoes obrigatorio
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- data de cadastro automatica com horario atual
    UNIQUE KEY uk_email(email)); -- opcional indice ínico:  impede e-mail repitidos


## comando para adicionar uma nova coluna em uma tabela

ALTER TABLE cadastros
ADD COLUMN foto VARCHAR(255) AFTER telefone; -- posição da coluna depois de telefone