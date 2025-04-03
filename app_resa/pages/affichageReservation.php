<?php

use function fonction\affichageReservation;
use function fonction\affichageTypeReservation;
use function fonction\listeActivites;
use function fonction\listeDate;
use function fonction\listeHeureDebut;
use function fonction\listeHeureFin;
use function fonction\listeSalles;
use function fonction\supprimerResa;
use function fonction\verif_session;
    $startTime = microtime(true); // temps de chargement de la page
    require '../fonction/connexion.php';
    require '../fonction/reservation.php';

    session_start();
    verif_session();

    $messageSucces = $messageErreur ='';
    $tabSalle         = listeSalles();
    $tabActivite      = listeActivites();
    $tabDate          = listeDate();
    $heureDebut       = listeHeureDebut();
    $heureFin         = listeHeureFin();

    if (isset($_POST['id_reservation']) && $_POST['supprimer'] == "true") {
        $id_reservation = $_POST['id_reservation'];

        // Appeler la fonction de suppression
        try {
            supprimerResa($id_reservation);
            $messageSucces = 'Reservation supprimée avec succès !';
        } catch (Exception $e) {
            $messageErreur = "La réservation n'a pas pu être supprimée, 
                                    un problème est survenu.";
        }
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
                <!-- Titre de la page -->
                <div class="padding-header row">
                    <div class="col-12">
                        <h1 class="text-center">Liste des Réservations</h1>
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

                <!-- 1 ère ligne avec le bouton "Réserver" -->
                <div class="row mb-3">
                    <div class="col-12 text-center text-md-end">
                        <button class="btn-bleu rounded-2" onclick="window.location.href='creationReservation.php';">
                            <i class="fa fa-calendar"></i>
                            Réserver
                        </button>
                    </div>
                </div>

                <!-- Ligne des filtres -->
                <div class="row g-1 justify-content-start">
                    <!-- Nom des employés -->
                    <div class="col-12 col-md-2 mb-1 col-reduit-reservation">
                        <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom de l'employé">
                    </div>
                    <!-- Nom des salles -->
                    <div class="col-12 col-md-2 mb-1 col-grand-reservation">
                        <select class="form-select select-nom" id="salles">
                            <option value=""  selected>Salle</option>
                            <?php
                            foreach ($tabSalle as $salle) {
                                echo '<option value="' . $salle . '">' . $salle . "</option>";
                            } // On boucle sur les noms des salles contenues dans le tableau
                            ?>
                        </select>
                    </div>
                    <!-- Nom des activités -->
                    <div class="col-12 col-md-1 mb-1 col-reduit-reservation">
                        <select class="form-select select-nom" id="activites">
                            <option value=""  selected>Activités</option>
                            <?php
                            foreach ($tabActivite as $activite){ // On boucle sur les différentes activités contenues dans le tableau
                                echo '<option value="' . $activite . '">' . $activite . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!-- Date début -->
                    <div class=" col-grand-reservation col-12 col-md-1 mb-1">
                        <select class="form-select select-nom" id="date_debut">
                            <option value=""  selected>Date Début</option>
                            <?php
                            foreach ($tabDate as $date){ // On boucle sur les différentes dates contenues dans le tableau
                                echo '<option value="' . $date . '">' . date('d/m/Y', strtotime($date)) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!-- Date fin -->
                    <div class="col-grand-reservation col-12 col-md-1 mb-1">
                        <select class="form-select select-nom" id="date_fin">
                            <option value=""  selected>Date Fin</option>
                            <?php
                            foreach ($tabDate as $date){ // On boucle sur les différentes dates contenues dans le tableau
                                echo '<option value="' . $date . '">' . date('d/m/Y', strtotime($date)) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!-- Heure début -->
                    <div class="col-grand-reservation col-12 col-md-1 mb-1">
                        <select class="form-select select-nom" id="heure_debut">
                            <option value=""  selected>Heure début</option>
                            <?php
                            foreach ($heureDebut as $heure){ // On boucle sur les différentes dates contenues dans le tableau
                                echo '<option value="' . $heure . '">' . $heure . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!-- Heure fin -->
                    <div class="col-grand-reservation col-12 col-md-1 mb-1">
                        <select class="form-select select-nom" id="heure_fin">
                            <option value="" selected>Heure fin</option>
                            <?php
                            foreach ($heureFin as $heure){ // On boucle sur les différentes dates contenues dans le tableau
                                echo '<option value="' . $heure . '">' . $heure . "</option>";
                            }
                            ?>
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

                    <!-- Compteur -->
                    <?php
                        try {
                            $listeReservation = affichageReservation();
                        } catch (PDOException $e) {
                            echo '<div class="text-center text-danger fw-bold">Impossible de charger les réservations en raison d’un problème technique.</div>';
                        }

                        $nombreReservations = count($listeReservation ?? []); // nombre de réservations
                    ?>
                    <div class="col-12 text-center mb-3">
                        <p class="fw-bold compteur-reservation">
                            Nombre de réservation trouvée(s) : <?= $nombreReservations ?>
                        </p>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped text-center">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Salle</th>
                                    <th>Employe</th>
                                    <th>Activite</th>
                                    <th>Date</th>
                                    <th>Heure debut</th>
                                    <th>Heure fin</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                foreach ($listeReservation as $ligne) {
                                    echo '<tr class="tab-trier">';
                                    echo '<td class="tab-trier">' . $ligne['id_reservation'] . '</td>';
                                    echo '<td class="tab-trier">' . $ligne['nom_salle'] . '</td>';
                                    echo '<td class="tab-trier">' . $ligne['nom_employe'] . ' ' . $ligne['prenom_employe'] . '</td>';
                                    echo '<td class="tab-trier">';
                                    echo $ligne['nom_activite'];
                                    echo '<span class="fa-solid fa-circle-info info-icon">';
                                    echo '<span class="tooltip">'; //bulle quand on passe le curseur sur le logo i
                                    echo '<table class="table">';
                                    try {
                                        //récupération des informations supplémentaires sur les réservations
                                        $listeType = affichageTypeReservation($ligne['id_reservation']);
                                    } catch (PDOException $e) {
                                        echo '<tr><td colspan="5" class="text-center text-danger fw-bold">Impossible de charger les informations sur le type de reservation</td></tr>';
                                    }

                                    if (!empty($listeType)) {
                                        // Filtrer les valeurs non vides
                                        $listeSansVide = array_filter($listeType, function ($valeur) {
                                            return !empty($valeur);
                                        });
                                        if (!empty($listeSansVide)) {
                                            echo "<tr>";
                                            foreach ($listeSansVide as $key => $valeur) {
                                                echo "<td>" . $valeur . "</td>";
                                            }
                                            echo "</tr>";
                                        } else {
                                            echo "<tr><td colspan='1'>Aucune donnée trouvée pour cette réservation</td></tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='1'>Aucune donnée trouvée pour cette réservation</td></tr>";
                                    }
                                    echo ' </table>';
                                    echo '</span>';
                                    echo '</span>';
                                    echo '</td>';
                                    echo '<td class="tab-trier">' . date('d/m/Y', strtotime($ligne['date'])) . '</td>';
                                    echo '<td class="tab-trier">' . $ligne['heure_debut'] . '</td>';
                                    echo '<td class="tab-trier">' . $ligne['heure_fin'] . '</td>';

                                    echo '<td class="btn-colonne">';
                                    echo '<div class="d-flex justify-content-center gap-1">';
                                    if ($_SESSION['typeUtilisateur'] === 1) {

                                        echo '<form method="POST" onsubmit="return confirm(\'Êtes-vous sûr de vouloir supprimer cette réservation ?\')">';
                                        echo '    <input type="hidden" name="id_reservation" value="' . htmlspecialchars($ligne['id_reservation']) . '">';
                                        echo '    <input type="hidden" name="supprimer" value="true">';
                                        echo '    <button type="submit" class="btn-suppr rounded-2">';
                                        echo '        <span class="fa-solid fa-trash"></span>';
                                        echo '    </button>';
                                        echo '</form>';

                                        echo '<!-- Paramètre envoyé pour modifier la salle -->
                                               <form  method="post" action="modificationReservation.php">';
                                        //Vérifier que cette ligne prend bien l'id de la reservation
                                        echo '       <input name="idReservation" type="hidden" value="' . htmlentities($ligne['id_reservation'], ENT_QUOTES) . '">
                                                     <button type="submit" class="btn-modifier rounded-2"><span class="fa-regular fa-pen-to-square"></span></button>
                                               </form>
                                               ';
                                    }
                                    echo '</div>';
                                    echo '</td>';
                                    echo '</tr>';
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
        <script defer src="../fonction/filtreReservation.js"></script>
    </body>
</html>