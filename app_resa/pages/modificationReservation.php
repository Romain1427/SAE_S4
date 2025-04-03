<?php
    $startTime = microtime(true); // temps de chargement de la page
    require '../fonction/connexion.php';
    require '../fonction/reservation.php';

    session_start();
    verif_session();

    $idLogin = $_SESSION['id'];

    // Variables des erreurs
    $messageSucces = "";
    $erreurs = [];

    // Vérification des variables issues du formulaire
    $idResa =         isset($_POST['idReservation'])  ? htmlspecialchars($_POST['idReservation']) : null;
    $nomSalle =       isset($_POST['nomSalle'])       ? htmlspecialchars($_POST['nomSalle']) : '';
    $nomActivite =    isset($_POST['nomActivite'])    ? htmlspecialchars($_POST['nomActivite']) : '';
    $date =           isset($_POST['date'])           ? htmlspecialchars($_POST['date']) : '';
    $heureDebut =     isset($_POST['heureDebut'])     ? htmlspecialchars($_POST['heureDebut']) : '';
    $heureFin =       isset($_POST['heureFin'])       ? htmlspecialchars($_POST['heureFin']) : '';
    $valeurChamp1 =   isset($_POST['objet'])          ? htmlspecialchars($_POST['objet']) : '';
    $valeurChamp2 =   isset($_POST['nom'])            ? htmlspecialchars($_POST['nom']) : '';
    $valeurChamp3 =   isset($_POST['prenom'])         ? htmlspecialchars($_POST['prenom']) : '';
    $valeurChamp4 =   isset($_POST['numTel'])         ? htmlspecialchars($_POST['numTel']) : '';
    $precisActivite = isset($_POST['precisActivite']) ? htmlspecialchars($_POST['precisActivite']) : '';
    $nomActivitePrecedent = isset($_POST['nomActivitePrecedente']) ? $_POST['nomActivitePrecedente'] : '';
    $mettreAJour =    isset($_POST['mettreAJour']) ?? htmlspecialchars($_POST['mettreAJour']);

    // Contenu pour les listes déroulantes
    $tabSalles = listeSalles();
    $tabActivites = listeActivites();

    $detailsResa = recupAttributReservation($idResa);
    if ($detailsResa && $mettreAJour != 1) {
        // Préremplir les champs avec les données existantes, passage une seulle fois
        $nomSalle = $detailsResa['nomSalle'];
        $nomActivite = $detailsResa['nomActivite'];
        $date = $detailsResa['date_reservation'];
        $heureDebut = date("H:i", strtotime($detailsResa['heure_debut']));
        $heureFin = date("H:i", strtotime($detailsResa['heure_fin']));
        if (isset($detailsResa['details'])) {
            $details = $detailsResa['details'];

            switch ($nomActivite) {
                case "Réunion":
                    $valeurChamp1 = $details['objet'] ?? "";
                    break;
                case "Formation":
                    $valeurChamp1 = $details['sujet'] ?? "";
                    $valeurChamp2 = $details['nom_formateur'] ?? "";
                    $valeurChamp3 = $details['prenom_formateur']?? "";
                    $valeurChamp4 = $details['num_tel_formateur'] ?? "";
                    break;
                case "Entretien de la salle":
                    $valeurChamp1 = $details['nature'] ?? "";
                    break;
                case "Prêt" || "Location":
                    $valeurChamp1 = $details['nom_organisme'] ?? "";
                    $valeurChamp2 = $details['nom_interlocuteur'] ?? "";
                    $valeurChamp3 = $details['prenom_interlocuteur'] ?? "";
                    $valeurChamp4 = $details['num_tel_interlocuteur'] ?? "";
                    $precisActivite = $details['type_activite'] ?? '';
                    break;
                case "Autre":
                    $valeurChamp1 = $details['description'] ?? "";
                    break;
            }
        }
    }

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

    // Vérification que l'heure de fin est après l'heure de début
    if (strtotime($heureDebut) >= strtotime($heureFin)) {
        $erreurs['heureFin'] = "L'heure de fin doit être après l'heure de début.";
    }

    // Si aucun champ n'a d'erreur, on tente l'insertion
    if (empty($erreurs) && $mettreAJour == 1) {
        try {
            modifReservation($idResa, $nomSalle, $nomActivite, $date, $heureDebut, $heureFin, $valeurChamp1, $valeurChamp2, $valeurChamp3, $valeurChamp4, $precisActivite, $nomActivitePrecedent);
            $messageSucces = "Modification effectuée avec succès!";
        } catch (PDOException $e) {
            $erreurs[] = "Impossible de modifier la réservation dans la base de données : " . $e->getMessage();
        }
    }


    /*
     * Pour les prochains développeurs, l'impossibilité de réserver une salle
     * sur un même créneau le même jour a été effectuer sur la création d'une réservation.
     * Mais il faudrait aussi refaire cela sur la modification d'une réservation.
     * -----------------------------------------------------------------------------------
     * On vous laisse ci-dessous la structuration des informations que l'on a utiliser dans "creationReservation.php".
     * Structuration des réservations par salle et par date
     * afin de faciliter leur utilisation en JavaScript
     * (griser les heures déjà prises selon la salle et la date)
     */
    /*
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
    }*/
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

            <div class="full-screen mb-5">
                <!-- Contenu de la page -->
                <div class="row text-center padding-header">
                    <h1>Modifiez votre réservation</h1>
                </div>
                <br>

                <!-- Affichage des erreurs globales seulement après soumission -->
                <?php if (!empty($erreurs)): ?>
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <div class="alert alert-danger">
                                   <ul>
                                    <?php foreach ($erreurs as $erreur): ?>
                                        <li><?= $erreur ?></li>
                                    <?php endforeach; ?>
                                </ul>
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
                <form method="post" action="modificationReservation.php">
                    <div class="row"> <!-- Grande row -->
                        <div class="form-group offset-md-2 col-md-4"> <!-- first colonne -->
                            <br>
                            <div class="row"> <!-- Salle -->
                                <div class="form-group col-md-12">
                                    <input name="mettreAJour" type="hidden" value="1">
                                    <input name="nomActivitePrecedente" type="hidden" value="<?php echo htmlentities($nomActivite, ENT_QUOTES); ?>">

                                    <label for="salle" class="<?= isset($erreurs['nomSalle']) ? 'erreur' : '' ;?>"><a title="Champ Obligatoire">Nom de la salle : *</a></label>
                                    <select class="form-select" name="nomSalle" id="salle" required>
                                        <option value="<?php echo htmlentities($nomSalle, ENT_QUOTES); ?>" disabled selected>Choisir la salle</option>
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
                                <div class="form-group col-md-6"> <!-- Date -->
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label for="date" class="<?= isset($erreurs['date']) ? 'erreur' : '' ;?>"><a title="Champ Obligatoire">Date : *</a></label>
                                            <input type="date" id="date" name="date" class="form-control" min="<?= date('Y-m-d'); ?>" required
                                                   oninput="const day = new Date(this.value).getUTCDay(); if(day === 0){ this.value=''; alert('Réservation impossible le dimanche.'); }"
                                                   value="<?php echo htmlentities($date, ENT_QUOTES); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-3"> <!-- Heure Début -->
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label for="heureDebut" class="petite-taille <?= isset($erreurs['heureDebut']) ? 'erreur' : '' ;?>"><a title="Champ Obligatoire">Heure début : *</a></label>
                                            <select id="heureDebut" name="heureDebut" class="form-select" required>
                                                <option value="" disabled selected>00:00</option>
                                                <?php
                                                $heureDebutPossible = 7;
                                                $heureFinPossible = 20;

                                                for ($heure = $heureDebutPossible; $heure < $heureFinPossible; $heure++) {
                                                    for ($minute = 0; $minute < 60; $minute += 10) {
                                                        $heureFormatee = str_pad($heure, 2, '0', STR_PAD_LEFT);
                                                        $minuteFormatee = str_pad($minute, 2, '0', STR_PAD_LEFT);
                                                        $heureComplete = "$heureFormatee:$minuteFormatee";
                                                        // Comparaison et ajout de l'attribut 'selected' si l'heure correspond
                                                        $selected = ($heureComplete === $heureDebut) ? 'selected' : '';
                                                        echo "<option value=\"$heureComplete\" $selected>$heureComplete</option>\n";
                                                    }
                                                }
                                                echo "<option value=\"20:00\">20:00</option>\n";
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-3"> <!-- Heure Fin -->
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label for="heureFin" class="petite-taille <?= isset($erreurs['heureFin']) ? 'erreur' : '' ;?>"><a title="Champ Obligatoire">Heure de fin : *</a></label>
                                            <select id="heureFin" name="heureFin" class="form-select" required>
                                                <option value="" disabled selected>00:00</option>
                                                <?php
                                                for ($heure = $heureDebutPossible; $heure < $heureFinPossible; $heure++) {
                                                    for ($minute = 0; $minute < 60; $minute += 10) {
                                                        $heureFormatee = str_pad($heure, 2, '0', STR_PAD_LEFT);
                                                        $minuteFormatee = str_pad($minute, 2, '0', STR_PAD_LEFT);
                                                        $heureComplete = "$heureFormatee:$minuteFormatee";
                                                        // Comparaison et ajout de l'attribut 'selected' si l'heure correspond
                                                        $selected = ($heureComplete === $heureFin) ? 'selected' : '';
                                                        echo "<option value=\"$heureComplete\" $selected>$heureComplete</option>\n";
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
                            <div class="row" id="champ1"> <!-- Objet de l'activité sélectionné -->
                                <div class="form-group col-md-6" >
                                    <input name="idReservation" type="hidden" value="<?php echo $idResa; ?>">
                                    <label for="objet" id="titreObjet" class="">Objet :</label>
                                    <input type="text" class="form-control" name="objet" id="objet" value="<?php echo htmlentities($valeurChamp1, ENT_QUOTES) ?>">
                                </div>
                            </div>
                            <br>
                            <div class="row"> <!-- Nom formateur ou interlocuteur -->
                                <div class="col-md-6">
                                    <div class="row" id="champ2">
                                        <div class="form-group col-12">
                                            <label for="nom" id="titreNom" class="">Nom :</label>
                                            <input type="text" id="nom" name="nom" class="form-control" value="<?php echo htmlentities($valeurChamp2, ENT_QUOTES) ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6"> <!-- Prénom formateur ou interlocuteur -->
                                    <div class="row" id="champ3">
                                        <div class="form-group col-12">
                                            <label for="prenom" id="titrePrenom" class="">Prénom :</label>
                                            <input type="text" class="form-control" name="prenom" id="prenom" value="<?php echo htmlentities($valeurChamp3, ENT_QUOTES) ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row" > <!-- Numéro de téléphone -->
                                <div class="form-group col-md-6">
                                    <div class="row">
                                        <div class="form-group col-12" id="champ4">
                                            <label for="numTel" class="">Numéro de téléphone :</label>
                                            <input type="tel" class="form-control" name="numTel" id="numTel" value="<?php echo htmlentities($valeurChamp4, ENT_QUOTES) ?>">
                                        </div>
                                    </div>
                                    <br>
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="row"> <!-- Précision sur l'activité -->
                                        <div class="form-group col-12" id="champ5">
                                            <label for="precisActivite">Précision :</label>
                                            <input type="text" class="form-control"  placeholder="Précisez votre activité" name="precisActivite" id="precisActivite" value="<?php echo htmlentities($precisActivite, ENT_QUOTES) ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row mb-3">
                        <div class="col-12 col-sm-7 offset-sm-3 col-md-8 offset-md-2">
                            <button type="submit" class="btn-bleu rounded w-100" id="submit">
                                Mise à jour de la réservation
                            </button>
                        </div>
                    </div>
                </form>
                <div class ="row offset-md-2">
                    <div class="col-12 col-sm-7 offset-sm-3 col-md-2 offset-md-0">
                        <button class="btn-suppr rounded w-100" type="button" onclick="window.location.href='affichageReservation.php'">
                            Retour
                        </button>
                    </div>
                </div>
            </div>
            <!-- Footer de la page -->
            <?php include '../include/footer.php'; ?>
        </div>
        <!-- JavaScript pour les formulaires dynamique -->
        <script defer src="../fonction/formulaireReservation.js"></script>
    </body>
</html>