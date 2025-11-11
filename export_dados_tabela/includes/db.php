<?php
//Função para se concenctar com o banco de dados, conexão PDO
function db(): PDO
{
    static $pdo;
    // A Variavel $pdo "static", ou seja, se a funcçãofor chamada de novo ela reaproveita a mesma conexão (nãom criar uma nova a cada vez)
    if (!$pdo) {
        // sinal de "!" é como se fosse uma negativa tipo (se não for verdadeiro...)
        // o "try" vai tentar tratar execultar o bloco abaixo (se der erro, i catch vai tratar)

        try {
            $url_conecta = 'mysql:host=127.0.0.1;dbname=projeto_db;charset=utf8mb4';
            //Define é uma variavel string pode ver que esta em aspas ($url_conecta) dizendo que o tipo de banco de dados é (mysql),
            // o servidor (127.0.0.1 -> local, nbome do banco de dados ()       
            // conjunto de caracteres 

            $pdo = new PDO(
                $url_conecta, // Caminho do banco
                'root',       // Usuario do banco (no XAMPP geralmente é "root")                               
                '',           // Senha do banco (no XAMPP normalmente fica vazia)
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    // Isso é usado para exibir um erro na tela quando tiver algum erro exibir no navegador

                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    // Define que quando buscar dados, eçes virão como arrays associativos

                ]
            );


         /*    echo "<b> Conectado com sucesso ao banco! </b>"; */
            // Aqui é somente para teste



        } catch (PDOException $e) {
            //Se der algum erro no bloco try cai aqui
            echo "<b>Erro ao conectar ao banco: </b>" . $e->getMessage();
            //Mostra a mensagem de erro diretamente na tela
            exit;
            //Encerrar a execução do script 


        }
    }

    return $pdo;
    //retorna o objeto de conexão PDO
}


if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    db(); //executa a conexão e mostra a mensagem na tela
}
