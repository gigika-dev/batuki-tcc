<?php
// Inicia a sessão
session_start();

// Destroi todas as variáveis de sessão para efetuar logout
session_destroy();

// Redireciona para a página de login
header("Location: entre.php");
exit;
?>
