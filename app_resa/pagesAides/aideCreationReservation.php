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
        <title>StatiSalle - Aides Réservations</title>
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
                            Tout d’abord le formulaire est en deux parties, la première est une partie obligatoire que l’administrateur se doit de remplir s'il veut créer une nouvelle salle. Il contient le nom de la salle, l’activité qui va être exercée durant le créneau de la réservation, la date et le créneau horaire. Les champs obligatoires sont caractérisés par une étoile rouge. Sachant qu'une réservation ne peut pas être effectuée le dimanche.
                        </p>
                        <div class="row"> <!-- Salle -->
                            <div class="form-group col-md-12">
                                <label for="salle" class="erreur"><a title="Champ Obligatoire">Nom de la salle : *</a></label>
                                <select class="form-select" name="nomSalle" id="salle" required>
                                    <option value="" disabled selected>Choisir la salle</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row"> <!-- Activité -->
                            <div class="form-group col-md-12">
                                <label for="activite" class="erreur"><a title="Champ Obligatoire">Activité : *</a></label>
                                <select class="form-select" name="nomActivite" id="activite" required>
                                    <option value="" disabled selected>Choisir l'activité</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row"> <!-- Date, Heure Début, Heure Fin -->
                            <div class="form-group col-md-6">
                                <div class="row">
                                    <div class="form-group col-12">
                                        <label for="date" class="erreur"><a title="Champ Obligatoire">Date : *</a></label>
                                        <input type="date" id="date" name="date" class="form-control" required>

                                    </div>
                                </div>
                                <br>
                            </div>
                            <div class="form-group col-md-3">
                                <div class="row">
                                    <div class="form-group col-12">
                                        <label for="heureDebut" class="petite-taille erreur"><a title="Champ Obligatoire">Heure début : *</a></label>
                                        <select id="heureDebut" name="heureDebut" class="form-select" required>
                                        </select>
                                    </div>
                                </div>
                                <br>
                            </div>
                            <div class="form-group col-md-3">
                                <div class="row">
                                    <div class="form-group col-12">
                                        <label for="heureFin" class="petite-taille erreur"><a title="Champ Obligatoire">Heure de fin : *</a></label>
                                        <select id="heureFin" name="heureFin" class="form-select" required>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p>
                            Il y a une particularité, c'est que quand une activité est ajoutée il y a des précisions qui peuvent être apportées sur la réservation par exemple, si c’est une formation, nous pouvons mettre le sujet de la formation le nom et prénom du formateur, mais aussi son numéro de téléphone.
                        </p>
                        <div class="form-group col-md-4"> <!-- Informations supplémentaires -->
                            <br>
                            <div class="row" id="ligneObjet"> <!-- Objet de l'activité sélectionné -->
                                <div class="form-group col-md-6" >
                                    <label for="objet" id="titreObjet" class="">Objet :</label>
                                    <input type="text" class="form-control" name="objet" id="objet">
                                </div>
                            </div>
                            <br>
                            <div class="row"> <!-- Nom formateur ou interlocuteur -->
                                <div class="col-md-6">
                                    <div class="row" id="ligneNom">
                                        <div class="form-group col-12">
                                            <label for="nom" id="titreNom" class="">Nom :</label>
                                            <input type="text" id="nom" name="nom" class="form-control" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6"> <!-- Prénom formateur ou interlocuteur -->
                                    <div class="row" id="lignePrenom">
                                        <div class="form-group col-12">
                                            <label for="prenom" id="titrePrenom" class="">Prénom :</label>
                                            <input type="text" class="form-control" name="prenom" id="prenom">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row" > <!-- Numéro de téléphone formateur ou interlocuteur -->
                                <div class="form-group col-md-6">
                                    <div class="row">
                                        <div class="form-group col-12" id="ligneNum">
                                            <label for="numTel" class="">Numéro de téléphone :</label>
                                            <input type="tel" class="form-control" name="numTel" id="numTel">
                                        </div>
                                    </div>
                                    <br>
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="row"> <!-- Précision sur l'activité -->
                                        <div class="form-group col-12" id="lignePrecision">
                                            <label for="precisActivite">Précision :</label>
                                            <input type="text" class="form-control"  placeholder="Précisez votre activité" name="precisActivite" id="precisActivite">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer de la page -->
        <?php include '../include/footer.php'; ?>
    </body>
</html>
