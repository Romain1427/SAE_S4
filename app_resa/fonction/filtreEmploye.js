// ---------------- FILTRE EMPLOYE ----------------
document.addEventListener("DOMContentLoaded", function () {
    // Récupération des champs de filtre
    const filters = {
        nom: document.getElementById("nom"),
        prenom: document.getElementById("prenom"),
        compte: document.getElementById("compte"),
        telephone: document.getElementById("telephone")
    };

    // Récupération des lignes du tableau
    const rows = document.querySelectorAll("table.table-striped tbody tr");

    // Ajout d'une ligne par défaut pour les résultats vides
    const tbody = document.querySelector("table.table-striped tbody");
    const noResultRow = document.createElement("tr");
    noResultRow.innerHTML = `<td colspan="5" class="text-center text-danger fst-italic">Aucun compte trouvé.</td>`;
    noResultRow.style.display = "none"; // Cachée par défaut
    tbody.appendChild(noResultRow);

    // Récupération de l'élément compteur
    const compteur = document.querySelector(".compteur-employe");

    // Fonction de filtrage
    function filterTable() {
        let visibleCount = 0; // Compteur d'employés visibles

        rows.forEach(row => {
            const columns = row.getElementsByTagName("td");
            const values = {
                nom: columns[0]?.textContent.toLowerCase() || "",
                prenom: columns[1]?.textContent.toLowerCase() || "",
                compte: columns[2]?.textContent.toLowerCase() || "",
                telephone: columns[3]?.textContent.toLowerCase() || ""
            };

            // Vérifie si toutes les conditions de filtre sont remplies
            const visible = Object.keys(filters).every(key => {
                const filterValue = filters[key].value.trim().toLowerCase();
                return filterValue === "" || values[key].includes(filterValue);
            });

            row.style.display = visible ? "" : "none";
            if (visible) visibleCount++;
        });

        // Met à jour le compteur d'employés visibles
        if (compteur) {
            compteur.textContent = `Nombre de comptes employé trouvé(s) : ${visibleCount}`;
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
                filter.value = "";
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
