

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hamburger Toggle Sidebar</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../sidebar/side.css">
</head>
<body>
    <button class="hamburger-button">
        <i class="bi bi-list"></i>
    </button>

    <div class="sidebar">  
        <div class="sidebar-header">ADON-Ams</div>

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
        <a href="../login/logout.php" class="sidebar-link">
            <i class="bi bi-box-arrow-left"></i><span class="sidebar-text">Log out</span>
        </a>

        <div class="bottom-section">
            <div class="user">User: <?php echo htmlspecialchars($_SESSION['name']); ?></div>
            <div class="attribution">@Ad0nPH2025</div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../dashboard/charts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
