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
        <title>StatiSalle - Aides Modification Salle</title>
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

            <div class="full-screen mt-4">
                <div class="row text-center">
                    <h1>StatiSalle</h1>
                </div>

                <div class="row d-flex justify-content-center align-items-start w-100 mb-5">
                    <div class="acc-container p-4 w-50">
                        <p>
                            Pour effectuer une modification, il suffit de renseigner uniquement le champ que vous souhaitez modifier parmi les informations disponibles sur l'utilisateur. Par exemple, dans le cas d'une salle, vous pouvez changer son nom, sa capacité, indiquer si elle contient un vidéoprojecteur ou un ordinateur XXL, préciser le nombre d'ordinateurs présents, le type de matériel disponible, les logiciels installés sur les ordinateurs, ou encore si la salle est équipée d'une imprimante.
                            <br>
                            Si on essaye de modifier le nom d'une salle pour mettre le nom d'une salle déjà existante alors un message d'erreur apparaitra.
                            <br>
                            Si une salle est déjà réservée et que vous tentez de la modifier un pop up apparaitra demandant si vous voulez annuler ou confirmer. Si vous annulez vous serez redirigé vers la liste des salles si vous confirmez la modification est effectuée.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer de la page -->
            <?php include '../include/footer.php'; ?>
        </div>
    </body>
</html>
