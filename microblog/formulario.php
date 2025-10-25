<!DOCTYPE html>
<html lang="PT-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Formulário</title>
</head>

<body class="row col-12 d-flex justify-content-center">

    <div class="col-12 col-md-4  w-25 mt-5">
        <form class=" shadow p-3 mb-5 bg-body-tertiary rounded border border-light" action="formulario.php"
            method="POST">
            <h4 class="text-center py-4">Preencha o Formulario</h4>
            <div class="mb-3">
                <label for="" class="form-label">Nome:</label>
                <input type="text" class="form-control" name="nome" placeholder="Nome" required>
            </div>
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Mensagem:</label>
                <textarea class="form-control " name="mensagem" rows="3" placeholder="Digite aqui"
                    style="min-height: 200px;"></textarea>
            </div>
            <button class="btn-primary btn w-100" type="submit">Enviar</button>
        </form>

        <?php
        //Aqui esta pegando os dados pelo name e exibindo na tela 
        $nome = $_POST["nome"] ?? '';
        $mensagem = $_POST["mensagem"] ?? '';

        // htmlspecialchars evita que o usuario injete HTML/JS no retorno
        
        echo "<div class=''><b> Nome: </b>" . htmlspecialchars($nome) . "</div>";
        echo "<b> Mensagem:</b> " . htmlspecialchars($mensagem);


        ?>
    </div>





    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>