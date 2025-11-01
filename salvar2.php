<?php

//carrega a funcão db() para conectar no MSQL 
require __DIR__ . '/projeto01/includes/db.php';

//guarda a mensagem de erro (se houver)
$erro = '';

//indica se salvou com sucesso
$ok = false;

// o trim serve para remover os espaçamentos antes e depois caso houver
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');

    // validações
    if ($nome === '' || mb_strlen($nome) < 3) {
        $erro = 'Insira o nome (mínimo 3 caracteres).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'E-mail inválido.';
    } elseif ($telefone === '' || mb_strlen(preg_replace('/\D+/', '', $telefone)) < 8) {
        $erro = 'Telefone inválido.';
    }

    // Upload da foto
    $foto = null;

    if ($erro === '' && isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            $erro = 'Erro ao enviar a imagem.';
        } else {
            // Verifica tamanho máximo de 2MB
            if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
                $erro = 'Imagem muito grande (máx. 2MB).';
            }

            if ($erro === '') {
                // finfo -> classe nativa do PHP usada pra descobrir o tipo real do arquivo
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime = $finfo->file($_FILES['foto']['tmp_name']);

                // Tipos permitidos
                $permitidos = [
                    'image/jpeg' => 'jpg',
                    'image/pjpeg' => 'jpg', // variante antiga
                    'image/png' => 'png',
                    'image/x-png' => 'png', // variante comum no Windows
                    'image/gif' => 'gif'
                ];

                // Verifica formato
                if (!isset($permitidos[$mime])) {
                    $erro = 'Formato de imagem inválido. Use JPG, PNG ou GIF.';
                }
            }

            // Criar a pasta "uploads" se ainda não existir
            if ($erro === '') {
                $dirUpload = __DIR__ . '/uploads';

                if (!is_dir($dirUpload)) {
                    mkdir($dirUpload, 0755, true);
                }

                // Gera nome único
                $novoNome = uniqid('img_', true) . '.' . $permitidos[$mime];

                // Caminho completo
                $destino = $dirUpload . '/' . $novoNome;

                // Move o arquivo
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
                    $foto = 'uploads/' . $novoNome;
                } else {
                    $erro = 'Falha ao salvar a imagem no servidor.';
                }
            }
        }
    }

    // Se tudo deu certo até aqui, salva no banco
    if ($erro === '') {
        try {
            $sql = 'INSERT INTO cadastros (nome, email, telefone, foto)
                    VALUES (:nome, :email, :telefone, :foto)';

            $query = db()->prepare($sql);
            $query->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':telefone' => $telefone,
                ':foto' => $foto,
            ]);

            $ok = true;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $erro = 'Este e-mail já está cadastrado.';
            } else {
                $erro = 'Erro ao salvar: ' . $e->getMessage();
            }
        }
    }
}
?>

<!doctype html>
<meta charset="utf-8">
<title>Salvar</title>

<!-- Se deu tudo certo no cadastro, mostra mensagem de sucesso -->
<?php if ($ok): ?>
    <p>Dados salvos com sucesso!</p>
    <p><a href="cadastro-usuario.php">Voltar</a></p>

<?php else: ?>

    <?php if ($erro): ?>
        <p style="color:red;"><?= htmlspecialchars($erro) ?></p>
    <?php else: ?>
        <p>Nada enviado.</p>
    <?php endif; ?>

    <p><a href="cadastro-usuario.php">Voltar</a></p>

<?php endif; ?>
