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
  <title>Facenote v7</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: 'Arial', sans-serif;
    }
  </style>
</head>
<body class="h-screen flex bg-cover bg-center" style="background-image: url('../image/pc.png');">

  <!-- Sidebar -->
  <aside class="w-16 md:w-64 bg-black bg-opacity-50 text-white flex flex-col justify-between h-full transition-all duration-300">
    <div>

        <!-- Branding -->
    <div class="p-4 flex justify-center md:justify-start items-center">
    <i class="fas fa-fire text-xl md:hidden mr-2"></i> <!-- Add margin-right to the icon -->
    <h1 class="text-xl md:text-2xl font-bold hidden md:block">AIMS v3</h1>
    </div>


      <hr class="border-gray-600 mx-4" />

      <!-- User Profile -->
      <div class="p-4 flex items-center justify-center md:justify-start">
        <img src="image/image.jpg" class="w-10 h-10 rounded-full" alt="User profile picture">
        <div class="ml-4 hidden md:block"> <!-- Increased left margin -->
            <p class="font-semibold"><?php echo htmlspecialchars($_SESSION['name']); ?></p>
            <p class="text-sm text-gray-300">My Account</p>
        </div>
        </div>

      <hr class="border-gray-600 mx-4" />

      <!-- Navigation -->
      <nav class="p-2 md:p-4">
        <ul class="space-y-1">
          <li class="group flex items-center justify-center md:justify-start py-3 px-4 hover:text-blue-400 cursor-pointer gap-0 md:gap-4">
            <a href="../dashboard/dashboard.php" class="flex items-center">
              <i class="fas fa-tachometer-alt w-6 text-center"></i>
              <span class="ml-2 text-sm md:text-base hidden group-hover:inline md:inline">Dashboard</span>
            </a>
          </li>
          <li class="group flex items-center justify-center md:justify-start py-3 px-4 hover:text-blue-400 cursor-pointer gap-0 md:gap-4">
            <a href="../inventory/inventory.php" class="flex items-center">
              <i class="fas fa-boxes w-6 text-center"></i>
              <span class="ml-2 text-sm md:text-base hidden group-hover:inline md:inline">Inventory</span>
            </a>
          </li>
          <li class="group flex items-center justify-center md:justify-start py-3 px-4 hover:text-blue-400 cursor-pointer gap-0 md:gap-4">
            <a href="../assets/asset.php" class="flex items-center">
              <i class="fas fa-warehouse w-6 text-center"></i>
              <span class="ml-2 text-sm md:text-base hidden group-hover:inline md:inline">Asset</span>
            </a>
          </li>
          <li class="group flex items-center justify-center md:justify-start py-3 px-4 hover:text-blue-400 cursor-pointer gap-0 md:gap-4">
            <a href="../employee/employee.php" class="flex items-center">
              <i class="fas fa-users w-6 text-center"></i>
              <span class="ml-2 text-sm md:text-base hidden group-hover:inline md:inline">Employee</span>
            </a>
          </li>
          <li class="group flex items-center justify-center md:justify-start py-3 px-4 hover:text-blue-400 cursor-pointer gap-0 md:gap-4">
            <a href="../account/account.php" class="flex items-center">
              <i class="fas fa-user-circle w-6 text-center"></i>
              <span class="ml-2 text-sm md:text-base hidden group-hover:inline md:inline">Account</span>
            </a>
          </li>
          <li class="group flex items-center justify-center md:justify-start py-3 px-4 hover:text-blue-400 cursor-pointer gap-0 md:gap-4">
            <a href="../ticket/tickets.php" class="flex items-center">
              <i class="fas fa-ticket-alt w-6 text-center"></i>
              <span class="ml-2 text-sm md:text-base hidden group-hover:inline md:inline">Tickets</span>
              <span class="ml-auto text-blue-500 hidden md:inline">•</span>
            </a>
          </li>
        </ul>

        <hr class="border-gray-600 my-4" />

        <ul>
          <li class="group flex items-center justify-center md:justify-start py-3 px-4 hover:text-red-400 cursor-pointer gap-0 md:gap-4">
            <a href="../login/logout.php" class="flex items-center">
              <i class="fas fa-sign-out-alt w-6 text-center"></i>
              <span class="ml-2 text-sm md:text-base hidden group-hover:inline md:inline">Sign Out</span>
            </a>
          </li>
        </ul>
      </nav>
    </div>

    <!-- Footer -->
    <div class="p-4 text-center text-gray-400 text-xs hidden md:block">
      © 2025 ADON PH. All rights reserved.
    </div>
  </aside>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
