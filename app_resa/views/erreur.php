<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>StatiSalle - Employés</title>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <!-- CSS -->
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/header.css">
        <link rel="stylesheet" href="css/footer.css">
        <!-- Icon du site -->
        <link rel="icon" href=" img/logo.ico">
    </head>

    <body>
        <?php include 'include/header.php'; ?>

        <div class="container ms-4 pt-2 pt-md-4 d-flex align-items-center justify-content-center">
            <div class="d-flex flex-column flex-md-row gap-0 gap-sm-2 gap-md-5">
                <!-- Image d'erreur -->
                <div class="mt-2">
                    <img src="static/images/erreur.png" alt="Image d'erreur" class="error-page-image">
                </div>

                <div class="align-content-center">
                    <!-- Nom de l'erreur -->
                    <h1 class="fw-bold">Erreur <?= $codeErreur?></h1>
                    <h2><?= $nomErreur ?></h2>

                    <!-- Description de l'erreur -->
                    <div class="mt-1 mt-md-3">
                        Une erreur est survenue lors de l'utilisation du site. 
                        <br>
                        Veuillez réessayer plus tard. 

                        <br><br>
                        Nous somme désolés pour la gêne occasionnée, merci 
                        de votre compréhension !
                    </div>
                </div>
            </div>
        </div>

        <?php include "include/footer.php"; ?>
    </body>
</html>