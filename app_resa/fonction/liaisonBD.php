<?php
namespace fonction;

use PDO;
use PDOException;
function connecteBD() {

    /*
     * Configuration de l'accès à la base de données.
     * - Si vous travaillez en local, décommentez la ligne `$hostAUtiliser = 'localhost';`.
     * - Si vous devez connecter une autre base de données, modifiez directement les variables
     *   `$host`, `$user`, `$pass`, et éventuellement `$db` pour refléter vos paramètres de connexion (à partir de ligne 54).
     */
      $hostAUtiliser = 'local';                    // local
    //$hostAUtiliser = 'distant';                  // base de donnée distante






















    if ($hostAUtiliser == 'local') {

        $host = 'localhost';                     // Adresse de l'hôte pour la base locale
        $db = 'statisallebd';                    // Base de données local
        $user = 'root';                          // Identifiant pour la base locale
        $pass = 'root';                         // Mot de passe pour la base locale
        $charset = 'utf8mb4';                    // Jeu de caractères à utiliser (UTF-8 étendu)
        $port = 3306;                            // Port MySQL (3306 par défaut)

    } else {

        // Vérification si le script est exécuté sur le serveur en production ou en local avec accès distant au serveur
        if ($_SERVER['HTTP_HOST'] === 'statisalle.fr') {
            // NE PAS MODIFIER
            $host = 'localhost';           // Utiliser l'hôte local si le script est appelé depuis le domaine principal
        } else {
            /*
             * MODIFIER AVEC L'ADDRESSE DE VOTRE BASE DE DONNEE
             */
            $host = 'statisalle.fr';       // Utilisation de la base de données hébergée chez O2Switch
        }

        /*
         * MODIFIER EN CAS DE BESOIN
         */
        $db = 'sc1vosi2297_StatisalleBD';  // Nom de la base de données
        $user = 'sc1vosi2297_application'; // Identifiant de l'utilisateur de la base
        $pass = '@ppl1cat1on123';          // Mot de passe pour accéder à la base
        $charset = 'utf8mb4';              // Jeu de caractères à utiliser (UTF-8 étendu)
        $port = 3306;                      // Port MySQL (3306 par défaut)

    }

    // Création de la chaîne DSN (Data Source Name) pour définir la connexion
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

    // Options pour configurer le comportement de PDO
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,    // Active les exceptions en cas d'erreur
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, // Récupère les résultats sous forme d'objets
        PDO::ATTR_EMULATE_PREPARES => false             // Désactive l'émulation des requêtes préparées pour plus de sécurité
    ];

    // Tentative de connexion à la base de données
    try {
        return new PDO($dsn, $user, $pass, $options); // Création de l'objet PDO pour se connecter à la base
    } catch (PDOException $e) {
        // Gestion des erreurs de connexion : affichage des détails pour le débogage
        echo "Erreur de connexion : " . $e->getMessage() . "<br>"; // Message d'erreur
        echo "Code d'erreur : " . $e->getCode() . "<br>";          // Code de l'erreur
        echo "Hôte : $host<br>";                                   // Hôte utilisé
        echo "Port : $port<br>";                                   // Port utilisé
        echo "Utilisateur : $user<br>";                            // Identifiant utilisé
        exit; // Arrêt du script en cas d'échec
    }
}
?>
