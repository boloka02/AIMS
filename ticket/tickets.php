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
                    <th scope="col">Assign To</th>
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

        // SQL Query: Fetch the tickets ordered by date_created and time_created
        $query = "
        SELECT id, ticket_number, subject, assign_to, category, process, status, 
               date_created, time_created, created_by, priority, image 
        FROM ticket 
        ORDER BY 
            CASE 
                WHEN status = 'Pending' THEN 1
                WHEN status = 'In Progress' THEN 2
                WHEN status = 'Resolved' THEN 3
                 WHEN status = 'Close' THEN 4
                ELSE 5
            END,
            date_created DESC, 
            time_created DESC";
    
        
        $result = mysqli_query($conn, $query);
        $rows = [];

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              // Handle the image display
            if (!empty($row['image']) && file_exists("../ui_employee/" . $row['image'])) {
                // If the image exists, show it
                $imagePath = "../ui_employee/" . $row['image'];
                $imageTag = "<img src='$imagePath' class='img-thumbnail' 
                style='width: 50px; cursor: pointer;' 
                onerror=\"this.src='../ui_employee/uploads/placeholder.jpg'\" 
                onclick='showImageModal(\"$imagePath\")'>";
            } else {
                // Show the placeholder if the image doesn't exist or is empty
                $imageTag = "<img src='../ui_employee/uploads/placeholder.jpg' class='img-thumbnail' 
                style='width: 50px; cursor: pointer;' 
                onclick='showImageModal(\"../ui_employee/uploads/placeholder.jpg\")'>";
            }


              
                            // Action buttons
                $actionButtons = "<div class='dropdown'>
                <button class='btn btn-light btn-sm' type='button' id='dropdownMenu{$row['id']}' data-bs-toggle='dropdown' aria-expanded='false'>
                    <i class='bi bi-three-dots-vertical'></i>
                </button>
                <ul class='dropdown-menu' aria-labelledby='dropdownMenu{$row['id']}' >
                    <li><h6 class='dropdown-header'>Actions</h6></li>";

                // Show Accept button only if not assigned, not accepted, and not resolved
                if (empty($row['assign_to']) && strtolower($row['process']) !== 'accepted' && strtolower($row['status']) !== 'resolved') {
                $actionButtons .= "<li><a class='dropdown-item text-success accept-btn' data-id='{$row['id']}' href='#'><i class='bi bi-check-circle'></i> Accept</a></li>";
                }

                // Action buttons for other operations
                $actionButtons .= "<li><a class='dropdown-item text-primary onhold-btn' data-id='{$row['id']}' href='#'><i class='bi bi-pause-circle'></i> On Hold</a></li>
                <li><a class='dropdown-item text-warning assign-btn' href='#' data-bs-toggle='modal' data-bs-target='#assignModal'><i class='bi bi-person'></i> Assign to</a></li>";

                // Only show Close button if the status is Resolved
                if (strtolower($row['status']) === 'resolved') {
                $actionButtons .= "<li><a class='dropdown-item text-danger decline-btn' data-id='{$row['id']}' href='#'><i class='bi bi-x-circle'></i> Close</a></li>";
                }

                $actionButtons .= "</ul>
                </div>";

               

                // If ticket is closed, show 'Closed' text and disable actions
                if (strtolower($row['status']) === 'close') {
                    $actionButtons = "<span class='text-muted'>Closed</span>";
                }

                // Prepare the row for rendering
                $rows[] = "<tr class='inventory-row' data-id='{$row['id']}'>
                    <td>{$row['ticket_number']}</td>
                    <td>{$row['subject']}</td>
                    <td>{$row['assign_to']}</td>
                    <td>{$row['category']}</td>
                    <td>{$row['process']}</td>
                    <td class='status-cell'>{$row['status']}</td>
                    <td>{$row['date_created']}</td>
                    <td>{$row['created_by']}</td>
                    <td class='$priorityColor fw-bold'>{$row['priority']}</td> 
                    <td>$imageTag</td>
                    <td class='actions-cell'>$actionButtons</td>
                </tr>";
            }
        } else {
            $rows[] = "<tr><td colspan='11' class='text-center'>No Assets Found</td></tr>";
        }

        mysqli_close($conn);
        echo implode("", $rows); // Output the rows
    ?>
</tbody>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".decline-btn").forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            let ticketId = this.getAttribute("data-id");
            let row = this.closest("tr");

            if (confirm("Are you sure you want to close this ticket?")) {
                fetch("close_ticket.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `id=${ticketId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update status
                        row.querySelector(".status-cell").textContent = "Close";

                        // Remove action buttons and replace with "Closed"
                        row.querySelector(".actions-cell").innerHTML = "<span class='text-muted'>Closed</span>";

                        // Move the closed ticket to the bottom of the table
                        document.getElementById("inventoryTable").appendChild(row);
                    } else {
                        alert("Error closing ticket: " + data.error);
                    }
                })
                .catch(error => console.error("Error:", error));
            }
        });
    });
});
</script>

<!-- Assign Modal -->
<div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="assignModalLabel">Assign to a Dev-Support</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form id="assignForm" action="assign_dev.php" method="POST">
    <div class="mb-3">
        <label for="Dev-Support" class="form-label">Dev-Support</label>
        <select class="form-control" id="Dev-Support" name="dev_support" required>
            <option value="">Select Dev-Support</option>
            <?php
            include '../db_connection.php';

            // Fetch all users from the 'user' table with role 'Dev-Support'
            $result = mysqli_query($conn, "SELECT name FROM user WHERE role = 'Dev-Support'");

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='{$row['name']}'>{$row['name']}</option>";
            }
            ?>
        </select>
    </div>

    <input type="hidden" id="ticketNumber" name="ticket_number"> <!-- Hidden field for Ticket Number -->

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Assign</button>
    </div>
</form>

<script>
    // Ensure the correct ticket number is set when clicking "Assign"
    document.querySelectorAll('.assign-btn').forEach(button => {
        button.addEventListener('click', function () {
            let ticketNumber = this.closest('tr').querySelector('td:first-child').textContent;
            document.getElementById('ticketNumber').value = ticketNumber;
        });
    });
</script>

      </div>
    </div>
  </div>
</div>












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
        const rowsPerPage = 8;
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
