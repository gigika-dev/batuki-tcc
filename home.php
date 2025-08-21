<?php
require_once('config.php');
session_start();

// Verifica se o usuário já está logado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Redireciona para a página de login se o usuário não estiver logado
    header("Location: entre.php");
    exit();
}

// Client ID e Client Secret fornecidos pelo Spotify Developer Dashboard
$client_id = '0d999c447cd44c38bf2b461727d4a31b';  // ID único do cliente fornecido pelo Spotify
$client_secret = 'e7da045676784a078b4a572b6aae5a01';  // Segredo do cliente fornecido pelo Spotify

// Função para obter o token de acesso
function getAccessToken($client_id, $client_secret) {
    // Codifica o client_id e client_secret em Base64 para formar o cabeçalho de autorização
    $auth = base64_encode($client_id . ':' . $client_secret);

    // Inicializa uma nova sessão cURL
    $curl = curl_init();

    // Configura as opções da requisição cURL
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://accounts.spotify.com/api/token",  // URL da API do Spotify para obter o token
        CURLOPT_RETURNTRANSFER => true,  // Faz com que a resposta da requisição seja retornada como string
        CURLOPT_HTTPHEADER => [  // Define os cabeçalhos da requisição
            "Authorization: Basic $auth",  // Cabeçalho de autorização com o client_id e client_secret codificados
            "Content-Type: application/x-www-form-urlencoded"  // Tipo de conteúdo como formulário URL codificado
        ],
        CURLOPT_POSTFIELDS => "grant_type=client_credentials",  // Dados do formulário para indicar que estamos utilizando a autenticação por client_credentials
    ]);

    // Executa a requisição cURL e captura a resposta
    $response = curl_exec($curl);

    // Verifica se houve algum erro durante a execução do cURL
    if(curl_errno($curl)) {
        // Caso tenha ocorrido um erro, exibe a mensagem de erro e retorna nulo
        echo 'Curl error: ' . curl_error($curl);
        return null;  // Retorna nulo em caso de erro
    }

    // Fecha a sessão cURL
    curl_close($curl);

    // Decodifica a resposta JSON para um array associativo
    $token_info = json_decode($response, true);

    // Retorna o token de acesso se presente na resposta, caso contrário retorna nulo
    return $token_info['access_token'] ?? null;
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batuki</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="image." type="image/x-icon">
</head>
<body>
    <nav id="menu">
        <img src="logo.png" id="logo" alt="logo batuki">
        <a href="home.php">Home</a>
        <a href="idiomas.php">Idiomas</a>
        <a href="musicas.php">Músicas</a>

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

    <main>
        <br><br><br><br><br> <br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        <br><br><br><br><br><br><br>
        <div class="carousel">
            <div class="carousel-inner">
                <div class="card-card">
                    <a href="ing.php">
                      <button id="card" class="card1" alt="inglês">inglês</button>
                    </a>
                </div>
                <div class="card-card">
                <a href="pt.php">
                      <button id="card" class="card2" alt="chinês">chinês</button>
                    </a>
                </div>
                <div class="card-card">
                <a href="pt.php">
                      <button id="card" class="card3" alt="espanhol">espanhol</button>
                    </a>
                </div>
                <div class="card-card">
                <a href="pt.php">
                      <button id="card" class="card4" alt="árabe">árabe</button>
                    </a>
                </div>
                <div class="card-card">
                <a href="pt.php">
                      <button id="card" class="card5" alt="hindi">hindi</button>
                    </a>
                </div>
                <div class="card-card">
                <a href="pt.php">
                      <button id="card" class="card6" alt="português">português</button>
                    </a>
                </div>
                <div class="card-card">
                <a href="pt.php">
                      <button id="card" class="card7" alt="coreano">coreano</button>
                    </a>
                </div>
            </div>
            <button class="carousel-prev">&#10094;</button>
            <button class="carousel-next">&#10095;</button>
        </div>
        <br>
        <div class="ranking-container">
    <h1> ranking mundial. </h1>
    <div id="ranking">
        <?php include 'song.php'; ?>
    </div>
</div>  

        <br><br><br><br>

        <div class="cards-home">
            <table>
                <tr>
                    <td><button id="card" class="card7" alt="coreano">Coreano</button></td>
                    <td><button id="card" class="card8" alt="turco">Turco</button></td>
                    <td><button id="card" class="card9" alt="russo">Russo</button></td>
                    <td><button id="card" class="card10" alt="japonês">Japonês</button></td>
                    <td><button id="card" class="card11" alt="francês">Francês</button></td>
                    <td><button id="card" class="card12" alt="aramaico">Aramaico</button></td>
                </tr>
            </table>
            <br><br><br>

            <table>
                <tr>
                    <td><button id="card" class="card13" alt="grego">Grego</button></td>
                    <td><button id="card" class="card14" alt="italiano">Italiano</button></td>
                    <td><button id="card" class="card15" alt="polonês">Polonês</button></td>
                    <td><button id="card" class="card16" alt="sueco">Sueco</button></td>
                    <td><button id="card" class="card17" alt="alemão">Alemão</button></td>
                    <td><button id="card" class="card18" alt="hebraico">Hebraico</button></td>
                </tr>
            </table>
        </div>
    </main>
    <br><br><br><br>
    <footer>
    <div class="footer-content">
        © 2024 <strong>Batuki</strong>. Todos os direitos reservados
        <br>
        Desenvolvido por Giovana Karolina | Fins Acadêmicos
        <div class="policies">
            <a href="#">Política de Privacidade</a> | 
            <a href="#">Termos de Uso</a>
        </div>
    </div>
    </footer>
</body>
</html>

<!-- Script player -->
<script>
function togglePlayPause(icon) {
    // Encontrar o div "audio-player" pai
    var player = icon.closest('.audio-player');
    
    // Procurar um elemento <audio> ou criar se não existir
    var audio = player.querySelector('audio');
    if (!audio) {
        var previewUrl = player.getAttribute('data-preview-url');
        audio = document.createElement('audio');
        audio.src = previewUrl;
        player.appendChild(audio);
    }

    // Alternar entre play e pause
    if (audio.paused) {
        audio.play();
        icon.classList.remove('fa-play');  // Remove ícone de play
        icon.classList.add('fa-pause', 'pause-icon');    // Adiciona ícone de pause
    } else {
        audio.pause();
        icon.classList.remove('fa-pause'); // Remove ícone de pause
        icon.classList.add('fa-play', 'play-icon');     // Adiciona ícone de play
    }

    // Evento para voltar o ícone para play quando a música terminar
    audio.onended = function() {
        icon.classList.remove('fa-pause');
        icon.classList.add('fa-play');
    };
}
</script>
<script>
// Script para o carrossel
document.addEventListener('DOMContentLoaded', function() {
    const carouselInner = document.querySelector('.carousel-inner');
    const prevButton = document.querySelector('.carousel-prev');
    const nextButton = document.querySelector('.carousel-next');
    let currentSlide = 0;
    
    // Captura todos os cards dentro do carrossel
    const cards = document.querySelectorAll('.card-card');

    // Função para mudar o slide visível
    function updateCarousel() {
        // Calcula a largura do carrossel para mover os slides corretamente
        const cardWidth = cards[0].offsetWidth + 16; // 16px de margem entre os cards
        carouselInner.style.transform = `translateX(-${currentSlide * cardWidth}px)`;
    }

    // Evento de clique para o botão "anterior"
    prevButton.addEventListener('click', function() {
        if (currentSlide > 0) {
            currentSlide--;
            updateCarousel();
        }
    });

    // Evento de clique para o botão "próximo"
    nextButton.addEventListener('click', function() {
        if (currentSlide < cards.length - 1) {
            currentSlide++;
            updateCarousel();
        }
    });

    // Defina a rotação automática (opcional)
    let autoRotate = setInterval(autoRotateCarousel, 5000); // a cada 5 segundos

    // Pausar a rotação automática ao passar o mouse no carrossel
    carouselInner.addEventListener('mouseover', function() {
        clearInterval(autoRotate);
    });

    // Retomar a rotação automática ao remover o mouse
    carouselInner.addEventListener('mouseout', function() {
        autoRotate = setInterval(autoRotateCarousel, 5000);
    });
});
</script>
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
    </script>