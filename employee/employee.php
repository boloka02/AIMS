<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <title>Inventory</title>
    <style>
        .search-box {
            max-width: 200px;
        }
    </style>
</head>

<body>
    <?php include "../sidebar/sidebar.php"; ?>

    <div class="container mt-3">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Employee</h3>
            <div class="d-flex">
                <input type="text" id="searchInput" class="form-control search-box me-2" placeholder="Search...">
                <a href="/mis-v6.1/employee/add_employee.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Employee</a>
            </div>
        </div>

        <table class="table mt-3">
            <thead>
                <tr>
                    <th scope="col">ID No.</th>
                    <th scope="col">Name</th>
                    <th scope="col">Position</th>
                    <th scope="col">Department</th>
                    <th scope="col">Assign Unit</th>
                    <th scope="col">Status</th>
                    <th scope="col">Date Hired</th>
                    <th scope="col">Total Asset Value</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody id="inventoryTable">
                <?php 
                    include '../db_connection.php';

                    $query = "SELECT id, idnumber, name, position, department, adonwork_no, status,
                            date_hired, total_asset_value FROM employee";
                    $result = mysqli_query($conn, $query);
                    $rows = [];

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $rows[] = "<tr class='inventory-row'>
                                <td>{$row['idnumber']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['position']}</td>
                                <td>{$row['department']}</td>
                                <td>{$row['adonwork_no']}</td>
                                <td>{$row['status']}</td>
                                <td>{$row['date_hired']}</td>
                                <td>{$row['total_asset_value']}</td>
                                <td>
                                    <div class='dropdown'>
                                        <button class='btn btn-light btn-sm' type='button' id='dropdownMenu{$row['id']}' data-bs-toggle='dropdown' aria-expanded='false'>
                                            <i class='bi bi-three-dots-vertical'></i>
                                        </button>
                                        <ul class='dropdown-menu' aria-labelledby='dropdownMenu{$row['id']}' >
                                            <li><h6 class='dropdown-header'>Actions</h6></li>
                                            <li><a class='dropdown-item text-danger delete-btn' data-id='{$row['id']}' href='#'><i class='bi bi-trash'></i> Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>";
                        }
                    } else {
                        $rows[] = "<tr><td colspan='9'>No Assets Found</td></tr>";
                    }

                    mysqli_close($conn);
                    echo implode("", $rows);
                ?>
            </tbody>
        </table>

        <!-- Pagination Controls -->
        <ul class="pagination" id="paginationControls">
            <li class="page-item disabled"><a class="page-link" href="#" id="prevPage">Previous</a></li>
            <li class="page-item"><a class="page-link" href="#" id="nextPage">Next</a></li>
        </ul>
    </div>

    <script>
        // Pagination logic
        let currentPage = 1;
        const rowsPerPage = 10;
        const tableRows = document.querySelectorAll('#inventoryTable tr');
        const totalRows = tableRows.length;
        const totalPages = Math.ceil(totalRows / rowsPerPage);

        function showPage(page) {
            let start = (page - 1) * rowsPerPage;
            let end = start + rowsPerPage;

            tableRows.forEach((row, index) => {
                row.style.display = (index >= start && index < end) ? '' : 'none';
            });

            document.getElementById('prevPage').parentElement.classList.toggle('disabled', page === 1);
            document.getElementById('nextPage').parentElement.classList.toggle('disabled', page === totalPages);
        }

        document.getElementById('prevPage').addEventListener('click', function(event) {
            event.preventDefault();
            if (currentPage > 1) {
                currentPage--;
                showPage(currentPage);
            }
        });

        document.getElementById('nextPage').addEventListener('click', function(event) {
            event.preventDefault();
            if (currentPage < totalPages) {
                currentPage++;
                showPage(currentPage);
            }
        });

        // Search logic
        document.getElementById('searchInput').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            tableRows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });

            // Recalculate pagination after search
            totalRows = document.querySelectorAll('#inventoryTable tr:visible').length;
            const totalPagesAfterSearch = Math.ceil(totalRows / rowsPerPage);
            showPage(1); // Show the first page after search filter
        });

        // Initial setup
        showPage(currentPage);
    </script>
<script>
    // Delete functionality
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault();
        
        // Get employee ID from data-id attribute
        const employeeId = this.getAttribute('data-id');
        
        // Confirm deletion
        if (confirm('Are you sure you want to delete this employee?')) {
            // Send AJAX request to delete employee
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `delete_employee.php?id=${employeeId}`, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert('Employee deleted successfully!');
                    // Remove the row from the table
                    const row = button.closest('tr');
                    row.remove();
                } else {
                    alert('Error deleting employee');
                }
            };
            xhr.send();
        }
    });
});

</script>
</body>
</html>
