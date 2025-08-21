<?php
// Client ID e Client Secret fornecidos pelo Spotify Developer Dashboard
$client_id = '0d999c447cd44c38bf2b461727d4a31b';  // ID do cliente do Spotify
$client_secret = 'e7da045676784a078b4a572b6aae5a01';  // Segredo do cliente do Spotify

// Função para obter o token de acesso
function getAccessToken($client_id, $client_secret) {
    $auth = base64_encode($client_id . ':' . $client_secret);
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://accounts.spotify.com/api/token",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Basic $auth",
            "Content-Type: application/x-www-form-urlencoded"
        ],
        CURLOPT_POSTFIELDS => "grant_type=client_credentials",
    ]);
    $response = curl_exec($curl);
    curl_close($curl);
    $token_info = json_decode($response, true);
    return $token_info['access_token'];
}

// Função para obter os detalhes do álbum
function getAlbumDetails($album_id, $access_token) {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.spotify.com/v1/albums/$album_id",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $access_token"
        ],
    ]);
    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response, true);  // Retorna os detalhes do álbum como um array
}

// Verifica se o ID do álbum foi passado na URL
if (isset($_GET['id'])) {
    $album_id = $_GET['id'];  // Obtém o ID do álbum
} else {
    echo 'ID do álbum não fornecido.';
    exit;
}

// Obter token de acesso
$access_token = getAccessToken($client_id, $client_secret);

// Obter os detalhes do álbum com o ID
$album = getAlbumDetails($album_id, $access_token);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="album.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <title>Detalhes do Álbum</title>
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
        <!-- Ícone de lupa que será clicado -->
        <i id="meu_icon" class="fa fa-search" onclick="toggleSearch()"></i>
        <!-- Campo de texto que aparecerá quando clicar na lupa -->
        <input type="text" id="meu_barra" class="search-input" name="query">
    </form>
</div>

<div class="perfil-container">
        <a href="perfil.php">
        <i class="fa-solid fa-headphones"></i></a>
    </div>
        </div>
    </div>
</nav>

    <div class="meu_container">
        <div class="meu-album-container">
            <?php if ($album && isset($album['images'][0])): ?>
                <div class="album-cover-container">
                    <img src="<?= htmlspecialchars($album['images'][0]['url']); ?>" id="img" alt="<?= htmlspecialchars($album['name']); ?>">
                </div>
                <div class="album-info">
                    <div class="album"><?= htmlspecialchars($album['name']); ?></div>
                    <div class="artista">Artista(s): <?= htmlspecialchars(implode(", ", array_column($album['artists'], 'name'))); ?></div>
                </div>
            </div>
            <div class="faixas-container">
                <div class="title">Faixas:</div>
                <ul>
                    <?php foreach ($album['tracks']['items'] as $index => $track): ?>
                        <li>
                        <li>
                        <ul>
    <?php foreach ($album['tracks']['items'] as $index => $track): ?>
        <li>
            <div class="faixa-info">
                <img src="<?= htmlspecialchars($album['images'][0]['url']); ?>" class="miniatura" alt="<?= htmlspecialchars($album['name']); ?>">
                <div class="faixa-detalhes">
                    <p><?= htmlspecialchars($track['name']); ?></p>
                    <?php if (isset($track['preview_url'])): ?>
                        <div class="player-container">
                            <button class="play-button" onclick="togglePlay(this, <?= $index; ?>)">
                                <i class="fa-solid fa-play"></i>
                            </button>
                            <audio id="audio-<?= $index; ?>" src="<?= htmlspecialchars($track['preview_url']); ?>"></audio>
                        </div>
                    <?php else: ?>
                        <p class="preview-unavailable">Prévia indisponível.</p>
                    <?php endif; ?>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
</ul>

                    <?php endforeach; ?>
                </ul>
            </div>
            <?php else: ?>
                <p>Detalhes do álbum não disponíveis.</p>
            <?php endif; ?>
        </div>
    </div>
    <script>
function togglePlay(button, index) {
    // Identificar o áudio correspondente ao botão clicado
    const audio = document.getElementById(`audio-${index}`);

    // Pausar todos os outros áudios
    document.querySelectorAll('audio').forEach((a) => {
        if (!a.paused && a !== audio) {
            a.pause();
            const otherButton = a.previousElementSibling.querySelector('.play-button');
            if (otherButton) otherButton.innerHTML = '<i class="fa-solid fa-play"></i>';
        }
    });

    // Alternar estado do áudio clicado
    if (audio.paused) {
        audio.play();
        button.innerHTML = '<i class="fa-solid fa-pause"></i>';
    } else {
        audio.pause();
        button.innerHTML = '<i class="fa-solid fa-play"></i>';
    }

    // Quando o áudio terminar, resetar o botão
    audio.onended = () => {
        button.innerHTML = '<i class="fa-solid fa-play"></i>';
    };
}
  
         
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
  
  </script>
</body>
<br> <br> <br> <br> <br> <br> <br>
<footer>
<div class="footer-content">
            <p>© 2024 <strong>Batuki</strong>. Todos os direitos reservados.</p>
            <p>Desenvolvido por Giovana Karolina | Fins Acadêmicos</p>
            <div class="policies">
                <a href="#">Política de Privacidade</a> |
                <a href="#">Termos de Uso</a>
            </div>
        </div>
    </footer>
</html>
