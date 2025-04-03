<?php 

namespace src;

/* 
    Routes de l'API
*/

require("Util.php");
require("Api.php");
use src\Util;
use src\Api;

$pdo = Util::getPDO();  
$api = new Api($pdo);

$request_method = $_SERVER["REQUEST_METHOD"];  // GET / POST / DELETE / PUT
if (!empty($_GET['demande'])) {

    $url = explode("/", filter_var($_GET['demande'],FILTER_SANITIZE_URL));
    // Récupération des données du put
    $donnees = json_decode(file_get_contents("php://input"),true);

    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET":
            $cle = $api->apiKeyRequired(); // bloque la suite du programme si la clé est incorrecte
            switch($url[0]) {
                case 'reservations': // récupère les réservations de l'utilisateur correspondant à la clé API fournie
                    Util::sendJson($api->getReservations($cle), 200);
                    break;
                case 'signalements':
                    if (isset($url[1]) && $url[1] != null) {
                        Util::sendJson($api->getSignalements($cle, $url[1]), 200); // avec id de resa
                    } else {
                        Util::sendJson($api->getSignalements($cle), 200);
                    }
                    break;
                default: 
                    Util::sendError(404, "GET ".$url[0]." inexistant");
            }
            break;
        case "PUT": // ajout problème
            $cle = $api->apiKeyRequired();
            switch($url[0]) {
                case 'edit':
                    if (empty($url[1])) {
                        Util::sendError(400, "Vous devez indiquer l'identifiant du signalement");
                    } else {
                        Util::sendJson($api->editSignalement($url[1], $donnees, $cle), 200);
                    }
                    break;
                default: 
                    Util::sendError(404, "PUT ".$url[0]." inexistant");
            }
        case "POST": // connexion
            switch($url[0]) {
                case 'login':
                    Util::sendJson($api->getApiKey($donnees), 200);
                    break;
                case 'report':
                    $cle = $api->apiKeyRequired();
                    if (empty($url[1])) {
                        Util::sendError(400, "Vous devez indiquer l'identifiant de la réservation");
                    } else {
                        Util::sendJson($api->insertSignalement($url[1], $donnees, $cle), 201);
                    }
                    break;
                default: 
                    Util::sendError(404, "POST ".$url[0]." inexistant");
            }
        case "DELETE":
            $cle = $api->apiKeyRequired();
            switch($url[0]) {
                case 'supprimer':
                    if (empty($url[1])) {
                        Util::sendError(400, "Vous devez indiquer l'identifiant du signalement");
                    } else {
                        Util::sendJson($api->deleteSignalement($url[1], $cle), 200);
                    }
                    break;
                default: 
                    Util::sendError(404, "DELETE ".$url[0]." inexistant");
            }
        default:
            Util::sendError(404, "URL non valide");
    }
} else {
    Util::sendError(404, "URL non valide");
}
