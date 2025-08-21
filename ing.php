<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title> Músicas em Inglês </title>
    <link rel="stylesheet" href="ing.css"> <!-- Link para o arquivo CSS externo --></head>
<body>
    
  <nav id="menu">
      <img src="logo.png" id="logo" alt="logo batuki"> 

      <a href="home.php">
       home
      </a>
      <a href="idiomas.php">
          idiomas
      </a>
      <a href="musicas.php">
          músicas
      </a>

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
</nav>

<div class="meu_container"> 
    <?php
    // Credenciais de API do Spotify
    $client_id = '0d999c447cd44c38bf2b461727d4a31b';
    $client_secret = 'e7da045676784a078b4a572b6aae5a01';
    
    // URL de autenticação para obter o token de acesso do Spotify
    $auth_url = 'https://accounts.spotify.com/api/token';

    // Cabeçalhos necessários para a autenticação (client_id e client_secret em base64)
    $auth_headers = [
        'Authorization: Basic ' . base64_encode($client_id . ':' . $client_secret),
        'Content-Type: application/x-www-form-urlencoded'
    ];

    // Dados necessários para a requisição de token (usando o tipo client_credentials)
    $auth_data = http_build_query(['grant_type' => 'client_credentials']);
    
    // Inicializa uma requisição cURL para fazer a chamada de autenticação
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $auth_url); // Define a URL da requisição
    curl_setopt($ch, CURLOPT_POST, true); // Define o método da requisição como POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, $auth_data); // Anexa os dados da autenticação no corpo
    curl_setopt($ch, CURLOPT_HTTPHEADER, $auth_headers); // Adiciona os cabeçalhos da requisição
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Define que a resposta deve ser retornada como string
    $auth_response = curl_exec($ch); // Executa a requisição e armazena a resposta
    curl_close($ch); // Fecha a sessão cURL
    
    // Decodifica a resposta JSON da autenticação para extrair o token de acesso
    $auth_data = json_decode($auth_response);
    $token = $auth_data->access_token; // Armazena o token de acesso

    // Função para obter os dados de uma playlist do Spotify
    function getPlaylist($playlist_id, $token) {
        // URL da API do Spotify para acessar a playlist com base no ID fornecido
        $playlist_url = "https://api.spotify.com/v1/playlists/$playlist_id";
        
        // Cabeçalhos da requisição (inclui o token de acesso)
        $headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ];

        // Inicializa uma requisição cURL para buscar os dados da playlist
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $playlist_url); // Define a URL da requisição
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Adiciona os cabeçalhos (token de acesso)
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Define que a resposta deve ser retornada como string
        $response = curl_exec($ch); // Executa a requisição e armazena a resposta
        curl_close($ch); // Fecha a sessão cURL

        // Retorna a resposta da playlist como um array PHP
        return json_decode($response, true);
    }

    // Lista de playlists com seus IDs (associados a diferentes rankings)
    $playlists = [
        'Olá! você está no idioma Inglês :)' => '37i9dQZEVXbKuaTI1Z1Afx' // Nome da playlist => ID da playlist
    ];

    // Itera sobre cada playlist para exibir as faixas
    foreach ($playlists as $ranking_name => $playlist_id) {
        echo "<div class='ranking'>"; // Cria um container para o ranking
        echo "<h2>$ranking_name</h2>"; // Exibe o nome do ranking como um título

        // Chama a função getPlaylist para buscar os dados da playlist
        $playlist_data = getPlaylist($playlist_id, $token);

        // Verifica se os dados da playlist foram retornados corretamente (tracks)
        if (isset($playlist_data['tracks']) && isset($playlist_data['tracks']['items'])) {
            echo "<div class='track-list'>"; // Cria um container para a lista de faixas

            $position = 1; // Inicializa a posição do ranking

            // Itera sobre cada faixa da playlist
            foreach ($playlist_data['tracks']['items'] as $item) {
                $track = $item['track']; // Acessa os dados da faixa
                $track_name = $track['name']; // Nome da faixa
                $artist_name = $track['artists'][0]['name']; // Nome do artista (primeiro artista)
                $album_image = $track['album']['images'][0]['url']; // URL da imagem do álbum
                $preview_url = $track['preview_url']; // URL da prévia da faixa (se disponível)

                // Cria um item da faixa com suas informações
                echo "<div class='track-item'>"; // Inicia o item da faixa
                echo "<img src='$album_image' alt='Capa do Álbum' class='album-cover'>"; // Exibe a capa do álbum
                echo "<div class='track-info'>"; // Inicia a área de informações da faixa
                echo "<div class='music-artista'> <strong>$track_name</strong>  <br> $artist_name</div>"; // Exibe o nome da faixa e do artista
                
 
    // Se a música tiver uma prévia, exibe o ícone de player com controle play/pause
    if ($preview_url) {
        echo "<div class='audio-player' data-preview-url='$preview_url'>";
        echo "<i class='fa fa-play play-icon' onclick='togglePlayPause(this)' aria-hidden='true'></i>";  // Ícone de play
        echo "</div>";
    } else {
        echo "<p>Prévia indisponível</p>";  // Exibe uma mensagem se a prévia não estiver disponível
    }

                echo "</div>"; // Fecha a área de informações da faixa
                echo "</div>"; // Fecha o item da faixa

                $position++; // Incrementa a posição para a próxima faixa
            }

            echo "</div>"; // Fecha o container da lista de faixas
        } else {
            // Exibe uma mensagem de erro se os dados da playlist não forem carregados corretamente
            echo "<p>Não foi possível carregar o ranking de $ranking_name. Tente novamente mais tarde.</p>";
        }

        echo "</div>"; // Fecha o container do ranking
    }
    ?>
</div>
<footer>
    <div class="footer-content">
        © 2024 <strong>Batuki</strong>. Todos os direitos reservados.
        <br>
        Desenvolvido por Giovana Karolina | Fins Acadêmicos
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
      </script>

      
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

