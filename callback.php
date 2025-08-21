<?php
// Credenciais do Spotify
$client_id = '0d999c447cd44c38bf2b461727d4a31b';
$client_secret = 'e7da045676784a078b4a572b6aae5a01';
$redirect_uri = 'http://localhost/BATUKI/callback.php';

// Verificar se o código de autorização foi retornado
if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Solicitar o token de acesso
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => $redirect_uri,
        'client_id' => $client_id,
        'client_secret' => $client_secret
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = json_decode(curl_exec($ch));
    curl_close($ch);

    // Verifica se a resposta contém um token de acesso
    if (isset($response->access_token)) {
        // Armazenar o token de acesso para futuras requisições
        session_start();
        $_SESSION['access_token'] = $response->access_token;

        // Redirecionar para a página principal
        header('Location: perfil.php');
        exit();
    } else {
        // Exibir erro
        echo "Erro ao autenticar: " . $response->error; // Exibe a mensagem de erro
    }
} else {
    echo "Erro ao autenticar! Código não encontrado.";
}
?>
