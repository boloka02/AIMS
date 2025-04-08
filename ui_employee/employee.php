<?php
session_start();
if (!isset($_SESSION['name'])) {
    header("Location: ../login/login.php");
    exit();
}

include '../db_connection.php';

$userName = $_SESSION['name']; // ✅ Fix: Define $userName

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $priority = mysqli_real_escape_string($conn, $_POST['priority']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $accept = mysqli_real_escape_string($conn, $_POST['accept']);
    $process = mysqli_real_escape_string($conn, $_POST['process']);
    $assign_to = mysqli_real_escape_string($conn, $_POST['assign_to']);
    $time_created = mysqli_real_escape_string($conn, $_POST['time_created']);
    $date_created = date("Y-m-d");

    // Handle File Upload
    $image_path = "";
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $image_name = basename($_FILES['image']['name']);
        $image_path = $target_dir . $image_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }

    // Generate Ticket Number
    $query = "SELECT COUNT(*) AS count FROM ticket";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $ticket_number = "TCK-" . str_pad($row['count'] + 1, 4, "0", STR_PAD_LEFT);

    // Insert Ticket
    $insertQuery = "INSERT INTO ticket 
        (ticket_number, subject, priority, category, image, status, date_created, created_by, accept, process, assign_to, time_created) 
        VALUES (?, ?, ?, ?, ?, 'Pending', ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param(
        "sssssssssss",
        $ticket_number,
        $subject,
        $priority,
        $category,
        $image_path,
        $date_created,
        $userName,
        $accept,
        $process,
        $assign_to,
        $time_created
    );
    $stmt->execute();
    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch Tickets
$query = "SELECT * FROM ticket WHERE created_by = ? ORDER BY FIELD(status, 'Pending', 'In Progress', 'Waiting', 'Resolved', 'Close')";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $userName);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css.css">
    <link rel="icon" type="image/jpeg" href="https://adongroup.com.au/wp-content/uploads/2020/12/aog-favicon-192px.svg">
    <title>ADON PH</title>
</head>

<body>
<audio id="background-music" src="../ui_employee/pseat.mp3" preload="auto" autoplay loop></audio>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const music = document.getElementById('background-music');

        // Some browsers block autoplay unless it's muted first
        music.muted = false;

        // Try to play music with a short delay
        setTimeout(() => {
            music.play().then(() => {
                console.log("Background music playing.");
            }).catch((err) => {
                console.warn("Autoplay blocked. Waiting for user interaction...");
            });
        }, 200);
    });
</script>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
    <a class="navbar-brand fw-bold" href="#"><img src="https://adongroup.com.au/wp-content/uploads/2019/12/AdOn-Logo-v4.gif" alt="AdonPH Logo" style="height: 40px;"></a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <?php echo htmlspecialchars($_SESSION['name']); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><?php echo htmlspecialchars($_SESSION['name']); ?></a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="../login/logout.php">Logout</a></li>
                        
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

 
<div class="container mt-4">
    <div class="row">
        <!-- Left Section: Ticket Submission & Table -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-primary text-white">Tickets Overview</div>
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <!-- Modal Button -->
                        <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#addTicketModal">+ Add Ticket</button>
                        <!-- Search Bar -->
                        <input type="text" id="searchInput" class="form-control" placeholder="Search tickets...">
                    </div>
        

                    <table class="table mt-3">
                    <thead style="background-color: #6c757d; color: white;">

        <tr>
            <th>Ticket No.</th>
            <th>Subject</th>
            <th>Category</th>
            <th>Activity</th>
            <th>Status</th>
            <th>Priority</th>
            <th>Date</th>
            <th>Assign</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="inventoryTable">
    <?php
    // Fetch tickets
    while ($ticket = $result->fetch_assoc()) {
        // Determine the class for status
        $statusClass = '';
        if ($ticket['status'] == "Pending") {
            $statusClass = "bg-primary text-white";
        } elseif ($ticket['status'] == "In Progress") {
            $statusClass = "bg-warning text-dark";
        } elseif ($ticket['status'] == "Close") {
            $statusClass = "bg-light text-dark";
        } elseif ($ticket['status'] == "Resolved") {
            $statusClass = "bg-success text-white";
        } elseif ($ticket['status'] == "On Hold") {
            $statusClass = "bg-purple text-white";
        }

        // Determine the class for priority
        $priorityClass = '';
        if ($ticket['priority'] == "High") {
            $priorityClass = "bg-danger text-white";
        } elseif ($ticket['priority'] == "Medium") {
            $priorityClass = "bg-warning text-dark";
        } elseif ($ticket['priority'] == "Low") {
            $priorityClass = "bg-light text-dark";
        }

        // Determine the class for process
        $processClass = '';
        if ($ticket['process'] == "Accepted") {
            $processClass = "bg-primary text-white";
        } elseif ($ticket['process'] == "Fixed") {
            $processClass = "bg-success text-white";
        } elseif ($ticket['process'] == "Waiting") {
            $processClass = "bg-purple text-white";
        }
    ?>
        <tr>
            <td><?= htmlspecialchars($ticket['ticket_number']) ?></td>
            <td><?= htmlspecialchars($ticket['subject']) ?></td>
            <td><?= htmlspecialchars($ticket['category']) ?></td>
            <td><span class="badge <?= $processClass ?>"><?= htmlspecialchars($ticket['process']) ?></span></td>
            <td><span class="badge <?= $statusClass ?>"><?= htmlspecialchars($ticket['status']) ?></span></td>
            <td><span class="badge <?= $priorityClass ?>"><?= htmlspecialchars($ticket['priority']) ?></span></td>
            <td><?= htmlspecialchars($ticket['date_created']) ?></td>
            <td><?= htmlspecialchars($ticket['accept']) ?></td>
            <td>
                <?php if ($ticket['status'] != "Resolved" && $ticket['status'] != "Close") { ?>
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <?php if (!empty($ticket['accept'])) { ?>
                                <li><button class="dropdown-item text-success" onclick="showFixedPopup('<?= $ticket['ticket_number'] ?>')">✅ Fixed</button></li>
                            <?php } ?>
                            <li><button class="dropdown-item text-danger" onclick="showCancelPopup('<?= $ticket['ticket_number'] ?>')">❌ Cancel</button></li>
                        </ul>
                    </div>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>




<!-- Fixed Confirmation Popup (Auto-Submits) -->
<div class="modal fade" id="fixedPopup" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center custom-popup">
            <div class="modal-body">
                <div class="icon success">
                    <i class="bi bi-check-circle"></i>
                </div>
                <h5>✅ Thank You for Confirming</h5>
                <p>don't hesitate to call for support.. </p>
                <form id="fixedForm" method="post" action="fixed_ticket.php">
                    <input type="hidden" id="fixedTicketId" name="ticket_id">
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Confirmation Popup -->
<div class="modal fade" id="cancelPopup" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center custom-popup">
            <div class="modal-body">
                <div class="icon danger">
                    <i class="bi bi-exclamation-circle"></i>
                </div>
                <h5>❌ Are You Sure?</h5>
                <p>Do you really want to cancel this ticket?</p>
                <form id="cancelForm" method="post" action="cancel_ticket.php">
                    <input type="hidden" id="cancelTicketId" name="ticket_id">
                    <div class="popup-buttons">
                        <button type="submit" class="btn btn-danger">Yes, Cancel</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Go Back</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Show Fixed Confirmation Popup and Auto-Submit Form
function showFixedPopup(ticketId) {
    document.getElementById('fixedTicketId').value = ticketId;
    var fixedPopup = new bootstrap.Modal(document.getElementById('fixedPopup'));
    fixedPopup.show();
    
    // Auto-submit form after 1 second
    setTimeout(() => {
        document.getElementById('fixedForm').submit();
    }, 1000);
}

// Show Cancel Confirmation Popup
function showCancelPopup(ticketId) {
    document.getElementById('cancelTicketId').value = ticketId;
    var cancelPopup = new bootstrap.Modal(document.getElementById('cancelPopup'));
    cancelPopup.show();
}
</script>

</table>

        <!-- Pagination Controls -->
        <ul class="pagination" id="paginationControls">
            <li class="page-item disabled"><a class="page-link" href="#" id="prevPage">Previous</a></li>
            <li class="page-item"><a class="page-link" href="#" id="nextPage">Next</a></li>
        </ul>
            </div>
            </div>
            </div>
   
    <script>
      // Fixed Confirmation Popup with Auto-Submit
function showFixedPopup(ticketId) {
    document.getElementById('fixedTicketId').value = ticketId;
    var fixedPopup = new bootstrap.Modal(document.getElementById('fixedPopup'));
    fixedPopup.show();

    setTimeout(() => {
        fixedPopup.hide();
        document.getElementById('fixedForm').submit();
    }, 1000);
}

// Cancel Confirmation Popup
function showCancelPopup(ticketId) {
    document.getElementById('cancelTicketId').value = ticketId;
    var cancelPopup = new bootstrap.Modal(document.getElementById('cancelPopup'));
    cancelPopup.show();
}

// Pagination & Search
let currentPage = 1;
const rowsPerPage = 5;
const tableRows = document.querySelectorAll('#inventoryTable tr');

function showPage(page) {
    let start = (page - 1) * rowsPerPage;
    let end = start + rowsPerPage;
    tableRows.forEach((row, index) => row.style.display = (index >= start && index < end) ? '' : 'none');
    document.getElementById('prevPage').parentElement.classList.toggle('disabled', page === 1);
    document.getElementById('nextPage').parentElement.classList.toggle('disabled', page === Math.ceil(tableRows.length / rowsPerPage));
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
    if (currentPage < Math.ceil(tableRows.length / rowsPerPage)) {
        currentPage++;
        showPage(currentPage);
    }
});

// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    let filter = this.value.toLowerCase();
    tableRows.forEach(row => row.style.display = row.innerText.toLowerCase().includes(filter) ? '' : 'none');
});

// Initialize
showPage(currentPage);

</script>

      <!-- Right Section: Assigned Units -->
<div class="col-md-3">
    <div class="card">
        <div class="card-header bg-white text-dark font-weight-bold">Assigned Unit</div>
        <div class="card-body">
            <table class="table table-bordered small-table">
                <tbody>
                    <?php 
                    $tables = [
                        "Motherboard" => "mboard",
                        "Keyboard" => "keyboard",
                        "Mouse" => "mouse",
                        "Monitor" => "monitor",
                        "Monitor2" => "monitor2",
                        "Webcam" => "webcam",
                        "Headset" => "headset",
                        "Processor" => "processor",
                        "RAM" => "ram",
                        "Laptop" => "laptop"
                    ];

                    foreach ($tables as $label => $table) {
                        $sql = "SELECT name FROM $table WHERE assign_to = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $userName);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $assignedName = ($row = $result->fetch_assoc()) ? $row['name'] : "N/A";
                        echo "<tr><th class='bg-white text-dark'>$label</th><td>$assignedName</td></tr>";
                        $stmt->close();
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>



<!-- Centered Modal for Adding Tickets -->
<div class="modal fade" id="addTicketModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="ticketForm" method="POST" enctype="multipart/form-data" onsubmit="showSuccessMessage(event)">
                <div class="modal-body">
                    <label class="form-label">Ticket Subject</label>
                    <input type="text" name="subject" class="form-control" placeholder="Enter ticket subject" required>

                    <label class="form-label mt-2">Priority</label>
                    <select name="priority" class="form-select" required>
                        <option value="High">High</option>
                        <option value="Medium">Medium</option>
                        <option value="Low">Low</option>
                    </select>      

                    <label class="form-label mt-2">Category</label>
                    <select name="category" class="form-select" required>
                        <option value="Hardware">Hardware</option>
                        <option value="Software">Software</option>
                        <option value="Access">Access</option>
                        <option value="Network">Network</option>
                    </select>

                    <!-- Modern Drag-and-Drop File Upload -->
                    <label class="form-label mt-2">Attachments (Optional)</label>
                    <div id="drop-area" class="upload-box">
                        <input type="file" id="fileInput" name="image" accept="image/*" hidden>
                        <div class="upload-content" onclick="document.getElementById('fileInput').click();">
                            <i class="bi bi-upload"></i>
                            <p>Drag & drop files or <span class="browse">browse</span></p>
                            <p class="file-info">Upload screenshots or relevant files (max 5MB each)</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Centered Success Popup -->
<div id="successPopup" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <p>Submit successfully, please wait for the support...</p>
            </div>
        </div>
    </div>
</div>

<script>
function showSuccessMessage(event) {
    event.preventDefault(); // Prevent form submission

    // Close the Add Ticket Modal
    var addTicketModal = bootstrap.Modal.getInstance(document.getElementById('addTicketModal'));
    if (addTicketModal) {
        addTicketModal.hide();
    }

    // Show the success popup
    var successModal = new bootstrap.Modal(document.getElementById('successPopup'));
    successModal.show();

    // Wait for 3 seconds, then submit the form
    setTimeout(function() {
        document.getElementById("ticketForm").submit();
    }, 1000);
}
</script>





<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../ui_employee/script.js"></script>

</body>
</html>
