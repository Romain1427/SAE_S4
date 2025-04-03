<?php

use function fonction\creationSalle;
use function fonction\verif_session;
use function fonction\verifNomSalle;
    $startTime = microtime(true); // temps de chargement de la page
    require '../fonction/connexion.php';
    require '../fonction/salle.php';

    session_start();
    verif_session();

    // Vérification des variables issues du formulaire
    $idSalle =          "";
    $nomSalle =         isset($_POST['nomSalle'])        ? htmlspecialchars($_POST['nomSalle']) : '';
    $capacite =         isset($_POST['capacite'])        ? htmlspecialchars($_POST['capacite']) : '';
    $videoProjecteur =  isset($_POST['videoProjecteur']) ? htmlspecialchars($_POST['videoProjecteur']) : '';
    $ordinateurXXL =    isset($_POST['ordinateurXXL'])   ? htmlspecialchars($_POST['ordinateurXXL']) : '';
    $nbrOrdi =          isset($_POST['nbrOrdi'])         ? htmlspecialchars($_POST['nbrOrdi']) : '';
    $typeMateriel =     isset($_POST['typeMateriel'])    ? htmlspecialchars($_POST['typeMateriel']) : '';
    $logiciel =         isset($_POST['logiciel'])        ? htmlspecialchars($_POST['logiciel']) : '';
    $imprimante =       isset($_POST['imprimante'])      ? htmlspecialchars($_POST['imprimante']) : '';

    // Tableau pour stocker les erreurs
    $erreurs = [];
    $messageSucces = $messageErreur = "";

    // Vérification des champs obligatoires
    if ($nomSalle == '') {
        $erreurs['nomSalle'] = "Le nom de la salle est obligatoire.";
    }
    if ($capacite == '') {
        $erreurs['capacite'] = "La capacité est obligatoire.";
    }
    if ($videoProjecteur == '') {
        $erreurs['videoProjecteur'] = "Le choix pour le vidéo projecteur est obligatoire.";
    }
    if ($ordinateurXXL == '') {
        $erreurs['ordinateurXXL'] = "Le choix pour l'ordinateur XXL est obligatoire.";
    }

    // Vérification que l'id est bien unique dans la base de données
    if (verifNomSalle($nomSalle, $idSalle)) {
        $messageErreur = "Erreur : Ce nom de salle existe déjà. Veuillez en choisir un autre.";
        $nomSalle = '';
    }

    if (!isset($erreurs['nomSalle']) && !isset($erreurs['capacite']) && !isset($erreurs['videoProjecteur']) && !isset($erreurs['ordinateurXXL']) && $messageErreur == ""){
        try {
            creationSalle($nomSalle, $capacite, $videoProjecteur, $ordinateurXXL, $nbrOrdi, $typeMateriel, $logiciel, $imprimante);
            $messageSucces = "Salle ajoutée avec succès !";
        } catch (PDOException $e) {
            $messageErreur = "Une erreur est survenue lors de la création de la salle.";
        }
    }
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>StatiSalle - Création Salle</title>
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

            <div class="full-screen mb-4">
                <!-- Contenu de la page -->
                <div class="row text-center padding-header">
                    <h1>Création d'une salle</h1>
                </div>
                <br>
                <!-- Affichage du message d'erreur -->
                <?php if ($messageErreur): ?>
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <div class="alert alert-danger">
                                <?= $messageErreur ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Affichage du message de succès -->
                <?php if ($messageSucces): ?>
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <div class="alert alert-success">
                                <?= $messageSucces ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <br>
                <form method="post" action="creationSalle.php">
                    <div class="row"> <!-- Grande row -->
                        <div class="form-group offset-md-2 col-md-4"> <!-- first colonne -->
                            <br>
                            <div class="row"> <!-- Nom de la salle -->
                                <div class="form-group col-12">
                                    <label for="nomSalle" class="<?= isset($erreurs['nomSalle']) ? 'erreur' : '' ;?>" ><a title="Champ Obligatoire">Nom de la salle : *</a></label>
                                    <input type="text" class="form-control" name="nomSalle" id="nomSalle" value="<?php echo htmlentities($nomSalle, ENT_QUOTES); ?>" placeholder="Exemple : Salle Picasso" required>
                                </div>
                            </div>
                            <br>
                            <div class="row"> <!-- Capacité -->
                                <div class="form-group col-12">
                                    <label for="capacite" class="<?= isset($erreurs['capacite']) ? 'erreur' : ' ' ?>"><a title="Champ Obligatoire">Capacité de la salle : *</a></label>
                                    <input type="number" class="form-control" name="capacite" id="capacite" value="<?php echo htmlentities($capacite, ENT_QUOTES); ?>" min="1" required>
                                </div>
                            </div>
                            <br>
                            <br>
                            <div class="row"> <!-- Vidéo projecteur -->
                                <div class="form-group col-12">
                                    <label for="videoProjecteur" class="<?= isset($erreurs['videoProjecteur']) ? 'erreur' : ' ' ?>"><a title="Champ Obligatoire">Vidéo projecteur : *</a></label>
                                    <input type="radio" class="form-check-input" id="OuiVideoProj" name="videoProjecteur" value="1" <?= $videoProjecteur == '1' ? 'checked' : '' ?> required>
                                    <label class="form-check-label" for="OUI">Oui</label>
                                    <input type="radio" class="form-check-input" id="NonVideoProj" name="videoProjecteur" value="0" <?= $videoProjecteur == '0' ? 'checked' : '' ?> required>
                                    <label class="form-check-label" for="NON">Non</label>
                                </div>
                            </div>
                            <br>
                            <br>
                            <div class="row"> <!-- Ordinateur XXL -->
                                <div class="form-group col-md-12">
                                    <label for="ordinateurXXL" class="<?= isset($erreurs['ordinateurXXL']) ? 'erreur' : ' ' ?>"><a title="Champ Obligatoire">Ordinateur XXL : *</a></label>
                                    <input type="radio" class="form-check-input" id="OuiOrdinateurXXL" name="ordinateurXXL" value="1" <?= $ordinateurXXL == '1' ? 'checked' : '' ?> required>
                                    <label class="form-check-label" for="OUI">Oui</label>
                                    <input type="radio" class="form-check-input" id="NonOrdinateurXXL" name="ordinateurXXL" value="0" <?= $ordinateurXXL == '0' ? 'checked' : '' ?> required >
                                    <label class="form-check-label" for="NON">Non</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-4"> <!-- Informations supplémentaires -->
                            <br>
                            <div class="row"> <!-- Nombre d'ordinateurs -->
                                <div class="form-group col-12">
                                    <label for="nbrOrdi">Nombre d'ordinateur : </label>
                                    <input type="number" class="form-control" name="nbrOrdi" id="nbrOrdi" min="0" value="0" >
                                </div>
                            </div>
                            <br>
                            <div class="row"> <!-- Type matériel -->
                                <div class="form-group col-12">
                                    <label for="typeMateriel">Type de matériel :</label>
                                    <input type="text" class="form-control" name="typeMateriel" id="typeMateriel">
                                </div>
                            </div>
                            <br>
                            <div class="row"> <!-- Logiciels installés  -->
                                <div class="form-group col-md-12">
                                    <label for="logiciel">Logiciels installés :</label>
                                    <input type="text" class="form-control" name="logiciel" id="logiciel" placeholder="Exemple à suivre : bureautique, java, intellij, photoshop">
                                </div>
                            </div>
                            <br>
                            <div class="row"> <!-- Imprimante -->
                                <div class="form-group col-md-12">
                                    <label for="imprimante">Imprimante : </label>
                                    <input type="radio" class="form-check-input" id="OuiImprimante" name="imprimante" value="1">
                                    <label class="form-check-label" for="OUI">Oui</label>
                                    <input type="radio" class="form-check-input" id="NonImprimante" name="imprimante" value="0" checked>
                                    <label class="form-check-label" for="NON">Non</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-3 text-center w-100 ">
                            <button type="submit" class="btn-bleu rounded" id="submit">
                                Créer la salle
                            </button>
                            <br><br>
                        </div>
                    </div>
                </form>
                <div class ="row offset-md-2">
                    <div>
                        <button class="btn-suppr rounded-2" type="button"
                                onclick="window.location.href='affichageSalle.php'">
                            Retour
                        </button>
                    </div>
                </div>
            </div>

            <!-- Footer de la page -->
            <?php include '../include/footer.php'; ?>
        </div>
    </body>
</html>
