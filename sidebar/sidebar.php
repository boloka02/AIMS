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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpeg" href="https://adongroup.com.au/wp-content/uploads/2020/12/aog-favicon-192px.svg">
    <title>ADON PH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../sidebar/css.css">
</head>
<body>
    <!-- Audio element for the notification sound -->
    <audio id="notification-sound" src="../sidebar/notif.mp3" preload="auto"></audio>

    <button class="hamburger-button">☰</button>
<style>
    button.hamburger-button {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    pointer-events: none !important;
    position: absolute !important;
    left: -9999px !important;
    z-index: -9999 !important;
}

</style>


    <div class="sidebar">
        <div class="sidebar-header"><img src="../alogo.png" alt="AdonPH Logo" style="height: 50px; width: 150px;"></div>

        <a href="../dashboard/dashboard.php" class="sidebar-link">
            <i class="bi bi-speedometer2"></i><span class="sidebar-text">Dashboard</span>
        </a>
        <a href="../inventory/inventory.php" class="sidebar-link">
            <i class="bi bi-box-seam"></i><span class="sidebar-text">Inventory</span>
        </a>
        <a href="../assets/asset.php" class="sidebar-link">
            <i class="bi bi-laptop"></i><span class="sidebar-text">Assets</span>
        </a>
        <a href="../employee/employee.php" class="sidebar-link">
            <i class="bi bi-people"></i><span class="sidebar-text">Employee</span>
        </a>
        <a href="../ticket/tickets.php" class="sidebar-link">
            <i class="fas fa-ticket-alt"></i><span class="sidebar-text">Tickets</span>
        </a>
        <a href="../account/account.php" class="sidebar-link">
            <i class="bi bi-people"></i><span class="sidebar-text">Accounts</span>
        </a>
        <a href="../login/logout.php" class="sidebar-link">
            <i class="bi bi-box-arrow-left"></i><span class="sidebar-text">Log out</span>
        </a>

        <!-- Bottom Section -->
        <div class="bottom-section">
            <div class="user">User: <?php echo htmlspecialchars($_SESSION['name']); ?></div>
            <div class="attribution">@AD ON GROUP,2025</div>
        </div>
    </div>

    <?php
    // Database query to get the count of pending tickets
    include ('../db_connection.php');
    $sql = "SELECT COUNT(*) FROM ticket WHERE status = 'pending'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $new_tickets = mysqli_fetch_row($result)[0];
    } else {
        $new_tickets = 0;
    }
    ?>

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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
