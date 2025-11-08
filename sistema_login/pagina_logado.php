<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php?msg=login');
    exit;
}
?>

<?php $u = $_SESSION['usuario'] ?? null; ?>
<?php if ($u): ?>
    <p>Logado como: <b><?= htmlspecialchars($u['nome']) ?></b> | <a href="logout.php">Sair</a></p>
<?php endif; ?>
