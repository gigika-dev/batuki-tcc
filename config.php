<?php
// cria uma conexão com o banco de dados
$banco = new mysqli('127.0.0.1', 'root', '2406122', 'db_batuki'); //NOTBOOK
// se nao funcionar, use:
//$banco = new mysqli('127.0.0.1', 'root', '', 'db_batuki'); //ETEC
//$banco = new mysqli('127.0.0.1', 'root', '2406122', 'db_batuki'); //NOTBOOK



// idendifica algum erro na conexão se tiver
if ($banco->connect_errno) {
    echo "<p>Encontrei um erro: {$banco->connect_error}</p>"; // Use chaves para melhor legibilidade
    die(); // para a execução do script do erro
}

// Definir conjunto de caracteres para UTF-8
if (!$banco->set_charset("utf8")) { //verifica se a configuração do charset foi bem-sucedida
    echo "<p>Erro ao definir o conjunto de caracteres: {$banco->error}</p>";
    die();
}
?>