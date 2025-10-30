## Crianção do banco de dados 

CREATE DATABASE IF NOT EXISTS projeto_db -- cria o banco de dados caso ele não exista
	DEFAULT CHARACTER SET utf8mb4 		 -- definir o charset moderno (aceita emojis e acentos)
	COLLATE utf8mb4_general_ci;          -- define collation (cria regras de ordenação)

## Comando para excluir o banco de dados OBS: É excecutado no servidor e não direto no banco de dados


DROP DATABASE IF EXISTES projeto_db  


PDO PHP Data Objects = forma do PHP se conecta com o banco de dados