<?php
// Configurações da API do Spotify
$clientId = '0d999c447cd44c38bf2b461727d4a31b';
$clientSecret = 'e7da045676784a078b4a572b6aae5a01';

// Função para obter o token de acesso
function getSpotifyToken($clientId, $clientSecret) {
    $url = "https://accounts.spotify.com/api/token";
    $headers = [
        "Authorization: Basic " . base64_encode("$clientId:$clientSecret"),
        "Content-Type: application/x-www-form-urlencoded",
    ];
    $data = "grant_type=client_credentials";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response)->access_token;
}

// Função para obter informações do álbum
function getAlbumInfo($token, $albumId) {
    $url = "https://api.spotify.com/v1/albums/$albumId";
    $headers = [
        "Authorization: Bearer $token",
    ];
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response);
}

// Função para obter faixas de um álbum
function getTracksFromAlbum($token, $albumId) {
    $url = "https://api.spotify.com/v1/albums/$albumId/tracks";
    $headers = [
        "Authorization: Bearer $token",
    ];
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response)->items;
}

// Obter token e informações do álbum
$token = getSpotifyToken($clientId, $clientSecret);
$albumId = $_GET['album_id'];
$albumInfo = getAlbumInfo($token, $albumId);
$tracks = getTracksFromAlbum($token, $albumId);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($albumInfo->name); ?></title>
    <link rel="stylesheet" href="ritmos.css">
</head>
<body>
    <h1><?php echo htmlspecialchars($albumInfo->name); ?></h1>
    <h2>Artistas: <?php echo htmlspecialchars(implode(", ", array_map(fn($artist) => $artist->name, $albumInfo->artists))); ?></h2>
    <img src="<?php echo $albumInfo->images[0]->url; ?>" alt="<?php echo htmlspecialchars($albumInfo->name); ?>" style="width:300px;">

    <h3>Faixas:</h3>
    <ul>
        <?php foreach ($tracks as $track): ?>
            <li>
                <p><?php echo htmlspecialchars($track->name); ?> - <?php echo htmlspecialchars(implode(", ", array_map(fn($artist) => $artist->name, $track->artists))); ?></p>
                <audio controls>
                    <source src="<?php echo $track->preview_url; ?>" type="audio/mpeg">
                    Seu navegador não suporta o elemento de áudio.
                </audio>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="ritmos.php">Voltar</a>
</body>
</html>
