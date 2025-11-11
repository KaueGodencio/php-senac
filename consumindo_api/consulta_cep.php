<?php
// Verifica se o CEP foi enviado pelo usuário (via GET ou POST)
$cep = isset($_GET['cep']) ? preg_replace('/[^0-9]/', '', $_GET['cep']) : '';
 
if ($cep) {
    // URL da API ViaCEP
    $url = "https://viacep.com.br/ws/{$cep}/json/";
 
    // Inicializa o cURL
    $ch = curl_init($url);
 
    // Configurações básicas do cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 
    // Executa a requisição
    $response = curl_exec($ch);
 
    // Verifica erro
    if (curl_errno($ch)) {
        echo "Erro na requisição: " . curl_error($ch);
        curl_close($ch);
        exit;
    }
 
    curl_close($ch);
 
    // Decodifica o JSON retornado
    $data = json_decode($response, true);
 
    // Exibe os dados
    if (isset($data['erro']) && $data['erro'] === true) {
        echo "CEP não encontrado!";
    } else {
        echo "<h2>Endereço encontrado:</h2>";
        echo "CEP: {$data['cep']}<br>";
        echo "Logradouro: {$data['logradouro']}<br>";
        echo "Bairro: {$data['bairro']}<br>";
        echo "Cidade: {$data['localidade']}<br>";
        echo "Estado: {$data['uf']}<br>";
    }
} else {
    // Formulário para o usuário digitar o CEP
    echo '
        <form method="GET">
            <label>Digite o CEP:</label>
            <input type="text" name="cep" placeholder="Ex: 01001000">
            <button type="submit">Consultar</button>
        </form>
    ';
}
?>