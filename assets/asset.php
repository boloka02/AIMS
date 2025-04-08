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

        .pagination {
            justify-content: center;
        }
    </style>
</head>

<body>
    <?php include "../sidebar/sidebar.php"; ?>

    <div class="container mt-3">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Inventory</h3>
            <div class="d-flex">
                <input type="text" id="searchInput" class="form-control search-box me-2" placeholder="Search...">
                <a href="/AIMS/assets/add_assign.php" class="btn btn-primary"><i class="fas fa-plus"></i> Assign Asset</a>
            </div>
        </div>

        <table class="table mt-3">
            <thead>
                <tr>
                    <th scope="col">PPE No.</th>
                    <th scope="col">Assign To:</th>
                    <th scope="col">Date Assign:</th>
                    <th scope="col">Location:</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody id="inventoryTable">
            <?php
include '../db_connection.php';

// Fetch total records count
$queryCount = "SELECT COUNT(*) FROM asset";
$resultCount = mysqli_query($conn, $queryCount);
$totalRecords = mysqli_fetch_row($resultCount)[0];

// Set pagination limit
$limit = 10; // Rows per page
$totalPages = ceil($totalRecords / $limit);

// Get the current page from the URL or set default to page 1
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Ensure integer
$offset = ($currentPage - 1) * $limit;

// Fetch the records for the current page
$query = "SELECT id, adonwork_no, employee, assign_date, location FROM asset LIMIT $offset, $limit";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr class='inventory-row'>";
        echo "<td>" . htmlspecialchars($row['adonwork_no']) . "</td>";
        echo "<td>" . htmlspecialchars($row['employee']) . "</td>";
        echo "<td>" . htmlspecialchars($row['assign_date']) . "</td>";
        echo "<td>" . htmlspecialchars($row['location']) . "</td>";
        echo "<td>
                <div class='dropdown'>
                    <button class='btn btn-light btn-sm' type='button' id='dropdownMenu" . htmlspecialchars($row['id']) . "' data-bs-toggle='dropdown' aria-expanded='false'>
                        <i class='bi bi-three-dots-vertical'></i>
                    </button>
                    <ul class='dropdown-menu' aria-labelledby='dropdownMenu" . htmlspecialchars($row['id']) . "'>
                        <li><h6 class='dropdown-header'>Actions</h6></li>
                        <li><a class='dropdown-item' href='../assets/transfer.php?id=" . htmlspecialchars($row['id']) . "'><i class='bi bi-arrow-right'></i> Transfer</a></li>
                        <li><a class='dropdown-item' href='#'><i class='bi bi-clock-history'></i> View History</a></li>
                        <li><hr class='dropdown-divider'></li>
                        <li><a class='dropdown-item' href='/AIMS/assets/Unassignment.php?id=" . htmlspecialchars($row['id']) . "'><i class='bi bi-pencil-square'></i> Unassign</a></li>
                    </ul>
                </div>
            </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No Assets Found</td></tr>";
}

mysqli_close($conn);
?>
            </tbody>
        </table>

        <!-- Pagination Controls -->
        <ul class="pagination" id="paginationControls">
            <?php if ($currentPage > 1): ?>
                <li class="page-item"><a class="page-link" href="?page=<?php echo $currentPage - 1; ?>" id="prevPage">Previous</a></li>
            <?php else: ?>
                <li class="page-item disabled"><a class="page-link" href="#" id="prevPage">Previous</a></li>
            <?php endif; ?>
            
            <?php if ($currentPage < $totalPages): ?>
                <li class="page-item"><a class="page-link" href="?page=<?php echo $currentPage + 1; ?>" id="nextPage">Next</a></li>
            <?php else: ?>
                <li class="page-item disabled"><a class="page-link" href="#" id="nextPage">Next</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#inventoryTable tr');
            
            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>

</body>
</html> 