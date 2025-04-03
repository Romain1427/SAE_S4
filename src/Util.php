<?php

namespace src;
use PDO;
/*************************/
/* METHODES  UTILITAIRES */
/*************************/
class Util {
	//Connexion BD
	public static function getPDO(){
		// Retourne un objet connexion à la BD
		$host='localhost';	// Serveur de BD
		$db='statisallebd';		// Nom de la BD
		$user='root';		// User 
		$pass='root';		// Mot de passe
		$charset='utf8mb4';	// charset utilisé
		
		// Constitution variable DSN
		$dsn="mysql:host=$host;dbname=$db;charset=$charset";
		
		// Réglage des options
		$options=[																				 
			PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES=>false];
		
		try {	// Bloc try bd injoignable ou si erreur SQL
			$pdo=new PDO($dsn,$user,$pass,$options);
			return $pdo ;			
		} catch(PDOException $e) {
			//Il y a eu une erreur de connexion
			self::sendError(500, "Problème connexion base de données");
			die();
		}
	}

	// Envoi JSON
	public static function sendJSON($infos, $codeRetour){
		header("Access-Control-Allow-Origin: *"); // Permet que tout le monde peut y acceder (toutes les IP)
		header("Content-Type: application/json; charset=UTF-8"); // Type de données envoyées de type JSON

		header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT");
			
		// header("Access-Control-Max-Age: 3600"); // Durée de la requete
		http_response_code($codeRetour);
		echo json_encode($infos,JSON_UNESCAPED_UNICODE);
		die();
	}

	// Envoi erreur
	public static function sendError($status, $message) {
		$infos['statut']="KO";
		$infos['message']=$message;
		self::sendJSON($infos, $status);
	}
}