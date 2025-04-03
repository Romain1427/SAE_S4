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
        <title>StatiSalle - Aides Employés</title>
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
                            Tout d’abord pour l’affichage des employés, nous avons le nom, prénom, identifiant et numéro de téléphone de l’employé. Mais nous avons aussi un bouton pour la modification de l’employé qui redirige sur une nouvelle page contenant un formulaire pour la modification. Pour plus d’informations, vous pouvez aller voir la page d’aide de la modification.
                            Il y a également un bouton pour supprimer l’utilisateur. Cependant, si l'employé que vous avez essayé de supprimer a effectué une réservation alors la suppression et impossible, il faudra supprimer la réservation en premier. Avant d'effectuer la suppression une confirmation vous sera demandé pour faire en sorte que vous ne supprimiez pas un employé par inadvertence. Nous avons aussi mis un petit bouclier pour montrer que l’utilisateur est un administrateur.
                        </p>
                        <table class="table table-striped">
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Identifiant compte</th>
                                <th>Numéro de téléphone</th>
                                <th></th>
                            </tr>
                            <tr>
                                <td>User nom</td>
                                <td>User prénom</td>
                                <td>user</td>
                                <td>0000</td>
                                <td>
                                    <button type="submit" class="btn-suppr rounded-2">
                                        <span class="fa-solid fa-trash"></span>
                                    </button>
                                    <button type="submit" class="btn-modifier rounded-2">
                                        <span class="fa-regular fa-pen-to-square"></span>
                                    </button>
                                </td>
                            </tr>
                        </table>
                        <p>
                            Ensuite, il y a des filtres qui permettent de faciliter la recherche d’un employé avec le nom, le prénom le compte employé qui signifie l’identifiant et le numéro de téléphone. Si aucun employé ne correspond au filtre que vous appliquez alors un message s'affiche disant : "Aucun compte trouvé".
                        </p>
                        <!-- Champs de filtres -->
                        <div class="row g-1 justify-content-start">
                            <!-- Nom employé -->
                            <div class="col-12 col-md-2 mb-1">
                                <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom">
                            </div>
                            <!-- Prénom employé -->
                            <div class="col-12 col-md-2 mb-1">
                                <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Prénom">
                            </div>
                            <!-- ID compte employé -->
                            <div class="col-4">
                                <input type="text" class="form-control" id="compte" name="compte" placeholder="Compte employé">
                            </div>
                            <!-- Numéro de téléphone employé -->
                            <div class="col-4">
                                <input type="text" class="form-control" id="telephone" name="telephone" placeholder="Numéro de téléphone">
                            </div>
                        </div>
                        <p>
                            Pour supprimer le contenu de tous les filtres, il vous suffit de cliquer sur le bouton réinitialiser filtre.
                        </p>
                        <!-- Bouton de soumission -->
                        <div class="col-4">
                            <button class="btn-reset rounded-1 w-100" type="submit">Réinitialiser filtres</button>
                        </div>
                        <p>
                            Nous avons aussi un bouton ajouter qui va vous rediriger vers une nouvelle page qui contient un formulaire pour créer un nouvel utilisateur dans la base de données du site.
                            Pour plus d'informations, allez sur la page d’aide de la page de création d’un employé.
                        </p>
                        <div class="col-12">
                            <button class="btn-bleu rounded-2" type="button">
                                <span class="fa-plus"></span> Ajouter
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer de la page -->
            <?php include '../include/footer.php'; ?>
        </div>
    </body>
</html>
