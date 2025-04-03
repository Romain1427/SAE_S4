<?php
    namespace fonction;
    // Détruit la session
    session_start();
    session_destroy();

    // Redirige l'utilisateur vers la page index
    header("Location: ../index.php");
    exit;
?>