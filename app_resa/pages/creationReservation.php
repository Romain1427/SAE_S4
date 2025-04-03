<?php

use function fonction\affichageReservation;
use function fonction\insertionReservation;
use function fonction\listeActivites;
use function fonction\listeSalles;
use function fonction\verif_session;
    $startTime = microtime(true); // temps de chargement de la page
    require '../fonction/connexion.php';
    require '../fonction/reservation.php';

    session_start();
    verif_session();

    $idLogin = $_SESSION['id'];

    $tabSalles = listeSalles();
    $tabActivites = listeActivites();
    $tabReservation = affichageReservation();

    // Vérification des variables du formulaire
    $nomSalle =         isset($_POST['nomSalle'])       ? htmlspecialchars($_POST['nomSalle']) : '';
    $nomActivite =      isset($_POST['nomActivite'])    ? htmlspecialchars($_POST['nomActivite']) : '';
    $date =             isset($_POST['date'])           ? htmlspecialchars($_POST['date']) : '';
    $heureDebut =       isset($_POST['heureDebut'])     ? htmlspecialchars($_POST['heureDebut']) : '';
    $heureFin =         isset($_POST['heureFin'])       ? htmlspecialchars($_POST['heureFin']) : '';
    $objet =            isset($_POST['objet'])          ? htmlspecialchars($_POST['objet']) : '';
    $nom =              isset($_POST['nom'])            ? htmlspecialchars($_POST['nom']) : '';
    $prenom =           isset($_POST['prenom'])         ? htmlspecialchars($_POST['prenom']) : '';
    $numTel =           isset($_POST['numTel'])         ? htmlspecialchars($_POST['numTel']) : '';
    $precisActivite =   isset($_POST['precisActivite']) ? htmlspecialchars($_POST['precisActivite']) : '';

    // Tableau pour stocker les erreurs
    $erreurs = [];
    $messageSucces = $messageErreur = "";

    // Validation des champs
    if ($nomSalle == '') {
        $erreurs['nomSalle'] = "Le nom de la salle est obligatoire.";
    }
    if ($nomActivite == '') {
        $erreurs['nomActivite'] = "L'activité est obligatoire.";
    }
    if ($date == '') {
        $erreurs['date'] = "La date est obligatoire.";
    }
    if ($heureDebut == '') {
        $erreurs['heureDebut'] = "L'heure de début est obligatoire.";
    }
    if ($heureFin == '') {
        $erreurs['heureFin'] = "L'heure de fin est obligatoire.";
    }

    // Si aucun champ n'a d'erreur, on tente l'insertion
    if (empty($erreurs)) {
        try {
            // Appel à la fonction pour insérer la réservation
            insertionReservation($nomSalle, $nomActivite, $date, $heureDebut, $heureFin, $objet, $nom, $prenom, $numTel, $precisActivite, $idLogin);
            $messageSucces = "Votre réservation a été effectuée avec succès!";
        } catch (PDOException $e) {
            $messageErreur = "Une erreur est survenue lors de la réservation.";
        }
    }

    /*
     * Structuration des réservations par salle et par date
     * afin de faciliter leur utilisation en JavaScript
     * (griser les heures déjà prises selon la salle et la date)
     */
    $reservationsParSalle = [];
    foreach ($tabReservation as $reservation) {
        $salle = $reservation['nom_salle'];
        $dateReservation = $reservation['date'];
        $heureDebut = $reservation['heure_debut'];
        $heureFin = $reservation['heure_fin'];

        if (!isset($reservationsParSalle[$salle])) {
            $reservationsParSalle[$salle] = [];
        }

        if (!isset($reservationsParSalle[$salle][$dateReservation])) {
            $reservationsParSalle[$salle][$dateReservation] = [];
        }

        // Ajouter les plages horaires réservées
        $reservationsParSalle[$salle][$dateReservation][] = [
            'heureDebut' => $heureDebut,
            'heureFin' => $heureFin
        ];
    }
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>StatiSalle - Réservation</title>
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
                <!-- Contenu de la page -->
                <div class="row text-center padding-header">
                    <h1>Réservez votre salle</h1>
                </div>
                <br>

                <!-- Affichage des erreurs -->
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
                <form method="post" action="creationReservation.php">
                    <div class="row"> <!-- Grande row -->
                        <div class="form-group offset-md-2 col-md-4"> <!-- first colonne -->
                            <br>
                            <div class="row"> <!-- Salle -->
                                <div class="form-group col-md-12">
                                    <label for="salle" class="<?= isset($erreurs['nomSalle']) ? 'erreur' : '' ;?>"><a title="Champ Obligatoire">Nom de la salle : *</a></label>
                                    <select class="form-select" name="nomSalle" id="salle" required>
                                        <option value="" disabled selected>Choisir la salle</option>
                                        <?php
                                        foreach ($tabSalles as $salle) { ?>
                                            <option value="<?= $salle ?>" <?= $salle == $nomSalle ? 'selected' : '' ?>><?= $salle ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row"> <!-- Activité -->
                                <div class="form-group col-md-12">
                                    <label for="activite" class="<?= isset($erreurs['nomActivite']) ? 'erreur' : '' ;?>"><a title="Champ Obligatoire">Activité : *</a></label>
                                    <select class="form-select" name="nomActivite" id="activite" required>
                                        <option value="" disabled selected>Choisir l'activité</option>
                                        <?php
                                        foreach ($tabActivites as $activite) { ?>
                                            <option value="<?= $activite ?>" <?= $activite == $nomActivite ? 'selected' : '' ?>><?= $activite ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row"> <!-- Date, Heure Début, Heure Fin -->
                                <div class="form-group col-md-6">
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label for="date" class="<?= isset($erreurs['date']) ? 'erreur' : '' ;?>"><a title="Champ Obligatoire">Date : *</a></label>
                                            <input type="date" id="date" name="date" class="form-control" min="<?= date('Y-m-d'); ?>" required
                                                   oninput="const day = new Date(this.value).getUTCDay(); if(day === 0){ this.value=''; alert('Réservation impossible le dimanche.'); }"
                                                   value="<?php echo htmlentities($date, ENT_QUOTES); ?>">
                                        </div>
                                    </div>
                                    <br>
                                </div>
                                <div class="form-group col-md-3">
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label for="heureDebut" class="petite-taille <?= isset($erreurs['heureDebut']) ? 'erreur' : '' ;?>">
                                                <a title="Champ Obligatoire">Heure début : *</a>
                                            </label>
                                            <select id="heureDebut" name="heureDebut" class="form-select" required>
                                                <!-- Option initiale vide -->
                                                <option value="" disabled selected>00:00</option>
                                                <?php
                                                    $heureDebutPossible = 7;
                                                    $heureFinPossible = 20;

                                                    for ($heure = $heureDebutPossible; $heure < $heureFinPossible; $heure++) {
                                                        for ($minute = 0; $minute < 60; $minute += 10) {
                                                            $heureFormatee = str_pad($heure, 2, '0', STR_PAD_LEFT);
                                                            $minuteFormatee = str_pad($minute, 2, '0', STR_PAD_LEFT);
                                                            echo "<option value=\"$heureFormatee:$minuteFormatee\">$heureFormatee:$minuteFormatee</option>\n";
                                                        }
                                                    }
                                                    echo "<option value=\"20:00\">20:00</option>\n";
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                                <div class="form-group col-md-3">
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label for="heureFin" class="petite-taille <?= isset($erreurs['heureFin']) ? 'erreur' : '' ;?>">
                                                <a title="Champ Obligatoire">Heure fin : *</a>
                                            </label>
                                            <select id="heureFin" name="heureFin" class="form-select" required>
                                                <!-- Option initiale vide -->
                                                <option value="" disabled selected>00:00</option>
                                                <?php
                                                    for ($heure = $heureDebutPossible; $heure < $heureFinPossible; $heure++) {
                                                        for ($minute = 0; $minute < 60; $minute += 10) {
                                                            $heureFormatee = str_pad($heure, 2, '0', STR_PAD_LEFT);
                                                            $minuteFormatee = str_pad($minute, 2, '0', STR_PAD_LEFT);
                                                            echo "<option value=\"$heureFormatee:$minuteFormatee\">$heureFormatee:$minuteFormatee</option>\n";
                                                        }
                                                    }
                                                    echo "<option value=\"20:00\">20:00</option>\n";
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                    <br>
                    <div class="row mt-4 offset-md-3">
                        <!-- Bouton envoyer le formulaire -->
                        <div class="col-12 col-md-8 mb-2">
                            <button type="submit" class="btn-bleu rounded w-100">
                                Réserver
                            </button>
                        </div>
                    </div>
                </form>
                <div class ="row  offset-md-2">
                    <div>
                        <button class="btn-suppr rounded-2" type="button"
                                onclick="window.location.href='affichageReservationUtilisateur.php';">
                            Retour
                        </button>
                    </div>
                </div>
            </div>
            <!-- Footer de la page -->
            <?php include '../include/footer.php'; ?>
        </div>
        <!-- JavaScript pour les formulaires dynamique -->
        <script id="data-reservations" type="application/json">
            <?= json_encode($reservationsParSalle, JSON_HEX_TAG); ?>
        </script>
        <script src="../fonction/formulaireCreationReservation.js" defer></script>
    </body>
</html>