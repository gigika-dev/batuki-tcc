<?php
$apiKey = 'dd82abd0a12656c0fb197ca7615fa90e'; // Sua credencial API para autenticação
$artist = isset($_GET['artist']) ? $_GET['artist'] : ''; // Obtém o nome do artista da URL, ou usa uma string vazia se não estiver definido
$music = isset($_GET['music']) ? $_GET['music'] : ''; // Obtém o nome da música da URL, ou usa uma string vazia se não estiver definido

// Verifica se ambos os parâmetros foram fornecidos
if ($artist && $music) {
    // Monta a URL da API com os parâmetros fornecidos e a chave da API
    $url = "https://api.vagalume.com.br/search.php?art=" . urlencode($artist) . "&mus=" . urlencode($music) . "&apikey={$apiKey}";

    // Faz a requisição para a API usando file_get_contents
    $response = @file_get_contents($url);
    
    // Verifica se houve um erro ao fazer a requisição
    if ($response === FALSE) {
        echo "Erro ao conectar-se à API."; // Mensagem de erro se a conexão falhar
        exit; // Encerra o script
    }

    // Decodifica a resposta JSON da API em um array associativo
    $data = json_decode($response, true);

    // Verifica se o tipo de resposta é 'exact', indicando que a música foi encontrada
    if (isset($data['type']) && $data['type'] === 'exact') {
        // Exibe o nome da música
        echo "<h1>Letra da Música: " . htmlspecialchars($data['mus'][0]['name']) . "</h1>";
        // Exibe o nome do artista
        echo "<h2>Artista: " . htmlspecialchars($data['art']['name']) . "</h2>";
        // Exibe o texto da música, com formatação HTML preservada
        echo "<pre>" . htmlspecialchars($data['mus'][0]['text']) . "</pre>";
    } else {
        // Mensagem exibida se a letra da música não for encontrada
        echo "Letra não encontrada.";
    }
} else {
    // Mensagem exibida se os parâmetros necessários não forem fornecidos
    echo "Parâmetros inválidos.";
}
?>
