<?php

// Função para consultar o CEP via API ViaCEP

function consultarCep($cep)

{

    $cep = preg_replace('/[^0-9]/', '', $cep); // Remove caracteres não numéricos

    $url = "https://viacep.com.br/ws/{$cep}/json/";
 
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);

    curl_close($ch);
 
    return json_decode($response, true);

}
 
// Se o usuário enviou o CEP

$resultado = null;

if (!empty($_GET['cep'])) {

    $resultado = consultarCep($_GET['cep']);

}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Consulta de CEP</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
<div class="card shadow-sm">
<div class="card-header bg-primary text-white">
<h4 class="mb-0">🔎 Consulta de Endereço por CEP</h4>
</div>
<div class="card-body">
<form method="GET" class="row g-3">
<div class="col-md-6">
<label for="cep" class="form-label">Digite o CEP</label>
<input type="text" name="cep" id="cep" class="form-control" 

                               placeholder="Ex: 01001000" value="<?php echo isset($_GET['cep']) ? htmlspecialchars($_GET['cep']) : ''; ?>" required>
</div>
<div class="col-md-6 d-flex align-items-end">
<button type="submit" class="btn btn-success w-100">Consultar</button>
</div>
</form>
 
                <?php if ($resultado): ?>
<hr class="my-4">
<?php if (isset($resultado['erro'])): ?>
<div class="alert alert-danger">❌ CEP não encontrado.</div>
<?php else: ?>
<h5>🏠 Endereço encontrado:</h5>
<ul class="list-group">
<li class="list-group-item"><strong>CEP:</strong> <?php echo $resultado['cep']; ?></li>
<li class="list-group-item"><strong>Logradouro:</strong> <?php echo $resultado['logradouro']; ?></li>
<li class="list-group-item"><strong>Bairro:</strong> <?php echo $resultado['bairro']; ?></li>
<li class="list-group-item"><strong>Cidade:</strong> <?php echo $resultado['localidade']; ?></li>
<li class="list-group-item"><strong>Estado:</strong> <?php echo $resultado['uf']; ?></li>
</ul>
<?php endif; ?>
<?php endif; ?>
</div>
</div>
<footer class="text-center mt-4 text-muted">
<small>Desenvolvido com ❤️ usando PHP + Bootstrap + ViaCEP</small>
</footer>
</div>
</body>
</html>

 