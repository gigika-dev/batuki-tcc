<?php
// Credenciais do Spotify
$client_id = '0d999c447cd44c38bf2b461727d4a31b';
$client_secret = 'e7da045676784a078b4a572b6aae5a01';

// Função para obter o token de acesso
function getAccessToken($client_id, $client_secret) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Basic ' . base64_encode($client_id . ':' . $client_secret),
        'Content-Type: application/x-www-form-urlencoded'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = json_decode(curl_exec($ch));
    curl_close($ch);

    if (isset($response->access_token)) {
        return $response->access_token;
    } else {
        die('Erro ao obter token: ' . json_encode($response));
    }
}

// Obter o token de acesso
$access_token = getAccessToken($client_id, $client_secret);

// Função para fazer requisições à API Spotify
function makeRequest($url, $access_token) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $access_token
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response);
    if (isset($data->error)) {
        die('Erro na API: ' . $data->error->message);
    }
    return $data;
}

// Requisições para dados públicos
$top_artists = makeRequest('https://api.spotify.com/v1/artists?ids=0TnOYISbd1XYRBk9myaseg,6eUKZXaKkcviH0Ku9w2n3V', $access_token); 
$top_tracks = makeRequest('https://api.spotify.com/v1/tracks?ids=3n3Ppam7vgaVa1iaRUc9Lp,7ouMYWpwJ422jRcDASZB7P', $access_token);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Spotify</title>
    <link rel="stylesheet" href="perfil.css">
</head>
<body>
    <div class="container">
        <!-- Top Artistas Públicos -->
        <div class="top-artists">
            <h2>Top Artistas</h2>
            <ul>
                <?php foreach ($top_artists->artists as $artist) { ?>
                    <li>
                        <img src="<?php echo $artist->images[0]->url; ?>" alt="Artista">
                        <p><?php echo $artist->name; ?></p>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <!-- Top Músicas Públicas -->
        <div class="top-tracks">
            <h2>Top Músicas</h2>
            <ul>
                <?php foreach ($top_tracks->tracks as $track) { ?>
                    <li>
                        <p><?php echo $track->name; ?> - <?php echo $track->artists[0]->name; ?></p>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</body>
</html>
