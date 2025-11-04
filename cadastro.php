<!DOCTYPE html>
<html lang="PT-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous"> 
    <title>Formulário</title>
</head>

<?php
/* include __DIR__ . "/projeto01/includes/header.php";  */
?>

<div class="row col-12 m-0 vh-100 bg-body-tertiary">
    <div class="col-12 col-md-8 p-0">
        <img src="./assets/img/90-cliente-1.jpg.webp" class="w-100 h-100" alt="" style="border-radius: 0px 30px 30px 30px;">
    </div>

    <div class="col-12 col-md-4 d-flex justify-content-center align-items-center p-0 bg-body-tertiary">
        <form class="p-3 mb-5 bg-body-tertiary rounded border border-light w-100" action="salvar.php" method="POST" enctype="multipart/form-data">
            <!-- o atributo enctype serve para avisar ao navegador que o formulario vai enviar arquivos e não so texto -->
            <h4 class=" py-4 mb-0"> <b>Preencha o formulário para se cadastrar!</b> </h4>
      
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

            <button class="btn-primary btn w-100" type="submit">Cadastrar</button>
        </form>
    </div>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script> -->
</div>

<?php
/* include __DIR__ . "/projeto01/includes/footer.php"; */
?>

</html>
