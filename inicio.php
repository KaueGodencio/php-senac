<?php

// ============================================================================
// CONEXÃO E INCLUSÕES
// ============================================================================

// Inclui o arquivo de conexão com o banco de dados
require __DIR__ . '/projeto01/includes/db.php';

// Inclui o cabeçalho (caso tenha um HTML padrão com menus, etc.)
include __DIR__ . "/projeto01/includes/header.php";
// ============================================================================
// LÓGICA DA BUSCA
// ============================================================================

// Captura o termo digitado na busca (se existir)
$busca = trim($_GET['busca'] ?? ''); // Se não existir, fica vazio

// Se o usuário digitou algo no campo de busca
if ($busca !== '') {

    // Consulta SQL que filtra nome OU e-mail conforme o termo digitado
    $sql = 'SELECT id, nome, email, telefone, foto, data_cadastro
            FROM cadastros
            WHERE nome LIKE :busca OR email LIKE :busca
            ORDER BY id DESC';

    // Prepara e executa o comando, substituindo o placeholder :busca
    $stmt = db()->prepare($sql);
    $stmt->execute([':busca' => "%$busca%"]);
} else {
    // Caso não tenha busca, mostra todos os registros
    $sql = 'SELECT id, nome, email, telefone, foto, data_cadastro
            FROM cadastros
            ORDER BY id DESC';

    $stmt = db()->prepare($sql);
    $stmt->execute();
}

// Busca todos os resultados como array associativo
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ============================================================================
// FIM DA LÓGICA DE BUSCA
// ============================================================================
?>

<style>
   /*    .container-box {
        background-image: url('./assets/img/banner.png'); /
        background-size: cover;      
        background-position: center;  
        background-repeat: no-repeat; 
        width: 1400px;                 
        height: 1600px;               
    } */
</style>



<div class=" container-box">
    <div>

    </div>
   


</div>




</html>

<?php
include __DIR__ . "/projeto01/includes/footer.php";
?>