<?php

    // FAZ O LOGOUT DA SESSION
    session_start();
    session_destroy();
    header('Location: ../../../index.php');
    exit();

?>