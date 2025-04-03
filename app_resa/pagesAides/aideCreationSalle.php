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
        <title>StatiSalle - Aides Salles</title>
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
                            Tout d’abord le formulaire est en deux parties, la première est une partie obligatoire que l’administrateur se doit de remplir s'il veut créer une nouvelle salle. Il contient le nom de la salle, sa capacité si elle contient une vidéo projecteur ou non et si elle contient un ordinateur XXL. Les champs obligatoires sont caractérisés par une étoile rouge.
                        </p>
                        <div class="row"> <!-- Nom de la salle -->
                            <div class="form-group col-12">
                                <label for="nomSalle"><a title="Champ Obligatoire" class="erreur">Nom de la salle : *</a></label>
                                <input type="text" class="form-control" name="nomSalle" id="nomSalle" placeholder="Exemple : Salle Picasso" required>
                            </div>
                        </div>
                        <br>
                        <div class="row"> <!-- Capacité -->
                            <div class="form-group col-12">
                                <label for="capacite"><a title="Champ Obligatoire" class="erreur">Capacité de la salle : *</a></label>
                                <input type="number" class="form-control" name="capacite" id="capacite" min="1" required>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="row"> <!-- Vidéo projecteur -->
                            <div class="form-group col-12">
                                <label for="videoProjecteur"><a title="Champ Obligatoire" class="erreur">Vidéo projecteur : *</a></label>
                                <input type="radio" class="form-check-input" id="OUI" name="videoProjecteur" value="1" required>
                                <label class="form-check-label" for="OUI" class="erreur">Oui</label>
                                <input type="radio" class="form-check-input" id="NON" name="videoProjecteur" value="0" required>
                                <label class="form-check-label" for="NON" class="erreur">Non</label>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="row"> <!-- Ordinateur XXL -->
                            <div class="form-group col-md-12">
                                <label for="ordinateurXXL"><a title="Champ Obligatoire" class="erreur">Ordinateur XXL : *</a></label>
                                <input type="radio" class="form-check-input" id="OUI" name="ordinateurXXL" value="1" required>
                                <label class="form-check-label" for="OUI">Oui</label>
                                <input type="radio" class="form-check-input" id="NON" name="ordinateurXXL" value="0" required >
                                <label class="form-check-label" for="NON">Non</label>
                            </div>
                        </div>
                        <p>
                            Après, il y a les champs non obligatoires qui sont les champs qui concernent le nombre d’ordinateurs présent dans la salle le type de matériel qu’il peut y avoir dans la salle comme des pc portable par exemple. Il y a aussi les logiciels que le matériel peut contenir dans la salle et s'il y a une imprimante ou non.
                        </p>
                        <div class="form-group col-md-4"> <!-- Informations supplémentaires -->
                            <br>
                            <div class="row"> <!-- Nombre d'ordinateurs -->
                                <div class="form-group col-12">
                                    <label for="nbrOrdi">Nombre d'ordinateur : </label>
                                    <input type="number" class="form-control" name="nbrOrdi" id="nbrOrdi" min="0" >
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
                                    <input type="radio" class="form-check-input" id="OUI" name="imprimante" value="1">
                                    <label class="form-check-label" for="OUI">Oui</label>
                                    <input type="radio" class="form-check-input" id="NON" name="imprimante" value="0">
                                    <label class="form-check-label" for="NON">Non</label>
                                </div>
                            </div>
                        </div>
                        <p>
                            Enfin, il y a le bouton pour confirmer la création de la salle, mais aussi pour retourner en arrière.
                        </p>
                        <button class="btn-suppr rounded-2" type="button">
                            Retour
                        </button>
                        <button class="btn-bleu rounded" id="submit">
                            Créer la salle
                        </button>
                    </div>
                </div>
            </div>

            <!-- Footer de la page -->
            <?php include '../include/footer.php'; ?>
        </div>
    </body>
</html>
