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
        .modal-img {
            max-width: 90%;
            max-height: 80vh;
            display: block;
            margin: auto;
        }
        th:nth-child(2) {
    width: 40%; /* Adjust as needed */
}
    </style>
</head>

<body>
    <?php include "../sidebar/sidebar.php"; ?>

    <div class="container mt-3">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Ticket</h3>
            <div class="d-flex">
                <input type="text" id="searchInput" class="form-control search-box me-2" placeholder="Search...">
            </div>
        </div>

        <table class="table mt-3">
            <thead>
                <tr>
                    <th scope="col">Ticket No.</th>
                    <th scope="col">Subject</th>
                    <th scope="col">Category</th>
                    <th scope="col">Activity</th>
                    <th scope="col">Status</th>
                    <th scope="col">Date Request</th>
                    <th scope="col">requested</th>
                    <th scope="col">Priority</th>
                    <th scope="col">Image</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody id="inventoryTable">
    <?php 
        include '../db_connection.php';

        $query = "SELECT id, ticket_number, subject, category, process, status, date_created, created_by, priority, image 
        FROM ticket 
        ORDER BY date_created DESC"; // Ordering by newest first
        $result = mysqli_query($conn, $query);
        $rows = [];

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Determine image display
                if (!empty($row['image'])) {
                    $imagePath = "../ui_employee/" . $row['image'];
                    $imageTag = "<img src='$imagePath' class='img-thumbnail' 
                    style='width: 50px; cursor: pointer;' 
                    onerror=\"this.src='../ui_employee/uploads/placeholder.jpg'\" 
                    onclick='showImageModal(\"$imagePath\")'>";
                } else {
                    $imageTag = "<span class='text-muted'>No Image</span>"; // Display text if no image
                }

                // Assign text color based on priority
                switch (strtolower($row['priority'])) {
                    case 'high':      $priorityColor = "text-danger"; break; // Red
                    case 'medium':    $priorityColor = "text-warning"; break; // Yellow
                    case 'low':       $priorityColor = "text-dark"; break; // Black
                    case 'critical':  $priorityColor = "text-purple"; break; // Purple
                    default:          $priorityColor = "text-secondary"; break; // Gray
                }

                $rows[] = "<tr class='inventory-row'>
                    <td>{$row['ticket_number']}</td>
                    <td>{$row['subject']}</td>
                    <td>{$row['category']}</td>
                    <td>{$row['process']}</td>
                    <td>{$row['status']}</td>
                    <td>{$row['date_created']}</td>
                    <td>{$row['created_by']}</td>
                    <td class='$priorityColor fw-bold'>{$row['priority']}</td> <!-- Colored priority text -->
                    <td>$imageTag</td>
                    <td>
                        <div class='dropdown'>
                            <button class='btn btn-light btn-sm' type='button' id='dropdownMenu{$row['id']}' data-bs-toggle='dropdown' aria-expanded='false'>
                                <i class='bi bi-three-dots-vertical'></i>
                            </button>
                            <ul class='dropdown-menu' aria-labelledby='dropdownMenu{$row['id']}'>
                                <li><h6 class='dropdown-header'>Actions</h6></li>
                                <li><a class='dropdown-item text-success accept-btn' data-id='{$row['id']}' href='#'><i class='bi bi-check-circle'></i> Accept</a></li>
                               <li><a class='dropdown-item text-primary onhold-btn' data-id='{$row['id']}' href='#'><i class='bi bi-pause-circle'></i> On Hold</a></li>
                                <li><a class='dropdown-item text-danger decline-btn'  href='#'><i class='bi bi-x-circle'></i> Close</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>";
            }
        } else {
            $rows[] = "<tr><td colspan='9' class='text-center'>No Assets Found</td></tr>";
        }

        mysqli_close($conn);
        echo implode("", $rows);
    ?>
</tbody>



        </table>
        </script>
        <!-- Pagination Controls -->
        <ul class="pagination" id="paginationControls">
            <li class="page-item disabled"><a class="page-link" href="#" id="prevPage">Previous</a></li>
            <li class="page-item"><a class="page-link" href="#" id="nextPage">Next</a></li>
        </ul>
    </div>

    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" class="modal-img" src="" alt="Preview">
                </div>
            </div>
        </div>
    </div>

    <script>
        function showImageModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            var imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
            imageModal.show();
        }
    </script>
        <!-- Pagination Controls -->
      
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
<script>document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".accept-btn").forEach(button => {
        button.addEventListener("click", function (e) {
            e.preventDefault();
            let ticketId = this.getAttribute("data-id");

            // AJAX request to update the database
            fetch("accept_ticket.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "ticket_id=" + ticketId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Ticket accepted successfully!");
                    location.reload(); // Reload to reflect changes
                } else {
                    alert("Error accepting ticket: " + data.message);
                }
            })
            .catch(error => console.error("Error:", error));
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".onhold-btn").forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            let ticketId = this.getAttribute("data-id");

            if (confirm("Are you sure you want to put this ticket on hold?")) {
                fetch("onhold_ticket.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `ticket_id=${ticketId}&action=onhold`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Ticket has been put on hold.");
                        location.reload(); // Reload the page to update the table
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(error => console.error("Error:", error));
            }
        });
    });
});

</script>
</body>
</html>
