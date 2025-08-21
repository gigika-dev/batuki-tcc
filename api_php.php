    <?php

    // Client ID e Client Secret fornecidos pelo Spotify Developer Dashboard
    $client_id = '0d999c447cd44c38bf2b461727d4a31b';  
    $client_secret = 'e7da045676784a078b4a572b6aae5a01';

    // Codifica as credenciais em Base64 (necessário para autenticação HTTP básica)
    $auth = base64_encode($client_id . ':' . $client_secret);

    // Inicializa a sessão cURL para realizar a requisição
    $curl = curl_init();

    // Define as opções da sessão cURL, como URL, tipo de requisição e cabeçalhos
    curl_setopt_array($curl, [
        // URL da API do Spotify para obter o token de acesso
        CURLOPT_URL => "https://accounts.spotify.com/api/token",
        // Retorna a resposta como string em vez de exibir diretamente
        CURLOPT_RETURNTRANSFER => true,
        // Cabeçalhos HTTP para a requisição: autenticação Base64 e tipo de conteúdo
        CURLOPT_HTTPHEADER => [
            "Authorization: Basic $auth",  // Autenticação usando o Client ID e Client Secret
            "Content-Type: application/x-www-form-urlencoded"  // Tipo de conteúdo enviado no corpo da requisição
        ],
        // O corpo da requisição, especificando o tipo de fluxo (client_credentials)
        CURLOPT_POSTFIELDS => "grant_type=client_credentials",
    ]);

    // Executa a requisição cURL e armazena a resposta na variável $response
    $response = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Obtém o código de status HTTP

    // Fecha a sessão cURL para liberar recursos
    curl_close($curl);

    // Verifica se a requisição para obter o token foi bem-sucedida
    if ($http_code == 200) {
        // Decodifica a resposta JSON para extrair o token de acesso
        $token_info = json_decode($response, true);
        $access_token = $token_info['access_token'];  // Armazena o token de acesso

        // Agora que temos o token de acesso, podemos fazer requisições à API do Spotify

        // Exemplo de requisição para buscar informações de um álbum (substitua o ID pelo que deseja)
        $album_id = '6akEvsycLGftJxYudPjmqK';  // ID do álbum que você deseja buscar

        // Inicializa uma nova sessão cURL para fazer a requisição do álbum
        $curl = curl_init();

        // Define as opções da sessão cURL para a segunda requisição
        curl_setopt_array($curl, [
            // URL da API do Spotify para buscar informações do álbum
            CURLOPT_URL => "https://api.spotify.com/v1/albums/$album_id",
            // Retorna a resposta como string
            CURLOPT_RETURNTRANSFER => true,
            // Cabeçalhos HTTP, incluindo o token de acesso obtido anteriormente
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $access_token"  // O token de acesso necessário para autenticar a requisição
            ],
        ]);

        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // Tempo máximo de 30 segundos
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10); // Tempo para conectar ao servidor (10 segundos)
        

        // Executa a requisição e armazena a resposta na variável $response
        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Obtém o código de status HTTP

        // Fecha a sessão cURL
        curl_close($curl);

        // Verifica se a requisição foi bem-sucedida (código 200)
        if ($http_code == 200) {
            echo "Requisição bem-sucedida! Dados do álbum:<br><br>";
            // Decodifica e exibe as informações do álbum
            $album_info = json_decode($response, true);
            echo "<pre>";
            print_r($album_info);  // Exibe as informações retornadas do álbum
            echo "</pre>";
        } else {
            // Exibe mensagem de erro se a requisição à API do Spotify falhar
            echo "Erro na requisição à API do Spotify. Código de status HTTP: " . $http_code;
        }
    } else {
        // Exibe mensagem de erro se a requisição para obter o token falhar
        echo "Erro ao obter o token de acesso. Código de status HTTP: " . $http_code;
    }
    ?>
