<?php

// EXPORTAR DADOS EM CSV (compatível com Excel)
// Este script exporta os dados da tabela cadastros para um arquivo CSV.
// Ao ser acessado, ele faz o download automático do arquivo.
// ---------------------------------------------------------------------------

require __DIR__ . '/projeto01/includes/db.php';

// 2) Consulta dos dados no banco
// ---------------------------------------------------------------------------

$sql = 'SELECT id, nome, email, telefone,  data_cadastro
        FROM cadastros
        ORDER BY id ASC'; // ASC ordem crescente

// prepara a consulta SQL
$st = db()->prepare($sql);

// executa a consulta no banco
$st->execute();

// fetchAll() => busca todos os registros e retorna como um array associativo
$rows = $st->fetchAll(PDO::FETCH_ASSOC);

// 3) Cabeçalhos HTTP para download
// ---------------------------------------------------------------------------

// aqui definimos o nome do arquivo que será baixado
$arquivo = 'cadastros.csv';

// informa ao navegador que o conteúdo é CSV e está em UTF-8
header('Content-Type: text/csv; charset=UTF-8');

// força o download do arquivo, com o nome definido acima
header('Content-Disposition: attachment; filename="' . $arquivo . '"');

// Ajuda o Excel e outros programas reconhecer o arquivo como UTF-8
// Sem isso, acentos podem aparecer errados (ex: "João" vira "JoÃo")
echo "\xEF\xBB\xBF";

// 4) Geração do arquivo CSV
// ---------------------------------------------------------------

// fopen('php://output', 'w') abre a saída do PHP para enviar o conteúdo direto pro navegador
// ou seja, o arquivo é gerado “ao vivo” sem salvar nada no servidor
$out = fopen('php://output', 'w');

// Cabeçalho do CSV (nomes das colunas)
fputcsv($out, ['ID', 'Nome', 'E-mail', 'Telefone', 'Data de Cadastro']);

// Loop para escrever cada linha da tabela
foreach ($rows as $r) {
    // fputcsv() converte o array em uma linha CSV (separada por vírgulas)
    fputcsv($out, [
        $r['id'],
        $r['nome'],
        $r['email'],
        $r['telefone'],       
        $r['data_cadastro'],
    ]);
}


//5) Finalizando do arquivo

//fecha a saida de escrita
fclose($out);

// encerra o script imediatamente

exit;
?>