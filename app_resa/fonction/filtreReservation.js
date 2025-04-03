// ---------------- FILTRE RESERVATION ----------------
document.addEventListener("DOMContentLoaded", function () {
    // Récupération des champs de filtre
    const filters = {
        nom: document.getElementById("nom"),
        salles: document.getElementById("salles"),
        activites: document.getElementById("activites"),
        date_debut: document.getElementById("date_debut"),
        date_fin: document.getElementById("date_fin"),
        heure_debut: document.getElementById("heure_debut"),
        heure_fin: document.getElementById("heure_fin"),
    };

    // Récupération des lignes du tableau
    const rows = document.querySelectorAll("table.table-striped tbody tr.tab-trier");

    // Ajout d'une ligne par défaut pour les résultats vides
    const tbody = document.querySelector("table.table-striped tbody");
    const noResultRow = document.createElement("tr");
    noResultRow.innerHTML = `<td colspan="8" class="text-center text-danger fw-bold">Aucune réservation trouvée.</td>`;
    noResultRow.style.display = "none"; // Cachée par défaut
    tbody.appendChild(noResultRow);

    // Récupération de l'élément compteur
    const compteur = document.querySelector(".compteur-reservation");

    // Fonction de filtrage
    function filterTable() {
        let visibleCount = 0; // Compteur des réservations visibles

        rows.forEach(row => {
            const columns = row.querySelectorAll("td.tab-trier");

            const values = {
                salles:    columns[1]?.innerText.trim().toLowerCase() || "",
                nom:       columns[2]?.innerText.trim().toLowerCase() || "",
                activites: columns[3]?.childNodes[0]?.textContent.trim().toLowerCase() || "",
                date:      columns[4]?.textContent.trim() || "",
                heure_debut: columns[5]?.textContent.trim() || "",
                heure_fin: columns[6]?.textContent.trim() || "",
            };

            const isDateInRange = (() => {
                const filterStart = filters.date_debut?.value.trim();
                const filterEnd = filters.date_fin?.value.trim();

                if (!filterStart && !filterEnd) return true;

                const rowDate = new Date(values.date.replace(/(\d{2})\/(\d{2})\/(\d{4})/, '$3-$2-$1')); // Assure que la date est formatée comme YYYY-MM-DD
                const startDate = filterStart ? new Date(filterStart) : null;
                const endDate = filterEnd ? new Date(filterEnd) : null;

                return (!startDate || rowDate >= startDate) && (!endDate || rowDate <= endDate);
            })();

            const isTimeInRange = (() => {
                const filterStartTime = filters.heure_debut?.value.trim();
                const filterEndTime = filters.heure_fin?.value.trim();

                if (!filterStartTime && !filterEndTime) return true;

                const rowStartTime = values.heure_debut;
                const rowEndTime = values.heure_fin;

                if (!rowStartTime || !rowEndTime) return false;

                const timeToMinutes = time => {
                    const [hours, minutes] = time.split(":").map(Number);
                    return hours * 60 + minutes;
                };

                const filterStartMinutes = filterStartTime ? timeToMinutes(filterStartTime) : null;
                const filterEndMinutes = filterEndTime ? timeToMinutes(filterEndTime) : null;
                const rowStartMinutes = timeToMinutes(rowStartTime);
                const rowEndMinutes = timeToMinutes(rowEndTime);

                const isStartInRange = filterStartMinutes === null || rowStartMinutes >= filterStartMinutes;
                const isEndInRange = filterEndMinutes === null || rowEndMinutes <= filterEndMinutes;

                return isStartInRange && isEndInRange;
            })();

            const visible = Object.keys(filters).every(key => {
                if (!filters[key]) return true;

                const filterValue = filters[key]?.value.trim().toLowerCase();
                if (!filterValue || (filters[key]?.tagName === "SELECT" && filterValue === filters[key]?.options[0]?.value.trim())) {
                    return true;
                }

                if (key === "salles") {
                    return values.salles === filterValue;
                }

                if (key === "date_debut" || key === "date_fin" || key === "heure_debut" || key === "heure_fin") {
                    return true; // Ces champs sont déjà gérés séparément
                }

                return values[key]?.includes(filterValue) ?? false;
            }) && isDateInRange && isTimeInRange;

            row.style.display = visible ? "" : "none";
            if (visible) visibleCount++;
        });

        // Met à jour le compteur de réservations visibles
        if (compteur) {
            compteur.textContent = `Nombre de réservations trouvée(s) : ${visibleCount}`;
        }

        // Gère l'affichage de la ligne "Aucun résultat trouvé"
        noResultRow.style.display = visibleCount === 0 ? "" : "none";
    }

    // Ajout d'écouteurs d'événements pour chaque filtre
    Object.values(filters).forEach(filter => {
        filter.addEventListener("input", filterTable);
    });

    // Bouton de réinitialisation
    const resetButton = document.querySelector('.btn-reset');
    if (resetButton) {
        resetButton.addEventListener('click', function () {
            // Réinitialisation des champs de filtre
            Object.values(filters).forEach(filter => {
                if (filter?.tagName === "SELECT") {
                    filter.selectedIndex = 0;
                } else if (filter) {
                    filter.value = "";
                }
            });
            filterTable(); // Met à jour l'affichage du tableau
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
