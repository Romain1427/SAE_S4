// ---------------- FILTRE RESERVATION ----------------
document.addEventListener("DOMContentLoaded", function () {
    // Récupération des éléments <select>
    const filters = {
        nom: document.getElementById("nom"),
        capacite: document.getElementById("capacite"),
        videoproj: document.getElementById("videoproj"),
        grandEcran: document.getElementById("grandEcran"),
        ordinateur: document.getElementById("nbrOrdi"),
        logiciel: document.getElementById("logiciel"),
        imprimante: document.getElementById("imprimante"),
    };

    // Récupération de toutes les lignes du tableau
    const rows = document.querySelectorAll("table.table-striped tbody tr");

    // Ajout d'une ligne par défaut pour les résultats vides
    const tbody = document.querySelector("table.table-striped tbody");
    const noResultRow = document.createElement("tr");
    noResultRow.innerHTML = `<td colspan="10" class="text-center text-danger fst-italic">Aucune salle trouvée.</td>`;
    noResultRow.style.display = "none"; // Cachée par défaut
    tbody.appendChild(noResultRow);

    // Récupération de l'élément compteur
    const compteur = document.querySelector(".compteur-salle");

    // Fonction de filtrage
    function filterTable() {
        let visibleCount = 0; // Compteur de salles visibles

        rows.forEach(row => {
            const columns = row.getElementsByTagName("td");

            const values = {
                nom: (columns[1]?.textContent || "").toLowerCase(),
                capacite: columns[2]?.textContent.trim() || "",
                videoproj: (columns[3]?.textContent.trim() || "").toLowerCase(),
                grandEcran: (columns[4]?.textContent.trim() || "").toLowerCase(),
                ordinateur: columns[5]?.textContent.trim() || "",
                logiciel: (columns[7]?.textContent.trim() || "").toLowerCase(),
                imprimante: (columns[8]?.textContent.trim() || "").toLowerCase(),
            };

            const visible = Object.keys(filters).every(key => {
                const filterValue = filters[key].value.trim().toLowerCase();

                // Comparaison stricte pour les nombres
                if (key === "capacite" || key === "ordinateur") {
                    return filterValue === "" || parseInt(values[key]) === parseInt(filterValue);
                }

                // Comparaison pour les autres champs
                return filterValue === "" || values[key].includes(filterValue);
            });

            row.style.display = visible ? "" : "none";
            if (visible) {
                visibleCount++; // Incrémente uniquement si la ligne est visible
            }
        });

        // Met à jour le compteur de salles visibles
        if (compteur) {
            compteur.textContent = `Nombre de salle(s) trouvée(s) : ${visibleCount}`;
        }

        // Gère l'affichage de la ligne "Aucune salle trouvée"
        noResultRow.style.display = visibleCount === 0 ? "" : "none";
    }

    // Ajout des écouteurs d'événements
    Object.values(filters).forEach(filter => {
        filter.addEventListener("change", filterTable);
    });

    // Fonction pour réinitialiser les filtres
    const resetButton = document.querySelector('.btn-reset');
    if (resetButton) {
        resetButton.addEventListener('click', function () {
            // Réinitialisation des filtres
            Object.values(filters).forEach(filter => {
                filter.value = ""; // Réinitialise les filtres à leur valeur par défaut
            });
            filterTable(); // Applique les filtres réinitialisés
        });
    }

    // Initialisation du compteur et du tableau
    filterTable();
});

/* ---------------- Boutons remonter en haut ---------------- */
var boutonScroll = document.getElementById("scrollToTopBtn");

// Afficher le bouton après avoir fait défiler 100px
window.onscroll = function() {
    if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
        boutonScroll.style.display = "block";
    } else {
        boutonScroll.style.display = "none";
    }
};

// Lorsque l'utilisateur clique sur le bouton, faire défiler la page vers le haut
boutonScroll.onclick = function() {
    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });
};

