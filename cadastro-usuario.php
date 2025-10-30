<!DOCTYPE html>
<html lang="PT-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous"> -->
    <title>Formulário</title>
</head>


<?php
include __DIR__ . "/projeto01/includes/header.php";
?>

<body class="row col-12 d-flex justify-content-center">

    <div class="col-12 col-md-4  w-25 mt-5">
        <form class=" shadow p-3 mb-5 bg-body-tertiary rounded border border-light" action="salvar.php" method="POST" enctype="multipart/form-data">
            <!-- o atributo enctype serve para avisar ao navegador que o formulario vai enviar arquivos e não so texto -->
            <h4 class="text-center py-4">Cadastro</h4>
            <div class="mb-3">
                <label for="" class="form-label">Nome:</label>
                <input type="text" class="form-control" name="nome" placeholder="Digite seu nome" required>
            </div>
            <div class="mb-3">
                <label for="" class="form-label">E-mail:</label>
                <input type="email" class="form-control" name="email" placeholder="Digite seu email" required>
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Telefone:</label>
                <input type="number" class="form-control" name="telefone" placeholder="(11) 9999-9999" required>
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Foto:</label>
                <input type="file" class="form-control" name="foto" required>
            </div>

            <button class="btn-primary btn w-100" type="submit">Salvar</button>
        </form>


    </div>





    <!--    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script> -->
</body>
<?php
include __DIR__ . "/projeto01/includes/footer.php";
?>

</html>