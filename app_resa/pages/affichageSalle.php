<?php

use function fonction\listeDesCapacites;
use function fonction\listeDesLogiciels;
use function fonction\listeDesNoms;
use function fonction\listeDesOrdinateurs;
use function fonction\listeDesSalles;
use function fonction\supprimerSalle;
use function fonction\verif_session;
use function fonction\verifierReservations;
    $startTime = microtime(true); // temps de chargement de la page
    require '../fonction/connexion.php';
    require '../fonction/salle.php';

    session_start();
    verif_session();

    $messageSucces = $messageErreur ='';
    $supprimer = isset($_POST['supprimer']) ? $_POST['supprimer'] : 'false';
    $id_salle = $_POST['idSalle'] ?? null;

    if ($supprimer == "true" && $id_salle) {
        try {
            $reservations = verifierReservations($id_salle);

            if (count($reservations) > 0) {
                // Si des réservations existent, afficher un message d'erreur
                $messageErreur = '<span class="fa-solid fa-arrow-right erreur"></span>
                                            <span class="erreur">Impossible de supprimer cette salle. 
                                            Des réservations y sont associées.</span>
                                            <a href="affichageReservation.php" title="Page réservation">Cliquez ici</a>';
            } else {
                // Si pas de réservations, supprimer la salle
                supprimerSalle($id_salle);
                $messageSucces = 'Salle supprimée avec succès !';
            }
        } catch (Exception $e) {
            $messageSucces = '<span class="fa-solid fa-arrow-right erreur"></span>
                                        <span class="erreur">Une erreur est survenue : ' . htmlspecialchars($e->getMessage()) . '</span>';
        }
    }

    // Chargement des filtres
    $tabNoms       = listeDesNoms();
    $tabCapacite   = listeDesCapacites();
    $tabOrdinateur = listeDesOrdinateurs();
    $tabLogiciels  = listeDesLogiciels();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>StatiSalle - Salles</title>
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
                <!-- Titre de la page -->
                <div class="padding-header row">
                    <div class="col-12">
                        <h1 class="text-center">Liste des Salles</h1>
                    </div>
                    <br><br><br>
                </div>

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

                <!-- 1 ère ligne avec le bouton "Ajouter" -->
                <?php
                if($_SESSION['typeUtilisateur'] === 1 ){
                    echo '<div class="row mb-3">
                                <div class="col-12 text-center text-md-end">
                                        <button class="btn-bleu rounded-2" onclick="window.location.href=`creationSalle.php`;">
                                        <span class="fa-plus">
                                            Ajouter
                                        </span>
                                    </button>
                                </div>
                          </div>';
                }
                ?>

                <div class="row g-1 justify-content-start"> <!-- Grande row des filtres avec espacement réduit -->
                    <!-- Nom des salles -->
                    <div class="col-12 col-md-2 mb-1 col-reduit-salle ">
                        <select class="form-select select-nom" id="nom">
                            <option value=""  selected>Nom</option>
                            <?php
                            foreach ($tabNoms as $nom) { // On boucle sur les noms contenus dans le tableau
                                echo '<option value="'.$nom.'">'.$nom.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <!-- Capacité -->
                    <div class="col-12 col-md-1 mb-1">
                        <select class="form-select select-nom" id="capacite">
                            <option value="" selected>Capacité</option>
                            <?php
                            foreach ($tabCapacite as $capacite) { // On boucle sur la capacité contenue dans le tableau
                                echo "<option value=".$capacite.">".$capacite."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!-- Vidéo projecteur -->
                    <div class="col-12 col-md-1 mb-1 col-grand-salle ">
                        <select class="form-select select-nom" id="videoproj">
                            <option value="" selected>Vidéo projecteur</option>
                            <option value ="oui">Oui</option>
                            <option value ="non">Non</option>
                        </select>
                    </div>
                    <!-- Grand écran -->
                    <div class="col-12 col-md-1 mb-1 col-grand-salle ">
                        <select class="form-select select-nom" id="grandEcran">
                            <option value="" selected>Écran XXL</option>
                            <option value ="oui">Oui</option>
                            <option value ="non">Non</option>
                        </select>
                    </div>
                    <!-- Nombre ordinateur -->
                    <div class="col-12 col-md-1 mb-1 col-grand-salle ">
                        <select class="form-select select-nom" id="nbrOrdi">
                            <option value="" selected>Ordinateur</option>
                            <?php
                            foreach ($tabOrdinateur as $nbrOrdi) { // On boucle sur le nombre d'ordinateurs contenus dans le tableau
                                echo "<option value=".$nbrOrdi.">".$nbrOrdi."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!-- Logiciel -->
                    <div class="col-12 col-md-3 mb-1">
                        <select class="form-select select-nom" id="logiciel">
                            <option value="" selected>Logiciel</option>
                            <?php
                            foreach ($tabLogiciels as $logiciel) { // On boucle sur les logiciels contenus dans le tableau
                                echo '<option value="'.$logiciel.'">'.$logiciel.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <!-- Imprimante -->
                    <div class="col-12 col-md-1 mb-1 col-grand-salle ">
                        <select class="form-select select-nom" id="imprimante">
                            <option value="" selected>Imprimante</option>
                            <option value ="oui">Oui</option>
                            <option value ="non">Non</option>
                        </select>
                    </div>
                    <!-- Bouton de réinitialisation des filtres -->
                    <div class="col-6 col-sm-6 col-md-1 mb-1">
                        <button class="btn-reset rounded-1 col-md-12">
                            Réinitialiser filtres
                        </button>
                    </div>
                </div>

                <!-- Tableau des données -->
                <div class="row mt-3">

                    <!-- Compteur et chargement des données -->
                    <?php
                    try {
                        $listeSalle = listeDesSalles();
                    } catch (PDOException $e) {
                        echo '<div class="text-center text-danger fw-bold">Impossible de charger les salles en raison d’un problème technique.</div>';
                    }

                    $nombreSalle = count($listeSalle ?? []);
                    ?>
                    <div class="col-12 text-center mb-3">
                        <p class="fw-bold compteur-salle">
                            Nombre de comptes employé trouvé(s) : <?= $nombreSalle ?>
                        </p>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped text-center">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Capacité</th>
                                    <th>Vidéo Projecteur</th>
                                    <th>Écran XXL</th>
                                    <th>Nombre Ordinateurs</th>
                                    <th>Type</th>
                                    <th>Logiciels</th>
                                    <th>Imprimante</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            // Affichage des salles
                                foreach ($listeSalle as $salle) {
                                echo "<tr>";
                                echo "<td>".$salle['id_salle']."</td>";
                                echo "<td>".$salle['nom']."</td>";
                                echo "<td>".$salle['capacite']."</td>";
                                // Videoproj : Condition pour afficher Oui/Non
                                echo "<td>".($salle['videoproj'] == 1 ? "Oui" : "Non")."</td>";
                                // Ecran XXL : Condition pour afficher Oui/Non
                                echo "<td>".($salle['ecran_xxl'] == 1 ? "Oui" : "Non")."</td>";
                                echo "<td>".$salle['ordinateur']."</td>";
                                echo "<td>".$salle['type']."</td>";
                                echo "<td>".$salle['logiciels']."</td>";
                                // Imprimante : Condition pour afficher Oui/Non
                                echo "<td>".($salle['imprimante'] == 1 ? "Oui" : "Non")."</td>";
                                if($_SESSION['typeUtilisateur'] === 1){
                                    // Mise en forme (boutons alignés verticalement
                                    echo '<td class="btn-colonne">';
                                    echo '<div class="d-flex justify-content-center gap-1">';
                                    ?>
                                    <!-- Paramètre envoyé pour supprimer la salle -->
                                    <form method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette salle ?')">
                                        <input name="idSalle" type="hidden" value="<?php echo $salle['id_salle']; ?>">
                                        <input name="supprimer" type="hidden" value="true">
                                        <button type="submit" class="btn-suppr rounded-2"><span class="fa-solid fa-trash"></span></button>
                                    </form>

                                    <!-- Paramètre envoyé pour modifier la salle -->
                                    <form  method="post" action="modificationSalle.php">
                                        <input name="idSalle" type="hidden" value="<?php echo $salle['id_salle']; ?>">
                                        <button type="submit" class="btn-modifier rounded-2"><span class="fa-regular fa-pen-to-square"></span></button>
                                    </form>
                                    <?php
                                    echo '</div>';
                                    echo "</td>";
                                }
                                echo "</tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Bouton Retour en haut -->
                <div class="row mt-3 mb-5">
                    <button id="scrollToTopBtn" class="boutton btn-bleu rounded-2" title="Retour en haut de la page">
                        <span class="fa-solid fa-arrow-up"></span>
                    </button>
                </div>
            </div>
            <!-- Footer de la page -->
            <?php include '../include/footer.php'; ?>
        </div>
        <!-- JavaScript pour les filtres -->
        <script defer src="../fonction/filtreSalle.js"></script>
    </body>
</html>
