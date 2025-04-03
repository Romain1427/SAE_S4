// Récupération des éléments
const activiteSelect = document.getElementById('activite');

// Champs à afficher ou masquer selon l'activité
const champ1Input = document.getElementById('champ1');
const champ2Input = document.getElementById('champ2');
const champ3Input = document.getElementById('champ3');
const champ4Input = document.getElementById('champ4');
const champ5Input = document.getElementById('champ5');

const champ1Label = document.getElementById('titreObjet');
const champ2Label = document.getElementById('titreNom');
const champ3Label = document.getElementById('titrePrenom');

// Fonction pour masquer tous les champs
function masquerTousLesChamps() {
    champ1Input.style.display = 'none';
    champ2Input.style.display = 'none';
    champ3Input.style.display = 'none';
    champ4Input.style.display = 'none';
    champ5Input.style.display = 'none';
}

// Fonction pour afficher les champs nécessaires selon l'activité sélectionnée
function afficherChampsSupplementaires() {
    // Masquer tous les champs avant d'en afficher certains
    masquerTousLesChamps();

    // Récupérer l'activité sélectionnée
    const activite = activiteSelect.value;

    // Afficher les champs correspondant à l'activité choisie
    if (activite === 'Réunion') {
        champ1Input.style.display = 'block';
        champ1Label.textContent = 'Objet de la réunion :';

    } else if (activite === 'Formation') {
        champ1Input.style.display = 'block';
        champ2Input.style.display = 'block';
        champ3Input.style.display = 'block';
        champ4Input.style.display = 'block';
        champ1Label.textContent = 'Sujet de la formation :';
        champ2Label.textContent = 'Nom du formateur :';
        champ3Label.textContent = 'Prénom du formateur :';

    } else if (activite === 'Entretien de la salle') {
        champ1Input.style.display = 'block';
        champ1Label.textContent = 'Nature de l\'entretien :';

    } else if (activite === 'Prêt' || activite === 'Location') {
        champ1Input.style.display = 'block';
        champ2Input.style.display = 'block';
        champ3Input.style.display = 'block';
        champ4Input.style.display = 'block';
        champ5Input.style.display = 'block';
        champ1Label.textContent = 'Nom de votre organisme :';
        champ2Label.textContent = 'Nom de l\'interlocuteur :';
        champ3Label.textContent = 'Prénom de l\'interlocuteur :';

    } else if (activite === 'Autre') {
        champ1Input.style.display = 'block';
        champ1Label.textContent = 'Description de votre activité :';
    }
}

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    afficherChampsSupplementaires(); // Met à jour l'affichage dès le début
});

// Mise à jour lors du changement d'activité
activiteSelect.addEventListener('change', afficherChampsSupplementaires);

// Vérification que l'heure de fin plus tard que l'heure de début
const heureDebut = document.getElementById('heureDebut');
const heureFin   = document.getElementById('heureFin');

function verifierHeures() {
    const debut = heureDebut.value;
    const fin = heureFin.value;

    if (debut && fin) {
        if (debut >= fin) {
            heureFin.setCustomValidity("L'heure de fin doit être strictement après l'heure de début.");
        } else {
            heureFin.setCustomValidity('');
        }
    }
}

//// Mise à jour lors du changement d'heure de début ou de fin
//heureDebut.addEventListener('change', verifierHeures);
//heureFin.addEventListener('change', verifierHeures);
//
//const heure_debut = <?php //= json_encode($heureDebut); ?>//;
//const heure_fin = <?php //= json_encode($heureFin); ?>//;
//
//preRemplirHeuresReservation(heure_debut,heure_fin);
//
////------------------------------------------
//
//// Passage de la variable php en javascript
//const reservationsParSalle = <?php //= json_encode($reservationsParSalle, JSON_HEX_TAG); ?>//;
//
//// Récupération des éléments
//const salleSelect =      document.getElementById('salle');
//const dateInput =        document.getElementById('date');
//const heureDebutSelect = document.getElementById('heureDebut');
//const heureFinSelect =   document.getElementById('heureFin');
//
//// Identifiez les heures de la réservation en cours
//let heureDebutActuelle = null;
//let heureFinActuelle = null;
//
//function preRemplirHeuresReservation(heureDebut, heureFin) {
//    heureDebutActuelle = heureDebut;
//    heureFinActuelle = heureFin;
//
//    heureDebutSelect.value = heureDebut;
//    heureFinSelect.value = heureFin;
//
//    // Appeler mettreAJourPlagesHoraires après avoir défini les heures actuelles
//    mettreAJourPlagesHoraires();
//}
//
//// Fonction pour griser ou supprimer les plages horaires
//function mettreAJourPlagesHoraires() {
//    const salle = salleSelect.value;
//    const date = dateInput.value;
//
//    // Réinitialiser les options pour heureDebut et heureFin
//    for (let option of heureDebutSelect.options) {
//        option.disabled = false;
//    }
//    for (let option of heureFinSelect.options) {
//        option.disabled = false;
//    }
//
//    if (salle && date && reservationsParSalle[salle] && reservationsParSalle[salle][date]) {
//        const reservations = reservationsParSalle[salle][date];
//
//        // Désactive les créneaux dans heureDebut qui chevauchent une réservation
//        for (let option of heureDebutSelect.options) {
//            const debutValue = option.value;
//            if (reservations.some(reservation => debutValue >= reservation.heureDebut && debutValue < reservation.heureFin)) {
//                option.disabled = true;
//            }
//        }
//
//        // Désactive les créneaux dans heureFin qui chevauchent une réservation
//        function mettreAJourHeureFin() {
//            for (let option of heureFinSelect.options) {
//                option.disabled = false; // Réinitialiser avant recalcul
//            }
//
//            const debutValue = heureDebutSelect.value;
//
//            for (let option of heureFinSelect.options) {
//                const finValue = option.value;
//
//                // Désactive si fin <= début sélectionné
//                if (finValue <= debutValue) {
//                    option.disabled = true;
//                }
//
//                // Désactive si le créneau chevauche une réservation
//                if (reservations.some(reservation => debutValue < reservation.heureFin && finValue > reservation.heureDebut)) {
//                    option.disabled = true;
//                }
//            }
//        }
//
//        // Appeler mettreAJourHeureFin à chaque changement d'heureDebut
//        heureDebutSelect.addEventListener('change', mettreAJourHeureFin);
//
//        // Appeler une fois pour synchroniser
//        mettreAJourHeureFin();
//    }
//}
//
//// Mettre à jour les plages horaires lorsque la salle ou la date change
//salleSelect.addEventListener('change', mettreAJourPlagesHoraires);
//dateInput.addEventListener('change', mettreAJourPlagesHoraires);