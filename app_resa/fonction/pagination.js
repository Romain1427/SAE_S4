// ---------------- PAGINATION ----------------
/*




              CODE NON UTILISÉ
              FUTUR Mise à jour :
              Utiliser des filtres en JS ou en AJAX
              Limiter le nombre de lignes par requete




*/
const paginationContainer = document.querySelector("#pagination");
const rowsPerPageOptions = document.querySelectorAll(".rows-per-page");
let rowsPerPage = 10; // Par défaut, 10 lignes par page
let currentPage = 1;
let filteredRows = [];

function generatePageLinks(totalPages, currentPage) {
    const pages = [];

    pages.push(1);

    if (currentPage > 3) {
        pages.push('...');
    }

    for (let i = Math.max(2, currentPage - 2); i <= Math.min(totalPages - 1, currentPage + 2); i++) {
        pages.push(i);
    }

    if (currentPage < totalPages - 2) {
        pages.push('...');
    }

    if (totalPages > 1) {
        pages.push(totalPages);
    }

    return pages;
}

function renderTable() {
    filteredRows = filterTable(); // Applique le filtrage et récupère les lignes filtrées
    const totalRows = filteredRows.length;
    const totalPages = Math.ceil(totalRows / rowsPerPage);

    const startRow = (currentPage - 1) * rowsPerPage;
    const endRow = startRow + rowsPerPage;

    filteredRows.forEach((row, index) => {
        row.style.display = index >= startRow && index < endRow ? "" : "none";
    });

    paginationContainer.innerHTML = "";

    const pageLinks = generatePageLinks(totalPages, currentPage);

    const firstButton = document.createElement("button");
    firstButton.textContent = "<<";
    firstButton.className = currentPage === 1
        ? "btn-pagination-disabled rounded"
        : "btn-pagination rounded";
    if (currentPage !== 1) {
        firstButton.addEventListener("click", () => {
            currentPage = 1;
            renderTable();
        });
    }
    paginationContainer.appendChild(firstButton);

    const prevButton = document.createElement("button");
    prevButton.textContent = "<";
    prevButton.className = currentPage === 1
        ? "btn-pagination-disabled rounded"
        : "btn-pagination rounded";
    if (currentPage !== 1) {
        prevButton.addEventListener("click", () => {
            currentPage -= 1;
            renderTable();
        });
    }
    paginationContainer.appendChild(prevButton);

    pageLinks.forEach(link => {
        const button = document.createElement("button");
        button.textContent = link;
        if (link === "...") {
            button.className = "btn-pagination btn-points disabled rounded";
        } else {
            button.className = `btn-pagination rounded ${link === currentPage ? "active" : ""}`;
            button.addEventListener("click", () => {
                currentPage = link;
                renderTable();
            });
        }
        paginationContainer.appendChild(button);
    });

    const nextButton = document.createElement("button");
    nextButton.textContent = ">";
    nextButton.className = currentPage === totalPages
        ? "btn-pagination-disabled rounded"
        : "btn-pagination rounded";
    if (currentPage !== totalPages) {
        nextButton.addEventListener("click", () => {
            currentPage += 1;
            renderTable();
        });
    }
    paginationContainer.appendChild(nextButton);

    const lastButton = document.createElement("button");
    lastButton.textContent = ">>";
    lastButton.className = currentPage === totalPages
        ? "btn-pagination-disabled rounded"
        : "btn-pagination rounded";
    if (currentPage !== totalPages) {
        lastButton.addEventListener("click", () => {
            currentPage = totalPages;
            renderTable();
        });
    }
    paginationContainer.appendChild(lastButton);
}

rowsPerPageOptions.forEach(option => {
    option.addEventListener("click", function () {
        rowsPerPage = parseInt(this.dataset.rows, 10);
        currentPage = 1; // Revenir à la première page
        renderTable();
    });
});

// Ajouter les événements de changement pour les filtres
Object.values(filters).forEach(filter => {
    filter.addEventListener("change", renderTable);
});

renderTable();