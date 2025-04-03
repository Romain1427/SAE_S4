<?php
namespace services;

use PDO;

class EmployeService {

    public function verifMdp($mdp): bool {
        // Vérifier que le mot de passe fait plus de 8 caractères
        if (strlen($mdp) <= 8) {
            return false;
        }

        // Vérifier qu'il contient au moins un caractère spécial
        if (!preg_match('/[\@\#\$\%\&\*\!\?]/', $mdp)) {
            return false;
        }

        // Si toutes les vérifications sont bonnes
        return true;
    }
    public function checkErreurs(PDO $pdo, $nom, $prenom, $numTel, $login, $mdp, 
                                 $cmdp, &$erreurs) {
        // Vérification des champs requis
        if (!isset($nom)    || $nom === '')    $erreurs['nom'] = "Le nom est requis.";
        if (!isset($prenom) || $prenom === '') $erreurs['prenom'] = "Le prénom est requis.";
        if (!isset($numTel) || $numTel === '') $erreurs['numTel'] = "Le numéro de téléphone est requis.";
        if (!isset($login)  || $login === '')  $erreurs['id'] = "Le login est requis.";
        if (!isset($mdp)    || $mdp === '')    $erreurs['mdp'] = "Le mot de passe est requis.";
        if (!isset($cmdp)   || $cmdp === '')   $erreurs['cmdp'] = "La confirmation du mot de passe est requise.";

        // Vérification des longueurs des champs
        if (strlen($numTel) < 4 || strlen($numTel) > 10) {
            $erreurs['numTel'] = "Le numéro de téléphone doit contenir entre 4 et 10 caractères.";
        }
        if (strlen($nom) < 1 || strlen($nom) > 50) {
            $erreurs['nom'] = "Le nom doit contenir entre 1 et 50 caractères.";
        }
        if (strlen($prenom) < 1 || strlen($prenom) > 50) {
            $erreurs['prenom'] = "Le prénom doit contenir entre 1 et 50 caractères.";
        }

        if (!ctype_digit($numTel)) {
            $erreurs['numTel'] = "Le numéro de téléphone doit contenir uniquement des chiffres.";
        }

        // Vérification que le mot de passe et sa confirmation sont identiques
        if ($mdp !== $cmdp) {
            $erreurs['cmdp'] = "Les mots de passe ne correspondent pas.";
        }
        if (!$this->verifMdp($mdp)) {
            $erreurs['mdp'] = "Le mot de passe doit faire plus de 8 caractères et contenir un caractère spécial, par exemple : @, #, $, %, & ou *.";
        }

        // Vérifiaction de l'unicité du login pour un employé
        if ($this->verifLogin($pdo, $login) > 0) {
            $erreurs['login'] = "Le login est déjà utilisé.";
        }
    } 


    function verifierDonneesSaisies(PDO $pdo, $nom, $prenom, $numTel, $login, 
                                    $mdp, $cmdp, &$tabAttributLogin, 
                                    &$tabAttributEmploye, &$erreurs) {
        // Vérification des longueurs des champs
        if (strlen($numTel) < 4 || strlen($numTel) > 10) {
            $erreurs['numTel'] = "Le numéro de téléphone doit contenir entre 4 et 10 caractères.";
        }
        if (strlen($nom) < 1 || strlen($nom) > 50) {
            $erreurs['nom'] = "Le nom doit contenir entre 1 et 50 caractères.";
        }
        if (strlen($prenom) < 1 || strlen($prenom) > 50) {
            $erreurs['prenom'] = "Le prénom doit contenir entre 1 et 50 caractères.";
        }
    
        // Vérification que le numéro de téléphone contient uniquement des chiffres
        if (!ctype_digit($numTel)) {
            $erreurs['numTel'] = "Le numéro de téléphone doit contenir uniquement des chiffres.";
        }
    
        // Vérification du login uniquement s'il a été modifié
        if ($login !== $tabAttributLogin['login']) {
            if ($this->verifLoginExiste($pdo, $login)) {
                $erreurs['login'] = "Ce login existe déjà. Veuillez en choisir un autre.";
            }
        }
    
        // Vérification des mots de passe si modifiés
        if ($mdp !== null) {
            if ($mdp !== $cmdp) {
                $erreurs['cmdp'] = "Les mots de passe ne correspondent pas.";
            }
            if (!$this->verifMdp($mdp)) {
                $erreurs['mdp'] = "Le mot de passe doit faire plus de 8 caractères et contenir un caractère spécial, par exemple : @, #, $, %, & ou *.";
            }
        }
    
    }

    function verifLogin(PDO $pdo, $login): bool {

        $sql = "SELECT COUNT(*) 
                FROM login 
                WHERE login = :login";
        $stmtVerif = $pdo->prepare($sql);
        $stmtVerif->bindParam(':login', $login);
        $stmtVerif->execute();
        $result = $stmtVerif->fetchColumn();

        return $result > 0;  // Si le nombre est supérieur à 0, le login existe déjà
    }

    // Fonction pour vérifier si le login existe déjà
    public function verifLoginExiste(PDO $pdo, $login): bool {
        $query = $pdo->prepare("SELECT COUNT(*) FROM login WHERE login = :login");
        $query->execute(['login' => $login]);
        return $query->fetchColumn() > 0;
    }
    public function verifIdType(PDO $pdo, $id_type) {

        $requeteVerifType = "SELECT COUNT(id_type) FROM type_utilisateur WHERE id_type = :id_type";
        $stmtVerif = $pdo->prepare($requeteVerifType);
        $stmtVerif->execute(['id_type' => $id_type]);
        return $stmtVerif->fetchColumn(); // Retourne le nombre d'occurrences d'id_type
    }


    public function compterEmployes(PDO $pdo): int {

        $requete = "SELECT COUNT(*) AS total FROM employe";
        $stmt = $pdo->query($requete);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Retourne uniquement la valeur numérique
        return (int)$result['total'];
    }

    public function recupAttributEmploye(PDO $pdo, $idEmploye) {
        try {
            // Récupérer les informations de la table employe
            $stmt = $pdo->prepare("SELECT nom, prenom, telephone
                                          FROM employe
                                          WHERE id_employe = :id_employe");
            $stmt->bindParam(':id_employe', $idEmploye, PDO::PARAM_INT);
            $stmt->execute();
            $resultat = $stmt->fetch(PDO::FETCH_ASSOC); // Une seule ligne attendue

            // Retourner les résultats combinés
            return $resultat;

        } catch (PDOException $e) {
            // Gestion des erreurs
            echo "Erreur lors de la récupération des attributs des employés : " . $e->getMessage();
            return null;
        }
    }

    public function recupAttributLogin(PDO $pdo, $idEmploye) {
        try{
            // Récupérer les informations de la table salle
            $stmt = $pdo->prepare("SELECT login, mdp, id_type
                                         FROM login 
                                         WHERE id_employe = :id_employe");
            $stmt->bindParam(':id_employe', $idEmploye, PDO::PARAM_INT);
            $stmt->execute();
            $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultat;
        }catch(Exception $e){
            // Gestion des erreurs
            echo "Erreur lors de la récupération des attributs des logins : " . $e->getMessage();
            return null;
        }
    }
    
    public function modifierEmployeSansMdp(PDO $pdo, $id, $nom, $prenom, $login, $numTel, $id_type) {

        // Mise à jour des informations personnelles (table employe)
        $sqlEmploye = "UPDATE employe 
                           SET nom = ?, prenom = ?, telephone = ?
                           WHERE id_employe = ?";
        $stmtEmploye = $pdo->prepare($sqlEmploye);
        $stmtEmploye->execute([$nom, $prenom, $login, $numTel]);

        // Mise à jour des informations de connexion (table login)
        $sqlLogin = "UPDATE login 
                     SET login = ?, id_type = ? 
                     WHERE id_employe = ?";
        $stmtLogin = $pdo->prepare($sqlLogin);
        $stmtLogin->execute([$login, $id_type, $id]);
    }


    function modifierEmploye(PDO $pdo, $id, $nom, $prenom, $login, $telephone, $mdp, $id_type) {
        try {
            // Début de la transaction
            $pdo->beginTransaction();

            // Construction dynamique de la requête pour les informations de connexion
            $requete = "UPDATE login 
                SET login = :login, id_type = :id_type";

            // Ajout de la mise à jour du mot de passe uniquement si $mdp n'est pas null
            if ($mdp !== null) {
                $requete .= ", mdp = :mdp";
            }

            $requete .= " WHERE id_employe = :id_employe";

            $stmt = $pdo->prepare($requete);
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':id_type', $id_type);
            $stmt->bindParam(':id_employe', $id);

            // Bind du mot de passe seulement s'il est fourni
            if ($mdp !== null) {
                $hashMdp = sha1($mdp);
                $stmt->bindParam(':mdp', $hashMdp);
            }

            $stmt->execute();

            // Deuxième requête : mise à jour des informations personnelles
            $requete = "UPDATE employe
                SET nom = :nom, prenom = :prenom, telephone = :telephone
                WHERE id_employe = :id_employe";

            $stmt = $pdo->prepare($requete);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':telephone', $telephone);
            $stmt->bindParam(':id_employe', $id);
            $stmt->execute();

            // Validation de la transaction
            $pdo->commit();

            echo "Mise à jour réussie.";
        } catch (Exception $e) {
            // Annulation de la transaction en cas d'erreur
            $pdo->rollBack();
            echo "Une erreur est survenue : " . $e->getMessage();
        }
    }

    function supprimerEmploye(PDO $pdo, $id_employe): void {

        try {
            // Démarrage de la transaction
            $pdo->beginTransaction();

            // Requête SQL pour supprimer le login
            $requeteLogin = "DELETE FROM login WHERE id_employe = :id_employe";
            $stmtLogin = $pdo->prepare($requeteLogin);
            $stmtLogin->bindParam(':id_employe', $id_employe);
            $stmtLogin->execute();

            // Requête SQL pour supprimer l'employé
            $requeteEmploye = "DELETE FROM employe WHERE id_employe = :id_employe";
            $stmtEmployer = $pdo->prepare($requeteEmploye);
            $stmtEmployer->bindParam(':id_employe', $id_employe);
            $stmtEmployer->execute();

            // Validation de la transaction
            $pdo->commit();
        } catch (Exception $e) {
            // Annulation de la transaction en cas d'erreur
            $pdo->rollBack();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    function ajouterEmploye(PDO $pdo, $nom, $prenom, $login, $telephone, $mdp, $id_type): void {

        try {
            // Démarrer une transaction
            $pdo->beginTransaction();

            // Récupérer le dernier ID employé au format 'E00000X'
            $requeteId = "SELECT id_employe 
                          FROM employe 
                          WHERE id_employe LIKE 'E%' 
                          ORDER BY id_employe DESC 
                          LIMIT 1";
            $dernierId = $pdo->query($requeteId)->fetchColumn();

            // Générer le nouvel ID
            $numero = $dernierId ? (int)substr($dernierId, 1) + 1 : 1;
            $id = 'E' . str_pad($numero, 6, '0', STR_PAD_LEFT);

            // Insérer dans la table employe
            $requeteEmploye = "INSERT INTO employe (id_employe, nom, prenom, telephone) VALUES (:id, :nom, :prenom, :telephone)";
            $stmtEmploye = $pdo->prepare($requeteEmploye);
            $stmtEmploye->execute([
                ':id' => $id,
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':telephone' => $telephone
            ]);

            // Vérifier si le type d'utilisateur existe
            if (!$this->verifIdType($pdo, $id_type)) {
                throw new Exception('Le type d\'utilisateur n\'existe pas dans la table type_utilisateur.');
            }

            // Hashage du mot de passe
            $mdpHash = sha1($mdp);

            // Insérer dans la table login
            $requeteLogin = "INSERT INTO login (login, mdp, id_type, id_employe) 
                             VALUES (:login, :mdp, :id_type, :id_employe)";
            $stmtLogin = $pdo->prepare($requeteLogin);
            $stmtLogin->execute([
                ':login' => $login,
                ':mdp' => $mdpHash,
                ':id_type' => $id_type,
                ':id_employe' => $id
            ]);

            // Valider la transaction
            $pdo->commit();

        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            $pdo->rollBack();
            throw $e; // Propager l'exception pour gestion par l'appelant
        }
    }

    function renvoyerEmployes(PDO $pdo, ?int $limite = null): array {

        $requete = "SELECT nom, prenom, login.login AS id_compte, 
                           telephone, type_utilisateur.nom_type AS type_utilisateur, 
                           employe.id_employe
                    FROM employe
                    JOIN login 
                    ON login.id_employe = employe.id_employe
                    JOIN type_utilisateur 
                    ON type_utilisateur.id_type = login.id_type
                    ORDER BY nom, prenom";

        if ($limite) {
            $requete .= " LIMIT :limite";
        }

        $stmt = $pdo->prepare($requete);

        if ($limite) {
            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

}
?>