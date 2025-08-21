<?php
require_once('config.php');
session_start();

// Verifica se o usuário já está logado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Redireciona para a página de login se o usuário não estiver logado
    header("Location: entre.php");
    exit();
}

// Obtém os dados da sessão ou define valores padrão
$avatar = isset($_SESSION['avatar']) ? $_SESSION['avatar'] : 'uploads/default-avatar.png';
$header = isset($_SESSION['header']) ? $_SESSION['header'] : 'uploads/default-header.png';
$nome = isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Usuário';
$user = isset($_SESSION['user']) ? $_SESSION['user'] : 'Sem user cadastrado';
$bio = isset($_SESSION['bio']) ? $_SESSION['bio'] : 'Sem biografia.';
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Perfil</title>
    <link rel="stylesheet" href="perfil.css">
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

</nav>
<div class="meu_container">
    <div class="header-container">
        <img src="<?php echo $header; ?>" alt="Header" class="header-img">
        <!-- Botão para Editar Perfil -->
        <div class="edit-profile-btn">
        <a href="editar_perfil.php" class="edit-button">Editar Perfil</a>
    </div>
        <div class="avatar-container">
        <img src="<?php echo $avatar; ?>" alt="Avatar" class="avatar-img">
    </div>
<div class="container-logout">
        <a href="logout.php" class="logout-btn"> sair. </a>
    </div>
    </div>
    <div class="info-container">
        <div class="nome"><?php echo htmlspecialchars($nome); ?></div>
        <div class="user"> <?php echo htmlspecialchars($user); ?></div>
        <div class="bio"> <?php echo htmlspecialchars($bio); ?></div>
    </div>
</div>
</body>
</html>

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
