// Récupération des données PHP converties en JSON
const dataElement = document.getElementById("data-reservations");
const reservationsParSalle = JSON.parse(dataElement.textContent);

// Récupération des éléments
const activiteSelect = document.getElementById('activite');
const objetInput = document.getElementById('ligneObjet');
const nomInput = document.getElementById('ligneNom');
const prenomInput = document.getElementById('lignePrenom');
const numInput = document.getElementById('ligneNum');
const precisionInput = document.getElementById('lignePrecision');
const objetLabel = document.getElementById('titreObjet');
const nomLabel = document.getElementById('titreNom');
const prenomLabel = document.getElementById('titrePrenom');

// Initialisation de l'affichage
objetInput.style.display = 'none';
nomInput.style.display = 'none';
prenomInput.style.display = 'none';
numInput.style.display = 'none';
precisionInput.style.display = 'none';

// Apparition de certain champ à remplir selon l'activité sélectionnée
function afficherChampsSupplementaires() {
    const activite = activiteSelect.value;

    // Initialisation de l'affichage
    objetInput.style.display = 'none';
    nomInput.style.display = 'none';
    prenomInput.style.display = 'none';
    numInput.style.display = 'none';
    precisionInput.style.display = 'none';

    if (activite === 'Réunion') {
        objetInput.style.display = 'block';
        objetLabel.textContent = 'Objet de la réunion :';

    } else if (activite === 'Formation') {
        objetInput.style.display = 'block';
        nomInput.style.display = 'block';
        prenomInput.style.display = 'block';
        numInput.style.display = 'block';
        objetLabel.textContent = 'Sujet de la formation :';
        nomLabel.textContent = 'Nom du formateur :';
        prenomLabel.textContent = 'Prénom du formateur :';

    } else if (activite === 'Entretien de la salle') {
        objetInput.style.display = 'block';
        objetLabel.textContent = 'Nature de l\'entretien :';

    } else if (activite === 'Prêt' || activite === 'Location') {
        objetInput.style.display = 'block';
        nomInput.style.display = 'block';
        prenomInput.style.display = 'block';
        numInput.style.display = 'block';
        precisionInput.style.display = 'block';
        objetLabel.textContent = 'Nom de votre organisme :';
        nomLabel.textContent = 'Nom de l\'interlocuteur :';
        prenomLabel.textContent = 'Prénom de l\'interlocuteur :';

    } else if (activite === 'Autre') {
        objetInput.style.display = 'block';
        objetLabel.textContent = 'Description de votre activité :';
    }
}

activiteSelect.addEventListener('change', afficherChampsSupplementaires);

// Vérification que l'heure de fin est plus tard que l'heure de début
const heureDebut = document.getElementById('heureDebut');
const heureFin = document.getElementById('heureFin');

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

heureDebut.addEventListener('change', verifierHeures);
heureFin.addEventListener('change', verifierHeures);

// Griser ou supprimer les plages horaires
const salleSelect = document.getElementById('salle');
const dateInput = document.getElementById('date');
const heureDebutSelect = document.getElementById('heureDebut');
const heureFinSelect = document.getElementById('heureFin');

function mettreAJourPlagesHoraires() {
    const salle = salleSelect.value;
    const date = dateInput.value;

    // Réinitialiser les options pour heureDebut et heureFin
    for (let option of heureDebutSelect.options) {
        option.disabled = false;
    }
    for (let option of heureFinSelect.options) {
        option.disabled = false;
    }

    if (salle && date && reservationsParSalle[salle] && reservationsParSalle[salle][date]) {
        const reservations = reservationsParSalle[salle][date];

        // Désactive les créneaux dans heureDebut qui chevauchent une réservation
        for (let option of heureDebutSelect.options) {
            const debutValue = option.value;
            if (reservations.some(reservation => debutValue >= reservation.heureDebut && debutValue < reservation.heureFin)) {
                option.disabled = true;
            }
        }

        // Désactive les créneaux dans heureFin qui chevauchent une réservation
        function mettreAJourHeureFin() {
            for (let option of heureFinSelect.options) {
                option.disabled = false; // Réinitialiser avant recalcul
            }

            const debutValue = heureDebutSelect.value;

            for (let option of heureFinSelect.options) {
                const finValue = option.value;

                // Désactive si fin <= début sélectionné
                if (finValue <= debutValue) {
                    option.disabled = true;
                }

                // Désactive si le créneau chevauche une réservation
                if (reservations.some(reservation => debutValue < reservation.heureFin && finValue > reservation.heureDebut)) {
                    option.disabled = true;
                }
            }
        }

        // Appeler mettreAJourHeureFin à chaque changement d'heureDebut
        heureDebutSelect.addEventListener('change', mettreAJourHeureFin);

        // Appeler une fois pour synchroniser
        mettreAJourHeureFin();
    }
}

// Mettre à jour les plages horaires lorsque la salle ou la date change
salleSelect.addEventListener('change', mettreAJourPlagesHoraires);
dateInput.addEventListener('change', mettreAJourPlagesHoraires);
