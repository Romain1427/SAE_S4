<?php
// Déterminer si la page actuelle commence par "aide"
$_SESSION['nom_page'] = basename($_SERVER['PHP_SELF'], '.php');
$estPageAide = isset($_SESSION['nom_page']) && str_starts_with($_SESSION['nom_page'], 'aide');
$baseChemin = $estPageAide ? 'pages/' : ''; // Chemin de base en fonction du type de page
?>

<header class="header row d-flex align-items-center fixed-top px-3">
    <!-- Conteneur pour organiser le contenu à gauche et à droite -->
    <div class="d-flex w-100 justify-content-between align-items-center">
        <!-- Partie gauche -->
        <div class="d-flex align-items-center gap-3">
            <!-- Logo cliquable pour revenir à l'accueil -->
            <a href="<?php echo $baseChemin; ?>pages/accueil.php" title="Page d'accueil" class="d-flex align-items-center d-none d-sm-block">
                <img src="<?php echo $baseChemin; ?>img/LogoStatisalle.jpg" alt="Logo de StatiSalle" class="img-fluid">
            </a>

            <!-- Boutons principaux -->
            <div class="menu-pages d-flex flex-row gap-3 align-items-center">
                <!-- Bouton Accueil (visible sur écrans md et plus) -->
                <button class="rounded bouton-header d-none d-md-block" type="button"
                        onclick="window.location.href='<?php echo $baseChemin; ?>pages/accueil.php';">
                    Accueil
                </button>

                <!-- Menu déroulant pour le choix des pages -->
                <div class="dropdown d-none d-md-block">
                    <button class="rounded dropdown-toggle bouton-header custom-dropdown-toggle"
                            type="button" id="menuDeroulantPage" data-bs-toggle="dropdown" aria-expanded="false">
                        Pages
                        <i class="fas fa-angle-down"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="menuDeroulantPage">
                        <!-- Accueil visible seulement sur écrans sm et moins -->
                        <li><a class="dropdown-item d-md-none" href="<?php echo $baseChemin; ?>pages/accueil.php">Accueil</a></li>
                        <li><a class="dropdown-item" href="<?php echo $baseChemin; ?>pages/affichageReservation.php">Réservations</a></li>
                        <li><a class="dropdown-item" href="<?php echo $baseChemin; ?>pages/affichageSalle.php">Salles</a></li>
                        <li><a class="dropdown-item" href="<?php echo $baseChemin; ?>pages/affichageReservationUtilisateur.php">Mes réservations</a></li>
                    </ul>
                </div>

                <!-- Boutons "Exporter" et "Utilisateurs" (visibles sur md et plus) -->
                <button class="rounded bouton-header d-none d-md-block" type="button" onclick="window.location.href='<?php echo $baseChemin; ?>pages/exportation.php';">
                    Exporter
                </button>
                <?php if (isset($_SESSION['typeUtilisateur']) && $_SESSION['typeUtilisateur'] === 1) { ?>
                    <button class="rounded bouton-header d-none d-md-block" type="button" onclick="window.location.href='<?php echo $baseChemin; ?>index.php?controller=employe';">
                        Utilisateurs
                    </button>
                <?php } ?>

                <!-- Menu déroulant pour "Exporter" et "Utilisateurs" (visible sur sm et moins) -->
                <div class="dropdown d-md-none">
                    <button class="rounded dropdown-toggle bouton-header custom-dropdown-toggle"
                            type="button" id="menuDeroulantOption" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-compass"></i>
                        Naviguer
                        <i class="fas fa-angle-down"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="menuDeroulantOption">
                        <li><a class="dropdown-item" href="<?php echo $baseChemin; ?>accueil.php">Accueil</a></li>
                        <li><a class="dropdown-item" href="<?php echo $baseChemin; ?>affichageReservation.php">Réservations</a></li>
                        <li><a class="dropdown-item" href="<?php echo $baseChemin; ?>affichageSalle.php">Salles</a></li>
                        <li><a class="dropdown-item" href="<?php echo $baseChemin; ?>affichageReservationUtilisateur.php">Mes réservations</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Partie droite (Bouton Nom de l'Employé) -->
        <div class="d-flex justify-content-end">
            <div class="dropdown">
                <button class="rounded dropdown-toggle bouton-header-employer custom-dropdown-toggle"
                        type="button"
                        id="menuDeroulantEmployer"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                    <?php echo $_SESSION['login']; ?>
                    <i class="fas fa-angle-down"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="menuDeroulantEmployer">
                    <li><a class="dropdown-item" href="<?php echo $baseChemin; ?>fonction/deconnexion.php">Déconnexion</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>
