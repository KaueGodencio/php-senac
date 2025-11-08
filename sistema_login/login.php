<?php

//LOGIN SIMPLES (verifica e-mail e senha )

require __DIR__ . '/includes/db.php'; // faz a conexão com o banco de dados 

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
            header('Location: pagina_inicial.php?msg=logado');
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

<h2>Login</h2>

<!-- Se houver erro, exibe mensagem em vermelho -->
<?php if ($erro): ?>
    <p style="color:red;"><?= htmlspecialchars($erro) ?></p>
<?php endif; ?>

<!-- Formulário de login -->
<form method="post">
    <p>admin@admin.com <br> 123456</p>
    <p>
        <label>E-mail:<br>
            <input type="email" name="email" autocomplete="off" required>
        </label>
    </p>
    <p>
        <label>Senha:<br>
            <input type="password" name="senha" autocomplete="new-password" required>
        </label>
    </p>
    <button type="submit">Entrar</button>
</form>