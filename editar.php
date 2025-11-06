<?php


include __DIR__ . "/projeto01/includes/header.php";


// Início – Conexão e captura do ID
// ===================================================

require __DIR__ . '/projeto01/includes/db.php';

// Pega o ID que veio pela URL (ex: editar.php?id=3)
// Se não existir ou for inválido (0, texto, etc), volta pra página de listagem
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// ===================================================
// Fim – Conexão e captura do ID

// Início – Busca do registro (para preencher o formulário)
// ===========================================================

$sql = 'SELECT id, nome, email, telefone, foto, data_cadastro
        FROM cadastros
        WHERE id = :id';
$stmt = db()->prepare($sql);
$stmt->execute([':id' => $id]);
$registro = $stmt->fetch(PDO::FETCH_ASSOC);

// Se não encontrou o registro, volta para a lista
if (!$registro) {
    header('Location: index.php');
    exit;
}

// Guarda a foto atual do registro (vinda do banco)
// Se não enviar uma nova foto no formulário,
// Essa aqui continua sendo usada (pra não apagar a existente)
$fotoAtual = $registro['foto'] ?? null;

// ===================================================
// Fim – Busca do registro

// Início - Processamento do POST (quando clicar em “Salvar”)
// ==============================================

$erro = '';
$ok   = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1) Captura dos dados
    $nome      = trim($_POST['nome']      ?? '');
    $email     = trim($_POST['email']     ?? '');
    $telefone  = trim($_POST['telefone']  ?? '');
    $fotoAtual = $_POST['foto_atual']     ?? null;

    // 2) Validações básicas (iguais às usadas no salvar.php)
    if ($nome === '' || mb_strlen($nome) < 3) {
        $erro = 'Nome é obrigatório (mín. 3 caracteres).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'E-mail inválido.';
    } elseif ($telefone === '' || mb_strlen(preg_replace('/\D+/', '', $telefone)) < 8) {
        $erro = 'Telefone inválido.';
    }

    // 3) Upload da nova foto (se enviada)
    $novaFoto = null; // se não enviar, vamos manter a foto atual
    if ($erro === '' && isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {

        if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            $erro = 'Erro ao enviar a imagem.';
        } elseif ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
            $erro = 'Imagem muito grande (máx. 2MB).';
        } else {
            // valida tipo real (MIME)
            $finfo = new finfo(FILEINFO_MIME_TYPE);      // classe nativa pra detectar MIME
            $mime  = $finfo->file($_FILES['foto']['tmp_name']);  // tipo real do arquivo
            $permitidos = [
                'image/jpeg'      => 'jpg',
                'image/pjpeg'     => 'jpg', // variante antiga
                'image/png'       => 'png',
                'image/x-png'     => 'png', // variante comum no Windows
                'image/gif'       => 'gif',
            ];

            if (!isset($permitidos[$mime])) {
                $erro = 'Formato de imagem inválido. Use JPG, PNG ou GIF.';
            } else {
                // garante existência da pasta e move o arquivo
                $dirUpload = __DIR__ . '/uploads';
                if (!is_dir($dirUpload)) {
                    mkdir($dirUpload, 0755, true);
                }

                $novoNome = uniqid('img_', true) . '.' . $permitidos[$mime]; // nome único
                $destino  = $dirUpload . '/' . $novoNome;

                if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
                    $novaFoto = 'uploads/' . $novoNome; // salva caminho relativo
                } else {
                    $erro = 'Falha ao salvar a imagem no servidor.';
                }
            }
        }
    }

    // 4) Se tudo estiver Ok, faz o UPDATE
    if ($erro === '') {
        try {
            // define qual foto será salva: nova (se enviada) ou mantém a atual
            $fotoParaSalvar = $novaFoto !== null ? $novaFoto : $fotoAtual;

            $sql = 'UPDATE cadastros
                    SET nome = :nome,
                        email = :email,
                        telefone = :telefone,
                        foto = :foto
                    WHERE id = :id;';

            $stmt = db()->prepare($sql);
            $stmt->execute([
                ':nome'     => $nome,
                ':email'    => $email,
                ':telefone' => $telefone,
                ':foto'     => $fotoParaSalvar,
                ':id'       => $id,
            ]);

            // Se trocou a foto, apaga a antiga do disco (se existir)
            if ($novaFoto !== null && !empty($fotoAtual) && file_exists(__DIR__ . '/' . $fotoAtual)) {
                unlink(__DIR__ . '/' . $fotoAtual);
            }

            $ok = true;

            // Redireciona para a lista após atualizar
            header('Location: index.php?msg=atualizado');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $erro = 'Este e-mail já está cadastrado.';
            } else {
                $erro = 'Erro ao atualizar: ' . $e->getMessage();
            }
        }
    }
}

// chave do if antes do try fechada aqui
// chave do primeiro if fechada aqui

// ===============================
// Fim - Processamento do POST
?>

<!doctype html>
<meta charset="utf-8">
<title>Editar Cadastro</title>

<h1>Editar Cadastro</h1>

<?php if ($erro): ?>
    <p style="color:red;"><?= htmlspecialchars($erro) ?></p>
<?php endif; ?>



<!-- Início - Formulário de edição (pré-preenchido) -->
<!-- =================================================== -->

<form class="p-3 mb-5 bg-body-tertiary rounded border shadow-sm w-100" method="POST" enctype="multipart/form-data">

    <!-- o atributo enctype serve para avisar ao navegador que o formulário vai enviar arquivos e não só texto -->
    <h4 class="py-4 mb-0"><b>Edite os dados do cadastro</b></h4>

    <div class="mb-3">
        <label for="nome" class="form-label">Nome:</label>
        <input type="text" class="form-control" name="nome" id="nome" placeholder="Digite seu nome" minlength="3" value="<?= htmlspecialchars($registro['nome'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">E-mail:</label>
        <input type="email" class="form-control" name="email" placeholder="Digite seu e-mail" required value="<?= htmlspecialchars($registro['email'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <label for="telefone" class="form-label">Telefone:</label>
        <input type="text"
            class="form-control"
            name="telefone"
            id="telefone"
            placeholder="(11) 99999-9999"
            required
            value="<?= htmlspecialchars($registro['telefone'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <label for="foto" class="form-label">Foto:</label>

        <!-- Mostra a foto atual se existir -->
        <?php if (!empty($registro['foto']) && file_exists(__DIR__ . '/' . $registro['foto'])): ?>
            <div class="mb-2">
                <img src="<?= htmlspecialchars($registro['foto']) ?>"
                    alt="Foto atual"
                    style="max-width: 120px; border-radius: 6px;">
            </div>
        <?php endif; ?>

        <input type="file" class="form-control" name="foto" id="foto">
        <!-- Guarda a foto atual para o caso de o usuário não enviar uma nova -->
        <input type="hidden" name="foto_atual" value="<?= htmlspecialchars($registro['foto'] ?? '') ?>">
    </div>

    <button class="btn btn-primary w-100" type="submit">Salvar alterações</button>
    <a href="index.php" class="btn btn-outline-danger w-100 mt-1">Cancelar</a>


</form>


<!-- =================================================== -->
<!-- Fim - Formulário de edição -->