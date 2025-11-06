<?php

//carrega a funcão db() para conectar no MSQL 
require __DIR__ . '/projeto01/includes/db.php';

//guarda a mensagem de erro (se hover)
$erro = '';

//indica se salvou com sucesso
$ok = false;

// o trim serve para romover os espaçamentos antes e depois caso houver
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');

    // validações
    if ($nome === '' || mb_strlen($nome) < 3) {
        $erro = 'Insira o nome (mínimo 3 caracteres).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'E-mail inválido.';

        //preg_replace() = função nativa fo PHP que substui partes de texto usando expressões regulares
        // aqui ela remove tudo que NÂO for número (D+ significa "qualquer caractere não numérico")
        // Depois usamos o mb_strlen() para contar quantos digitos sobram
    } elseif ($telefone === '' || mb_strlen(preg_replace('/\D+/', '', $telefone)) < 8) {
        $erro = 'Telefone inválido.';
    }

    $foto = null;

    // Se não houve erro até aqui e um arquivo foi enviado
    if ($erro === '' && isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
        // Verifica se houve erro no upload (contantes nativas UPLOAD_ERR_)
        if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            $erro = 'Erro ao enviar a imagem.';
        } else {
            // Verifica o tamanho do arquivo
            if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
                $erro = 'Imagem muito grande (máx. 2MB).';
            }

            if ($erro === '') {
                // finfo = classe nativa do PHP usada para descobrir o tipo real do arquivo
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                // (MIME = tipo do arquivo, ex: image/jpeg, image/png, application/pdf, etc.)
                $mime = $finfo->file($_FILES['foto']['tmp_name']);

                // Lista de tipos de imagem que o sistema aceita (extensão associada)
                $permitidos = [
                    'image/jpeg'      => 'jpg',
                    'image/pjpeg'     => 'jpg', // variante antiga
                    'image/png'       => 'png',
                    'image/x-png'     => 'png', // variante comum no Windows
                    'image/gif'       => 'gif',
                ];

                //verifica de o tipo detectado esta na lista dos permitidos
                // se não estiver, tenta detectar novamente com getimagesize()
                if (!isset($permitidos[$mime])) {
                    $infoImg = @getimagesize($_FILES['foto']['tmp_name']);
                    if ($infoImg && isset($infoImg['mime']) && isset($permitidos[$infoImg['mime']])) {
                        $mime = $infoImg['mime'];
                    } else {
                        $erro = 'Formato de imagem inválido. Use JPG, PNG ou GIF.';
                    }
                }

                // Se o formato for válido, faz o upload
                if ($erro === '') {
                    // Criar a pasta "uploads" se ainda não existir
                    $dirUpload = __DIR__ . '/uploads'; //__DIR__ mostra a pasta atual do arquivo 

                    if (!is_dir($dirUpload)) {
                        //is_dir() verificar se a pasta existe.
                        //mkdir()cria pastas
                        //0755 = permissão padrão (dono pode tudo)
                        // true = cria subpasta se for preciso
                        mkdir($dirUpload, 0755, true);
                    }

                    //Gera um nome unico e adiciona a extensão correta
                    // uniqid() cria um nome aleatório para evitar arquivos com o mesmo nome
                    $novoNome = uniqid('img_', true) . '.' . $permitidos[$mime];

                    //caminho completo de onde o arquivo será salvo
                    $destino = $dirUpload . '/' . $novoNome;

                    //move_uploaded_file() -> função nativa do PHP que move o arquivo
                    //local temporario (tmp_name) para o destino final (uploads/)

                    //Guarda apenas o caminho relativo para salvar no banco 
                    if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
                        $foto = 'uploads/' . $novoNome;
                    } else {
                        $erro = 'Falha ao salvar a imagem no servidor.';
                    }
                }
            }
        }
    }

    // Se chegou até aqui sem erro, salva no banco
    if ($erro === '') {
        try {
            //SQL com placeholders nomeados (evita SQL Injection)
            // Os dois-pontos (:) indicam variaveis que serão substituidas depois
            $sql = 'INSERT INTO cadastros (nome,email,telefone,foto)
                        VALUES(:nome, :email, :telefone, :foto)';

            //db() -> função personalizada que retorna a conexão com PDO com o banco de dados
            // prepare()-> méteodo nativo do PDO que "pre-compila" o SQL no servidor
            //isso aumenta a segurança e o desenpenho, pois separa o comando SQL dos dados
            $query = db()->prepare($sql);
            $query->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':telefone' => $telefone,
                ':foto' => $foto,
            ]);

            $ok = true; // Marca que o cadastro foi salvo com sucesso 

            // catch (PDOException $e) captura erros lançados pelo PDO (função nativa do PHP para exceções)
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') { // <- aqui o código correto
                $erro = 'Este e-mail já está cadastrado.';
            } else {
                $erro = 'Erro ao Salvar ' . $e->getMessage();
                // getMensage() -> méteodo nativo da classe Exeption que devolve o texto do erro
            }
        }
    }
}
?>

<!doctype html>
<meta charset="utf-8">
<title>Salvar</title>

<?php
include __DIR__ . "/projeto01/includes/header.php";
?>
<div class="container border">
    <div class="border border-danger">
        <?php if ($ok): ?>
            <div class="alert alert-success" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                    <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05" />
                </svg> Dados salvos com sucesso!
            </div>

            <a class="btn btn-primary" href="index.php">Ver Dados Cadastros</a>

            <!-- Se não deu certo, entra aqui -->
        <?php else: ?>

            <!-- Se existe mensagem de erro, exibe em vermelho -->
            <?php if ($erro): ?>
                <!-- htmlspecialchars() → função nativa do PHP que converte caracteres especiais em HTML seguro -->
                <!-- Evita que alguém insira tags HTML ou scripts maliciosos dentro da mensagem -->
                <p style="color:red;"><?= htmlspecialchars($erro) ?></p>

                <!-- Se chegou aqui sem erro e sem POST, o usuário acessou a página diretamente -->
            <?php else: ?>
                <p>Nada enviado.</p>
            <?php endif; ?>

            <!-- Link pra voltar pro formulário -->
            <a class="btn btn-primary" href="cadastro.php">Voltar</a>

        <?php endif; ?>
    </div>
    <!-- Se deu tudo certo no cadastro, mostra mensagem de sucesso -->


</div>