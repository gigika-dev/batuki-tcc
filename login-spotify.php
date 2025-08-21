<?php
session_start(); // Inicia a sessão

// Configuração do Spotify OAuth
$client_id = '7b6c09b9f061494f97fb54ec9c6ab906';
$client_secret = 'f8de5c2fa9be473d9174b73bae0cfa9c';
$redirect_uri = 'http://localhost/BATUKI/callback.php';

// Conexão com o banco de dados
$conn = new mysqli('localhost', 'usuario', 'senha', 'bd_batuki');

// Verifica se houve erro na conexão com o banco
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verifica se o código de autorização foi retornado pela URL
if (!isset($_GET['code'])) {
    // Redireciona o usuário para a página de autorização do Spotify
    $url = "https://accounts.spotify.com/authorize?response_type=code&client_id=$client_id&redirect_uri=" . urlencode($redirect_uri) . "&scope=user-read-email";
    header("Location: $url");
    exit();
}

// Obtém o código de autorização da URL
$code = $_GET['code'];

// Cria a autenticação em Base64
$auth = base64_encode("$client_id:$client_secret");

// Configura as opções para obter o token de acesso
$options = [
    'http' => [
        'header' => "Authorization: Basic $auth\r\nContent-Type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query([
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirect_uri,
        ]),
    ],
];

$context = stream_context_create($options);
$response = file_get_contents('https://accounts.spotify.com/api/token', false, $context);

// Verifica se a resposta foi obtida corretamente
if ($response === false) {
    die("Erro ao obter o token de acesso.");
}

$data = json_decode($response, true);
if (!isset($data['access_token'])) {
    die("Erro na autenticação com o Spotify.");
}

// Obtém o token de acesso
$token = $data['access_token'];

// Configura as opções para obter informações do usuário
$options = [
    'http' => [
        'header' => "Authorization: Bearer $token\r\n",
        'method' => 'GET',
    ],
];

$context = stream_context_create($options);
$response = file_get_contents('https://api.spotify.com/v1/me', false, $context);

// Verifica se a resposta foi obtida corretamente
if ($response === false) {
    die("Erro ao obter informações do usuário.");
}

$userInfo = json_decode($response, true);
if (!isset($userInfo['email'])) {
    die("Erro: informações do usuário incompletas.");
}

// Escapa os dados antes de inserir no banco
$nome = $conn->real_escape_string($userInfo['display_name']);
$email = $conn->real_escape_string($userInfo['email']);
$origem = 'Spotify';

// Insere ou atualiza o usuário no banco de dados
$query = "INSERT INTO usuarios (nome, email, origem) 
          VALUES ('$nome', '$email', '$origem') 
          ON DUPLICATE KEY UPDATE nome='$nome', origem='$origem'";

// Executa a consulta e verifica se foi bem-sucedida
if ($conn->query($query) === TRUE) {
    // Armazena informações na sessão
    $_SESSION['usuario'] = $userInfo['display_name'];
    $_SESSION['email'] = $userInfo['email'];
    $_SESSION['logged_in'] = true;

    echo "Login realizado com sucesso!";
} else {
    echo "Erro ao salvar no banco: " . $conn->error;
}

// Fecha a conexão com o banco
$conn->close();
?>
