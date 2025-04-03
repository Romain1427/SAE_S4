
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>StatiSalle - Modifications Employés</title>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <!-- CSS -->
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/header.css">
        <link rel="stylesheet" href="css/footer.css">
        <!-- Icon du site -->
        <link rel="icon" href=" img/logo.ico">
    </head>
    <body>
        <div class="container-fluid">
            <!-- Header de la page -->
            <?php include 'include/headerYasmf.php'; ?>

            <div class="full-screen">
                <!-- Titre de la page -->
                <div class="padding-header row">
                    <div class="text-center">
                        <br>
                        <h1>Modifications d'un employé</h1>
                    </div>
                </div>

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

                <!-- Contenu de la page -->
                <div class="container">
                    <form method="POST" action="index.php?controller=employe&action=modifier">
                        <!-- Nom et prénom -->
                        <div class="row">
                            <div class="col-md-3 offset-md-3">
                                <label for="nom"></label><input class="form-text form-control" type="text" placeholder="Nom" id="nom" name="nom" value="<?= htmlentities($tabAttributEmploye['nom']) ?>" required>
                                <?php if (isset($erreurs['nom'])): ?>
                                    <small class="text-danger"><?= $erreurs['nom'] ?></small>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-3">
                                <label for="prenom"></label><input class="form-text form-control" type="text" placeholder="Prénom" id="prenom" name="prenom" value="<?= htmlentities($tabAttributEmploye['prenom']) ?>" required>
                                <?php if (isset($erreurs['prenom'])): ?>
                                    <small class="text-danger"><?= $erreurs['prenom'] ?></small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Numéro de tel -->
                        <div class="row">
                            <div class="col-md-6 offset-md-3">
                                <label for="numTel"></label><input class="form-text form-control" type="text" placeholder="Numéro de téléphone" id="numTel" name="numTel" value="<?= htmlentities($tabAttributEmploye['telephone'], ENT_QUOTES) ?>" required>
                                <?php if (isset($erreurs['numTel'])): ?>
                                    <small class="text-danger"><?= $erreurs['numTel'] ?></small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- login -->
                        <div class="row">
                            <div class="col-md-6 offset-md-3">
                                <label for="login"></label><input class="form-text form-control" type="text" placeholder="Compte utilisateur" id="login" name="login" value="<?= htmlentities($tabAttributLogin['login'], ENT_QUOTES) ?>" required>
                                <?php if (isset($erreurs['login'])): ?>
                                    <small class="text-danger"><?= $erreurs['login'] ?></small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Mdp -->
                        <div class="row">
                            <div class="col-md-6 offset-md-3">
                                <label for="mdp"></label><input class="form-text form-control" type="password" placeholder="Mot de passe" id="mdp" name="mdp" value="">
                                <?php if (isset($erreurs['mdp'])): ?>
                                    <small class="text-danger"><?= $erreurs['mdp'] ?></small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Confirmation mot de passe -->
                        <div class="row">
                            <div class="col-md-6 offset-md-3">
                                <label for="cmdp"></label><input class="form-text form-control" type="password" placeholder="Confirmez le mot de passe" id="cmdp" name="cmdp" value="">
                                <?php if (isset($erreurs['cmdp'])): ?>
                                    <small class="text-danger"><?= $erreurs['cmdp'] ?></small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Case à cocher pour les permissions administratives -->
                        <div class="row mt-3">
                            <div class="col-md-6 offset-md-3">
                                <label for="admin">Permissions administratives</label>
                                <?php $checked = ($tabAttributLogin['id_type'] == 1) ? 'checked' : ''; ?>
                                <input type="checkbox" id="admin" name="admin" value="<?= $tabAttributLogin['id_type'] ?>" <?= $checked?> >
                                <small class="text-muted">Cochez cette case si l'employé a des permissions administratives.</small>
                            </div>
                        </div>

                        <!-- Boutton envoyer le formulaire -->
                        <div class="row mt-4">
                            <div class="col-md-6 offset-md-3">
                                <input type="hidden" id="modifier" name="modifier" value="true">
                                <input type="hidden" id="id_employe" name="id_employe" value="<?= $id ?>">
                                <button type="submit" class="btn-bleu rounded w-100">Modifier le compte</button>
                            </div>
                        </div>

                        <!-- Boutton retour -->
                        <br class="d-md-block d-none">
                        <div class="row mt-4 offset-md-2">
                            <div class="col-12 col-md-auto">
                                <button class="btn-suppr rounded-2 w-100 w-md-auto" type="button"
                                        onclick="window.location.href='index.php?controller=employe'">
                                    Retour
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Footer de la page -->
            <?php include 'include/footer.php'; ?>
        </div>
    </body>
</html>
