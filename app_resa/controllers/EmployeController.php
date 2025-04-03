<?php 
namespace controllers;

use PDO;
use PDOException;
use Exception;
require 'fonction/connexion.php';
use function fonction\verif_session;
use services\EmployeService;
use yasmf\HttpHelper;
use yasmf\View;


/**
 * Met en relation les différentes vues associées à l'exploitation 
 * d'un employé  
 */
class EmployeController {

    private ?EmployeService $employeService;

    /**
     * Créé un nouveau contrôleur gérant les comptes utilisateurs.
     * @param EmployeService $employeService le modèle associé au contrôleur 
     */
    public function __construct(EmployeService $employeService) {
        $this->employeService = $employeService;
    }

    public function index(PDO $pdo=null) {
        /* Affichage d'erreur si base de données inaccessible */
        $startTime = microtime(true); // temps de chargement de la page

        if ($pdo == null) {
            $view = new View("views/erreur");
            $view->setVar("startTime", $startTime);
            $view->setVar("codeErreur", "503");
            $view->setVar("nomErreur", "Base de données inaccessible");
            return $view;
        }

        $vue = new View("pages/affichageEmploye");

        session_start();
        verif_session();

        $messageSucces = $messageErreur ='';

        // Définir la limite par défaut
        $limite = HttpHelper::getParam("limite") ?? 5; // Valeur par défaut : 5

        if (HttpHelper::getParam("id_employe") != null 
            && HttpHelper::getParam("supprimer") == "true") {
            
            $id_employe = HttpHelper::getParam('id_employe');

            // Appeler la fonction de suppression
            try {
                $this->employeService->supprimerEmploye($pdo, $id_employe); 
                $messageSucces = 'Employé supprimé avec succès !';
            } catch (Exception $e) {
                if ($e->getCode() == '23000') { // Code SQLSTATE pour contrainte de clé étrangère
                    $messageErreur = '<span class="fa-solid fa-arrow-right erreur"></span>
                                            <span class="erreur">Impossible de supprimer cet employé : 
                                            veuillez supprimer la réservation qui lui est attribuée.</span>
                                            <a href="pages/affichageReservation.php" title="Page réservation">Cliquez ici</a>';
                } else {
                    $messageErreur = 'Erreur lors de la suppression de l\'employé : ' . $e->getMessage();
                }
            }
        }
        try {
            $listeEmploye = $this->employeService->renvoyerEmployes($pdo);
        } catch (PDOException $pbPdo) {
            $listeEmploye = null;
        }
        // Compteur
        try {
            $nombreTotalEmployes = $this->employeService->compterEmployes($pdo);

        } catch (PDOException $pbPdo) {
            $nombreTotalEmployes = -1;
        }
        $vue->setVar("startTime", $startTime);
        $vue->setVar("listeEmploye", $listeEmploye);
        $vue->setVar("nombreTotalEmployes", $nombreTotalEmployes);
        $vue->setVar("messageErreur", $messageErreur);
        $vue->setVar("messageSucces", $messageSucces);

        return $vue;
    }

    public function creer(PDO $pdo=null) {
        /* Affichage d'erreur si base de données inaccessible */
        $startTime = microtime(true); // temps de chargement de la page

        if ($pdo == null) {
            $view = new View("views/erreur");
            $view->setVar("startTime", $startTime);
            $view->setVar("codeErreur", "503");
            $view->setVar("nomErreur", "Base de données inaccessible");
            return $view;
        }
        $vue = new View("pages/creationEmploye");

        session_start();
        verif_session();
    
        // Initialisation des variables et messages d'erreur
        $nom = $prenom = $numTel = $login = $mdp = $cmdp = $messageSucces = "";
        $erreurs = [];
    
        // Vérification si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupération des données du formulaire
            $nom    = htmlspecialchars(HttpHelper::getParam("nom"))?? '';
            $prenom = htmlspecialchars(HttpHelper::getParam("prenom")) ?? '';
            $numTel = htmlspecialchars(HttpHelper::getParam("numTel")) ?? '';
            $login  = htmlspecialchars(HttpHelper::getParam("login")) ?? '';
            $mdp    = htmlspecialchars(HttpHelper::getParam("mdp")) ?? '';
            $cmdp   = htmlspecialchars(HttpHelper::getParam("cmdp")) ?? '';
            $admin  = HttpHelper::getParam("admin") != null ? 1 : 2;
    
            $this->employeService->checkErreurs($pdo, $nom, $prenom, $numTel, 
                                                $login, $mdp, $cmdp, $erreurs);
    
            // Si aucune erreur, on appelle de la fonction pour ajouter l'employé dans la base de données
            if (empty($erreurs)) {
                try {
                    $this->employeService->ajouterEmploye($pdo, $nom, $prenom, $login, $numTel, $mdp, $admin);
                    $messageSucces = "Employé ajouté avec succès !";
                } catch (Exception $e) {
                    $erreurs[] = "Impossible d'ajouter l'employé a la base de donnée : " . $e->getMessage();
                }
            }
        }
        $vue->setVar("startTime", $startTime);
        $vue->setVar("messageSucces", $messageSucces);
        $vue->setVar("nom", $nom);
        $vue->setVar("prenom", $prenom);
        $vue->setVar("numTel", $numTel);
        $vue->setVar("login", $login);
        $vue->setVar("mdp", $mdp);
        $vue->setVar("cmdp", $cmdp);
        $vue->setVar("erreurs", $erreurs);

        return $vue;
    }

    public function modifier(PDO $pdo=null) {
        /* Affichage d'erreur si base de données inaccessible */
        $startTime = microtime(true); // temps de chargement de la page

        if ($pdo == null) {
            $view = new View("views/erreur");
            $view->setVar("startTime", $startTime);
            $view->setVar("codeErreur", "503");
            $view->setVar("nomErreur", "Base de données inaccessible");
            return $view;
        }

        $vue = new View("pages/modificationEmploye");
    
        session_start();
        verif_session();
    
        // Initialisation des variables et messages d'erreur
        $nom = $prenom = $numTel = $login = $mdp = $cmdp = $messageSucces = "";
        $erreurs = [];
    
        //vérifie si la modification est souhaitée
        $modifie = HttpHelper::getParam("modifie") ?? false;
        $id      = HttpHelper::getParam("id_employe") ?? null;
    
        // Récupération des attributs de l'employé en fonction de l'id de l'employé
        $tabAttributEmploye = $this->employeService->recupAttributEmploye($pdo, $id);
    
        // Récupération des attributs du login en fonction de l'id de l'employé
        $tabAttributLogin = $this->employeService->recupAttributLogin($pdo, $id);
    
        $modifier = HttpHelper::getParam("modifier") ?: "";

        if ($modifier  != "") { // Si la valeur 'modifier' est définie
            // Récupération de la valeur de la case admin
            $admin = HttpHelper::getParam("admin") ?? false; // Par défaut, pas d'admin
            $id_type = $admin ? 1 : 2;  // Si admin est coché, id_type = 1 (admin), sinon 2 (employé).
        
            $nom    = htmlspecialchars(HttpHelper::getParam("nom"))    ?: '';
            $prenom = htmlspecialchars(HttpHelper::getParam("prenom")) ?: '';
            $numTel = htmlspecialchars(HttpHelper::getParam("numTel")) ?: '';
            $login  = htmlspecialchars(HttpHelper::getParam("login"))  ?: '';
            $mdp    = HttpHelper::getParam("mdp") !== '' ? htmlspecialchars(HttpHelper::getParam("mdp")) : null;
            $cmdp    = HttpHelper::getParam("cmdp") !== '' ? htmlspecialchars(HttpHelper::getParam("cmdp")) : null;
        
            // Vérification des champs requis
            if (!isset($nom) || $nom === '')    $erreurs['nom'] = "Le nom est requis.";
            if (!isset($prenom) || $prenom === '') $erreurs['prenom'] = "Le prénom est requis.";
            if (!isset($numTel) || $numTel === '') $erreurs['numTel'] = "Le numéro de téléphone est requis.";
            if (!isset($login) || $login === '')  $erreurs['login'] = "Le login est requis.";
        
            $this->employeService
                 ->verifierDonneesSaisies($pdo, $nom, $prenom, $numTel, $login, 
                                          $mdp, $cmdp, $tabAttributLogin, 
                                          $tabAttributEmploye, $erreurs);
            // Si aucune erreur, on effectue la modification
            if (empty($erreurs)) {
                try {
                    // Si un mot de passe a été fourni, on le modifie, sinon on le laisse inchangé
                    if ($mdp !== null) {
                        $this->employeService->modifierEmploye($pdo, $id, $nom, $prenom, $login, $numTel, $mdp, $id_type);
                    } else {
                        $this->employeService->modifierEmployeSansMdp($pdo, $id, $nom, $prenom, $login, $numTel, $id_type);
                    }
        
                    // Actualisation des données pour affichage
                    $tabAttributEmploye = $this->employeService->recupAttributEmploye($pdo, $id);
                    $tabAttributLogin = $this->employeService->recupAttributLogin($pdo, $id);
        
                    $messageSucces = "Employé modifié avec succès !";
                } catch (Exception $e) {
                    $erreurs[] = "Impossible de modifier l'employé dans la base de données : " . $e->getMessage();
                }
            }
        }
        $vue->setVar("startTime", $startTime);
        $vue->setVar("tabAttributEmploye", $tabAttributEmploye);
        $vue->setVar("tabAttributLogin", $tabAttributLogin);
        $vue->setVar("messageSucces", $messageSucces);
        $vue->setVar("erreurs", $erreurs);
        $vue->setVar("id", $id);
        $vue->setVar("prenom", $prenom);
        $vue->setVar("nom", $nom);
        $vue->setVar("numTel", $numTel);
        $vue->setVar("login", $login);
        $vue->setVar("mdp", $mdp);
        return $vue;
    }
}
?>