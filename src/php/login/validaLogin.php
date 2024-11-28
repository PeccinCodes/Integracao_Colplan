<?php
    session_start();
    // Verificar se o usuário está autenticado
    if (!isset($_SESSION['usuarioAd'])) {
        // Redirecionar para a página de login se o usuário não estiver autenticado
        header('Location: ../../pages/login.php');
        exit();
    }
?>