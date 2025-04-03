<?php
namespace controllers;

use PDO;
use services\ReservationsService;
use yasmf\View;

/**
 * Contrôleur par défaut gérant la page d'accueil des utilisateurs
 * connectés.
 * 
 * @autor Groupe S3
 */
class HomeController {

    /**
     * Action par défaut pour afficher la page.
     *
     * @param PDO $pdo l'objet PDO lié à la base de données
     * @return View la vue par défaut avec la page d'accueil
     */
    public function index(PDO $pdo = null): View {
        /* Affichage d'erreur si base de données innaccessible */
        if ($pdo == null) {
            $view = new View("views/erreur");
            $view->setVar("codeErreur", "503");
            $view->setVar("nomErreur", "Base de données inaccessible");
            return $view;  
        }

        //session_start(); // TODO Si besoin on implémente cette partie 
        /* Redirection vers la connexion si utilisateur non connecté */
        // if (!AuthentificationController::isUserLoggedIn()) {
        //     header("Location: index.php?controller=Authentification");
        //     exit();
        // }

        $vue = new View("views/home");
        return $vue;
    }
}