<?php
    namespace fonction;

    use PDO;
    use ZipArchive;
    require "liaisonBD.php";
    $pdo = connecteBD();

    // Récupération des données depuis la base de données
    function recupererDonnees($table) {
        global $pdo;

        // Requête pour récupérer toutes les données de la table
        $requete = "SELECT * FROM $table";
        $resultat = $pdo->query($requete);
        $donnees = $resultat->fetchAll(PDO::FETCH_ASSOC);

        // Vérifie quelle table est récupérée et applique le format nécessaire
        switch($table) {
            case 'reservation':
                foreach ($donnees as &$ligne) {
                    // Formate l'ID des salles à 8 chiffres
                    $ligne['id_salle'] = str_pad($ligne['id_salle'], 8, '0', STR_PAD_LEFT);

                    // Formate la date en jj/mm/aaaa
                    $ligne['date_reservation'] = date("d/m/Y", strtotime($ligne['date_reservation']));

                    // Formate l'heure en HHhmm (sans les secondes)
                    $ligne['heure_debut'] = str_replace(':', 'h', date("H:i", strtotime($ligne['heure_debut'])));
                    $ligne['heure_fin']   = str_replace(':', 'h', date("H:i", strtotime($ligne['heure_fin'])));

                    // Récupére les détails de l'activité associée
                    $id_activite = $ligne['id_activite'];
                    $requete_activite = "SELECT nom_activite FROM activite WHERE id_activite = ?";
                    $stmt_activite = $pdo->prepare($requete_activite);
                    $stmt_activite->execute([$id_activite]);
                    $activite = $stmt_activite->fetch(PDO::FETCH_ASSOC);
                    $ligne['id_activite'] = $activite['nom_activite']; // Met le nom de l'activité au lieu de la clé étrangère pour respecter le format CSV

                    // Récupérer les détails des activités spécifiques
                    switch ($activite['nom_activite']) {
                        case 'Réunion':
                            $requete_details = "SELECT objet FROM reservation_reunion WHERE id_reservation = ?";
                            break;
                        case 'Formation':
                            $requete_details = "SELECT sujet, nom_formateur, prenom_formateur, num_tel_formateur FROM reservation_formation WHERE id_reservation = ?";
                            break;
                        case 'Entretien de la salle':
                            $requete_details = "SELECT nature FROM reservation_entretien WHERE id_reservation = ?";
                            break;
                        case 'Autre':
                            $requete_details = "SELECT description FROM reservation_autre WHERE id_reservation = ?";
                            break;
                        case 'Location' || 'Prét':
                            $requete_details = "SELECT nom_organisme, nom_interlocuteur, prenom_interlocuteur, num_tel_interlocuteur, type_activite FROM reservation_pret_louer WHERE id_reservation = ?";
                            break;
                    }

                    // Exécute la requête pour récupérer les informations supplémentaires à la réservation
                    $stmt = $pdo->prepare($requete_details);
                    $stmt->execute([$ligne['id_reservation']]);
                    $details = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    // Ajoute les informations supplémentaires à chaque réservation
                    switch ($activite['nom_activite']) {
                        case 'Réunion':
                            $ligne['club_gym'] = $details['objet'];
                            break;
                        case 'Formation':
                            $ligne['sujet'] = $details['sujet'];
                            $ligne['nom_formateur'] = $details['nom_formateur'];
                            $ligne['prenom_formateur'] = $details['prenom_formateur'];
                            $ligne['num_tel_formateur'] = (string) $details['num_tel_formateur'];
                            break;
                        case 'Entretien de la salle':
                            $ligne['nature'] = $details['nature'];
                            break;
                        case 'Autre':
                            $ligne['description'] = $details['description'];
                            break;
                        case 'Location' || 'Prét':
                            $ligne['nom_organisme'] = $details['nom_organisme'];
                            $ligne['nom_interlocuteur'] = $details['nom_interlocuteur'];
                            $ligne['prenom_interlocuteur'] = $details['prenom_interlocuteur'];
                            $ligne['num_tel_interlocuteur'] = (string) $details['num_tel_interlocuteur'];
                            $ligne['type_activite'] = $details['type_activite'];
                            break;
                    }
                }
                break;

            case 'salle':
                // Convertit les booléens pour videoproj, ecranXXL, imprimante
                foreach ($donnees as &$ligne) {
                    $ligne['videoproj'] =  ($ligne['videoproj'] == 1) ? 'oui' : 'non';
                    $ligne['ecran_xxl'] =  ($ligne['ecran_xxl'] == 1) ? 'oui' : 'non';
                    $ligne['imprimante'] = ($ligne['imprimante'] == 1) ? 'oui' : 'non';

                    // Formate l'ID des salles à 8 chiffres
                    $ligne['id_salle'] = str_pad($ligne['id_salle'], 8, '0', STR_PAD_LEFT);
                }
                break;
        }

        $maxColonnes = count($donnees[0]);

        foreach ($donnees as &$ligne) {
            while (count($ligne) < $maxColonnes) {
                $ligne[] = ''; // Ajout de colonnes vides pour le format CSV
            }
        }

        return $donnees;
    }

    // Fonction pour générer un fichier CSV à partir de données
    function genererCSV($nomFichier, $colonnes, $donnees) : void {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $nomFichier . '"');
        $fichier = fopen('php://output', 'w');
        if ($fichier) {
            // Ajouter les colonnes au fichier manuellement
            fwrite($fichier, implode(';', $colonnes) . "\n");
            // Ajouter les données
            foreach ($donnees as $ligne) {
                // Formater la ligne manuellement
                fwrite($fichier, implode(';', $ligne) . "\n");
            }
            fclose($fichier);
        }
        exit();
    }

    function genererCSVString($colonnes, $donnees) {
        // Utilisation d'un tampon de sortie pour capturer le contenu CSV
        ob_start();
        $fichier = fopen('php://output', 'w');

        // Écrire les en-têtes
        fwrite($fichier, implode(';', $colonnes) . "\n");

        // Écrire les lignes de données
        foreach ($donnees as $ligne) {
            fwrite($fichier, implode(';', $ligne) . "\n");
        }

        fclose($fichier);

        // Retourner le contenu CSV sous forme de chaîne
        return ob_get_clean();
    }

    function genererZip($nomZip, $fichiers) {
        $zipTemp = tempnam(sys_get_temp_dir(), 'zip'); // Crée un fichier temporaire
        $zip = new ZipArchive();

        if ($zip->open($zipTemp, ZipArchive::CREATE) === TRUE) {
            foreach ($fichiers as $nomFichier => $contenu) {
                $zip->addFromString($nomFichier, $contenu);
            }
            $zip->close();

            // Envoyer le fichier ZIP à l'utilisateur
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $nomZip . '"');
            header('Content-Length: ' . filesize($zipTemp));

            // Lire le fichier temporaire et l'envoyer à l'utilisateur
            readfile($zipTemp);
            unlink($zipTemp); // Supprimer le fichier temporaire
        }
        exit();
    }
?>