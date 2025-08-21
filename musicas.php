<?php
// Configurações da API do Spotify
$clientId = '0d999c447cd44c38bf2b461727d4a31b'; // ID do cliente fornecido pelo Spotify
$clientSecret = 'e7da045676784a078b4a572b6aae5a01'; // Segredo do cliente fornecido pelo Spotify

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

    return json_decode($response)->access_token ?? null;
}

// Função para buscar músicas por gênero
function getTracksByGenre($token, $genre) {
    $url = "https://api.spotify.com/v1/search?q=genre:$genre&type=track&limit=10";
    $headers = [
        "Authorization: Bearer $token",
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response);
    return $data->tracks->items ?? [];
}

// Função para obter playlists populares
function getPopularPlaylists($token) {
    $url = "https://api.spotify.com/v1/browse/categories?limit=50";
    $headers = [
        "Authorization: Bearer $token",
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response)->categories->items ?? [];
}

// Obter token de acesso
$token = getSpotifyToken($clientId, $clientSecret);
if (!$token) {
    die("Erro ao obter token de acesso.");
}

// Inicializar array de gêneros e faixas
$genres = [];
$tracksByGenre = [];

// Obter playlists populares para extrair os gêneros
$playlists = getPopularPlaylists($token);
foreach ($playlists as $playlist) {
    $genres[] = $playlist->name;
}

// Obter músicas por gênero
foreach ($genres as $genre) {
    $tracksByGenre[$genre] = getTracksByGenre($token, urlencode($genre));
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rankings e Álbuns</title>
    <link rel="stylesheet" href="musicas.css"> <!-- Vincula o arquivo CSS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Inclusão do jQuery -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"> <!-- Ícones do FontAwesome -->
</head>
<body>
<nav id="menu">
    <img src="logo.png" id="logo" alt="logo batuki"> <!-- Logo do site -->
    <a href="home.php">Home</a>
    <a href="idiomas.php">Idiomas</a>
    <a href="musicas.php">Músicas</a>

    <!-- PESQUISA -->
    <div class="meu_search-container">
        <form action="search.php" method="GET">
            <i id="meu_icon" class="fa fa-search" onclick="toggleSearch()"></i>
            <input type="text" id="meu_barra" class="search-input" name="query">
        </form>
    </div>
    <div class="perfil-container">
        <a href="perfil.php">
            <i class="fa-solid fa-headphones"></i>
        </a>
    </div>
</nav>
<br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br>
<br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br>
<!-- Seção de Músicas por Gênero -->
<?php $index = 0; ?>
<?php foreach ($tracksByGenre as $genre => $tracks): ?>
    <?php if (!empty($tracks)): ?>
        <div class="carousel-container">
            <div class="title-container"><?php echo htmlspecialchars($genre); ?></div>
            <div class="carousel">
                <?php foreach ($tracks as $track): ?>
                    <?php if (isset($track->name, $track->artists[0]->name, $track->album->images[0]->url)): ?>
                        <div class="carousel-item">
                            <img src="<?php echo $track->album->images[0]->url; ?>" class="album-cover" alt="<?php echo htmlspecialchars($track->name); ?>">
                            <div class="track-info">
                                <div class="nome-track"><?php echo htmlspecialchars($track->name); ?></div>
                                <div class="nome-artist"><?php echo htmlspecialchars($track->artists[0]->name); ?></div>
                                <?php if (isset($track->preview_url)): ?>
                                    <div class="play-button-container">
                                        <button class="play-button" onclick="togglePlay(this, <?php echo $index; ?>)">
                                            <i class="fa-solid fa-play"></i>
                                        </button>
                                        <audio id="audio-<?php echo $index++; ?>" src="<?php echo htmlspecialchars($track->preview_url); ?>"></audio>
                                    </div>
                                <?php else: ?>
                                    <div class="preview-unavailable">Prévia indisponível</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>

<footer>
    <div class="footer-content">
        <p>© 2024 <strong>Batuki</strong>. Todos os direitos reservados.</p>
        <p> Desenvolvido por Giovana Karolina | Fins Acadêmicos</p>
        <div class="policies">
            <a href="#">Política de Privacidade</a> | 
            <a href="#">Termos de Uso</a>
        </div>
    </div>
</footer>

<script>
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
    // Alterna a classe ativa no contêiner de busca
    if (searchInput.style.display === "none" || searchInput.style.display === "") {
        searchInput.style.display = "block";
        searchInput.placeholder = getRandomPlaceholder();  // Define o placeholder aleatório
        searchInput.focus();  // Coloca o cursor no campo de texto automaticamente
        searchContainer.classList.add('active'); // Adiciona a classe para expandir o campo de texto
    } else {
        searchInput.style.display = "none";
        searchContainer.classList.remove('active'); // Remove a classe para esconder o campo de texto
        searchInput.blur();   // Tira o foco do input
    }

    // LUPA
    function toggleSearch() {
    var container = document.querySelector('.meu_search-container');
    container.classList.toggle('active');
}

});

    // Função para tocar/pausar a prévia
    function togglePlay(button, index) {
        const audio = document.getElementById(`audio-${index}`);
        const allAudios = document.querySelectorAll('audio');
        const allButtons = document.querySelectorAll('.play-button');

        allAudios.forEach(a => {
            a.pause();
            a.currentTime = 0;
        });

        allButtons.forEach(b => {
            b.innerHTML = '<i class="fa-solid fa-play"></i>';
        });

        if (audio.paused) {
            audio.play();
            button.innerHTML = '<i class="fa-solid fa-pause"></i>';
            audio.onended = () => {
                button.innerHTML = '<i class="fa-solid fa-play"></i>';
            };
        } else {
            audio.pause();
            button.innerHTML = '<i class="fa-solid fa-play"></i>';
        }
    }
</script>
</body>
</html>
