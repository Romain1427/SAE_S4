<?php
    $startTime = microtime(true); // temps de chargement de la page
    require("../fonction/connexion.php");

    // Détection de la page actuelle
    $isAidePage = isset($_SESSION['nom_page']) && str_starts_with($_SESSION['nom_page'], 'aide');
    $basePath = $isAidePage ? '../' : ''; // Ajuste le chemin de base
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>StatiSalle - Aides Accueil</title>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <!-- CSS -->
        <link rel="stylesheet" href="<?php echo $basePath; ?>css/style.css">
        <link rel="stylesheet" href="<?php echo $basePath; ?>css/header.css">
        <link rel="stylesheet" href="<?php echo $basePath; ?>css/footer.css">
        <!-- Icon du site -->
        <link rel="icon" href="<?php echo $basePath; ?>img/logo.ico">
    </head>
    <body>
        <div class="container-fluid">

            <!-- header custom statique -->
            <header class="header row align-items-center">
                <div class="header-gauche d-flex align-items-center gap-2">
                    <a href="<?php echo $basePath; ?>pages/accueil.php" title="Page d'accueil">
                        <img src="<?php echo $basePath; ?>img/LogoStatisalle.jpg" alt="Logo de StatiSalle" class="img-fluid">
                    </a>
                    <a href="<?php echo $basePath; ?>pages/accueil.php" class="text-decoration-none text-white" title="Page d'accueil">
                        <h1 class="m-0">StatiSalle</h1>
                    </a>
                </div>
            </header>

            <div class="full-screen mt-4">
                <div class="row text-center">
                    <h1>StatiSalle</h1>
                </div>

                <div class="row d-flex justify-content-center align-items-start w-100 mb-5">
                    <div class="acc-container p-4 w-50">
                        <!-- Champ Identifiant -->
                        <div class="mb-3">
                            <div class="d-flex flex-column flex-sm-row justify-content-between">
                                <label for="identifiant" class="form-label mb-1 mb-sm-0">Identifiant</label>
                                <small class="form-text text-sm-start text-md-end">
                                    <a href="#" class="text-danger text-decoration-none" title="Identifiant oublié">
                                        Identifiant oublié ?
                                    </a>
                                </small>
                            </div>
                            <input type="text" class="form-control mt-2 mt-sm-0" name="identifiant" id="identifiant" placeholder="Entrez votre identifiant">
                        </div>
                        <p>
                            Dans ce champ, vous devez entrer l’identifiant de votre compte pour pouvoir accéder au reste des pages et fonctionnalités du site. La fonctionnalité de <b>l'identifiant oublié ne fonctionne pas</b>, elle est là pour montrer que cette fonctionnalité pourrait être implémentée dans le futur.
                        </p>
                        <!-- Champ mot de passe -->
                        <div class="mb-3">
                            <!-- Container pour label et small -->
                            <div class="d-flex flex-column flex-sm-row justify-content-between">
                                <label for="mdp" class="form-label mb-1 mb-sm-0">Mot de passe</label>
                                <small class="form-text text-sm-start text-md-end">
                                    <a href="#" class="text-danger text-decoration-none" title="Mot de passe oublié">
                                        Mot de passe oublié ?
                                    </a>
                                </small>
                            </div>
                            <input type="password" class="form-control mt-2 mt-sm-0" name="mdp" id="mdp" placeholder="Entrez votre mot de passe">
                        </div>
                        <p>
                            Dans ce champ, vous devez entrer le mot de passe concernant l’identifiant entré au-dessus afin d'accéder au reste du site. La fonctionnalité de <b>mot de passe oublié ne fonctionne pas</b>, elle est là pour montrer que cette fonctionnalité pourrait être implémentée dans le futur.
                            <br>
                        </p>
                        <button type="submit" class="btn btn-info w-100">Se connecter</button>
                        <p>
                            <br>
                            Une fois l’identifiant et le mot de passe saisis, il faut cliquer sur ce bouton pour effectuer une tentative de connexion.
                            Si jamais l’identifiant ou le mot de passe n’est pas juste, un message d’erreur s’affiche.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer de la page -->
            <?php include $basePath . 'include/footer.php'; ?>
        </div>
    </body>
</html>
