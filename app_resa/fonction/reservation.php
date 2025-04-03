<?php
    namespace fonction;

    use Exception;
    use PDO;
    use PDOException;
    require("liaisonBD.php");
    $pdo = connecteBD();

    /***********************************/
    /*                                 */
    /*    affichageReservation.php     */
    /*                                 */
    /***********************************/

    function affichageReservation() {
        global $pdo;
        $requete = "SELECT reservation.id_reservation as id_reservation, salle.nom as nom_salle, employe.nom as nom_employe,
                    employe.prenom as prenom_employe, activite.nom_activite as nom_activite, reservation.date_reservation 
                    as date, reservation.heure_debut as heure_debut, reservation.heure_fin as heure_fin, reservation.id_employe as id_employe
                    FROM reservation
                    JOIN salle
                    ON reservation.id_salle = salle.id_salle
                    JOIN employe
                    ON reservation.id_employe = employe.id_employe
                    JOIN activite
                    ON reservation.id_activite = activite.id_activite
                    ORDER BY date DESC";
        $requete = $pdo->prepare($requete);
        $requete->execute();
        $resultat = $requete->fetchAll(PDO::FETCH_ASSOC);
        return $resultat;
    }

    function affichageMesReservations($idEmploye) {
        global $pdo;
        try {
            $requete = "
                SELECT 
                    reservation.id_reservation AS id_reservation,
                    salle.nom AS nom_salle,
                    employe.nom AS nom_employe,
                    employe.prenom AS prenom_employe,
                    activite.nom_activite AS nom_activite,
                    reservation.date_reservation AS date,
                    reservation.heure_debut AS heure_debut,
                    reservation.heure_fin AS heure_fin
                FROM 
                    reservation
                JOIN 
                    salle ON reservation.id_salle = salle.id_salle
                JOIN 
                    employe ON reservation.id_employe = employe.id_employe
                JOIN 
                    activite ON reservation.id_activite = activite.id_activite
                WHERE 
                    reservation.id_employe = :id_employe
            ";

            $stmt = $pdo->prepare($requete);
            $stmt->bindValue(':id_employe', $idEmploye, PDO::PARAM_INT); // Vérifie que $idEmploye est un entier
            $stmt->execute();
            $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $resultat;

        } catch (PDOException $e) {
            error_log("Erreur dans affichageMesReservations: " . $e->getMessage());
            return [];
        }
    }

    function affichageTypeReservation($idReservation){
        global $pdo;
        $requete = "SELECT reservation_entretien.nature, 
                           reservation_formation.sujet, reservation_formation.nom_formateur, reservation_formation.prenom_formateur, reservation_formation.num_tel_formateur, 
                           reservation_pret_louer.nom_organisme, reservation_pret_louer.nom_interlocuteur, reservation_pret_louer.prenom_interlocuteur, reservation_pret_louer.num_tel_interlocuteur, reservation_pret_louer.type_activite, 
                           reservation_autre.description, 
                           reservation_reunion.objet
                    FROM reservation
                    LEFT JOIN reservation_entretien
                    ON reservation.id_reservation = reservation_entretien.id_reservation
                    LEFT JOIN reservation_formation
                    ON reservation.id_reservation = reservation_formation.id_reservation
                    LEFT JOIN reservation_pret_louer
                    ON reservation.id_reservation = reservation_pret_louer.id_reservation
                    LEFT JOIN reservation_autre
                    ON reservation.id_reservation = reservation_autre.id_reservation
                    LEFT JOIN reservation_reunion
                    ON reservation.id_reservation = reservation_reunion.id_reservation
                    WHERE reservation.id_reservation = :id_reservation";
        $requete = $pdo->prepare($requete);
        $requete->bindParam(':id_reservation', $idReservation, PDO::PARAM_STR);
        $requete->execute();
        $resultat = $requete->fetch(PDO::FETCH_ASSOC);

        return $resultat;
    }

    function listeSalles() {
        global $pdo;
        // Retourne la liste des noms de salles dans un tableau
        $tableauRetour = array();
        try {
            $maRequete = $pdo->prepare("SELECT DISTINCT nom FROM salle ORDER BY nom ASC");

            if ($maRequete->execute()) {
                while ($ligne = $maRequete->fetch()) {
                    $tableauRetour[] = $ligne->nom;
                }
            }
            return $tableauRetour;
        } catch (Exception $e) {
            // Erreur de BD
            throw new PDOException($e->getMessage(), $e->getCode());
        }
    }

    function listeActivites() {
        global $pdo;
        // Retourne la liste des noms d'activités dans un tableau
        $tableauRetour = array();
        try {
            $maRequete = $pdo->prepare("SELECT DISTINCT nom_activite FROM activite ORDER BY nom_activite ASC");

            if ($maRequete->execute()) {
                while ($ligne = $maRequete->fetch()) {
                    $tableauRetour[] = $ligne->nom_activite;
                }
            }
            return $tableauRetour;
        } catch (Exception $e) {
            // Erreur de BD
            throw new PDOException($e->getMessage(), $e->getCode());
        }
    }

    function listeDate() {
        global $pdo;
        // Retourne la liste des dates dans un tableau
        $tableauRetour = array();
        try {
            $maRequete = $pdo->prepare("SELECT DISTINCT date_reservation FROM reservation 
                                               ORDER BY date_reservation ASC");

            if ($maRequete->execute()) {
                while ($ligne = $maRequete->fetch()) {
                    $tableauRetour[] = $ligne->date_reservation;
                }
            }
            return $tableauRetour;
        } catch (Exception $e) {
            // Erreur de BD
            throw new PDOException($e->getMessage(), $e->getCode());
        }
    }

    function listeHeureDebut() {
        global $pdo;
        // Retourne la liste des heures de début dans un tableau
        $tableauRetour = array();
        try {
            $maRequete = $pdo->prepare("SELECT DISTINCT heure_debut FROM reservation 
                                               ORDER BY heure_debut ASC");

            if ($maRequete->execute()) {
                while ($ligne = $maRequete->fetch()) {
                    $tableauRetour[] = $ligne->heure_debut;
                }
            }
            return $tableauRetour;
        } catch (Exception $e) {
            // Erreur de BD
            throw new PDOException($e->getMessage(), $e->getCode());
        }
    }

    function listeHeureFin() {
        global $pdo;
        // Retourne la liste des heures de fin dans un tableau
        $tableauRetour = array();
        try {
            $maRequete = $pdo->prepare("SELECT DISTINCT heure_fin FROM reservation 
                                                   ORDER BY heure_fin ASC");

            if ($maRequete->execute()) {
                while ($ligne = $maRequete->fetch()) {
                    $tableauRetour[] = $ligne->heure_fin;
                }
            }
            return $tableauRetour;
        } catch (Exception $e) {
            // Erreur de BD
            throw new PDOException($e->getMessage(), $e->getCode());
        }
    }

    function supprimerResa($id_reservation) {
        global $pdo;

        // Liste des tables à parcourir
        $tables = [
            'reservation_autre',
            'reservation_entretien',
            'reservation_formation',
            'reservation_pret_louer',
            'reservation_reunion',
            'reservation' // la table réservation est mise en dernier, car les autres tables héritent de celle-ci
        ];

        try {
             foreach ($tables as $table) {
                $requete = "DELETE FROM $table WHERE id_reservation = :id_reservation";
                $stmt = $pdo->prepare($requete);
                $stmt->execute(['id_reservation' => $id_reservation]);

            }
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), $e->getCode());
        }
    }


    /***********************************/
    /*                                 */
    /*     creationReservation.php     */
    /*                                 */
    /***********************************/

    // Fonction pour insérer une réservation
    function insertionReservation($nomSalle, $nomActivite, $date, $heureDebut, $heureFin, $objet, $nom, $prenom, $numTel, $precisActivite, $idLogin) {
        global $pdo;

        try {
            // Récupère le dernier identifiant de la table 'reservation'
            $sqlLastId = "SELECT id_reservation FROM reservation ORDER BY id_reservation DESC LIMIT 1";
            $stmtLastId = $pdo->prepare($sqlLastId);
            $stmtLastId->execute();
            $lastId = $stmtLastId->fetchColumn();

            // Si aucune réservation n'existe encore, démarrer à "R000001".
            if ($lastId === false) {
                $idReservation = 'R000001';
            } else {
                // Extrait la partie numérique et l'incrémenter
                $partieNumerique = (int)substr($lastId, 1); // Extrait la partie numérique de l'ID
                $nouvellePartieNumerique = $partieNumerique + 1;

                // Formate le nouvel identifiant en "R000001"
                $idReservation = 'R' . str_pad($nouvellePartieNumerique, 6, '0', STR_PAD_LEFT);
            }

            // Récupère l'identifiant de la salle
            $sqlSalle = "SELECT id_salle FROM salle WHERE nom = :nomSalle";
            $stmtSalle = $pdo->prepare($sqlSalle);
            $stmtSalle->bindParam(':nomSalle', $nomSalle);
            $stmtSalle->execute();
            $idSalle = $stmtSalle->fetchColumn();

            // Récupère l'identifiant de l'employé à partir de l'identifiant de login
            $sqlEmploye = "SELECT id_employe FROM login WHERE id_login = :idLogin";
            $stmtEmploye = $pdo->prepare($sqlEmploye);
            $stmtEmploye->bindParam(':idLogin', $idLogin);
            $stmtEmploye->execute();
            $idEmploye = $stmtEmploye->fetchColumn();

            // Récupère l'identifiant de l'activité
            $sqlActivite = "SELECT id_activite FROM activite WHERE nom_activite = :nomActivite";
            $stmtActivite = $pdo->prepare($sqlActivite);
            $stmtActivite->bindParam(':nomActivite', $nomActivite);
            $stmtActivite->execute();
            $idActivite = $stmtActivite->fetchColumn();

            // Insère dans la table reservation
            $sqlReservation = "INSERT INTO reservation (id_reservation, id_salle, id_employe, id_activite, date_reservation, heure_debut, heure_fin)
                                   VALUES (:idReservation, :idSalle, :idEmploye, :idActivite, :dateReservation, :heureDebut, :heureFin)";
            $stmtReservation = $pdo->prepare($sqlReservation);
            $stmtReservation->bindParam(':idReservation', $idReservation);
            $stmtReservation->bindParam(':idSalle', $idSalle);
            $stmtReservation->bindParam(':idEmploye', $idEmploye);
            $stmtReservation->bindParam(':idActivite', $idActivite);
            $stmtReservation->bindParam(':dateReservation', $date);
            $stmtReservation->bindParam(':heureDebut', $heureDebut);
            $stmtReservation->bindParam(':heureFin', $heureFin);
            $stmtReservation->execute();

            // Insère dans la table correspondante à l'activité
            if ($nomActivite === 'Réunion') {
                $sqlReunion = "INSERT INTO reservation_reunion (id_reservation, objet) VALUES (:idReservation, :objet)";
                $stmtReunion = $pdo->prepare($sqlReunion);
                $stmtReunion->bindParam(':idReservation', $idReservation);
                $stmtReunion->bindParam(':objet', $objet);
                $stmtReunion->execute();

            } else if ($nomActivite === 'Formation') {
                $sqlFormation = "INSERT INTO reservation_formation (id_reservation, sujet, nom_formateur, prenom_formateur, num_tel_formateur)
                                     VALUES (:idReservation, :sujet, :nomFormateur, :prenomFormateur, :numTelFormateur)";
                $stmtFormation = $pdo->prepare($sqlFormation);
                $stmtFormation->bindParam(':idReservation', $idReservation);
                $stmtFormation->bindParam(':sujet', $objet);
                $stmtFormation->bindParam(':nomFormateur', $nom);
                $stmtFormation->bindParam(':prenomFormateur', $prenom);
                $stmtFormation->bindParam(':numTelFormateur', $numTel);
                $stmtFormation->execute();

            } else if ($nomActivite === 'Entretien de la salle') {
                $sqlEntretien = "INSERT INTO reservation_entretien (id_reservation, nature) VALUES (:idReservation, :nature)";
                $stmtEntretien = $pdo->prepare($sqlEntretien);
                $stmtEntretien->bindParam(':idReservation', $idReservation);
                $stmtEntretien->bindParam(':nature', $objet);
                $stmtEntretien->execute();

            } else if ($nomActivite === 'Prêt' || $nomActivite === 'Location') {
                $sqlPretLouer = "INSERT INTO reservation_pret_louer (id_reservation, nom_organisme, nom_interlocuteur, prenom_interlocuteur, num_tel_interlocuteur, type_activite)
                                     VALUES (:idReservation, :nomOrganisme, :nomInterlocuteur, :prenomInterlocuteur, :numTelInterlocuteur, :typeActivite)";
                $stmtPretLouer = $pdo->prepare($sqlPretLouer);
                $stmtPretLouer->bindParam(':idReservation', $idReservation);
                $stmtPretLouer->bindParam(':nomOrganisme', $nom);
                $stmtPretLouer->bindParam(':nomInterlocuteur', $prenom);
                $stmtPretLouer->bindParam(':prenomInterlocuteur', $objet);
                $stmtPretLouer->bindParam(':numTelInterlocuteur', $numTel);
                $stmtPretLouer->bindParam(':typeActivite', $precisActivite);
                $stmtPretLouer->execute();

            } else if ($nomActivite === 'Autre') {
                $sqlAutre = "INSERT INTO reservation_autre (id_reservation, description) VALUES (:idReservation, :description)";
                $stmtAutre = $pdo->prepare($sqlAutre);
                $stmtAutre->bindParam(':idReservation', $idReservation);
                $stmtAutre->bindParam(':description', $objet);
                $stmtAutre->execute();
            }

            return "Réservation insérée avec succès";

        } catch (PDOException $e) {
            return "Erreur lors de l'insertion de la réservation : " . $e->getMessage();
        }
    }

    /***********************************/
    /*                                 */
    /*   modificationReservation.php   */
    /*                                 */
    /***********************************/

    /**
     * Fonction qui récupère les valeurs d'une réservation,
     * afin de remplir les champs de la page de modification
     * avec les informations de la réservation choisie
     * @param $id_Resa
     * @return mixed|null
     */
    function recupAttributReservation($id_Resa) {
        global $pdo;

        try {
            // Récupére les informations principales de la réservation
            $query = "SELECT r.id_reservation, r.date_reservation, r.heure_debut, r.heure_fin,
                             r.id_salle, r.id_employe, r.id_activite
                      FROM reservation r
                      WHERE r.id_reservation = :idReservation";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':idReservation', $id_Resa, PDO::PARAM_STR);
            $stmt->execute();
            $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$reservation) {
                return null; // Aucune réservation trouvée
            }

            // Récupére le nom de la salle
            $querySalle = "SELECT nom FROM salle WHERE id_salle = :idSalle";
            $stmt = $pdo->prepare($querySalle);
            $stmt->bindParam(':idSalle', $reservation['id_salle'], PDO::PARAM_INT);
            $stmt->execute();
            $reservation['nomSalle'] = $stmt->fetchColumn();

            // Récupére le nom complet de l'employé
            $queryEmploye = "SELECT CONCAT(nom, ' ', prenom) AS nomComplet FROM employe WHERE id_employe = :idEmploye";
            $stmt = $pdo->prepare($queryEmploye);
            $stmt->bindParam(':idEmploye', $reservation['id_employe'], PDO::PARAM_STR);
            $stmt->execute();
            $reservation['nomEmploye'] = $stmt->fetchColumn();

            // Récupére le nom de l'activité
            $queryActivite = "SELECT nom_activite FROM activite WHERE id_activite = :idActivite";
            $stmt = $pdo->prepare($queryActivite);
            $stmt->bindParam(':idActivite', $reservation['id_activite'], PDO::PARAM_STR);
            $stmt->execute();
            $reservation['nomActivite'] = $stmt->fetchColumn();

            // Initialise les détails supplémentaires
            $reservation['details'] = [];

            // Récupére les détails supplémentaires en fonction de l'activité
            $queryDetails = "";
            switch ($reservation['nomActivite']) {
                case 'Réunion':
                    $queryDetails = "SELECT objet FROM reservation_reunion WHERE id_reservation = :idReservation";
                    break;

                case 'Formation':
                    $queryDetails = "SELECT sujet, nom_formateur, prenom_formateur, num_tel_formateur
                                     FROM reservation_formation WHERE id_reservation = :idReservation";
                    break;

                case 'Entretien de la salle':
                    $queryDetails = "SELECT nature FROM reservation_entretien WHERE id_reservation = :idReservation";
                    break;

                case 'Prêt':
                case 'Location':
                    $queryDetails = "SELECT nom_organisme, nom_interlocuteur, prenom_interlocuteur, num_tel_interlocuteur, type_activite
                                    FROM reservation_pret_louer WHERE id_reservation = :idReservation";
                    break;

                case 'Autre':
                    $queryDetails = "SELECT description FROM reservation_autre WHERE id_reservation = :idReservation";
                    break;

                default:
                    $queryDetails = null;
                    break;
            }

            if ($queryDetails) {
                $stmt = $pdo->prepare($queryDetails);
                $stmt->bindParam(':idReservation', $id_Resa, PDO::PARAM_STR);
                $stmt->execute();
                $reservation['details'] = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

            }
            return $reservation;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des détails de la réservation : " . $e->getMessage());
            return null;
        }
    }

    /**
     * Fonction permettant la modification d'une réservation.
     * Si l'activité n'est pas modifiée, un update est fait
     * Sinon si c'est le cas, alors un insert est réalisé dans la nouvelle table correspondant à la
     * nouvelle activité, et un delete est effectué dans l'ancienne table.
     * @param $idResa
     * @param $nomSalle
     * @param $nomActivite
     * @param $date
     * @param $heureDebut
     * @param $heureFin
     * @param $objet
     * @param $nom
     * @param $prenom
     * @param $numTel
     * @param $precisActivite
     * @param $nomActivitePrecedent
     * @return string
     */
    function modifReservation($idResa, $nomSalle, $nomActivite, $date, $heureDebut, $heureFin, $objet, $nom, $prenom, $numTel, $precisActivite, $nomActivitePrecedent) {
        global $pdo;

        try {
            // Démarre une transaction
            $pdo->beginTransaction();

            // Récupère l'identifiant de la salle
            $sqlSalle = "SELECT id_salle FROM salle WHERE nom = :nomSalle";
            $stmtSalle = $pdo->prepare($sqlSalle);
            $stmtSalle->bindParam(':nomSalle', $nomSalle);
            $stmtSalle->execute();
            $idSalle = $stmtSalle->fetchColumn();

            if (!$idSalle) {
                throw new Exception("Salle introuvable");
            }

            // Récupère l'identifiant de l'activité
            $sqlActivite = "SELECT id_activite FROM activite WHERE nom_activite = :nomActivite";
            $stmtActivite = $pdo->prepare($sqlActivite);
            $stmtActivite->bindParam(':nomActivite', $nomActivite);
            $stmtActivite->execute();
            $idActivite = $stmtActivite->fetchColumn();

            if (!$idActivite) {
                throw new Exception("Activité introuvable");
            }

            // Mise à jour dans la table reservation
            $sqlReservation = "UPDATE reservation
                                   SET id_salle = :idSalle, id_activite = :idActivite,
                                       date_reservation = :dateReservation, heure_debut = :heureDebut, heure_fin = :heureFin
                                   WHERE id_reservation = :idReservation";
            $stmtReservation = $pdo->prepare($sqlReservation);
            $stmtReservation->bindParam(':idReservation', $idResa);
            $stmtReservation->bindParam(':idSalle', $idSalle);
            $stmtReservation->bindParam(':idActivite', $idActivite);
            $stmtReservation->bindParam(':dateReservation', $date);
            $stmtReservation->bindParam(':heureDebut', $heureDebut);
            $stmtReservation->bindParam(':heureFin', $heureFin);
            $stmtReservation->execute();

            // Mise à jour dans la table spécifique à l'activité
            switch ($nomActivite == $nomActivitePrecedent) {
                case 'Réunion':
                    $sqlReunion = "UPDATE reservation_reunion SET objet = :objet WHERE id_reservation = :idReservation";
                    $stmtReunion = $pdo->prepare($sqlReunion);
                    $stmtReunion->bindParam(':idReservation', $idResa);
                    $stmtReunion->bindParam(':objet', $objet);
                    $stmtReunion->execute();
                    break;

                case 'Formation':
                    $sqlFormation = "UPDATE reservation_formation
                                         SET sujet = :sujet, nom_formateur = :nomFormateur, prenom_formateur = :prenomFormateur, num_tel_formateur = :numTelFormateur
                                         WHERE id_reservation = :idReservation";
                    $stmtFormation = $pdo->prepare($sqlFormation);
                    $stmtFormation->bindParam(':idReservation', $idResa);
                    $stmtFormation->bindParam(':sujet', $objet);
                    $stmtFormation->bindParam(':nomFormateur', $nom);
                    $stmtFormation->bindParam(':prenomFormateur', $prenom);
                    $stmtFormation->bindParam(':numTelFormateur', $numTel);
                    $stmtFormation->execute();
                    break;

                case 'Entretien de la salle':
                    $sqlEntretien = "UPDATE reservation_entretien SET nature = :nature WHERE id_reservation = :idReservation";
                    $stmtEntretien = $pdo->prepare($sqlEntretien);
                    $stmtEntretien->bindParam(':idReservation', $idResa);
                    $stmtEntretien->bindParam(':nature', $objet);
                    $stmtEntretien->execute();
                    break;

                case 'Location':
                case 'Prêt':
                    $sqlPretLouer = "UPDATE reservation_pret_louer
                                         SET nom_organisme = :nomOrganisme, nom_interlocuteur = :nomInterlocuteur, prenom_interlocuteur = :prenomInterlocuteur,
                                             num_tel_interlocuteur = :numTelInterlocuteur, type_activite = :typeActivite
                                         WHERE id_reservation = :idReservation";
                    $stmtPretLouer = $pdo->prepare($sqlPretLouer);
                    $stmtPretLouer->bindParam(':idReservation', $idResa);
                    $stmtPretLouer->bindParam(':nomOrganisme', $objet);
                    $stmtPretLouer->bindParam(':nomInterlocuteur', $nom);
                    $stmtPretLouer->bindParam(':prenomInterlocuteur', $prenom);
                    $stmtPretLouer->bindParam(':numTelInterlocuteur', $numTel);
                    $stmtPretLouer->bindParam(':typeActivite', $precisActivite);
                    $stmtPretLouer->execute();
                    break;

                case 'Autre':
                    $sqlAutre = "UPDATE reservation_autre SET description = :description WHERE id_reservation = :idReservation";
                    $stmtAutre = $pdo->prepare($sqlAutre);
                    $stmtAutre->bindParam(':idReservation', $idResa);
                    $stmtAutre->bindParam(':description', $objet);
                    $stmtAutre->execute();
                    break;
            }

            $nomTablePrecedent = '';
            if ($nomActivitePrecedent == 'Réunion') {
                $nomTablePrecedent = 'reservation_reunion';
            } else if ($nomActivitePrecedent == 'Prêt' || $nomActivitePrecedent == 'Location') {
                $nomTablePrecedent = 'reservation_pret_louer';
            } else if ($nomActivitePrecedent == 'Formation') {
                $nomTablePrecedent = 'reservation_formation';
            } else if ($nomActivitePrecedent == 'Entretien de la salle') {
                $nomTablePrecedent = 'reservation_entretien';
            } else if ($nomActivitePrecedent == 'Autre') {
                $nomTablePrecedent = 'reservation_autre';
            }

            // Si changement de type d'activité
            if ($nomActivite != $nomActivitePrecedent) {
                if ($nomActivite == 'Réunion') {
                    // Insertion pour Réunion, puis suppression de l'ancienne table
                    $sqlReunion = "INSERT INTO reservation_reunion (id_reservation, objet) VALUES (:idReservation, :objet)";
                    $stmtReunion = $pdo->prepare($sqlReunion);
                    $stmtReunion->bindParam(':idReservation', $idResa);
                    $stmtReunion->bindParam(':objet', $objet);
                    $stmtReunion->execute();

                } else if ($nomActivite == 'Formation') {
                    $sqlFormation = "INSERT INTO reservation_formation (id_reservation, sujet, nom_formateur, prenom_formateur, num_tel_formateur)
                                        VALUES (:idReservation, :sujet, :nomFormateur, :prenomFormateur, :numTelFormateur)";
                    $stmtFormation = $pdo->prepare($sqlFormation);
                    $stmtFormation->bindParam(':idReservation', $idResa);
                    $stmtFormation->bindParam(':sujet', $objet);
                    $stmtFormation->bindParam(':nomFormateur', $nom);
                    $stmtFormation->bindParam(':prenomFormateur', $prenom);
                    $stmtFormation->bindParam(':numTelFormateur', $numTel);
                    $stmtFormation->execute();

                } else if ($nomActivite == 'Entretien de la salle') {
                    $sqlEntretien = "INSERT INTO reservation_entretien (id_reservation, nature) VALUES (:idReservation, :nature)";
                    $stmtEntretien = $pdo->prepare($sqlEntretien);
                    $stmtEntretien->bindParam(':idReservation', $idResa);
                    $stmtEntretien->bindParam(':nature', $objet);
                    $stmtEntretien->execute();

                } else if ($nomActivite == 'Location' || $nomActivite == 'Prêt') {
                    $sqlPretLouer = "INSERT INTO reservation_pret_louer (id_reservation, nom_organisme, nom_interlocuteur, prenom_interlocuteur, num_tel_interlocuteur, type_activite)
                                         VALUES (:idReservation, :nomOrganisme, :nomInterlocuteur, :prenomInterlocuteur, :numTel, :typeActivite)";
                    $stmtPretLouer = $pdo->prepare($sqlPretLouer);
                    $stmtPretLouer->bindParam(':idReservation', $idResa);
                    $stmtPretLouer->bindParam(':nomOrganisme', $objet);
                    $stmtPretLouer->bindParam(':nomInterlocuteur', $nom);
                    $stmtPretLouer->bindParam(':prenomInterlocuteur', $prenom);
                    $stmtPretLouer->bindParam(':numTel', $numTel);
                    $stmtPretLouer->bindParam(':typeActivite', $precisActivite);
                    $stmtPretLouer->execute();

                } else if ($nomActivite == 'Autre') {
                    $sqlAutre = "INSERT INTO reservation_autre (id_reservation, description) VALUES (:idReservation, :description)";
                    $stmtAutre = $pdo->prepare($sqlAutre);
                    $stmtAutre->bindParam(':idReservation', $idResa);
                    $stmtAutre->bindParam(':description', $objet);
                    $stmtAutre->execute();
                }
                $sqlSuppr = "DELETE FROM $nomTablePrecedent WHERE id_reservation = :idReservation";
                $stmtSuppr = $pdo->prepare($sqlSuppr);
                $stmtSuppr->bindParam(':idReservation', $idResa);
                $stmtSuppr->execute();
            }

            // Validation de la transaction
            $pdo->commit();

            return "Réservation modifiée avec succès";

        } catch (Exception $e) {
            // Annulation de la transaction en cas d'erreur
            $pdo->rollBack();
            return "Erreur lors de la modification de la réservation : " . $e->getMessage();
        }
    }
?>