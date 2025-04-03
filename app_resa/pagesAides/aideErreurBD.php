<?php
$startTime = microtime(true); // temps de chargement de la page
require("../fonction/connexion.php");

session_start();
verif_session();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>StatiSalle - Aides erreur BD</title>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <!-- CSS -->
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="../css/header.css">
        <link rel="stylesheet" href="../css/footer.css">
        <!-- Icon du site -->
        <link rel="icon" href=" ../img/logo.ico">
    </head>
    <body>
        <div class="container-fluid">
            <!-- Header de la page -->
            <?php include '../include/header.php'; ?>

            <div class="full-screen padding-header">
                <div class="row text-center">
                    <h1>StatiSalle</h1>
                </div>

                <div class="row d-flex justify-content-center align-items-start w-100 mb-5">
                    <div class="acc-container p-4 w-50">
                        <p>
                            Cette page sert uniquement à vous informer d'un problème de connexion avec la base de données. Si jamais le problème persiste n'hésitez pas à nous contacter via le bouton situé en bas de page.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer de la page -->
            <?php include '../include/footer.php'; ?>
        </div>
    </body>
</html>
