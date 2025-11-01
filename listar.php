<?php

require __DIR__ . '/projeto01/includes/db.php';
include __DIR__ . "/projeto01/includes/header.php";
//inicio - Lógica da busca
// =================================================

//$$_GET pega o valor digitado no camoi de busca (se existir)

$busca = trim($_GET['busca'] ?? '');
// GET  é usado aqui pq estamos apenas consultado dados, não salvado

// ?? = é como se fosse um "então"

// Verificar se o usuário digitou algo
if ($busca !== '') {

    // Se tiver texto na busca, o SQL filtra pelo nome ou e-mail
    $sql = 'SELECT id, nome, email, telefone, foto, data_cadastro
            FROM cadastros
            WHERE nome LIKE :busca OR email LIKE :busca
            ORDER BY id DESC'; // ordena pelos IDs do maior pro menor (cadastros mais novos primeiro)

    // prepara o comando SQL
    $stmt = db()->prepare($sql);

    // executa substituindo o placeholder :busca
    // o % antes e depois permite buscar qualquer parte do nome/email
    $stmt->execute([':busca' => "%$busca%"]);
} else {

    $sql = 'SELECT id, nome, email, telefone, foto, data_cadastro
    FROM cadastros
    ORDER BY id DESC';

    $stmt = db()->prepare($sql);
    $stmt->execute();
}

// ============================================================
// Exibindo os dados da tabela
// ============================================================

// fetchAll() busca todos os resultados da consulta SQL e retorna
// como um array associativo (chave => valor)
// Exemplo de saída:
// [
//     ['id' => 1, 'nome' => 'João', 'email' => 'joao@email.com'],
//     ['id' => 2, 'nome' => 'Maria', 'email' => 'maria@email.com']
// ]

$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ============================================================
// Fim – Lógica da busca
// ============================================================
?> 


<!doctype html>
<meta charset="utf-8">
<title>Lista de Cadastros</title>

<h1>Lista de Cadastros</h1>

<!-- ===== Início – Formulário de busca ===== -->
<form method="get">

    <!-- Campo de texto pré-preenchido com o texto pesquisado -->
    <!-- htmlspecialchars() → impede códigos HTML maliciosos dentro da busca -->
    <input type="text" name="busca" placeholder="Pesquisar..." 
           value="<?= htmlspecialchars($busca) ?>">

    <button type="submit">Buscar</button>

    <!-- Link para limpar a pesquisa e recarregar a lista completa -->
    <a href="listar.php">Limpar</a>
</form>
<!-- ===== Fim – Formulário de busca ===== -->
<p><a href="formulario.php"></a></p>

<?php if (!$registros): ?>
   <!--  Se não houver resultados  */ -->    
<p>Nenhum cadastro encontrado </p>

<?php else: ?>
    <thead>
        <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>NOme</th>
            <th>E-mail</th>
            <th>Fotos</th>
            <th>Data de Cadastro</th>
            <th>Ações</th>
        </tr>
    </table>
    </thead> 

    <tbody>
        

        <?php 
        // foreach -> estrutura que percore todos os registros do banco 
        // $registro -> lista com todos os cadastros vindos do banco
        // $r = representa UM registro por vez dentro do loop      
        
        
        foreach ($registros as $r):
        ?>

        <tr>
            <td><?= (int)$r['id' ] ?> </td>
            <td><?= htmlspecialchars($r['nome']) ?></td>
            <td><?= htmlspecialchars($r['email']) ?></td>
            <td><?= htmlspecialchars($r['telefone']) ?></td>
            <td>
                <?php if (!empty($r['foto'])): ?>
                    <img src="<?= htmlspecialchars($r ['foto'])?>" alt="Foto" style="max-width:80px; max-height:80px;"
                
                ?>
                <?php else: ?>
                    --
                <?php endif: ?>
            </td>






        </tr>






    </tbody>