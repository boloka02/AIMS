<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css" rel="stylesheet"/>
</head>
<body class="bg-gray-200 dark:bg-gray-900 text-gray-900 dark:text-white transition-colors duration-300">

<div class="flex h-screen">
    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 bg-gray-800 text-white fixed h-full p-4 transition-all duration-300 z-40">
        <!-- Profile -->
        <div class="flex items-center mb-6">
            <img src="https://via.placeholder.com/40" class="w-10 h-10 rounded-full mr-3" alt="Profile Picture" />
            <div>
                <div class="font-bold">
                    <?= htmlspecialchars($_SESSION['name']) ?>
                </div>
                <div class="text-sm text-gray-400">Web developer.</div>
            </div>
        </div>

        <!-- Navigation -->
        <ul>
            <li><a href="../dashboard/dashboard.php" class="flex items-center p-2 rounded hover:bg-gray-700"><i class="bi bi-speedometer2 mr-3"></i>Dashboard</a></li>
            <li><a href="../inventory/inventory.php" class="flex items-center p-2 rounded bg-gray-700"><i class="bi bi-box-seam mr-3"></i>Inventory</a></li>
            <li><a href="../assets/asset.php" class="flex items-center p-2 rounded hover:bg-gray-700"><i class="bi bi-laptop mr-3"></i>Assets</a></li>
            <li><a href="../employee/employee.php" class="flex items-center p-2 rounded hover:bg-gray-700"><i class="bi bi-people mr-3"></i>Employee</a></li>
            <li><a href="../ticket/tickets.php" class="flex items-center p-2 rounded hover:bg-gray-700"><i class="fas fa-ticket-alt mr-3"></i>Tickets</a></li>
            <li><a href="../account/account.php" class="flex items-center p-2 rounded hover:bg-gray-700"><i class="bi bi-people mr-3"></i>Accounts</a></li>
            <li><a href="../login/logout.php" class="flex items-center p-2 rounded hover:bg-gray-700"><i class="bi bi-box-arrow-left mr-3"></i>Log out</a></li>
        </ul>

        <!-- Toggle -->
        <div class="mt-8 border-t border-gray-600 pt-4">
            <div class="flex items-center justify-between">
                <span><i class="fas fa-moon mr-2"></i>Light Mode</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="modeToggle" class="sr-only">
                    <div class="w-11 h-6 bg-gray-400 rounded-full peer dark:bg-gray-700 peer-checked:bg-blue-600 peer-focus:ring-blue-300 transition"></div>
                    <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-full"></div>
                </label>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-xs text-center text-gray-400 mt-10">&copy; 2023 Your Company</div>
    </aside>

    <!-- Main content -->
    <main class="ml-64 flex-1 p-6 overflow-y-auto">
        <!-- Place your dynamic content here -->
        <h1 class="text-2xl font-bold mb-4">Welcome to the Dashboard</h1>
        <!-- You can now include your content here -->
    </main>
</div>

<!-- Dark Mode Script -->
<script>
    const toggle = document.getElementById('modeToggle');
    const html = document.querySelector('html');

    // Restore from localStorage
    if (localStorage.getItem("theme") === "dark") {
        html.classList.add("dark");
        toggle.checked = true;
    }

    toggle.addEventListener('change', function () {
        if (this.checked) {
            html.classList.add("dark");
            localStorage.setItem("theme", "dark");
        } else {
            html.classList.remove("dark");
            localStorage.setItem("theme", "light");
        }
    });
</script>

</body>
</html>
