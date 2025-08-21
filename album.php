<?php
require 'api_php.php';

// Client ID e Client Secret fornecidos pelo Spotify Developer Dashboard
$client_id = '0d999c447cd44c38bf2b461727d4a31b';  
$client_secret = 'e7da045676784a078b4a572b6aae5a01';

$token = getSpotifyToken($clientId, $clientSecret);
$albumId = $_GET['id'];
$album = getTracksByAlbum($token, $albumId);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $album['name'] ?></title>
    <link rel="stylesheet" href="musicas.css">
</head>
<body>
    <h1><?= $album['name'] ?></h1>
    <img src="<?= $album['images'][0]['url'] ?>" alt="<?= $album['name'] ?>">
    <p><strong>Artista:</strong> <?= $album['artists'][0]['name'] ?></p>
    <h2>Faixas</h2>
    <ul>
        <?php foreach ($album['tracks']['items'] as $track): ?>
            <li>
                <?= $track['name'] ?> - 
                <audio controls>
                    <source src="<?= $track['preview_url'] ?>" type="audio/mpeg">
                    Seu navegador não suporta o elemento de áudio.
                </audio>
                <p><strong>Artistas:</strong> 
                    <?= implode(', ', array_map(fn($artist) => $artist['name'], $track['artists'])) ?>
                </p>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
