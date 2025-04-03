<?php

namespace src;

use src\Util;

class Api {
	
	private $pdo;

	public function __construct($pdo) {
        $this->pdo = $pdo;
    }

	public function hasReservation($idReservation, $apiKey) {
		try {
			$requete = 'SELECT * FROM reservation
						JOIN login ON reservation.id_employe = login.id_employe
						WHERE login.api_key = :api_key
						AND reservation.id_reservation = :id_reservation';
			$stmt = $this->pdo->prepare($requete);
			$stmt->bindParam("api_key", $apiKey);
			$stmt->bindParam("id_reservation", $idReservation);
			$stmt->execute();
			return $stmt->rowCount() >= 1;
		} catch (PDOException $e) {
			Util::sendError(500, $e->getMessage());
		}
	}

	public function hasSignalement($idSignalement, $apiKey) {
		try {
			$requete = 'SELECT id FROM signalements
						JOIN reservation 
						ON signalements.id_reservation = reservation.id_reservation
						JOIN login ON reservation.id_employe = login.id_employe
						WHERE login.api_key = :api_key
						AND signalements.id = :id_signalement';
			$stmt = $this->pdo->prepare($requete);
			$stmt->bindParam("api_key", $apiKey);
			$stmt->bindParam("id_signalement", $idSignalement);
			$stmt->execute();
			return $stmt->rowCount() >= 1;
		} catch (PDOException $e) {
			Util::sendError(500, $e->getMessage());
		}
	}


	/************************/
	/* GESTION DE L'API KEY */
	/************************/
	public function getApiKey($donnees) {
		if ($donnees != null 
			&& array_key_exists("login", $donnees) 
			&& array_key_exists("password", $donnees)) {

			try {
				$requete = 'SELECT api_key 
						    FROM login WHERE login=:login AND mdp=:password';
				$stmt = $this->pdo->prepare($requete);
				$stmt->bindParam("login", $donnees["login"]);
				$pwd = sha1($donnees["password"]);
				$stmt->bindParam("password", $pwd);
				$stmt->execute();
				$cle = $stmt->fetch();

				if ($cle) {
					if ($cle["api_key"] == "") {
						$nouvelleCle = $this->generateNewKey(16);
						$this->addKey($donnees["login"], $nouvelleCle);
						$cle["api_key"] = $nouvelleCle;
					}
					return $cle;
				} else {
					Util::sendError(404, "Login ou password invalides");
				}
			} catch(PDOException $e) {
				Util::sendError(500, $e->getMessage());
			}
		} else {
			Util::sendError(404, "Login ou password invalides");
		}
	}

	public function generateNewKey($length) {
		try {
			$requete = 'SELECT api_key FROM login';
			$stmt = $this->pdo->prepare($requete);
			$stmt->execute();
			$cles = $stmt->fetch();
			foreach ($cles as $cle) {
				if ($cle != null) {
					$listeCle[] = $cle["api_key"];
				}
			}
			// Générer des clés jusqu'à en avoir une
			// qui n'est pas déjà utilisée
			do {
				$bytes = random_bytes($length);
				$nouvelleCle = bin2hex($bytes);
			} while (isset($listeCle) && in_array($nouvelleCle, $listeCle));
			return $nouvelleCle;
		} catch (PDOException $e) {
			Util::sendError(500, $e->getMessage());
		}
	}

	public function addKey($login, $cle) {
		try {
			$requete = 'UPDATE login SET api_key = :cle WHERE login = :login';
			$stmt = $this->pdo->prepare($requete);
			$stmt->bindParam("cle", $cle);
			$stmt->bindParam("login", $login);
			$stmt->execute();
		} catch (PDOException $e) {
			Util::sendError(500, $e->getMessage());
		}
	}

	/**
	 * Lors de son appel, empêche la suite de l'exécution
	 * du code si aucune API_KEY valide n'est dans les headers
	 * de la requête.
	 */
	public function apiKeyRequired() {
		if (isset(getallheaders()['api_key'])) {
			try {
				$requete = 'SELECT id_employe FROM login WHERE api_key = :cle';
				$stmt = $this->pdo->prepare($requete);
				$stmt->bindParam("cle", getallheaders()['api_key']);
				$stmt->execute();
				if ($stmt->rowCount() == 0) {
					Util::sendError(401, "Vous devez fournir une clé API correcte");
				} else {
					return getallheaders()['api_key'];
				}
			} catch (PDOException $e) {
				Util::sendError(500, $e->getMessage());
			}
		} else {
			Util::sendError(401, "Vous devez fournir une clé API correcte");
		}
	}


	/***************/
	/*     GET     */
	/***************/
	public function getReservations($apiKey) {
		try {
			$requete = 'SELECT r.id_reservation as idReservation, 
					    salle.nom AS reservationRoom, 
						activite.nom_activite AS reservationActivity, 
						date_reservation AS reservationDate, 
						heure_debut AS startHour, 
						heure_fin AS endHour, 
						MIN(id_incident) AS idIncidentMax
						FROM reservation r
						JOIN login ON r.id_employe = login.id_employe
						JOIN salle ON r.id_salle = salle.id_salle
						JOIN activite ON r.id_activite = activite.id_activite
						LEFT OUTER JOIN signalements s 
						ON s.id_reservation = r.id_reservation
						WHERE login.api_key = :api_key
						AND  date_reservation >= curdate()
						GROUP BY r.id_reservation ';
						//TODO garder que les réservations aux dates d'aujourd'hui + postérieures
			$stmt = $this->pdo->prepare($requete);
			$stmt->bindParam("api_key", $apiKey);
			$stmt->execute();
			return $stmt->fetchAll();
		} catch (PDOException $e) {
			Util::sendError(500, $e->getMessage());
		}
	}

	public function getSignalements($apiKey, $idReservation = null) {
		try {
			$requete = 'SELECT signalements.id, date, incidents.id AS incident, 
						       resume, description, 
						reservation.id_reservation, salle.nom AS roomName, 
						activite.nom_activite AS activityName, 
						date_reservation AS reservationDate, 
						heure_debut AS startHour, heure_fin AS endHour, contact
						FROM signalements
						JOIN reservation 
						ON signalements.id_reservation = reservation.id_reservation
						JOIN salle ON reservation.id_salle = salle.id_salle
						JOIN activite ON reservation.id_activite = activite.id_activite
						JOIN login ON reservation.id_employe = login.id_employe
						JOIN incidents ON signalements.id_incident = incidents.id
						WHERE login.api_key = :api_key';
			if ($idReservation != null) {
				$requete .= " AND reservation.id_reservation = :resaId";
			 }
			$stmt = $this->pdo->prepare($requete);
			$stmt->bindParam("api_key", $apiKey);
			if ($idReservation != null) {
				$stmt->bindParam("resaId", $idReservation);
			}
			$stmt->execute();
			return $stmt->fetchAll();
		} catch (PDOException $e) {
			Util::sendError(500, $e->getMessage());
		}
	}

	/***************/
	/*    POST     */
	/***************/
	public function insertSignalement($idReservation, $donnees, $apiKey) {
		if (!$this->hasReservation($idReservation, $apiKey)) {
			Util::sendError(403, "Vous ne possédez pas cette réservation");
		}
		if (array_key_exists("resume", $donnees)
			&& array_key_exists("description", $donnees)
			&& array_key_exists("incident", $donnees)
			&& array_key_exists("contact", $donnees)) {
			try {
				$requete = 'INSERT INTO signalements (id_reservation, 
							resume, description, id_incident, contact, date) 
							VALUES (:id_reservation, :resume, 
							:description, :id_incident, :contact, :date)';
				$stmt = $this->pdo->prepare($requete);
				$stmt->bindParam("id_reservation", $idReservation);
				$stmt->bindParam("resume", $donnees["resume"]);
				$stmt->bindParam("description", $donnees["description"]);
				$stmt->bindParam("id_incident", $donnees["incident"]);
				$stmt->bindParam("contact", $donnees["contact"]);
				$date = date("Y-m-d H:i:s");
				$stmt->bindParam("date", $date);
				$stmt->execute();
				$info['message'] = 'Signalement ajouté';
				return $info;
			} catch (PDOException $e) {
				Util::sendError(500, $e->getMessage());
			}
		} else  {
			Util::sendError(403, "Veuillez indiquer les informations nécessaires");
		}
	}

	/***************/
	/*     PUT     */
	/***************/
	public function editSignalement($idSignalement, $donnees, $apiKey) {
		if (!$this->hasSignalement($idSignalement, $apiKey)) {
			Util::sendError(403, "Vous ne possédez pas ce signalement");
		}
		if ($donnees != null) {
			try {
				$requete = 'UPDATE signalements ';
				$count = 0;
				if (array_key_exists("resume", $donnees)) {
					if ($count == 0) {
						$requete .= ' SET signalements.resume = :resume ';
					} else {
						$requete .= ', signalements.resume = :resume ';
					}
					$count++;
				}
				if (array_key_exists("description", $donnees)) {
					if ($count == 0) {
						$requete .= ' SET signalements.description = :description ';
					} else {
						$requete .= ', signalements.description = :description ';
					}
					$count++;
				}
				if (array_key_exists("incident", $donnees)) {
					if ($count == 0) {
						$requete .= ' SET signalements.id_incident = :id_incident ';
					} else {
						$requete .= ', signalements.id_incident = :id_incident ';
					}
					$count++;
				}
				if (array_key_exists("contact", $donnees)) {
					if ($count == 0) {
						$requete .= ' SET signalements.contact = :contact ';
					} else {
						$requete .= ', signalements.contact = :contact ';
					}
					$count++;
				}
				$requete .= ' WHERE signalements.id = :id_signalement';
				$stmt = $this->pdo->prepare($requete);
				$stmt->bindParam("id_signalement", $idSignalement);
				if (array_key_exists("resume", $donnees)) {
					$stmt->bindParam("resume", $donnees["resume"]);
				}
				if (array_key_exists("description", $donnees)) {
					$stmt->bindParam("description", $donnees["description"]);
				}
				if (array_key_exists("incident", $donnees)) {
					$stmt->bindParam("id_incident", $donnees["incident"]);
				}
				if (array_key_exists("contact", $donnees)) {
					$stmt->bindParam("contact", $donnees["contact"]);
				}
				$stmt->execute();
				$info['message'] = 'Signalement modifié';
				return $info;
			} catch (PDOException $e) {
				Util::sendError(500, $e->getMessage());
			}
		} else {
			Util::sendError(400, "Veuillez indiquer les champs à modifier");
		}
	}

	/***************/
	/*   DELETE    */
	/***************/
	public function deleteSignalement($idSignalement, $apiKey) {
		if (!$this->hasSignalement($idSignalement, $apiKey)) {
			Util::sendError(403, "Vous ne possédez pas ce signalement");
		}
		try {
			$requete = 'DELETE FROM signalements WHERE id = :id_signalement';
			$stmt = $this->pdo->prepare($requete);
			$stmt->bindParam("id_signalement", $idSignalement);
			$stmt->execute();
			$info['message'] = 'Signalement supprimé';
			return $info;
		} catch (PDOException $e) {
			Util::sendError(500, $e->getMessage());
		}
	}
}
