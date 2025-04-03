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
        <h3>Inventory</h3>
        <div class="d-flex">
            <input type="text" id="searchInput" class="form-control search-box me-2" placeholder="Search...">
            <a href="/mis-v6.1/inventory/add_item.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Item</a>
        </div>
    </div>
    
    <table class="table mt-3">
        <thead>
            <tr>
                <th scope="col">Type</th>
                <th scope="col">Category</th>
                <th scope="col">Quantity</th>
                <th scope="col">Total Value</th>
                <th scope="col">Stock</th>
                <th scope="col">Status</th>
                <th scope="col">Purchase Date</th>
                <th scope="col">Warranty</th>
                <th scope="col">Location</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody id="inventoryTable">
        <?php 
        include '../db_connection.php';

        $query = "SELECT id, type, category, quantity, total_value, stock, available_stock, status, purchasedate, warranty, location FROM inventory";
        $result = mysqli_query($conn, $query);
        $rows = [];

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Calculate stock percentage dynamically
                $stockPercentage = ($row['available_stock'] / max($row['quantity'], 1)) * 100;

                // Determine stock status, icon, and color based on percentage
                if ($row['available_stock'] <= 0) {
                    $stockDisplay = "<span class='text-danger'><i class='fas fa-circle'></i> Out of Stock</span>";
                } elseif ($stockPercentage <= 49) {
                    $stockDisplay = "<span class='text-warning'><i class='fas fa-circle'></i> Low Stock</span>";
                } else {
                    $stockDisplay = "<span class='text-success'><i class='fas fa-circle'></i> In Stock</span>";
                }

                $rows[] = "<tr>
                    <td>{$row['type']}</td>
                    <td>{$row['category']}</td>
                    <td>{$row['quantity']}</td>
                    <td>{$row['total_value']}</td>
                    <td>{$stockDisplay}</td>
                    <td>{$row['available_stock']}</td>
                    <td>{$row['purchasedate']}</td>
                    <td>{$row['warranty']}</td>
                    <td>{$row['location']}</td>
                    <td>
                        <div class='dropdown'>
                            <button class='btn btn-light btn-sm' type='button' id='dropdownMenu{$row['id']}' data-bs-toggle='dropdown' aria-expanded='false'>
                                <i class='bi bi-three-dots-vertical'></i>
                            </button>
                            <ul class='dropdown-menu' aria-labelledby='dropdownMenu{$row['id']}'>
                                <li><h6 class='dropdown-header'>Actions</h6></li>
                            <li><a class='dropdown-item' href='/mis-v6.1/inventory/edit.php?id={$row['id']}'><i class='bi bi-pencil-square'></i> Edit</a></li>
                             <li><a class='dropdown-item view-details' href='/mis-v6.1/inventory/view.php?type={$row['type']}'><i class='bi bi-clock-history'></i> View Details</a></li>
                            <li><hr class='dropdown-divider'></li>
                             <li><a class='dropdown-item' href='/mis-v6.1/inventory/delete.php?id={$row['id']}'><i class='bi bi-trash'></i>Delete</a></li></ul>
                        </div>
                    </td>
                </tr>";
            }
        } else {
            $rows[] = "<tr><td colspan='10'>No Assets Found</td></tr>";
        }

        mysqli_close($conn);

        echo implode("", $rows);
        ?>
        </tbody>
    </table>

    <!-- Pagination Controls -->
    <ul class="pagination">
        <li class="page-item disabled"><a class="page-link" href="#" id="prevPage">Previous</a></li>
        <li class="page-item"><a class="page-link" href="#" id="nextPage">Next</a></li>
    </ul>
</div>

<script>
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

    showPage(currentPage);

    document.getElementById('searchInput').addEventListener('input', function() {
        let filter = this.value.toLowerCase();
        tableRows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>

</body>
</html>
