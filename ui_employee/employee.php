<?php
session_start();
if (!isset($_SESSION['name'])) {
    header("Location: ../login/login.php");
    exit();
}

include 'db_connection.php';

$userName = $_SESSION['name'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $priority = mysqli_real_escape_string($conn, $_POST['priority']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
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
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-primary text-white">Tickets Overview</div>
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#addTicketModal">+ Add Ticket</button>
                        <input type="text" id="searchInput" class="form-control" placeholder="Search tickets...">
                    </div>

                    <table class="table mt-3">
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
                        <tbody id="inventoryTable">
                        <?php
                        while ($ticket = $result->fetch_assoc()) {
                            $statusClass = '';
                            switch ($ticket['status']) {
                                case "Pending": $statusClass = "bg-primary text-white"; break;
                                case "In Progress": $statusClass = "bg-warning text-dark"; break;
                                case "Resolved": $statusClass = "bg-success text-white"; break;
                                case "Close": $statusClass = "bg-light text-dark"; break;
                                case "On Hold": $statusClass = "bg-purple text-white"; break;
                            }

                            $priorityClass = '';
                            switch ($ticket['priority']) {
                                case "High": $priorityClass = "bg-danger text-white"; break;
                                case "Medium": $priorityClass = "bg-warning text-dark"; break;
                                case "Low": $priorityClass = "bg-light text-dark"; break;
                            }
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($ticket['ticket_number']) ?></td>
                                <td><?= htmlspecialchars($ticket['subject']) ?></td>
                                <td><?= htmlspecialchars($ticket['category']) ?></td>
                                <td><span class="badge"><?= htmlspecialchars($ticket['activity']) ?></span></td>
                                <td><span class="badge <?= $statusClass ?>"><?= htmlspecialchars($ticket['status']) ?></span></td>
                                <td><span class="badge <?= $priorityClass ?>"><?= htmlspecialchars($ticket['priority']) ?></span></td>
                                <td><?= htmlspecialchars($ticket['date_created']) ?></td>
                                <td><?= htmlspecialchars($ticket['assign_to']) ?></td>
                                <td>
                                    <?php if ($ticket['status'] != "Resolved" && $ticket['status'] != "Close") { ?>
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><button class="dropdown-item text-success" onclick="showFixedPopup('<?= $ticket['ticket_number'] ?>')">✅ Fixed</button></li>
                                                <li><button class="dropdown-item text-danger" onclick="showCancelPopup('<?= $ticket['ticket_number'] ?>')">❌ Cancel</button></li>
                                            </ul>
                                        </div>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>

                    <ul class="pagination" id="paginationControls">
                        <li class="page-item disabled"><a class="page-link" href="#" id="prevPage">Previous</a></li>
                        <li class="page-item"><a class="page-link" href="#" id="nextPage">Next</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Assigned Units Section -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-white text-dark font-weight-bold">Assigned Units</div>
                <div class="card-body">
                    <table class="table table-bordered small-table">
                        <tbody>
                            <?php
                            $tables = [
                                "Motherboard" => "mboard",
                                "Keyboard" => "keyboard",
                                "Mouse" => "mouse",
                                "Monitor" => "monitor",
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

    </div>
</div>

<!-- Add Ticket Modal -->
<div class="modal fade" id="addTicketModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="ticketForm" method="POST" enctype="multipart/form-data">
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

                    <label class="form-label mt-2">Attachments (Optional)</label>
                    <input type="file" name="image" accept="image/*" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
