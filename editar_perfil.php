<?php
session_start();
$nome = isset($_SESSION['nome']) ? $_SESSION['nome'] : '';
$user = isset($_SESSION['user']) ? $_SESSION['user'] : '';
$bio = isset($_SESSION['bio']) ? $_SESSION['bio'] : '';
$avatar = isset($_SESSION['avatar']) ? 'uploads/' . $_SESSION['avatar'] : 'uploads/default-avatar.png';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="editar.css">
</head>
<body>
    <br> <br> <br> <br>
<div class="meu_container_img">
    <div class="title"> editar perfil</div>
    <form id="form-editar-perfil" enctype="multipart/form-data">
            <!-- Avatar -->
    <label for="avatar-input" class="custom-file-label">Selecionar Avatar</label>
    <input type="file" name="avatar" accept="image/*" id="avatar-input">

    <!-- Header -->
    <label for="header-input" class="custom-file-label">Selecionar Header</label>
    <input type="file" name="header" accept="image/*" id="header-input">

    <br> <br>

        <div class="meu_container">
            <div class="title"> personalize do seu jeito!!</div>
        <!-- Nome -->
        <div class="input-style"> 
            <input type="nome" id="nome" name="nome" placeholder="nome" required>
        </div>

        <!-- User -->
        <div class="input-style">
        <input type="user" id="user" name="user" placeholder="user" required>
        </div>

        <!-- Bio -->
        <div class="input-style">
        <input type="bio" id="bio" name="bio" placeholder="bio" required>
        </div>

        <button type="submit" class="save-button">Salvar Perfil</button>
    </form>
</div>
</div>


<script>
    // Seleciona os inputs de arquivo
    const avatarInput = document.getElementById('avatar-input');
    const headerInput = document.getElementById('header-input');

    // Atualiza o estado do botão quando o arquivo for selecionado
    function handleFileChange(inputId) {
        const label = document.querySelector(`label[for="${inputId}"]`);
        label.classList.add('selected'); // Adiciona a classe para mudar a cor
    }

    // Eventos para detectar mudanças
    avatarInput.addEventListener('change', () => handleFileChange('avatar-input'));
    headerInput.addEventListener('change', () => handleFileChange('header-input'));


    document.getElementById('form-editar-perfil').addEventListener('submit', salvarPerfil);

    function salvarPerfil(event) {
        event.preventDefault(); // Previne o envio padrão do formulário
        
        var form = document.getElementById('form-editar-perfil');
        var formData = new FormData(form);

        // Validação simples (verificação de campos obrigatórios)
        if (!form.nome.value || !form.user.value) {
            alert("Por favor, preencha todos os campos obrigatórios!");
            return;
        }
        
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'salvar_perfil.php', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    alert("Perfil salvo com sucesso!");
                    // Redireciona para a página inicial
                    window.location.href = 'perfil.php'; 
                } else {
                    console.error('Erro ao salvar o perfil');
                    alert('Erro ao salvar o perfil');
                }
            }
        };
        xhr.send(formData);
    }

    // Atualiza a imagem do avatar ao escolher um arquivo
    document.querySelector('input[name="avatar"]').addEventListener('change', function(event) {
        const reader = new FileReader();
        reader.onload = function() {
            document.getElementById('avatar-img').src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    });

    // Atualiza a imagem do header ao escolher um arquivo
    document.querySelector('input[name="header"]').addEventListener('change', function(event) {
        const reader = new FileReader();
        reader.onload = function() {
            document.getElementById('header-img').src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    });
</script>
</body>
</html>
