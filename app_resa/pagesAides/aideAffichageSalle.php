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
                            Pour commencer il y a le tableau central qui contient la liste de toutes les salles de l’entreprise avec deux boutons, un pour modifier et un pour supprimer la salle. Avant d'effectuer la suppression une confirmation vous sera demandé pour faire en sorte que vous ne supprimiez pas une salle par inadvertence.
                        </p>
                        <table class="table table-striped">
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Capacité</th>
                                <th>Vidéo Projecteur</th>
                                <th>Nombre Ordinateurs</th>
                                <th>Type</th>
                                <th>Logiciels</th>
                                <th>Imprimante</th>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>A6</td>
                                <td>15</td>
                                <td>oui</td>
                                <td>4</td>
                                <td>Pc portable</td>
                                <td>Bureautique</td>
                                <td>oui</td>
                            </tr>
                        </table>
                        <p>
                            Ensuite, nous avons des filtres qui permettent de rechercher les salles en fonction de certain critère comme le nom de la salle. Si aucune salle n'est trouvé avec les filtres appliqués alors un message d'erreur s'affiche disant : "Aucune salle trouvée".
                        </p>
                        <!-- Nom -->
                        <div class="col-12 col-md-2 mb-1 col-reduit-salle ">
                            <select class="form-select select-nom" id="nom">
                                <option value=""  selected>Nom</option>
                            </select>
                        </div>
                        <p>
                            La capacité de la salle, c'est-à-dire le nombre de personnes que cela peut contenir.
                        </p>
                        <!-- Capacité -->
                        <div class="col-3">
                            <select class="form-select select-nom" id="capacite">
                                <option value="" selected>Capacité</option>
                            </select>
                        </div>
                        <p>
                            On peut également afficher toutes les salles contiennent un vidéo projecteur.
                        </p>
                        <!-- Vidéo projecteur -->
                        <div class="col-3">
                            <select class="form-select select-nom" id="videoproj">
                                <option value="" selected>Vidéo projecteur</option>
                                <option value ="oui">Oui</option>
                                <option value ="non">Non</option>
                            </select>
                        </div>
                        <p>
                            On peut afficher toutes les salles qui contiennent un écran XXL.
                        </p>
                        <!-- Grand écran -->
                        <div class="col-12 col-md-1 mb-1 col-grand-salle ">
                            <select class="form-select select-nom" id="grandEcran">
                                <option value="" selected>Écran XXL</option>
                                <option value ="oui">Oui</option>
                                <option value ="non">Non</option>
                            </select>
                        </div>
                        <p>
                            On peut trier les salles par le nombre d’ordinateurs qu’elles contiennent.
                        </p>
                        <div class="col-12 col-md-1 mb-1 col-grand-salle ">
                            <select class="form-select select-nom" id="nbrOrdi">
                                <option value="" selected>Ordinateur</option>
                            </select>
                        </div>
                        <p>
                            On peut trier par les logiciels disponibles dans les salles.
                        </p>
                        <div class="col-12 col-md-3 mb-1">
                            <select class="form-select select-nom" id="logiciel">
                                <option value="" selected>Logiciel</option>
                            </select>
                        </div>
                        <p>
                            Pour finir, nous pouvons sélectionner les salles qui possèdent une imprimante.
                        </p>
                        <!-- Imprimante -->
                        <div class="col-12 col-md-1 mb-1 col-grand-salle ">
                            <select class="form-select select-nom" id="imprimante">
                                <option value="" selected>Imprimante</option>
                                <option value ="oui">Oui</option>
                                <option value ="non">Non</option>
                            </select>
                        </div>
                        <p>
                            Pour rendre l’utilisation des filtres plus simples, nous pouvons réinitialiser tous les filtres, c'est-à-dire les remettre à leur valeur de départ.
                        </p>
                        <!-- Bouton de réinitialisation des filtres -->
                        <div class="col-4">
                            <button class="btn-reset rounded-1 w-100" type="submit">Réinitialiser filtres</button>
                        </div>
                        <br>
                        <p>
                            Il y a une flêche bleue constamment affichée en bas à gauche de la page qui sert à remonter tout en haut de la page.
                        </p>
                        <button class="boutton btn-bleu rounded-2" title="Retour en haut de la page">
                            <span class="fa-solid fa-arrow-up"></span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Footer de la page -->
            <?php include '../include/footer.php'; ?>
        </div>
    </body>
</html>
