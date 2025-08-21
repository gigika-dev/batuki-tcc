<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rankings de Música - Spotify</title>
    <link rel="stylesheet" href="ranking.css"> <!-- Link para o arquivo CSS externo -->
</head>
<body>
    <div class="container"> 
        <?php
        // Credenciais de API do Spotify
        $client_id = '0d999c447cd44c38bf2b461727d4a31b';
        $client_secret = 'e7da045676784a078b4a572b6aae5a01';
        
        // Autenticação via Client Credentials
        $auth_url = 'https://accounts.spotify.com/api/token';
        $auth_headers = [
            'Authorization: Basic ' . base64_encode($client_id . ':' . $client_secret),
            'Content-Type: application/x-www-form-urlencoded'
        ];
        $auth_data = http_build_query(['grant_type' => 'client_credentials']);
        
        // Fazendo a requisição para obter o token
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $auth_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $auth_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $auth_headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $auth_response = curl_exec($ch);
        curl_close($ch);
        
        // Decodifica a resposta de autenticação para obter o token de acesso
        $auth_data = json_decode($auth_response);
        $token = $auth_data->access_token;

        // Função para obter uma playlist do Spotify
        function getPlaylist($playlist_id, $token) {
            $playlist_url = "https://api.spotify.com/v1/playlists/$playlist_id";
            
            $headers = [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $playlist_url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            return json_decode($response, true);
        }

        // IDs das playlists de rankings
        $playlists = [
            'Top 50 Global' => '37i9dQZEVXbMDoHDwVN2tF',
            'Top 50 EUA' => '37i9dQZEVXbLRQDuF5jeBp',
            'Top 50 Brasil' => '37i9dQZEVXbMXbN3EUUhlg',
            'Viral 50 Global' => '37i9dQZEVXbLiRSasKsNU9',
            'Viral 50 EUA' => '37i9dQZEVXbKuaTI1Z1Afx',
        ];

        // Iterando sobre as playlists
        foreach ($playlists as $ranking_name => $playlist_id) {
            echo "<div class='ranking'>";
            echo "<h2>$ranking_name</h2>";
            $playlist_data = getPlaylist($playlist_id, $token);

            // Verificação se os dados da playlist estão corretos
            if (isset($playlist_data['tracks']) && isset($playlist_data['tracks']['items'])) {
                echo "<div class='carousel-container'>";
                echo "<div class='carousel'>";

                $position = 1; // Posição da faixa no ranking
                foreach ($playlist_data['tracks']['items'] as $item) {
                    $track = $item['track'];
                    $track_name = $track['name'];
                    $artist_name = $track['artists'][0]['name'];
                    $album_image = $track['album']['images'][0]['url']; // Imagem de capa do álbum
                    $preview_url = $track['preview_url']; // URL de prévia da música

                    echo "<div class='carousel-item'>";
                    echo "<img src='$album_image' alt='Capa do Álbum' class='album-cover'>";
                    echo "<div class='track-info'>";
                    echo "<strong>Posição #$position</strong>";
                    echo "<p><strong>$track_name</strong> - $artist_name</p>";
                    if ($preview_url) {
                        echo "<audio controls><source src='$preview_url' type='audio/mpeg'>Seu navegador não suporta a tag de áudio.</audio>";
                    } else {
                        echo "<p>Prévia indisponível</p>";
                    }
                    echo "</div>";
                    echo "</div>";

                    $position++;
                }

                echo "</div>"; // Fim do carrossel
                echo "<button class='carousel-prev'>Prev</button>";
                echo "<button class='carousel-next'>Next</button>";
                echo "</div>"; // Fim do container do carrossel
            } else {
                // Exibe uma mensagem de erro caso a playlist não tenha sido carregada corretamente
                echo "<p>Não foi possível carregar o ranking de $ranking_name. Tente novamente mais tarde.</p>";
            }

            echo "</div>"; // Fim do ranking
        }
        ?>
    </div>

    <script>
        // Função para controlar o carrossel
        $(document).ready(function() {
            $('.carousel-next').click(function() {
                var carousel = $(this).siblings('.carousel');
                var scrollAmount = carousel.width() / 3; // Move por 3 itens
                carousel.animate({ scrollLeft: '+=' + scrollAmount }, 400);
            });

            $('.carousel-prev').click(function() {
                var carousel = $(this).siblings('.carousel');
                var scrollAmount = carousel.width() / 3; // Move por 3 itens
                carousel.animate({ scrollLeft: '-=' + scrollAmount }, 400);
            });
        });
    </script>
</body>
</html>
