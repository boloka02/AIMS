<?php
// Ensure session is started only once
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php
    // Database query to get the count of pending tickets
    include('../db_connection.php'); // Adjust path as needed
    $sql = "SELECT COUNT(*) FROM ticket WHERE status = 'pending'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $new_tickets = mysqli_fetch_row($result)[0];
    } else {
        $new_tickets = 0;
    }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sidebar with Notification</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Arial', sans-serif;
    }

    .shake {
      animation: shake 0.5s infinite alternate;
    }

    @keyframes shake {
      0% { transform: rotate(0deg); }
      25% { transform: rotate(5deg); }
      50% { transform: rotate(-5deg); }
      75% { transform: rotate(5deg); }
      100% { transform: rotate(0deg); }
    }
  </style>
</head>
<body class="bg-gradient-to-b from-blue-500 to-black h-screen flex">

<!-- Sidebar -->
<div class="w-64 bg-black bg-opacity-50 h-full text-white flex flex-col justify-between fixed left-0 top-0 z-50 transition-all duration-300 ease-in-out">
  <div>
    <div class="p-4">
      <h1 class="text-2xl font-bold">Facenote v7</h1>
    </div>
    <hr class="border-gray-600 mx-4" />

    <div class="p-4 flex items-center">
      <img alt="User profile picture" class="rounded-full" height="40" width="40"
           src="https://oaidalleapiprodscus.blob.core.windows.net/private/org-LmQ09WWGIGwOeeA4ArnRw0x5/user-uJPET5fjNenSso8wCETWVNOp/img-c9l3P2wROn2Hd65Np2jK9HjX.png" />
      <div class="ml-2">
        <p class="font-semibold"><?php echo htmlspecialchars($_SESSION['name']); ?></p>
        <p class="text-sm">My Account</p>
      </div>
    </div>
    <hr class="border-gray-600 mx-4" />

    <div class="p-4">
      <ul class="mt-2 space-y-3"> <!-- Added space-y-3 for vertical spacing between items -->
        <!-- Dashboard Link -->
        <li>
          <a href="../dashboard/dashboard.php" class="sidebar-link">
            <i class="bi bi-speedometer2"></i><span class="sidebar-text">Dashboard</span>
          </a>
        </li>

        <!-- Inventory Link -->
        <li>
          <a href="../inventory/inventory.php" class="sidebar-link">
            <i class="bi bi-box-seam"></i><span class="sidebar-text">Inventory</span>
          </a>
        </li>

        <!-- Assets Link -->
        <li>
          <a href="../assets/asset.php" class="sidebar-link">
            <i class="bi bi-laptop"></i><span class="sidebar-text">Assets</span>
          </a>
        </li>

        <!-- Employee Link -->
        <li>
          <a href="../employee/employee.php" class="sidebar-link">
            <i class="bi bi-people"></i><span class="sidebar-text">Employee</span>
          </a>
        </li>

        <!-- Tickets Link -->
        <li>
          <a href="../ticket/tickets.php" class="sidebar-link">
            <i class="fas fa-ticket-alt"></i><span class="sidebar-text">Tickets</span>
            <?php if ($new_tickets > 0): ?>
              <span class="ml-auto text-blue-500">•</span>
            <?php endif; ?>
          </a>
        </li>

        <!-- Account Link -->
        <li>
          <a href="../account/account.php" class="sidebar-link">
            <i class="bi bi-person-circle"></i><span class="sidebar-text">Accounts</span>
          </a>
        </li>

        <!-- Log Out Link -->
        <li>
          <a href="../login/logout.php" class="sidebar-link">
            <i class="bi bi-box-arrow-left"></i><span class="sidebar-text">Log out</span>
          </a>
        </li>
      </ul>

      <hr class="border-gray-600 my-2" />

      <!-- Notification Bell -->
      <ul>
        <li class="flex items-center py-2 hover:text-blue-400 cursor-pointer relative" id="notification-bell">
          <a href="../ticket/tickets.php" class="flex items-center">
            <i class="fas fa-bell mr-3"></i>
            <span>Notifications</span>
            <?php if ($new_tickets > 0): ?>
              <span id="notification-count"
                    class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                <?= $new_tickets ?>
              </span>
            <?php endif; ?>
          </a>
        </li>
      </ul>

      <!-- Sign Out -->
      <ul>
        <li class="flex items-center py-2 hover:text-red-400 cursor-pointer">
          <i class="fas fa-sign-out-alt mr-3"></i>
          <span>Sign Out</span>
        </li>
      </ul>
    </div>
  </div>

  <div class="p-4 text-center text-gray-400 text-xs">
    © 2023 Facenote. All rights reserved.
  </div>
</div>

<!-- Main Content (Adjust the layout accordingly) -->
<div class="flex-1 ml-64 p-4">
  <!-- Your page content here -->
</div>

<!-- Notification Sound -->
<audio id="notification-sound" src="../sidebar/notif.mp3" preload="auto"></audio>

<!-- Script -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const bell = document.getElementById('notification-bell');
    const sound = document.getElementById('notification-sound');
    const newTickets = <?= $new_tickets ?>;

    if (newTickets > 0) {
      bell.classList.add('shake');
      sound.play();
    }
  });
</script>

</body>
</html>
