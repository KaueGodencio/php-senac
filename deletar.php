<?php
// Início - Conexão com o banco e verificação do ID
// ==================================================

require __DIR__ . '/projeto01/includes/db.php';

// verifica se veio o ID pela URL (GET)
$id = (int)($_GET['id'] ?? '');

// se o ID for inválido (zero ou vazio), redireciona para lista
if ($id <= 0) {
    header('Location: listar.php');
    exit;
}
// Fim - Conexão com o banco e verificação do ID
// ==================================================


// INICIAO BUSCA DO REGISTRO PARA EXCLUIR
$sql = 'SELECT * FROM cadastros WHERE id = :id';
$stmt = db()->prepare($sql);
$stmt->execute([':id' =>  $id]);
$registro = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$registro) {
    header('Location: listar.php');
    exit;
}
//Fim - Busca do registro 

//INICIO EXCLUSÃO

try {
    if (!empty($registro['foto']) && file_exists(__DIR__ . '/' . $registro['foto'])) {
        unlink(__DIR__ . '/' . $registro['foto']);
    }


    // if (!empty($registro['foto']) 
    // verifica se o campo foto no banco não esta vazio

    //file_exists(__DIR__ . '/' . $registro['foto']) 
    // Confirma se o arquivo realmente existe na psta do servidor antes de tentar apagar

    //unlink 
    // é a função nativa do PHP que deleta um arquivo fisico

    // Comando SQL pra excluir o registro
    $sql = 'DELETE FROM cadastros WHERE id = :id';
    $stmt = db()->prepare($sql);
    $stmt->execute([':id' => $id]);

    // redireciona de volta pra lista após excluir
    header('Location: listar.php?msg=excluido');
    exit;
} // Chave do try

catch (PDOException $e) {
    // Se der erro no banco, mostra mensagem
    echo '<p style="color:red;">Erro ao excluir: ' . htmlspecialchars($e->getMessage()) . '</p>';
}

// ============================================================
// Fim – Exclusão do registro