<?php
 
require __DIR__ . '/includes/db.php';
 
// Buscar tudo
$sql = 'SELECT id, nome, email, telefone, cidade, data_cadastro
        FROM contatos_demo
        ORDER BY id ASC';
$stmt = db()->prepare($sql);
$stmt->execute();
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<meta charset="utf-8">
<title>Listar (Demo)</title>
 
<h1>Contatos (DEMO)</h1>
 
<p>
  <!-- Links chamando os scripts de exportação -->
  <a href="exportar_csv.php"  target="_blank">Exportar CSV</a> |
  <a href="exportar_xls.php"  target="_blank">Exportar XLS</a>
</p>
 
<?php if (!$registros): ?>
  <p>Nenhum registro.</p>
<?php else: ?>
  <table border="1" cellpadding="8" cellspacing="0">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>E-mail</th>
        <th>Telefone</th>
        <th>Cidade</th>
        <th>Data de Cadastro</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($registros as $r): ?>
        <tr>
          <td><?= (int)$r['id'] ?></td>
          <td><?= htmlspecialchars($r['nome']) ?></td>
          <td><?= htmlspecialchars($r['email']) ?></td>
          <td><?= htmlspecialchars($r['telefone']) ?></td>
          <td><?= htmlspecialchars($r['cidade']) ?></td>
          <td><?= htmlspecialchars($r['data_cadastro']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>
 