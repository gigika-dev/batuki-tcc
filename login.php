<?php
// Inclui o arquivo de conexão com o banco de dados
require_once('config.php');

// Inicia a sessão
session_start();

// Verifica se a conexão foi bem-sucedida
if (!$banco) {
    die('Erro de conexão com o banco de dados.');
}

// Verifica se o formulário foi submetido via método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os dados enviados via POST e remove espaços extras
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    // Valida o e-mail
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['alert'] = 'Desculpe, o e-mail inserido é inválido.';
        header('Location: entre.php');
        exit();
    }

    // Prepara a consulta para verificar o e-mail
    $stmt = $banco->prepare("SELECT * FROM usuario WHERE email = ?");
    if (!$stmt) {
        // Log de erro para facilitar o debug (não exibir mensagens detalhadas no navegador)
        error_log("Erro ao preparar consulta: " . $banco->error);
        $_SESSION['alert'] = 'Erro ao acessar o sistema. Tente novamente mais tarde.';
        header('Location: entre.php');
        exit();
    }

    // Passa o e-mail como parâmetro e executa a consulta
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se o e-mail foi encontrado
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc(); // Obtém os dados do usuário

        // Verifica se a senha inserida corresponde ao hash armazenado
        if (password_verify($senha, $user['senha'])) {
            // Armazena o ID e nome do usuário na sessão
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nome'];
            $_SESSION['logged_in'] = true;

            // Redireciona para a página inicial
            header('Location: home.php');
            exit();
        } else {
            // Senha incorreta
            $_SESSION['alert'] = 'Senha incorreta.';
            header('Location: entre.php');
            exit();
        }
    } else {
        // E-mail não encontrado
        $_SESSION['alert'] = 'E-mail não encontrado.';
        header('Location: entre.php');
        exit();
    }

    // Fecha o statement
    $stmt->close();
}

// Fecha a conexão com o banco de dados
$banco->close();
?>
