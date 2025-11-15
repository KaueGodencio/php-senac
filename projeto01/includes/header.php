<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projeto CRUD</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous">

    <link href="/php-senac/assets/style.css" rel="stylesheet" crossorigin="anonymous">



    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"  rel="stylesheet">
   
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">


</head>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$u = $_SESSION['usuario'] ?? null;
?>
<header>
    <nav class="navbar navbar-expand-lg" style="background: #221d66; height: 90px;">
        <div class="container">
            <a class="navbar-brand text-white " href="">
                <h2> CRUD</h2>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll"
                aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarScroll">
                <ul class="navbar-nav ms-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">



                    <?php if (!$u): ?>
                        <!-- Se NÃO estiver logado -->
                        <li class="nav-item">
                            <a class="nav-link active text-white btn btn-primary btn_person-2" aria-current="page" href="login.php">Login</a>
                        </li>
                    <?php else: ?>

                        <!-- Se estiver logado -->
                        <!-- <li class="nav-item">
                            <a class="nav-link active text-white mt-1" aria-current="page" href="index.php">Ver cadastros</a>
                        </li> -->
                        <!-- <li class="nav-item">
                            <a class="nav-link active text-white mt-1 fw-bold" aria-current="page" href="cadastro.php">Cadastrar Novo</a>
                        </li> -->
                        <li class="nav-item">
                            <p class="text-white nav-link m-0 ">
                                <b><?= htmlspecialchars($u['nome']) ?></b> |
                                <a class="btn btn-danger btn_logut" href="logout.php"> Sair</a>
                            </p>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>

<body class="bg-body-tertiary">