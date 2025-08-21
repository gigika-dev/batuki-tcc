<?php
// Substitua pelos seus próprios Client ID e Client Secret fornecidos pelo Spotify Developer Dashboard
$client_id = '0d999c447cd44c38bf2b461727d4a31b';  
$client_secret = 'e7da045676784a078b4a572b6aae5a01';

// Função para obter o token de acesso
function getAccessToken($client_id, $client_secret) {
    // Codifica o client_id e client_secret em base64 para autenticação no Spotify
    $auth = base64_encode($client_id . ':' . $client_secret);

    $curl = curl_init();  // Inicia uma nova sessão cURL
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://accounts.spotify.com/api/token",  // URL da API de autenticação do Spotify
        CURLOPT_RETURNTRANSFER => true,  // Faz o cURL retornar o resultado como string
        CURLOPT_HTTPHEADER => [
            "Authorization: Basic $auth",  // Cabeçalho com autenticação em base64
            "Content-Type: application/x-www-form-urlencoded"  // Define o tipo de conteúdo do corpo da requisição
        ],
        CURLOPT_POSTFIELDS => "grant_type=client_credentials",  // Define o tipo de autorização como client_credentials
    ]);
    
    $response = curl_exec($curl);  // Executa a requisição cURL e armazena a resposta
    curl_close($curl);  // Fecha a sessão cURL

    $token_info = json_decode($response, true);  // Decodifica a resposta JSON para um array
    return $token_info['access_token'];  // Retorna o token de acesso
}

// Função para buscar álbuns, artistas, faixas e gêneros
function searchSpotify($query, $type, $access_token) {
    $query = urlencode($query);  // Codifica o termo de pesquisa para garantir que seja seguro para a URL
    
    $curl = curl_init();  // Inicia uma nova sessão cURL
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.spotify.com/v1/search?q=$query&type=$type&limit=10",  // URL da API de busca com parâmetros
        CURLOPT_RETURNTRANSFER => true,  // Faz o cURL retornar o resultado como string
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $access_token"  // Cabeçalho com token de acesso para autenticação
        ],
    ]);
    
    $response = curl_exec($curl);  // Executa a requisição cURL e armazena a resposta
    curl_close($curl);  // Fecha a sessão cURL

    return json_decode($response, true);  // Decodifica a resposta JSON para um array e retorna
}

// Obter token de acesso
$access_token = getAccessToken($client_id, $client_secret);  // Chama a função para obter o token de acesso

// Obter o termo de pesquisa enviado pelo formulário
$query = isset($_GET['query']) ? $_GET['query'] : '';  // Verifica se o parâmetro 'query' foi enviado na URL

if ($query) {
    // Buscar álbuns e faixas
    $search_results = searchSpotify($query, 'album,track', $access_token);  // Chama a função para buscar álbuns e faixas no Spotify
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">  <!-- Define a codificação de caracteres para o documento -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  <!-- Torna o site responsivo para diferentes dispositivos -->
    <title>Sua Pesquisa</title>  <!-- Define o título da página -->
    <link rel="stylesheet" href="search.css"> <!-- Link para o arquivo CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">  <!-- Link para o ícone de pesquisa do FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">  <!-- Link para o Bootstrap -->
    <link rel="shortcut icon" href="image" type="image/x-icon">  <!-- Define o ícone da página -->
</head>
<body>
<nav id="menu">  <!-- Barra de navegação -->
    <img src="logo.png" id="logo" alt="logo batuki">  <!-- Logo do site -->
    <a href="home.php">Home</a>  <!-- Link para a página inicial -->
    <a href="idiomas.php">Idiomas</a>  <!-- Link para a página de idiomas -->
    <a href="musicas.php">Músicas</a>  <!-- Link para a página de músicas -->

    <!-- PESQUISA -->
    <div class="meu_search-container">  <!-- Contêiner de busca -->
        <form action="search.php" method="GET">  <!-- Formulário que envia o termo de pesquisa via GET -->
            <i id="meu_icon" class="fa fa-search"></i>  <!-- Ícone de lupa -->
            <input type="text" id="meu_barra" class="search-input" name="query">  <!-- Campo de texto para inserir a pesquisa -->
        </form>
    </div>
    <div class="perfil-container">
        <a href="perfil.php">
        <i class="fa-solid fa-headphones"></i></a>
    </div>

</nav>

<?php if ($query): ?>  <!-- Verifica se há um termo de pesquisa -->
    <div class="results">  <!-- Contêiner dos resultados -->
        <!-- Exibir resultados de faixas -->
        <div class="title">Faixas encontradas com esse título:</div>
        <div class="carousel-container">  <!-- Contêiner para o carrossel de faixas -->
            <button class="carousel-arrow left-arrow" onclick="moveCarousel('tracks-carousel', -1)">&#10094;</button>  <!-- Botão para mover o carrossel para a esquerda -->
            <div class="carousel-wrapper">  <!-- Envolvente do carrossel -->
                <div class="carousel" id="tracks-carousel">  <!-- Carrossel de faixas -->
                <?php
                if (isset($search_results['tracks']['items'])) {  // Verifica se existem itens de faixas nos resultados
                    foreach ($search_results['tracks']['items'] as $index => $track) {  // Itera sobre cada faixa encontrada
                        $album_image = isset($track['album']['images'][0]['url']) ? $track['album']['images'][0]['url'] : 'default.jpg';  // Verifica se há imagem do álbum
                        echo '<div class="track">';  // Inicia o bloco de cada faixa
                        echo '<img src="' . htmlspecialchars($album_image) . '" alt="' . htmlspecialchars($track['album']['name']) . '" class="track-image">';  // Exibe a imagem do álbum
                        echo '<div class="title-track">' . htmlspecialchars($track['name']) . '</div>';  // Exibe o nome da faixa
                        echo '<div class="title-artist">Artista: ' . htmlspecialchars($track['artists'][0]['name']) . '</div>';  // Exibe o nome do artista

                        // Verifica se há prévia da música
                        if (isset($track['preview_url'])) {
                            echo '<div class="play-button-container">';  // Contêiner do botão de play
                            echo '<button class="play-button" onclick="togglePlay(this, ' . $index . ')"><i class="fa-solid fa-play"></i></button>';  // Botão de play
                            echo '<audio id="audio-' . $index . '" src="' . htmlspecialchars($track['preview_url']) . '"></audio>';  // Recurso de áudio com a prévia
                            echo '</div>';
                        } else {
                            // Se não houver prévia
                            echo '<p class="preview-unavailable">Desculpe, Prévia não disponível.</p>';
                        }

                        echo '</div>';
                    }
                } else {
                    echo '<p>Desculpe, Nenhuma música com este título foi encontrada.</p>';  // Caso não encontre nenhuma faixa
                }
                ?>
                </div>
            </div>
            <button class="carousel-arrow right-arrow" onclick="moveCarousel('tracks-carousel', 1)">&#10095;</button>  <!-- Botão para mover o carrossel para a direita -->
        </div>

        <br> <br> <br> <br>

        <!-- Exibir resultados de álbuns -->
        <div class="title">Álbuns encontrados com esse título:</div>
        <div class="carousel-container">  <!-- Contêiner para o carrossel de álbuns -->
            <button class="carousel-arrow left-arrow" onclick="moveCarousel('albums-carousel', -1)">&#10094;</button>  <!-- Botão para mover o carrossel para a esquerda -->
            <div class="carousel-wrapper">  <!-- Envolvente do carrossel -->
                <div class="carousel" id="albums-carousel">  <!-- Carrossel de álbuns -->
                <?php
                if (isset($search_results['albums']['items'])) {  // Verifica se existem itens de álbuns nos resultados
                foreach ($search_results['albums']['items'] as $album) {  // Itera sobre cada álbum encontrado
                $album_id = $album['id'];  // Obtém o ID do álbum
                $album_url = "detalhes_album.php?id=$album_id";  // Cria a URL para a página de detalhes do álbum
                echo '<a href="' . htmlspecialchars($album_url) . '" class="album-button">';  // Link para a página de detalhes do álbum
                echo '<img src="' . htmlspecialchars($album['images'][0]['url']) . '" alt="' . htmlspecialchars($album['name']) . '">';  // Exibe a imagem do álbum
                    echo '<div class="title-album">' . htmlspecialchars($album['name']) . '</div>';  // Exibe o nome do álbum
                echo '</a>';
                }
            } else {
                echo '<p>Desculpe, Nenhum álbum com esse título foi encontrado.</p>';  // Caso não encontre nenhum álbum
            }
                    ?>
                </div>
            </div>
            <button class="carousel-arrow right-arrow" onclick="moveCarousel('albums-carousel', 1)">&#10095;</button>  <!-- Botão para mover o carrossel para a direita -->
        </div>
    </div>
<?php endif; ?>
<br> <br> <br> <br> <br>
<footer>  <!-- Rodapé da página -->
    <div class="footer-content">
        <p>© 2024 <strong>Batuki</strong>. Todos os direitos reservados.</p>  <!-- Texto de copyright -->
        <p> Desenvolvido por Giovana Karolina | Fins Acadêmicos</p>  <!-- Informações sobre o desenvolvedor -->
        <div class="policies">  <!-- Links para políticas -->
            <a href="#">Política de Privacidade</a> | 
            <a href="#">Termos de Uso</a>
        </div>
    </div>
</footer>

<!-- Script para o funcionamento dos carrosséis -->
<script>
function moveCarousel(carouselId, direction) {  // Função para mover o carrossel
    const carousel = document.getElementById(carouselId);  // Obtém o carrossel
    const itemWidth = carousel.querySelector('.album-button, .track').offsetWidth + 20;  // Largura do item do carrossel
    const totalItems = carousel.children.length;  // Número total de itens no carrossel
    const visibleItems = Math.floor(carousel.parentElement.clientWidth / itemWidth);  // Itens visíveis no carrossel
    let currentPosition = parseInt(carousel.getAttribute('data-position')) || 0;  // Posição atual do carrossel

    currentPosition += direction;  // Muda a posição para a direção desejada

    if (currentPosition < 0) {
        currentPosition = 0;  // Limita a posição mínima
    } else if (currentPosition > totalItems - visibleItems) {
        currentPosition = totalItems - visibleItems;  // Limita a posição máxima
    }

    const offset = -currentPosition * itemWidth;  // Calcula o deslocamento do carrossel
    carousel.style.transform = `translateX(${offset}px)`;  // Aplica o deslocamento
    carousel.setAttribute('data-position', currentPosition);  // Atualiza a posição
}

    // FUNÇÃO PLAYER
    function togglePlay(button, index) {  // Função para tocar ou pausar o áudio
    const audio = document.getElementById(`audio-${index}`);  // Obtém o áudio
    const isPlaying = !audio.paused;  // Verifica se está tocando ou pausado

    if (isPlaying) {
        audio.pause();  // Pausa o áudio
        button.innerHTML = '<i class="fa-solid fa-play"></i>';  // Altera o ícone para play
    } else {
        // Pausa outros áudios
        const audios = document.querySelectorAll('audio');
        const buttons = document.querySelectorAll('.play-button');
        audios.forEach(a => a.pause());
        buttons.forEach(b => b.innerHTML = '<i class="fa-solid fa-play"></i>');

        audio.play();  // Toca o áudio atual
        button.innerHTML = '<i class="fa-solid fa-pause"></i>';  // Altera o ícone para pause
    }

    audio.onended = () => {  // Quando o áudio terminar
        button.innerHTML = '<i class="fa-solid fa-play"></i>';  // Altera o ícone para play
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
</html>
