<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../db_connection.php');
$sql = "SELECT COUNT(*) FROM ticket WHERE status = 'pending'";
$result = mysqli_query($conn, $sql);
$new_tickets = $result ? mysqli_fetch_row($result)[0] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADON PH</title>
    <link rel="icon" href="https://adongroup.com.au/wp-content/uploads/2020/12/aog-favicon-192px.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
        body {
            margin: 0;
            display: flex;
            height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }

        .sidebar {
            width: 260px;
            background-color: #0f1a2b;
            color: white;
            height: 100%;
            padding-top: 1rem;
            position: fixed;
            transition: transform 0.3s ease;
            z-index: 1000;
        }

        .sidebar.hidden {
            transform: translateX(-100%);
        }

        .sidebar .sidebar-header img {
            width: 80%;
            display: block;
            margin: 0 auto 20px auto;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #ccc;
            text-decoration: none;
            transition: background 0.3s;
        }

        .sidebar-link:hover {
            background-color: #1e2b3c;
            color: white;
        }

        .sidebar-link i {
            margin-right: 15px;
        }

        .bottom-section {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            color: #aaa;
        }

        .notification-bell {
            position: fixed;
            top: 20px;
            right: 20px;
            font-size: 22px;
            color: #333;
            z-index: 2000;
        }

        .notification-bell .badge {
            position: absolute;
            top: -8px;
            right: -10px;
            background-color: red;
            color: white;
            font-size: 12px;
            padding: 3px 6px;
            border-radius: 50%;
        }

        .shake {
            animation: shake 0.4s ease-in-out infinite alternate;
        }

        @keyframes shake {
            0% { transform: rotate(-3deg); }
            100% { transform: rotate(3deg); }
        }

        .hamburger-button {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            background-color: #0f1a2b;
            color: white;
            border: none;
            font-size: 24px;
            padding: 8px 12px;
            border-radius: 5px;
            z-index: 2000;
        }

        .main-content {
            margin-left: 260px;
            padding: 30px;
            flex-grow: 1;
            background-color: #f4f6f8;
            transition: margin-left 0.3s ease;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.visible {
                transform: translateX(0);
            }

            .hamburger-button {
                display: block;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
<audio id="notification-sound" src="../sidebar/notif.mp3" preload="auto"></audio>

<button class="hamburger-button" id="toggleSidebar">☰</button>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <img src="../alogo.png" alt="ADON Logo">
    </div>
    <a href="../dashboard/dashboard.php" class="sidebar-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="../inventory/inventory.php" class="sidebar-link"><i class="bi bi-box-seam"></i> Inventory</a>
    <a href="../assets/asset.php" class="sidebar-link"><i class="bi bi-laptop"></i> Assets</a>
    <a href="../employee/employee.php" class="sidebar-link"><i class="bi bi-people"></i> Employee</a>
    <a href="../ticket/tickets.php" class="sidebar-link"><i class="fas fa-ticket-alt"></i> Tickets</a>
    <a href="../account/account.php" class="sidebar-link"><i class="bi bi-person-circle"></i> Accounts</a>
    <a href="../login/logout.php" class="sidebar-link"><i class="bi bi-box-arrow-left"></i> Logout</a>

    <div class="bottom-section">
        <div>User: <?= htmlspecialchars($_SESSION['name']) ?></div>
        <small>© 2025 ADONPH INC.</small>
    </div>
</div>

<a href="../ticket/tickets.php" class="notification-bell" id="notification-bell">
    <i class="fa fa-bell"></i>
    <?php if ($new_tickets > 0): ?>
        <span class="badge" id="notification-count"><?= $new_tickets ?></span>
    <?php endif; ?>
</a>

<div class="main-content">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['name']) ?>!</h2>
    <p>This is your main dashboard content area.</p>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.getElementById('sidebar');
    const hamburger = document.getElementById('toggleSidebar');
    const bell = document.getElementById('notification-bell');
    const sound = document.getElementById('notification-sound');
    const newTickets = <?= $new_tickets ?>;

    if (newTickets > 0) {
        bell.classList.add('shake');
        sound.play();
    }

    hamburger.addEventListener('click', () => {
        sidebar.classList.toggle('visible');
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
