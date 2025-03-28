//filter
document.addEventListener("DOMContentLoaded", function () {
    const tableBody = document.getElementById("tableBody");
    const allRows = Array.from(tableBody.getElementsByTagName("tr"));
    const searchInput = document.getElementById("tableSearch");
    const dateAssignRadios = document.querySelectorAll("input[name='dateAssignFilter']");
    const paginationControls = document.querySelector(".pagination");
    const prevPage = document.getElementById("prevPage");
    const nextPage = document.getElementById("nextPage");
    const pageNumber = document.getElementById("pageNumber");

    const rowsPerPage = 5;
    let currentPage = 1;

    function getFilteredRows() {
        let selectedDateRange = document.querySelector("input[name='dateAssignFilter']:checked")?.value || "anytime";
        let searchQuery = searchInput.value.toLowerCase();
        let today = new Date();

        return allRows.filter(row => {
            let rowText = row.textContent.toLowerCase();
            let dateAssignText = row.cells[11]?.textContent.trim(); // "Date Assign" column

            let matchesSearch = searchQuery === "" || rowText.includes(searchQuery);

            // Date Assign Filtering
            let matchesDate = true;
            if (selectedDateRange !== "anytime") {
                let rowDate = new Date(dateAssignText);
                if (isNaN(rowDate)) return false; // Skip invalid dates

                let daysAgo = parseInt(selectedDateRange);
                let pastDate = new Date();
                pastDate.setDate(today.getDate() - daysAgo);
                matchesDate = rowDate >= pastDate;
            }

            return matchesSearch && matchesDate;
        });
    }

    function showPage(page) {
        let filteredRows = getFilteredRows();
        let totalPages = Math.ceil(filteredRows.length / rowsPerPage);

        if (filteredRows.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="10" style="text-align:center;">No results found</td></tr>`;
            paginationControls.style.display = "none";
            return;
        }

        if (page < 1) page = 1;
        if (page > totalPages) page = totalPages;

        let start = (page - 1) * rowsPerPage;
        let end = start + rowsPerPage;

        allRows.forEach(row => (row.style.display = "none"));
        filteredRows.forEach((row, index) => {
            row.style.display = index >= start && index < end ? "" : "none";
        });

        pageNumber.textContent = `Page ${page} of ${totalPages}`;
        prevPage.disabled = page === 1;
        nextPage.disabled = page === totalPages;

        paginationControls.style.display = filteredRows.length > rowsPerPage ? "flex" : "none";
        currentPage = page;
    }

    function updateTable() {
        let filteredRows = getFilteredRows();

        if (searchInput.value.trim() === "" && !document.querySelector("input[name='dateAssignFilter']:checked")) {
            allRows.forEach(row => (row.style.display = "")); // Show all rows
            paginationControls.style.display = "none"; // Hide pagination
            return;
        }

        if (filteredRows.length <= rowsPerPage) {
            allRows.forEach(row => (row.style.display = "none"));
            filteredRows.forEach(row => (row.style.display = ""));
            paginationControls.style.display = "none";
        } else {
            paginationControls.style.display = "flex";
            showPage(1);
        }
    }

    prevPage.addEventListener("click", function () {
        if (currentPage > 1) showPage(currentPage - 1);
    });

    nextPage.addEventListener("click", function () {
        let totalPages = Math.ceil(getFilteredRows().length / rowsPerPage);
        if (currentPage < totalPages) showPage(currentPage + 1);
    });

    searchInput.addEventListener("input", updateTable);
    dateAssignRadios.forEach(radio => radio.addEventListener("change", updateTable));

    showPage(currentPage);
});

//delete

