<?php

$nome = $_POST["nome"] ?? '';
$sobrenome = $_POST["sobrenome"] ?? '';

// htmlspecialchars evita que o usuario injete HTML/JS no retorno

echo "Olá, " . htmlspecialchars($nome) . "!" . htmlspecialchars($sobrenome);

?>