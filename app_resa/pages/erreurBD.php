<?php
    $startTime = microtime(true); // temps de chargement de la page
    session_start();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>StatiSalle - Erreur Base de Données</title>
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

            <div class="full-screen">
                <div class="row text-center padding-header">
                    <h1>Erreur de connexion à la base de données</h1>
                </div>

                <div class="row d-flex justify-content-center align-items-start w-100 acc-row mb-5">
                    <div class="acc-container p-4 w-50">
                        <h3>Une erreur s'est produite lors du chargement des données. Veuillez vérifier votre
                            connexion réseau ou contactez l'administrateur système si le problème persiste.</h3>
                    </div>
                </div>
            </div>

            <!-- Footer de la page -->
            <?php include '../include/footer.php'; ?>
        </div>
    </body>
</html>