<?php
// Inclui o arquivo de configuração
require_once('config.php');

// Verifica se a conexão com o banco foi estabelecida
if (!isset($banco) || $banco === null) {
    die("<script>alert('Erro na conexão com o banco de dados.'); window.location.href='cadastro_form.php';</script>");
}

// Restante do código para o cadastro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os dados enviados via POST
    $nome_usuario = trim($_POST["user"] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $origem = trim($_POST['origem'] ?? ''); // Obtém o valor de origem

    // Validação de campos
    if (strlen($nome_usuario) < 1) {
        echo '<script>alert("O nome de usuário deve conter no mínimo 1 caracteres"); window.location.href="cadastro_form.php";</script>';
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<script>alert("O e-mail inserido é inválido."); window.location.href="cadastro_form.php";</script>';
        exit();
    }

    if (strlen($senha) < 8 || 
        !preg_match('/[A-Z]/', $senha) || 
        !preg_match('/[a-z]/', $senha) || 
        !preg_match('/[0-9]/', $senha) || 
        !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $senha)) {
        echo '<script>alert("A senha deve ter pelo menos 8 caracteres, incluindo letra maiúscula, minúscula, número e caractere especial."); window.location.href="cadastro_form.php";</script>';
        exit();
    }

    // Verifica se o usuário ou e-mail já existe no banco de dados
    $stmt = $banco->prepare("SELECT * FROM usuario WHERE email = ? OR nome_usuario = ?");
    if ($stmt === false) {
        die("<script>alert('Erro na preparação da consulta.'); window.location.href='cadastro_form.php';</script>");
    }
    $stmt->bind_param("ss", $email, $nome_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Já existe um usuário com esses dados.'); window.location.href='cadastro_form.php';</script>";
        $stmt->close();
        exit();
    }
    $stmt->close();

    // Gera um hash seguro para a senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $banco->prepare("INSERT INTO usuario (nome_usuario, email, senha, origem) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        die("<script>alert('Erro na preparação da consulta de inserção.'); window.location.href='cadastro_form.php';</script>");
    }
    $stmt->bind_param("ssss", $nome_usuario, $email, $senha_hash, $origem);
    

    // Se a inserção foi bem-sucedida
    if ($stmt->execute()) {
        echo "<script>alert('Usuário $nome_usuario cadastrado com sucesso'); window.location.href='entre.php';</script>";
    } else {
        echo "<script>alert('Não foi possível criar o usuário $nome_usuario'); window.location.href='cadastro_form.php';</script>";
    }
    $stmt->close();
}

// Fechar a conexão com o banco de dados
$banco->close();
?>
