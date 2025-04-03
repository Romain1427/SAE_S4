<?php

use function fonction\verif_session;
    $startTime = microtime(true); // temps de chargement de la page
    require("../fonction/connexion.php");

    session_start();
    verif_session();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>StatiSalle - Accueil</title>
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
                        <?php
                            echo "<p>Bienvenue, " . $_SESSION['login'] . " !</p>"
                        ?>
                        <p>
                            Ce site vous permet de gérer facilement les réservations des salles partagées pour vos réunions, formations, et autres activités.
                            <br>
                            Que voulez-vous faire ?
                        </p>
                        <p>
                            <!-- Lien vers la page pour faire une réservation -->
                            <a href="creationReservation.php" target="blank" class="text-decoration-none">🕒 Nouvelle réservation (ajout rapide).</a>
                        </p>
                        <p>
                            <!-- Lien vers la page pour afficher les réservations -->
                            <a href="affichageReservation.php" target="blank" class="text-decoration-none">
                                <?php
                                // si l'utilisateur est un admin alors, il peut accéder à la liste des employés
                                if ($_SESSION['typeUtilisateur'] === 1) {
                                    echo '📅 Gérer les réservations.';
                                } else {
                                    echo '📅 Afficher les réservations.';
                                }
                                ?>
                            </a>
                        </p>
                        <p>
                            <!-- Lien vers la page pour afficher les réservations relié au compte -->
                            <a href="affichageReservationUtilisateur.php" target="blank" class="text-decoration-none">✏️ Gérer mes réservations.</a>
                        </p>
                        <p>
                            <!-- Lien vers la page pour afficher ou gérer les salles -->
                            <a href="affichageSalle.php" target="blank" class="text-decoration-none">
                                <?php
                                // si l'utilisateur est un admin alors, il peut accéder à la liste des employés
                                if ($_SESSION['typeUtilisateur'] === 1) {
                                    echo '🏢 Gérer les salles.';
                                } else {
                                    echo '🏢 Afficher les salles.';
                                }
                                ?>
                            </a>
                        </p>
                        <p>
                            <!-- Lien vers la page pour exporter les données -->
                            <a href="exportation.php" target="blank" class="text-decoration-none">📊 Exporter des données.</a>
                        </p>
                        <p>
                            <?php
                                //si l'utilisateur est un admin alors, il peut accéder à la liste des employés
                                if($_SESSION['typeUtilisateur'] === 1){
                                echo '<a href="../index.php?controller=employe" target="blank" class="text-decoration-none">👥 Gérer les utilisateurs.</a>';
                                }
                            ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer de la page -->
            <?php include '../include/footer.php'; ?>
        </div>
    </body>
</html>
