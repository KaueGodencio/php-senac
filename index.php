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

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>Lista de Cadastros</title>
</head>

<body>

    <h1>Lista de Cadastros</h1>

    <!-- =========================================================
     FORMULÁRIO DE BUSCA
     ========================================================= -->
    <form method="get">

        <div class="d-flex my-3">
            <!-- Campo de texto (mantém o termo pesquisado, se houver) -->
            <input class="form-control" name="busca" type="text" placeholder="Pesquisar..." aria-label="Search" value="<?= htmlspecialchars($busca) ?>" />

            <!-- Botão para buscar -->
            <button class="btn btn-outline-success me-3 ms-3" type="submit">Buscar</button>
            <button class="btn btn-outline-danger  " type="submit"><a href="index.php">Limpar</a></button>

            <!-- Link para limpar a busca -->

        </div>

    </form>


    <!-- =========================================================
     EXIBIÇÃO DOS RESULTADOS
     ========================================================= -->
    <?php if (!$registros): ?>

        <!-- Caso o banco não retorne nenhum registro -->
        <p>Nenhum cadastro encontrado.</p>

    <?php else: ?>

        <!-- Início da tabela -->
        <!-- Tabela de usuários -->
        <table class="table table-striped table-hover align-middle text-center table-bordered w-100">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>Foto</th>
                    <th>Data de Cadastro</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                <!-- Loop para percorrer todos os registros -->
                <?php foreach ($registros as $r): ?>
                    <tr>
                        <!-- Exibe o ID -->
                        <td><?= (int)$r['id'] ?></td>

                        <!-- Exibe o nome -->
                        <td><?= htmlspecialchars($r['nome'] ?? '') ?></td>

                        <!-- Exibe o e-mail -->
                        <td><?= htmlspecialchars($r['email'] ?? '') ?></td>

                        <!-- Exibe o telefone -->
                        <td><?= htmlspecialchars($r['telefone'] ?? '') ?></td>

                        <!-- Exibe a foto (ou “--” se não tiver) -->
                        <td>
                            <?php if (!empty($r['foto'])): ?>
                                
                                <img src="<?= htmlspecialchars($r['foto']) ?>"  alt="Foto do usuário"       class="img-thumbnail"
                                    width="80" height="80">
                            <?php else: ?>
                                --
                            <?php endif; ?>
                        </td>

                        <!-- Exibe a data de cadastro -->
                        <td><?= htmlspecialchars($r['data_cadastro'] ?? '') ?></td>

                        <!-- Links de ação: editar e deletar -->
                        <td>
                            <a href="editar.php?id=<?= (int)$r['id'] ?>"
                                class="btn btn-sm btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg>
                            </a>
                            <a href="deletar.php?id=<?= (int)$r['id'] ?>"
                                class="btn btn-sm btn-danger"
                                onclick="return confirm('Tem certeza que deseja excluir este registro?')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                    <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

</body>

</html>