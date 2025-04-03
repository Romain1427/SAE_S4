<?php
    $startTime = microtime(true); // temps de chargement de la page
    session_start();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>StatiSalle - Contact</title>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <!-- CSS -->
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="../css/header.css">
        <link rel="stylesheet" href="../css/footer.css">
        <!-- Icon du site -->
        <link rel="icon" href="../img/logo.ico">
    </head>
        <body>
        <div class="container-fluid">
            <!-- header custom statique pour les pages accessible sans connexion -->
            <header class="header row align-items-center">
                <div class="header-gauche d-flex align-items-center gap-2">
                    <a href="accueil.php" title="Page d'accueil">
                        <img src="../img/LogoStatisalle.jpg" alt="Logo de StatiSalle" class="img-fluid">
                    </a>
                    <a href="accueil.php" class="text-decoration-none text-white" title="Page d'accueil">
                        <h1 class="m-0">StatiSalle</h1>
                    </a>
                </div>
            </header>

            <div class="full-screen">
                <div class="row text-center padding-header">
                    <h1>Contactez-nous</h1>
                </div>

                <div class="row d-flex justify-content-center align-items-start w-100 acc-row mb-5">
                    <div class="acc-container p-4 w-50 bg-light rounded shadow">
                        <h3 class="text-center mb-4">Nous sommes là pour vous aider</h3>
                        <p>Pour toute question ou demande d'information, veuillez nous écrire à l'adresse suivante :</p>
                        <p class="text-center">
                            <a href="mailto:contact@statisalle.fr" class="btn btn-primary rounded-pill">
                                <i class="fas fa-envelope"></i> contact@statisalle.fr
                            </a>
                        </p>
                        <p class="text-muted mt-4">
                            Nos horaires : Lundi au samedi, de 7h à 20h.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer de la page -->
            <?php include '../include/footer.php'; ?>
        </div>
    </body>
</html>
