<?php
// Ensure session is started only once
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Responsive Sidebar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            background: #f1f4f9;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: #0f172a;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.3s ease;
            position: relative;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar .logo {
            padding: 20px;
            font-size: 1.3em;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        .sidebar .logo i {
            font-size: 1.5em;
            margin-right: 10px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0 10px;
        }

        .sidebar li {
            padding: 12px 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            border-radius: 8px;
            color: #cbd5e1;
            transition: background 0.3s;
        }

        .sidebar li:hover {
            background: #1e293b;
        }

        .sidebar li i {
            margin-right: 16px;
            font-size: 1.2em;
        }

        .sidebar .section-title {
            font-size: 0.75em;
            padding: 15px 20px 5px;
            color: #64748b;
            text-transform: uppercase;
        }

        .sidebar .user {
            padding: 20px;
            border-top: 1px solid #1e293b;
            color: white;
        }
        .sidebar .attribution{
            padding: 10px 20px;
            color: #94a3b8;
            font-size: 0.8em;
            text-align: center;
        }

        .content {
            flex-grow: 1;
            padding: 40px;
        }

        .content h1 {
            font-size: 2em;
            margin-bottom: 10px;
            color: #1e293b;
        }

        .mobile-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: #0f172a;
            border: none;
            color: white;
            font-size: 22px;
            z-index: 200;
            border-radius: 5px;
            padding: 8px 10px;
            cursor: pointer;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -260px;
                top: 0;
                height: 100%;
                z-index: 150;
            }

            .sidebar.active {
                left: 0;
            }

            .mobile-toggle {
                display: block;
            }

            .content {
                padding: 60px 20px 20px 20px;
            }
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            color: #cbd5e1;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .sidebar-link:hover {
            background: #1e293b;
        }

        .sidebar-link i {
            margin-right: 16px;
            font-size: 1.2em;
        }

        .sidebar-text {
            flex-grow: 1;
        }
    </style>
</head>
<body>
<audio id="notification-sound" src="../sidebar/notif.mp3" preload="auto"></audio>

    <button class="mobile-toggle" onclick="toggleMobileSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <div class="sidebar" id="sidebar">
        <div>
            <div class="logo">
                <i class="fas fa-circle"></i>
                <span>CodingLab</span>
            </div>

            <div class="section-title">Main Menu</div>
            <ul>
                <li>
                    <a href="../dashboard/dashboard.php" class="sidebar-link">
                        <i class="bi bi-speedometer2"></i><span class="sidebar-text">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="../inventory/inventory.php" class="sidebar-link">
                        <i class="bi bi-box-seam"></i><span class="sidebar-text">Inventory</span>
                    </a>
                </li>
                <li>
                    <a href="../assets/asset.php" class="sidebar-link">
                        <i class="bi bi-laptop"></i><span class="sidebar-text">Assets</span>
                    </a>
                </li>
                <li>
                    <a href="../employee/employee.php" class="sidebar-link">
                        <i class="bi bi-people"></i><span class="sidebar-text">Employee</span>
                    </a>
                </li>
                <li>
                    <a href="../ticket/tickets.php" class="sidebar-link">
                        <i class="fas fa-ticket-alt"></i><span class="sidebar-text">Tickets</span>
                    </a>
                </li>
                <li>
                    <a href="../account/account.php" class="sidebar-link">
                        <i class="bi bi-people"></i><span class="sidebar-text">Accounts</span>
                    </a>
                </li>
                <li>
                    <a href="../login/logout.php" class="sidebar-link">
                        <i class="bi bi-box-arrow-left"></i><span class="sidebar-text">Log out</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="user">User: <?php echo htmlspecialchars($_SESSION['name']); ?></div>
        <div class="attribution"> Copyright © 2025 ADONPH INC..</div>
    </div>

    <div class="content">
        <h1>Dashboard</h1>
        <p>This is your main content area.</p>
    </div>

    <script>
        function toggleMobileSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }
    </script>

      <!-- Notification Bell -->
      <a href="../ticket/tickets.php" class="notification-bell" id="notification-bell">
        <i class="fa fa-bell"></i>
        <?php if ($new_tickets > 0): ?>
            <span class="badge" id="notification-count"><?= $new_tickets ?></span>
        <?php endif; ?>
    </a>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const notificationBell = document.getElementById('notification-bell');
            const notificationSound = document.getElementById('notification-sound');
            const newTickets = <?= $new_tickets ?>;

            console.log("New tickets: " + newTickets);

            // If there are new tickets, add the shake animation and play the sound
            if (newTickets > 0) {
                notificationBell.classList.add('shake');
                notificationSound.play(); 
                console.log("Sound should play now.");
            } else {
                console.log("No new tickets, no sound.");
            }

            // Toggle sidebar on hamburger button click
            const hamburgerButton = document.querySelector('.hamburger-button');
            const sidebar = document.querySelector('.sidebar');
            const content = document.querySelector('.content');

            hamburgerButton.addEventListener('click', function () {
                sidebar.classList.toggle('active');
                content.classList.toggle('active');
            });
        });
    </script>
</body>
</html>