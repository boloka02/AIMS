
    document.getElementById("searchInput").addEventListener("keyup", function() {
        let searchValue = this.value.toLowerCase();
        document.querySelectorAll("tbody tr").forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(searchValue) ? "" : "none";
        });
    });


    document.addEventListener("DOMContentLoaded", function () {
        let currentPage = 1;
        const rowsPerPage = 5; // Show 5 tickets per page
        const tableRows = document.querySelectorAll("#ticketsTable tbody tr");
        let totalRows = tableRows.length;
        let totalPages = Math.ceil(totalRows / rowsPerPage);

        function showPage(page) {
            let start = (page - 1) * rowsPerPage;
            let end = start + rowsPerPage;

            tableRows.forEach((row, index) => {
                row.style.display = (index >= start && index < end) ? "" : "none";
            });

            document.getElementById("prevPage").parentElement.classList.toggle("disabled", page === 1);
            document.getElementById("nextPage").parentElement.classList.toggle("disabled", page === totalPages);
        }

        document.getElementById("prevPage").addEventListener("click", function (event) {
            event.preventDefault();
            if (currentPage > 1) {
                currentPage--;
                showPage(currentPage);
            }
        });

        document.getElementById("nextPage").addEventListener("click", function (event) {
            event.preventDefault();
            if (currentPage < totalPages) {
                currentPage++;
                showPage(currentPage);
            }
        });

        // Search only affects "Tickets Overview"
        document.getElementById("searchInput").addEventListener("input", function () {
            let filter = this.value.toLowerCase();
            let visibleRows = [];

            tableRows.forEach(row => {
                let text = row.innerText.toLowerCase();
                let match = text.includes(filter);
                row.style.display = match ? "" : "none";
                if (match) visibleRows.push(row);
            });

            // Recalculate pagination after search
            totalRows = visibleRows.length;
            totalPages = Math.ceil(totalRows / rowsPerPage);
            currentPage = 1; // Reset to first page
            showPage(currentPage);
        });

        // Initial setup
        showPage(currentPage);
    });

    document.addEventListener("DOMContentLoaded", function() {
        let dropArea = document.getElementById("drop-area");
        let fileInput = document.getElementById("fileInput");
    
        dropArea.addEventListener("dragover", function(e) {
            e.preventDefault();
            dropArea.classList.add("dragover");
        });
    
        dropArea.addEventListener("dragleave", function() {
            dropArea.classList.remove("dragover");
        });
    
        dropArea.addEventListener("drop", function(e) {
            e.preventDefault();
            dropArea.classList.remove("dragover");
            fileInput.files = e.dataTransfer.files;
        });
    });