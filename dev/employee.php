<?php
session_start();
if (!isset($_SESSION['name'])) {
    header("Location: ../login/login.php");
    exit();
}

$userName = $_SESSION['name'];
$conn = new mysqli("localhost", "root", "", "db_ams");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $priority = mysqli_real_escape_string($conn, $_POST['priority']);
    $category = mysqli_real_escape_string($conn, $_POST['category']); // Capture category
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

    // Insert Ticket with Category
    $insertQuery = "INSERT INTO ticket (ticket_number, subject, priority, category, image, status, date_created, created_by) 
                    VALUES (?, ?, ?, ?, ?, 'Pending', ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("sssssss", $ticket_number, $subject, $priority, $category, $image_path, $date_created, $userName);
    $stmt->execute();
    $stmt->close();

    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}



// Pagination Setup
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$ticketsQuery = "SELECT * FROM ticket WHERE created_by = ? ORDER BY date_created DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($ticketsQuery);
$stmt->bind_param("sii", $userName, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

// Get total tickets count for pagination
$countQuery = "SELECT COUNT(*) AS total FROM ticket WHERE created_by = ?";
$stmt = $conn->prepare($countQuery);
$stmt->bind_param("s", $userName);
$stmt->execute();
$countResult = $stmt->get_result();
$totalTickets = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalTickets / $limit);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdonPH Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">AdonPH</a>
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
                 
                    <table class="table table-bordered small-table">
    <thead class="table-dark">
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
    <tbody>
    <?php while ($ticket = $result->fetch_assoc()) { 
        $statusClass = match ($ticket['status']) {
            "Pending" => "bg-primary text-white",
            "In Progress" => "bg-warning text-dark",
            "Close" => "bg-light text-dark",
            "Done" => "bg-success text-white",
            "Waiting" => "bg-purple text-white",
            default => "",
        };

        $priorityClass = match ($ticket['priority']) {
            "High" => "bg-danger text-white",
            "Medium" => "bg-warning text-dark",
            "Low" => "bg-light text-dark",
            default => "",
        };

        $processClass = match ($ticket['process']) {
            "Accepted" => "bg-primary text-white",
            "Fixed" => "bg-success text-white",
            "On Hold" => "bg-purple text-white",
            default => "",
        };
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
                <?php if ($ticket['status'] != "Done" && $ticket['status'] != "Close") { ?>
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="actionMenu<?= $ticket['ticket_number'] ?>" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="actionMenu<?= $ticket['ticket_number'] ?>">
                            <li>
                                <button class="dropdown-item text-success" onclick="showFixedPopup('<?= $ticket['ticket_number'] ?>')">
                                    ✅ Fixed
                                </button>
                            </li>
                            <li>
                                <button class="dropdown-item text-danger" onclick="showCancelPopup('<?= $ticket['ticket_number'] ?>')">
                                    ❌ Cancel
                                </button>
                            </li>
                        </ul>
                    </div>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
</tbody>

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

<style>
/* Custom Popup Box Styling */
.custom-popup {
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.2);
    animation: fadeIn 0.3s ease-in-out;
}

/* Fade-in animation */
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}

/* Success and Danger Icons */
.icon {
    font-size: 50px;
    margin-bottom: 10px;
}
.icon.success { color: #28a745; }
.icon.danger { color: #dc3545; }

/* Modern Popup Buttons */
.popup-buttons {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-top: 15px;
}
.popup-buttons .btn {
    border-radius: 8px;
    padding: 10px 15px;
    font-size: 14px;
    font-weight: bold;
}

/* Modal Body Text */
.modal-body h5 {
    font-size: 20px;
    font-weight: bold;
}
.modal-body p {
    font-size: 14px;
    color: #555;
}
</style>


</table>

                    <nav>
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

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
    }, 3000);
}
</script>

<style>/* Center the modal */
.modal-dialog {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
}

/* Success Popup Box */
#successPopup .modal-content {
    text-align: center;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    animation: fadeIn 0.3s ease-in-out;
}

#successPopup .modal-header {
    background-color: #28a745;
    color: white;
    border-radius: 10px 10px 0 0;
}

#successPopup .modal-body {
    font-size: 16px;
    font-weight: bold;
}

/* Fade-in animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../ui_employee/script.js"></script>


</body>
</html>
