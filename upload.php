<?php
session_start();

if (isset($_FILES['profilePic'])) {
    $file = $_FILES['profilePic'];
    $uploadDir = 'uploads/'; // Diretório para salvar as imagens
    $fileName = uniqid() . '-' . basename($file['name']); // Nome único
    $uploadFile = $uploadDir . $fileName;

    // Verifica e move o arquivo para o diretório de upload
    if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
        $_SESSION['foto_perfil'] = $uploadFile; // Atualiza a sessão
        echo $uploadFile; // Retorna o caminho da imagem
    } else {
        http_response_code(500); // Caso falhe
        echo 'Erro ao salvar a imagem.';
    }
} else {
    http_response_code(400); // Nenhuma imagem enviada
    echo 'Nenhuma imagem foi enviada.';
}
?>
