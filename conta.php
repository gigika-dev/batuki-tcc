    <?php
        require_once('config.php');
    ?>

    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> Batuki - Cadastro. </title>
        <link rel="stylesheet" type="text/css" href="conta.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://getbootstrap.com/docs/5.2/assets/css/docs.css" rel="stylesheet">
        <link rel="shortcut icon" href="image." type="image/x-icon">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </head>
    <body>

        <form action="cadastro.php" method="POST">
        <div class="meu_container"> <!--vai conter todo o conteudo-->
            <div class="meu_container-login"> <!--estilo pagina login-->
            <div class="wrap-login"> <!--estilo formulario de login-->
                </span> <!-- O wrap inclui tudo q ta nele-->

                <div class="title"> criar conta </div>
                <div class="input-style">
                    <input type="text" id="username" name="user" placeholder="crie um nome de usuário" required>
                </div>

                <div class="input-style">
                    <input type="text" id="email" name="email" placeholder="email" required>
                </div>

                <div class="input-style" id="password-container">
                <input type="password" id="password" name="senha" placeholder="senha" required>
                <span id="eyepass" class="eyepass"> <!--MOSTRAR SENHA-->
                <i class="fa-regular fa-eye"></i> </span> 
            </div>  <!--TERMINA AQUI-->
            
                <div class="btn-login-meu">
                <button type="submit" class="meu_form-btn"> criar conta</button>
                </div>
                <br> <BR>
                <div class="login-utils">
                    <div class="text1"> Já possui uma conta? </div>
                            <a href="entre.php">
                            <button class="meu_btn"> Faça seu Login! </a> </button>
                </a>
            </form>        
        </div>
        </div>
    </form>
    </div>

        <script> 
            
                //  EFEITO   INPUT-LOGIN-SENHA-USER!!!
                
                // Seleciona todos os elementos que possuem a classe "input-form"
                let inputs = document.getElementsByClassName("input-form");

                // aItera sobre cada elemento encontrado
                for (let input of inputs) {

                // Adiciona um ouvinte de evento para o evento "blur" em cada elemento (input)
                input.addEventListener("blur", function() {

                // Verifica se o valor do input não está vazio após remover espaços em branco
                if (input.value.trim() != "") {

                // Se o input não estiver vazio, adiciona a classe "has-val" ao elemento
                input.classList.add("has-val");
            } else {

                // Se o input estiver vazio, remove a classe "has-val" do elemento
                input.classList.remove("has-val");
            }
        });
    }

    

                //  EFEITO   MOSTRAR SENHA!!!
            document.getElementById('eyepass').addEventListener('click', function () {
            // Adiciona um evento de clique ao ícone de olho


            // Obtém o campo de entrada de senha pelo ID 'password'
            const passwordField = document.getElementById('password');

            // Verifica o tipo atual do campo de senha
            // Se for 'password', muda para 'text' para mostrar a senha, caso contrário, volta para 'password'
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';

            // Define o novo tipo no campo de entrada (text ou password)
            passwordField.setAttribute('type', type);

            // Alterna o ícone (opcional)
            const icon = this.querySelector('i');
            // Obtém o elemento <i> dentro do botão de olho

            // Alterna entre os ícones de olho aberto e fechado
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
    });

                //  IMPEDE O COMPORTAMENTO PADRÃO (RECARREGAR PAGINA) !!!
            document.getElementById('registerForm').addEventListener('submit', function(event) {
            event.preventDefault();
    
            // Obtém os valores dos campos de entrada
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
    
            // Valida os valores (aqui você pode adicionar suas próprias validações ou chamar um servidor)
            if (username && password) {
            alert('Cadastro bem-sucedido!');

            // Aqui você pode redirecionar para a página de login ou fazer outra ação
            window.location.href = 'entre.html';
    } else {
            alert('Por favor, preencha todos os campos.');
    }
    });
            </script>        
    </body>
    </html>
