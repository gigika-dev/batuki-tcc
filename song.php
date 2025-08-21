<?php
// Client ID e Client Secret fornecidos pelo Spotify Developer Dashboard
$client_id = '0d999c447cd44c38bf2b461727d4a31b';  
$client_secret = 'e7da045676784a078b4a572b6aae5a01';

// 1. Obter o token de acesso
$auth_url = 'https://accounts.spotify.com/api/token';  // URL da API de autenticação para obter o token de acesso

// Cabeçalhos da requisição para obter o token, incluindo a codificação Base64 do client_id e client_secret
$auth_headers = [
    'Authorization: Basic ' . base64_encode($client_id . ':' . $client_secret),  // Cabeçalho de autorização com client_id e client_secret codificados
    'Content-Type: application/x-www-form-urlencoded'  // Define o tipo do conteúdo enviado (formulário URL codificado)
];

// Corpo da requisição para o tipo de autenticação "client_credentials"
$auth_body = 'grant_type=client_credentials';

$ch = curl_init();  // Inicia a sessão cURL para enviar a requisição
curl_setopt($ch, CURLOPT_URL, $auth_url);  // Define a URL da requisição
curl_setopt($ch, CURLOPT_POST, 1);  // Define que a requisição será do tipo POST
curl_setopt($ch, CURLOPT_POSTFIELDS, $auth_body);  // Passa o corpo da requisição (dados do formulário)
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Faz com que a resposta seja retornada como string
curl_setopt($ch, CURLOPT_HTTPHEADER, $auth_headers);  // Define os cabeçalhos da requisição
$response = curl_exec($ch);  // Executa a requisição cURL e captura a resposta
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);  // Captura o código de status HTTP da resposta
curl_close($ch);  // Fecha a sessão cURL

// Verificar se a autenticação foi bem-sucedida (código 200 indica sucesso)
if ($http_code !== 200) {
    die('Erro ao obter o token de acesso do Spotify.');  // Caso o código de status não seja 200, exibe erro e encerra o script
}

$token = json_decode($response)->access_token ?? null;  // Decodifica a resposta JSON e obtém o token de acesso

// Se o token não for encontrado, exibe erro e encerra
if (!$token) {
    die('Falha ao processar o token de acesso.');  // Caso o token não esteja presente na resposta, exibe erro
}

// 2. Buscar as top músicas globalmente
$api_url = 'https://api.spotify.com/v1/playlists/37i9dQZEVXbMDoHDwVN2tF/tracks';  // URL da playlist "Top 50 - Global"

// Cabeçalho de autorização para fazer requisição à API do Spotify com o token de acesso
$headers = [
    'Authorization: Bearer ' . $token  // Adiciona o token de acesso no cabeçalho da requisição
];

$ch = curl_init();  // Inicia uma nova sessão cURL para buscar as músicas
curl_setopt($ch, CURLOPT_URL, $api_url);  // Define a URL da API da playlist
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Faz com que a resposta seja retornada como string
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);  // Adiciona o cabeçalho de autorização com o token
$response = curl_exec($ch);  // Executa a requisição cURL para buscar as faixas da playlist
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);  // Captura o código de status HTTP da resposta
curl_close($ch);  // Fecha a sessão cURL

// Verificar se a resposta da API é válida (código 200 indica sucesso)
if ($http_code !== 200) {
    die('Erro ao buscar as músicas da playlist do Spotify.');  // Caso o código de status não seja 200, exibe erro e encerra o script
}

$data = json_decode($response);  // Decodifica a resposta JSON contendo as faixas da playlist

// Verificar se os dados da resposta são válidos
if (!isset($data->items) || !is_array($data->items)) {
    die('Dados inválidos retornados pela API do Spotify.');  // Caso a chave "items" não exista ou não seja um array, exibe erro
}

$tracks = $data->items;  // Armazena as faixas da playlist na variável $tracks

// 3. Exibir as top 5 músicas com ícone de player de áudio
for ($i = 0; $i < min(5, count($tracks)); $i++) {  // Loop para exibir no máximo 5 faixas
    $track = $tracks[$i]->track ?? null;  // Obtém a faixa da posição $i (caso exista)

    // Se a faixa não for encontrada, pula para a próxima iteração
    if (!$track) {
        continue;
    }

    // Obtém o nome da música (caso não exista, exibe 'Nome indisponível')
    $name = htmlspecialchars($track->name ?? 'Nome indisponível');  

    // Obtém o nome do primeiro artista da música (caso não exista, exibe 'Artista indisponível')
    $artist = htmlspecialchars($track->artists[0]->name ?? 'Artista indisponível');  

    // Obtém a URL da imagem do álbum (caso não exista, usa uma string vazia)
    $album_img = $track->album->images[0]->url ?? '';  

    // Obtém a URL da prévia de 30 segundos (caso não exista, usa null)
    $preview_url = $track->preview_url ?? null;  

    // Exibir as informações da música
    echo "<div class='song'>";  // Inicia o bloco para exibir uma música
    echo $album_img ? "<img src='" . htmlspecialchars($album_img) . "' width='80' height='80' alt='Capa do álbum'>" : '';  // Exibe a imagem do álbum (se existir)
    echo "<div class='song-info'>";  // Inicia o bloco de informações da música
    echo "<div class='musica'>$name</div>";  // Exibe o nome da música
    echo "<div class='artista'>$artist</div>";  // Exibe o nome do artista

    // Se a música tiver uma prévia, exibe o ícone de player com controle play/pause
    if ($preview_url) {
        echo "<div class='audio-player' data-preview-url='" . htmlspecialchars($preview_url) . "'>";  // Cria o player com a URL da prévia
        echo "<i class='fa fa-play play-icon' onclick='togglePlayPause(this)' aria-hidden='true'></i>";  // Exibe o ícone de play (Font Awesome) e chama a função de toggle
        echo "</div>";
    } else {
        echo "<p>Prévia indisponível</p>";  // Exibe mensagem caso a música não tenha prévia disponível
    }

    echo "</div>";  // Fecha o bloco de informações da música
    echo "</div>";  // Fecha o bloco da música
}
