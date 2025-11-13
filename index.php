<?php

session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php?msg=login');
    exit;
}
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

<div class="container  bg-body mt-5 p-4 rounded-3" style="min-height: 600px;" >



    <h2 class="fw-bold">Usuários Cadastrados</h2>



    <!-- =========================================================
     FORMULÁRIO DE BUSCA
     ========================================================= -->
    <form method="get">
        <div class="d-flex my-3">
            <!-- Campo de texto (mantém o termo pesquisado, se houver) -->
            <input class="form-control" name="busca" type="text" placeholder="Pesquisar..." aria-label="Search" value="<?= htmlspecialchars($busca) ?>" />

            <!-- Botão para buscar -->
            <button class="btn btn-outline-success me-3 ms-3" type="submit">Buscar</button>

            <!-- Link para limpar a busca -->
            <a class="btn btn-outline-danger" href="index.php">Limpar</a>
        </div>
    </form>

    <!-- =========================================================
     EXIBIÇÃO DOS RESULTADOS
     ========================================================= -->
    <?php if (!$registros): ?>
        <!-- Caso o banco não retorne nenhum registro -->

        <div class="alert alert-danger" role="alert">
            Nenhum cadastro.
        </div>
    <?php else: ?>

        <!-- Card em volta da tabela -->
        <div class="card shadow-sm">
            <div class="card-body">

                <!-- Tabela responsiva -->
                <div class="table-responsive">
                    <div class="d-flex justify-content-end my-2">
                        <a class="btn btn-primary m-1" href="exportar_csv.php" target="_blank">Exportar CSV</a>
                        <a class="btn btn-primary m-1" href="exportar_xls.php" target="_blank">Exportar XLS</a>
                    </div>
                    <table class="table table-striped table-hover table-bordered table-sm align-middle text-center">
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
                                            <img src="<?= htmlspecialchars($r['foto']) ?>" alt="Foto do usuário" class="img-thumbnail" width="80" height="80">
                                        <?php else: ?>
                                            --
                                        <?php endif; ?>
                                    </td>

                                    <!-- Exibe a data de cadastro -->
                                    <td><?= htmlspecialchars($r['data_cadastro'] ?? '') ?></td>

                                    <!-- Links de ação: editar e deletar -->
                                    <td class="d-flex justify-content-center gap-1">
                                        <a href="editar.php?id=<?= (int)$r['id'] ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="deletar.php?id=<?= (int)$r['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este registro?')" data-bs-toggle="tooltip" title="Excluir">
                                            <i class="bi bi-trash3-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    <?php endif; ?>

</div>

<!-- Inicialização de tooltips do Bootstrap -->
<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(el => new bootstrap.Tooltip(el))
</script>

<?php
include __DIR__ . "/projeto01/includes/footer.php";
?>