<?php
session_start();
include 'config.php'; // Certifique-se de que as configurações do banco e do servidor estão corretas

// Diretório de upload
$uploadDir = 'uploads/';
$maxFileSize = 2 * 1024 * 1024; // 2MB
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

// Garante que o diretório de upload exista
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Função para validar e salvar arquivos
function salvarArquivo($file, $allowedExtensions, $maxFileSize, $uploadDir) {
    if ($file['error'] === UPLOAD_ERR_OK) {
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // Validação de extensão
        if (!in_array($extension, $allowedExtensions)) {
            die("Formato de arquivo inválido. Apenas JPG, PNG e GIF são permitidos.");
        }

        // Validação de tamanho
        if ($file['size'] > $maxFileSize) {
            die("Arquivo muito grande. O tamanho máximo permitido é 2MB.");
        }

        // Validação de imagem
        if (!getimagesize($file['tmp_name'])) {
            die("Arquivo enviado não é uma imagem válida.");
        }

        // Nome único para o arquivo
        $uniqueName = time() . '-' . basename($file['name']);
        $filePath = $uploadDir . $uniqueName;

        // Move o arquivo para o diretório de upload
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return $filePath;
        } else {
            die("Erro ao salvar o arquivo.");
        }
    }
    return null;
}

// Processa avatar
if (isset($_FILES['avatar'])) {
    $avatarPath = salvarArquivo($_FILES['avatar'], $allowedExtensions, $maxFileSize, $uploadDir);
    if ($avatarPath) {
        $_SESSION['avatar'] = $avatarPath;
    }
}

// Processa header
if (isset($_FILES['header'])) {
    $headerPath = salvarArquivo($_FILES['header'], $allowedExtensions, $maxFileSize, $uploadDir);
    if ($headerPath) {
        $_SESSION['header'] = $headerPath;
    }
}

// Atualiza outras informações do perfil
if (isset($_POST['nome'])) {
    $_SESSION['nome'] = htmlspecialchars($_POST['nome']);
}
if (isset($_POST['user'])) {
    $_SESSION['user'] = htmlspecialchars($_POST['user']);
}
if (isset($_POST['bio'])) {
    $_SESSION['bio'] = htmlspecialchars($_POST['bio']);
}

// Redireciona de volta ao perfil
header("Location: perfil.php");
exit;
?>
