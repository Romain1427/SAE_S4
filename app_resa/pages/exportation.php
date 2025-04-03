<?php

use function fonction\genererCSV;
use function fonction\genererCSVString;
use function fonction\genererZip;
use function fonction\recupererDonnees;
use function fonction\verif_session;
    $startTime = microtime(true); // temps de chargement de la page
    require '../fonction/exportation.php';
    require '../fonction/connexion.php';
    session_start();
    verif_session();

    // Génération du fichier CSV lorsque le bouton est cliqué
    if (isset($_GET['action'])) {
        $date = date('d_m_Y H_i'); // Format jour_mois_année

        switch ($_GET['action']) {
            case 'reservations':
                genererCSV(
                    "reservations $date.csv",
                    ['Ident','salle','employe','activite','date','heuredebut','heurefin','','','','',''],
                    recupererDonnees('reservation')
                );
                break;

            case 'salles':
                genererCSV(
                    "salles $date.csv",
                    ['Ident','Nom','Capacite','videoproj','ecranXXL','ordinateur','type','logiciels','imprimante'],
                    recupererDonnees('salle')
                );
                break;

            case 'employes':
                genererCSV(
                    "employes $date.csv",
                    ['Ident','Nom','Prenom','Telephone'],
                    recupererDonnees('employe')
                );
                break;

            case 'activites':
                genererCSV(
                    "activites $date.csv",
                    ['Ident','Activité'],
                    recupererDonnees('activite')
                );
                break;

            case 'tous':
                try {
                    $fichiers = [];
                    $fichiers["activites $date.csv"] = genererCSVString(
                        ['Ident','Activité'],
                        recupererDonnees('activite')
                    );
                    $fichiers["employes $date.csv"] = genererCSVString(
                        ['Ident','Nom','Prenom','Telephone'],
                        recupererDonnees('employe')
                    );
                    $fichiers["salles $date.csv"] = genererCSVString(
                        ['Ident','Nom','Capacite','videoproj','ecranXXL','ordinateur','type','logiciels','imprimante'],
                        recupererDonnees('salle')
                    );
                    $fichiers["reservations $date.csv"] = genererCSVString(
                        ['Ident','salle','employe','activite','date','heuredebut','heurefin','','','','',''],
                        recupererDonnees('reservation')
                    );

                    // Créer et envoyer le fichier ZIP
                    genererZip("fichiers $date.zip", $fichiers);
                    $_SESSION['messageSucces'] = "Les fichiers ont été générés et compressés avec succès.";
                } catch (Exception $e) {
                    $_SESSION['erreurs'][] = "Erreur lors de la génération des fichiers ZIP: " . $e->getMessage();
                }
                break;
        }
    }
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>StatiSalle - Téléchargement des données</title>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <!-- CSS -->
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="../css/header.css">
        <link rel="stylesheet" href="../css/footer.css">
        <!-- Icon du site -->
        <link rel="icon" href="../img/logo.ico">
    </head>
    <body>
        <div class="container-fluid">
            <!-- Header de la page -->
            <?php include '../include/header.php'; ?>

            <div class="full-screen padding-header mt-2">
                <!-- Titre de la page -->
                <div class="row text-center mb-5">
                    <h1>Téléchargement des données</h1>
                </div>

                <!-- Contenu -->
                <div class="row d-flex justify-content-center align-items-start w-100 acc-row mt-5">
                    <div class="acc-container p-4 w-50">
                        <p>
                            Vous pouvez télécharger les données actuelles au format CSV pour les différentes catégories
                            ci-dessous. Chaque fichier contient les informations à jour de la base de données.
                        </p>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 mb-3">
                                <button type="button" class="btn-bleu rounded w-100"
                                        onclick="window.location.href='?action=reservations'">
                                    Télécharger le fichier Réservations <i class="fa-solid fa-download"></i>
                                </button>
                            </div>
                            <div class="col-md-6 col-sm-12 mb-3">
                                <button type="button" class="btn-bleu rounded w-100"
                                        onclick="window.location.href='?action=salles'">
                                    Télécharger le fichier Salles <i class="fa-solid fa-download"></i>
                                </button>
                            </div>
                            <div class="col-md-6 col-sm-12 mb-3">
                                <button type="button" class="btn-bleu rounded w-100"
                                        onclick="window.location.href='?action=employes'">
                                    Télécharger le fichier Employés <i class="fa-solid fa-download"></i>
                                </button>
                            </div>
                            <div class="col-md-6 col-sm-12 mb-3">
                                <button type="button" class="btn-bleu rounded w-100"
                                        onclick="window.location.href='?action=activites'">
                                    Télécharger le fichier Activités <i class="fa-solid fa-download"></i>
                                </button>
                            </div>
                            <div class="col-12 mb-3">
                                <button type="button" class="btn-bleu rounded w-100"
                                        onclick="window.location.href='?action=tous'">
                                    Télécharger tous les fichiers <i class="fa-solid fa-download"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer de la page -->
            <?php include '../include/footer.php'; ?>
        </div>
    </body>
</html>