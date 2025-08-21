<?php
require_once('config.php');
session_start();

// Verifica se o usuário já está logado
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: home.php"); // Redireciona para a homepage se já estiver logado
    exit();
}

// Credenciais do Spotify
$client_id = '0d999c447cd44c38bf2b461727d4a31b';
$redirect_uri = 'http://localhost/BATUKI/callback.php';
$scope = 'user-read-private user-read-email playlist-read-private user-top-read';

// URL de autorização do Spotify
$auth_url = "https://accounts.spotify.com/authorize?response_type=code&client_id=$client_id&redirect_uri=" . urlencode($redirect_uri) . "&scope=" . urlencode($scope);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batuki - Login</title>

    <link rel="stylesheet" href="entre.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="shortcut icon" href="image." type="image/x-icon">
</head>
<body>

<div class="meu_container"> <!-- Container principal -->
    <div class="meu_container-login"> <!-- Container da página de login -->
        <div class="wrap-login"> <!-- Envolve o formulário -->
            <div class="title">Faça seu login</div> 

            <form action="login.php" method="POST">
                <div class="input-style">
                    <input type="email" id="username" name="email" placeholder="Email" required>
                </div>

                <div class="input-style" id="password-container">
                    <input type="password" id="password" name="senha" placeholder="Senha" required>
                    <span id="eyepass" class="eyepass">
                        <i class="fa-regular fa-eye"></i> <!-- Ícone para mostrar/ocultar senha -->
                    </span>
                </div>

                <div class="btn-login-meu">
                    <button type="submit" class="meu_form-btn">Entrar</button>
                </div>
            </form>
            <br>
            <div class="login-utils">
                <div class="text1"> Ainda não possui uma conta? </div>
                    <a href="conta.php">
                    <div class="meu_btn"> Crie uma conta! </a>
            </div>


            </div>
        </div>
    </div>
</div>

<script>
// Efeito de mostrar/ocultar senha
document.getElementById('eyepass').addEventListener('click', function () {
    const passwordField = document.getElementById('password');
    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);

    const icon = this.querySelector('i');
    icon.classList.toggle('fa-eye');
    icon.classList.toggle('fa-eye-slash');
});

// Efeito para inputs com texto
let inputs = document.querySelectorAll(".input-style input");

inputs.forEach(input => {
    input.addEventListener("blur", function () {
        if (input.value.trim() !== "") {
            input.classList.add("has-val");
        } else {
            input.classList.remove("has-val");
        }
    });
});
</script>

</body>
</html>
