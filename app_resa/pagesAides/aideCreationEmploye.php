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
        <title>StatiSalle - Aides Employés</title>
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
                            Dans le formulaire, il y a plein de champs à remplir comme le nom, prénom, numéro de téléphone, compte utilisateur qui correspond au nom d’utilisateur du compte. Enfin, nous avons fait deux champs correspondant au mot de passe. Un premier qui sert à mettre le mot de passe une première fois et après un deuxième pour confirmer qu’il n’y a pas d’erreur sur la saisie.
                        </p>
                    </div>
                    <!-- Case à cocher pour les permissions administratives -->
                    <div class="row mt-3">
                        <div class="col-md-6 offset-md-3">
                            <label for="admin">Permissions administratives</label>
                            <input type="checkbox" id="admin" name="admin" value="1">
                            <small class="text-muted">Cochez cette case si l'employé a des permissions administratives.</small>
                        </div>
                    </div>
                    <p>
                        Pour confirmer la création d’un employé, nous avons mis un bouton de confirmation, mais aussi un bouton de retour si jamais l’administrateur veut revenir en arrière.
                    </p>
                </div>
            </div>

            <!-- Footer de la page -->
            <?php include '../include/footer.php'; ?>
        </div>
    </body>
</html>
