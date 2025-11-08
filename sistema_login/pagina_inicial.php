<?php

// validação para saber se o usuario esta logado
session_start(); // inicia (ou retoma) a sessão pra poder ler $_SESSION

// se NÃO existir usuário logado na sessão...
if (!isset($_SESSION['usuario'])) {
    // manda o navegador pra tela de login com um aviso na URL
    // caso o usuario não esteja logado 
    header('Location: login.php?msg=login');
    exit; // para o script aqui (impede que a página continue carregando)
}
?>


<!-- esse trecho chama exibe o nome do usuario e se ele esta logado -->

<?php
// Mostra o nome do usuário logado e o link de sair (se houver sessão ativa)
$u = $_SESSION['usuario'] ?? null; // pega os dados do usuário da sessão (ou null se não existir)

if ($u): // se houver usuário logado...
?>
    <p>Logado como: <b><?= htmlspecialchars($u['nome']) ?></b> | <a href="logout.php">Sair</a></p>
<?php endif; ?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Bem vindo, você esta logado!</h1>
</body>
</html>