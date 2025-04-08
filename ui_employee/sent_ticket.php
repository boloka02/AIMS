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
    $date_created = date("Y-m-d");
    
    // Generate Ticket Number
    $query = "SELECT COUNT(*) AS count FROM ticket";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $ticket_number = "TCK-" . str_pad($row['count'] + 1, 4, "0", STR_PAD_LEFT);
    
    // Insert Ticket
    $insertQuery = "INSERT INTO ticket (ticket_number, subject, status, date_created, created_by) VALUES (?, ?, 'Pending', ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ssss", $ticket_number, $subject, $date_created, $userName);
    $stmt->execute();
    $stmt->close();
}

$ticketsQuery = "SELECT * FROM ticket WHERE created_by = ? ORDER BY date_created DESC";
$stmt = $conn->prepare($ticketsQuery);
$stmt->bind_param("s", $userName);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sent Tickets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Sent Tickets</h2>
        <form method="POST" class="mb-4">
            <div class="input-group">
                <input type="text" name="subject" class="form-control" placeholder="Enter ticket subject" required>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
        
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Ticket Number122132132121</th>
                    <th>Subject1312332</th>
                    <th>Status</th>
                    <th>Date Created</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($ticket = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= htmlspecialchars($ticket['ticket_number']) ?></td>
                        <td><?= htmlspecialchars($ticket['subject']) ?></td>
                        <td><span class="badge bg-warning">Pending</span></td>
                        <td><?= htmlspecialchars($ticket['date_created']) ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
