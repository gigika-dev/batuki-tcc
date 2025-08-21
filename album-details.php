<?php
// Aqui você captura o ID do álbum da URL
$album_id = $_GET['album_id'];

// Agora, você pode fazer uma requisição para a API do Spotify para obter os detalhes do álbum
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.spotify.com/v1/albums/$album_id",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $access_token", // Não se esqueça de garantir que o token de acesso esteja disponível
    ],
]);

$response = curl_exec($curl);
curl_close($curl);

$album_details = json_decode($response, true);

// Extraindo as informações do álbum
$album_name = $album_details['name'];
$album_cover = $album_details['images'][0]['url'];
$album_artists = implode(", ", array_map(fn($artist) => $artist['name'], $album_details['artists']));
$album_tracks = $album_details['tracks']['items'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($album_name) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .album-container {
            padding: 20px;
        }
        .album-header {
            text-align: center;
        }
        .album-header img {
            max-width: 300px;
            margin-bottom: 20px;
        }
        .album-header h1 {
            font-size: 36px;
            color: #333;
        }
        .album-header p {
            font-size: 18px;
            color: #555;
        }
        .track-list {
            list-style: none;
            padding: 0;
            margin-top: 20px;
        }
        .track-item {
            margin-bottom: 10px;
        }
        audio {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="album-container">
        <div class="album-header">
            <img src="<?= $album_cover ?>" alt="<?= htmlspecialchars($album_name) ?>">
            <h1><?= htmlspecialchars($album_name) ?></h1>
            <p>Artistas: <?= htmlspecialchars($album_artists) ?></p>
        </div>

        <ul class="track-list">
            <?php foreach ($album_tracks as $track): ?>
                <li class="track-item">
                    <?= htmlspecialchars($track['name']) ?>
                    <?php if ($track['preview_url']): ?>
                        <audio controls>
                            <source src="<?= $track['preview_url'] ?>" type="audio/mpeg">
                        </audio>
                    <?php else: ?>
                        <span>Sem prévia</span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
