<?php
session_start(); 
// Inicia a sessão para acessar as variáveis de sessão do usuário (informações do usuário, como nome, e-mail, avatar).

include 'config.php'; 
// Inclui o arquivo de configuração, que deve conter a conexão com o banco de dados ou outras configurações necessárias.

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: entre.php"); 
    // Se o usuário não estiver logado (a sessão 'logged_in' não existe ou não é verdadeira), redireciona para a página de login (entre.php).
    exit; 
    // Interrompe a execução do script, garantindo que o código abaixo não seja executado se o usuário não estiver logado.
}

if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == UPLOAD_ERR_OK) { 
    // Verifica se foi enviado um arquivo através do formulário e se não houve erro no envio (UPLOAD_ERR_OK).
    $file = $_FILES['avatar']; 
    // Armazena as informações do arquivo enviado (como nome, tipo, etc.).
    $uploadDirectory = 'uploads/'; 
    // Define o diretório onde o arquivo será armazenado.
    $fileName = basename($file['name']); 
    // Obtém o nome do arquivo, removendo qualquer caminho anterior (evita problemas de segurança).
    $uploadFile = $uploadDirectory . $fileName; 
    // Cria o caminho completo do arquivo para upload.

    // Verifica se o arquivo enviado é uma imagem
    $check = getimagesize($file['tmp_name']); 
    // Usa a função getimagesize para verificar se o arquivo é uma imagem válida.
    if ($check !== false) { 
        // Se for uma imagem válida...
        if (move_uploaded_file($file['tmp_name'], $uploadFile)) { 
            // Move o arquivo da pasta temporária para o diretório de uploads.
            $_SESSION['avatar'] = $fileName; 
            // Atualiza a sessão com o novo avatar (salva o nome do arquivo).
        } else {
            echo 'Erro ao fazer upload do arquivo.'; 
            // Se houver erro ao mover o arquivo, exibe uma mensagem de erro.
            exit; 
            // Interrompe a execução do script.
        }
    } else {
        echo 'Arquivo não é uma imagem.'; 
        // Se o arquivo não for uma imagem, exibe uma mensagem de erro.
        exit; 
        // Interrompe a execução do script.
    }
}

// Exibe o perfil do usuário com a foto de avatar e as informações (nome e user)
echo '<div class="container">
        <h1>Perfil do Usuário</h1>';
        
// Exibe a imagem de avatar do usuário. Se o avatar estiver definido, exibe a imagem correspondente, senão exibe a imagem padrão.
$avatarPath = isset($_SESSION['avatar']) ? 'uploads/' . $_SESSION['avatar'] : 'uploads/default-avatar.png'; 
echo '<img src="' . $avatarPath . '" alt="Avatar" class="avatar">';
// Exibe a imagem do avatar com a classe "avatar", que pode ser estilizada no CSS.

// Exibe as informações do usuário (nome e user) que estão armazenadas na sessão.
echo '<p>Nome: <span id="nome">' . $_SESSION['nome'] . '</span></p>
      <p>User: <span id="user">' . $_SESSION['user'] . '</span></p>
      <form id="upload-avatar-form" enctype="multipart/form-data">
        <input type="file" name="avatar" accept="image/*">
        <button type="button" class="upload-button" onclick="uploadAvatar()">Upload Avatar</button>
      </form>
      <button class="edit-button" onclick="editarPerfil()">Editar Perfil</button>
      </div>';
?>


<script>
    // Função para fazer o upload do avatar sem recarregar a página
    function uploadAvatar() {
        var form = document.getElementById('upload-avatar-form'); 
        // Obtém o formulário de envio do avatar.
        var formData = new FormData(form); 
        // Cria um objeto FormData para enviar os dados do formulário (incluindo o arquivo) via AJAX.
        var xhr = new XMLHttpRequest(); 
        // Cria uma nova instância de XMLHttpRequest para fazer a requisição assíncrona.
        xhr.open('POST', 'atualizar_avatar.php', true); 
        // Define a URL de destino para onde os dados serão enviados (atualizar_avatar.php) e o método (POST).
        xhr.onreadystatechange = function() { 
            // Define o que fazer quando o estado da requisição mudar.
            if (xhr.readyState === 4) { 
                // Verifica se a requisição foi concluída.
                if (xhr.status === 200) { 
                    // Se a requisição foi bem-sucedida (status 200), atualiza o avatar na página.
                    var avatarImg = document.querySelector('.avatar'); 
                    // Obtém a imagem do avatar exibida na página.
                    avatarImg.src = xhr.responseText; 
                    // Atualiza o caminho da imagem com o novo avatar (o servidor retorna o caminho da imagem).
                } else {
                    console.error('Erro ao fazer upload do avatar'); 
                    // Se houver algum erro, exibe no console.
                }
            }
        };
        xhr.send(formData); 
        // Envia os dados do formulário (incluindo o arquivo) para o servidor.
    }
</script>
