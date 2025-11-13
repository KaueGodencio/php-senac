<?php

//LOGIN SIMPLES (verifica e-mail e senha )

require __DIR__ . '/projeto01/includes/db.php'; // faz a conexão com o banco de dados 

//iniciar a sessão para armazenar dados 
session_start();    // início de sessão 

// mensagem de erro variável em branco
$erro = '';

// 1) Processamento do formulário de login

// Só executa o código se o método da requisição for POST
// (ou seja, se o formulário foi enviado)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Captura e limpa os valores digitados
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    // Verifica se ambos os campos foram preenchidos
    if ($email === '' || $senha === '') {
        $erro = 'Preencha e-mail e senha.';
    } else {

        // 2) Busca o usuário no banco pelo e-mail digitado

        $sql = 'SELECT id, nome, senha_hash FROM usuarios WHERE email = :email'; // Consulta SQL com placeholder
        $st = db()->prepare($sql);                                               // Prepara a consulta
        $st->execute([':email' => $email]);                                      // Substitui :email pelo valor digitado
        $u = $st->fetch(PDO::FETCH_ASSOC);                                       // Busca o resultado (array associativo)

        // 3) Verifica se encontrou o usuário e se a senha está correta

        // Se encontrou um usuário e a senha digitada bate com o hash
        if ($u && password_verify($senha, $u['senha_hash'])) {

            // Guarda os dados em uma variável para depois eu conseguir buscar por meio da variável
            $_SESSION['usuario'] = [
                'id'    => (int)$u['id'],
                'nome'  => $u['nome']
            ];

            // Redireciona para a página principal (pagina_inicial.php)
            header('Location: index.php?msg=logado');
            exit; // Finaliza o script para evitar que o restante rode

        } else {
            // Caso o e-mail não exista ou a senha esteja errada
            $erro = 'E-mail ou senha incorretos.';
        }
    }
}

// Fim do script
?>


<!-- 4) HTML – Formulário de Login -->

<!doctype html>
<meta charset="utf-8">
<title>Login</title>
<head>
       <link href="/php-senac/assets/style.css" rel="stylesheet" crossorigin="anonymous">
</head>

<?php
include __DIR__ . "/projeto01/includes/header.php";
?>

<div class="container container-main ">

    <div class="row col-12 m-0 justify-content-center ">
        <div class="row d-flex  justify-content-center align-items-center p-0  ">
            <form class="col-12 col-md-8 col-lg-4 p-4 mt-5 mb-5 rounded border shadow-sm bg-body"
                action="login.php" method="POST" enctype="multipart/form-data">

                <!-- o atributo enctype serve para avisar ao navegador que o formulário vai enviar arquivos e não só texto -->
                <h4 class="py-3 mb-3 text-center text-primary fw-bold" ><b>Login</b></h4>

                <!-- Dica de acesso (pode remover depois) -->
                <div class="alert alert-info py-2 mb-4">
                    <small><strong>Login de teste:</strong><br>admin@admin.com<br>Senha: 123456</small>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">E-mail:</label>
                    <input type="email" id="email" name="email" class="form-control" autocomplete="off" required>
                </div>

                <div class="mb-3">
                    <label for="senha" class="form-label">Senha:</label>
                    <input type="password" id="senha" name="senha" class="form-control" autocomplete="new-password" required>
                </div>

                <!-- Se houver erro, exibe mensagem em vermelho -->
                <?php if ($erro): ?>
                    <div class="alert alert-danger py-2">
                        <?= htmlspecialchars($erro) ?>
                    </div>
                <?php endif; ?>
             

                <button class="btn  w-100 mt-3 btn_person btn-primary border-0" type="submit">Acessar</button>
            </form>

        </div>
    </div>
</div>

<?php
include __DIR__ . "/projeto01/includes/footer.php";
?>