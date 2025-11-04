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
        <!-- Campo de texto (mantém o termo pesquisado, se houver) -->
        <input type="text" name="busca" placeholder="Pesquisar..."
            value="<?= htmlspecialchars($busca) ?>">

        <!-- Botão para buscar -->
        <button type="submit">Buscar</button>

        <!-- Link para limpar a busca -->
        <a href="listar.php">Limpar</a>
    </form>

    <!-- Link para cadastrar novo registro -->
    <p><a href="cadastro.php">Cadastrar Novo</a></p>

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
                                <img src="uploads/<?= htmlspecialchars($r['foto']) ?>"
                                    alt="Foto do usuário"
                                    class="img-thumbnail"
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
                                Editar
                            </a>
                            <a href="deletar.php?id=<?= (int)$r['id'] ?>"
                                class="btn btn-sm btn-danger"
                                onclick="return confirm('Tem certeza que deseja excluir este registro?')">
                                Deletar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

</body>

</html>