## Crianção do banco de dados 

CREATE DATABASE IF NOT EXISTS projeto_db -- cria o banco de dados caso ele não exista
	DEFAULT CHARACTER SET utf8mb4 		 -- definir o charset moderno (aceita emojis e acentos)
	COLLATE utf8mb4_general_ci;          -- define collation (cria regras de ordenação)

## Comando para excluir o banco de dados OBS: É excecutado no servidor e não direto no banco de dados


DROP DATABASE IF EXISTES projeto_db  


PDO PHP Data Objects = forma do PHP se conecta com o banco de dados


## Comando para criar  nova coluna 

CREATE TABLE IF NOT EXISTS usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL, -- guarda o hash da senha (não a senha real)
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


## comando para preencher dados as tabelas aqui estou adicionando na tabela usuarios

INSERT INTO usuarios (nome, email, senha_hash)
VALUES ('admin', 'admin@admin.com', '$2y$10$EGIwbg/tQzBV4cc5dqlRJO6hgnaW4daS8TpA5f2S8z54G.XFPBwQu');

