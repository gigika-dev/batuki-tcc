<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Idiomas</title>
    <link rel="stylesheet" type="text/css" href="idiomas.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <nav id="menu">
        <img src="logo.png" id="logo" alt="logo batuki">
        <a href="home.php">Home</a>
        <a href="idiomas.php">Idiomas</a>
        <a href="musicas.php">Músicas</a>

    <!-- PESQUISA -->
    <div class="meu_search-container">
        <form action="search.php" method="GET">
            <i id="meu_icon" class="fa fa-search"></i>
            <input type="text" id="meu_barra" class="search-input" name="query">
        </form>
    </div>

    <div class="perfil-container">
        <a href="perfil.php">
        <i class="fa-solid fa-headphones"></i></a>
    </div>
    </nav>
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

                foreach ($playlist_data['tracks']['items'] as $item) {
                    $track = $item['track'];
                    $track_name = $track['name'];
                    $artist_name = $track['artists'][0]['name'];
                    $album_image = $track['album']['images'][0]['url']; // Imagem de capa do álbum
                    $preview_url = $track['preview_url']; // URL de prévia da música

                    // Início do item do carrossel
                    echo "<div class='carousel-item'>";
                    echo "<div class='track-container'>";
                    echo "<img src='$album_image' alt='Capa do Álbum' class='album-cover'>";
                    echo "<div class='track-info'>";
                    echo "<div class='track-text'><strong>$track_name</strong> - $artist_name</div>";
                    echo "</div>";

                    // Verifica se há prévia disponível
                    if ($preview_url) {
                        echo '<div class="play-button-container">';
                        echo "<button class='play-button' onclick='togglePlay(this)'><i class='fa-solid fa-play'></i></button>";
                        echo "<audio src='$preview_url'></audio>";
                        echo '</div>';
                    } else {
                        echo "<div class='preview-unavailable'>Desculpe, Prévia não disponível.</div>";
                    }
                    
                    // Fim do item do carrossel
                    echo "</div>";
                    echo "</div>";
                }
                echo "</div>"; // Fim do carrossel
                echo "<button class='carousel-prev' onclick='scrollCarousel(this, -1)'>&lt;</button>";
                echo "<button class='carousel-next' onclick='scrollCarousel(this, 1)'>&gt;</button>";
                echo "</div>"; // Fim do container do carrossel
            } else {
                echo "Não foi possível obter os dados da playlist.";
            }

            echo "</div>";
        }
        ?>
    </div>
    <footer>
    <div class="footer-content">
        <p>© 2024 <strong>Batuki</strong>. Todos os direitos reservados.</p>
        <p> Desenvolvido por Giovana Karolina | Fins Acadêmicos
        <div class="policies">
            <a href="#">Política de Privacidade</a> | 
            <a href="#">Termos de Uso</a>
        </div>
    </div>
    </footer>
</body>
</html>

    <script>
// Função para controlar o carrossel
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.carousel-next').forEach(function(nextButton) {
        nextButton.addEventListener('click', function() {
            var carousel = this.closest('.carousel-container').querySelector('.carousel');
            var scrollAmount = carousel.clientWidth;
            carousel.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        });
    });

    document.querySelectorAll('.carousel-prev').forEach(function(prevButton) {
        prevButton.addEventListener('click', function() {
            var carousel = this.closest('.carousel-container').querySelector('.carousel');
            var scrollAmount = carousel.clientWidth;
            carousel.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        });
    });
});

// -------------------------------- BARRA DE PESQUISA -------------------------------------------------------
// Seleciona os elementos da lupa e do campo de busca
const searchIcon = document.getElementById('meu_icon');
const searchInput = document.getElementById('meu_barra');
const searchContainer = document.querySelector('.meu_search-container');

// Array de placeholders que mudarão aleatoriamente
const placeholders = [
    " Qual o ritmo de hoje?",
    " Qual o idioma de hoje?",
    " Encontre o que precisa!",
    " Procurando algo específico?",
    " O que deseja saber hoje?",
    " Pop? Samba? Rock?"
];

// Função para selecionar um placeholder aleatório
function getRandomPlaceholder() {
    const randomIndex = Math.floor(Math.random() * placeholders.length);
    return placeholders[randomIndex];
}

// Adiciona um evento de clique ao ícone da lupa
searchIcon.addEventListener('click', function() {
    if (searchInput.style.display === "none" || searchInput.style.display === "") {
        searchInput.style.display = "block";
        searchInput.placeholder = getRandomPlaceholder();
        searchInput.focus();
        searchContainer.classList.add('active');
    } else {
        searchInput.style.display = "none";
        searchContainer.classList.remove('active');
        searchInput.blur();
    }

    // LUPA
    function toggleSearch() {
        var container = document.querySelector('.meu_search-container');
        container.classList.toggle('active');
    }
});

// FUNÇÃO PLAYER
function togglePlay(button) {
    const audio = button.nextElementSibling;
    const isPlaying = !audio.paused;

    if (isPlaying) {
        audio.pause();
        button.innerHTML = '<i class="fas fa-play"></i>';
    } else {
        audio.play();
        button.innerHTML = '<i class="fas fa-pause"></i>';
    }

    audio.onended = function() {
        button.innerHTML = '<i class="fas fa-play"></i>';
    };
}
</script>
</body>
</html>
