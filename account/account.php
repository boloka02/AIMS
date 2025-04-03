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
    <link rel="icon" type="image/jpeg" href="https://adongroup.com.au/wp-content/uploads/2020/12/aog-favicon-192px.svg">
    <title>ADON PH</title>
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
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody id="inventoryTable">
                <?php 
                    include '../db_connection.php';

                    $query = "SELECT id, idnumber, name, email, role FROM user";
                    $result = mysqli_query($conn, $query);
                    $rows = [];

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $rows[] = "<tr class='inventory-row' data-id='{$row['id']}'>
                                <td>{$row['idnumber']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['email']}</td>
                                <td>
                                    <select class='role-dropdown form-select form-select-sm' data-id='{$row['id']}'>
                                        <option value='Admin' " . ($row['role'] == 'Admin' ? 'selected' : '') . ">Admin</option>
                                        <option value='Employee' " . ($row['role'] == 'Employee' ? 'selected' : '') . ">Employee</option>
                                         <option value='Dev-Support' " . ($row['role'] == 'Dev-Support' ? 'selected' : '') . ">Dev-Support</option>
                                    </select>
                                </td>
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
                        $rows[] = "<tr><td colspan='5'>No Employees Found</td></tr>";
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
        let tableRows = document.querySelectorAll('#inventoryTable tr');
        let totalRows = tableRows.length;

        function showPage(page) {
            let start = (page - 1) * rowsPerPage;
            let end = start + rowsPerPage;

            tableRows.forEach((row, index) => {
                row.style.display = (index >= start && index < end) ? '' : 'none';
            });

            document.getElementById('prevPage').parentElement.classList.toggle('disabled', page === 1);
            document.getElementById('nextPage').parentElement.classList.toggle('disabled', page === Math.ceil(totalRows / rowsPerPage));
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
            if (currentPage < Math.ceil(totalRows / rowsPerPage)) {
                currentPage++;
                showPage(currentPage);
            }
        });

        // Search logic
        document.getElementById('searchInput').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let filteredRows = Array.from(tableRows).filter(row => row.innerText.toLowerCase().includes(filter));
            
            // Update the table rows and reset pagination
            totalRows = filteredRows.length;
            document.getElementById('inventoryTable').innerHTML = filteredRows.length > 0 ? filteredRows.map(row => row.outerHTML).join('') : '<tr><td colspan="5">No results found</td></tr>';

            showPage(1); // Show the first page after filter
        });

        // Initial setup
        showPage(currentPage);

        // Handle role change via AJAX
        document.getElementById('inventoryTable').addEventListener('change', function(event) {
            if (event.target && event.target.classList.contains('role-dropdown')) {
                const newRole = event.target.value;
                const employeeId = event.target.getAttribute('data-id');

                // AJAX request to update the role
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'update_role.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        alert('Role updated successfully!');
                    } else {
                        alert('Error updating role');
                    }
                };
                xhr.send(`id=${employeeId}&role=${newRole}`);
            }
        });

        // Delete functionality with event delegation
        document.getElementById('inventoryTable').addEventListener('click', function(event) {
            if (event.target && event.target.classList.contains('delete-btn')) {
                event.preventDefault();
                
                const employeeId = event.target.getAttribute('data-id');
                
                if (confirm('Are you sure you want to delete this employee?')) {
                    const xhr = new XMLHttpRequest();
                    xhr.open('GET', `delete_employee.php?id=${employeeId}`, true);
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            alert('Employee deleted successfully!');
                            const row = event.target.closest('tr');
                            row.remove();
                        } else {
                            alert('Error deleting employee');
                        }
                    };
                    xhr.send();
                }
            }
        });
    </script>
</body>

</html>
