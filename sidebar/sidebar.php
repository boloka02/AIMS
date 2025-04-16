<?php
// Ensure session is started only once
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<html>
 <head>
  <script src="https://cdn.tailwindcss.com">
  </script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&amp;display=swap" rel="stylesheet"/>
  <style>
   .switch {
            position: relative;
            display: inline-block;
            width: 34px;
            height: 20px;
        }
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 12px;
            width: 12px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .slider {
            background-color: #2196F3;
        }
        input:checked + .slider:before {
            transform: translateX(14px);
        }
  </style>
 </head>
 <body class="bg-gray-200 h-screen">
  <div id="sidebar" class="bg-gray-800 text-white w-64 p-4 rounded-lg h-full fixed flex flex-col justify-between">
   <div>
    <div class="flex items-center mb-6">
     <img alt="Profile picture" class="w-10 h-10 rounded-full mr-3" height="40" src="https://oaidalleapiprodscus.blob.core.windows.net/private/org-LmQ09WWGIGwOeeA4ArnRw0x5/user-uJPET5fjNenSso8wCETWVNOp/img-oYQkLpYjeC2QtDOyUAEqIW68.png?st=2025-04-15T22%3A17%3A42Z&amp;se=2025-04-16T00%3A17%3A42Z&amp;sp=r&amp;sv=2024-08-04&amp;sr=b&amp;rscd=inline&amp;rsct=image/png&amp;skoid=cc612491-d948-4d2e-9821-2683df3719f5&amp;sktid=a48cca56-e6da-484e-a814-9c849652bcb3&amp;skt=2025-04-15T14%3A31%3A12Z&amp;ske=2025-04-16T14%3A31%3A12Z&amp;sks=b&amp;skv=2024-08-04&amp;sig=CoShWeM7f95rI1aQ9ovvGU/K2z/Sd0bqvPsISDEFKcE%3D" width="40"/>
     <div>
      <div class="font-bold">
       <?php echo htmlspecialchars($_SESSION['name']); ?>
      </div>
      <div class="text-sm text-gray-400">
       Web developer.
      </div>
     </div>
    </div>
    <div class="mb-4">
     <ul>
      <li>
       <a class="sidebar-link flex items-center p-2 rounded-lg hover:bg-gray-700 cursor-pointer" href="../dashboard/dashboard.php">
        <i class="bi bi-speedometer2 mr-3">
        </i>
        <span class="sidebar-text">
         Dashboard
        </span>
       </a>
      </li>
      <li>
       <a class="sidebar-link flex items-center p-2 rounded-lg bg-gray-700 cursor-pointer" href="../inventory/inventory.php">
        <i class="bi bi-box-seam mr-3">
        </i>
        <span class="sidebar-text">
         Inventory
        </span>
       </a>
      </li>
      <li>
       <a class="sidebar-link flex items-center p-2 rounded-lg hover:bg-gray-700 cursor-pointer" href="../assets/asset.php">
        <i class="bi bi-laptop mr-3">
        </i>
        <span class="sidebar-text">
         Assets
        </span>
       </a>
      </li>
      <li>
       <a class="sidebar-link flex items-center p-2 rounded-lg hover:bg-gray-700 cursor-pointer" href="../employee/employee.php">
        <i class="bi bi-people mr-3">
        </i>
        <span class="sidebar-text">
         Employee
        </span>
       </a>
      </li>
      <li>
       <a class="sidebar-link flex items-center p-2 rounded-lg hover:bg-gray-700 cursor-pointer" href="../ticket/tickets.php">
        <i class="fas fa-ticket-alt mr-3">
        </i>
        <span class="sidebar-text">
         Tickets
        </span>
       </a>
      </li>
      <li>
       <a class="sidebar-link flex items-center p-2 rounded-lg hover:bg-gray-700 cursor-pointer" href="../account/account.php">
        <i class="bi bi-people mr-3">
        </i>
        <span class="sidebar-text">
         Accounts
        </span>
       </a>
      </li>
      <li>
       <a class="sidebar-link flex items-center p-2 rounded-lg hover:bg-gray-700 cursor-pointer" href="../login/logout.php">
        <i class="bi bi-box-arrow-left mr-3">
        </i>
        <span class="sidebar-text">
         Log out
        </span>
       </a>
      </li>
     </ul>
    </div>
    <div class="border-t border-gray-700 mt-4 pt-4">
     <div class="flex items-center p-2 rounded-lg hover:bg-gray-700 cursor-pointer">
      <i class="fas fa-sign-out-alt mr-3">
      </i>
      <span>
       Signout
      </span>
     </div>
     <div class="flex items-center p-2 rounded-lg hover:bg-gray-700 cursor-pointer mt-4">
      <i class="fas fa-moon mr-3">
      </i>
      <span>
       Light Mode
      </span>
      <div class="ml-auto">
       <label class="switch">
        <input id="toggle" type="checkbox"/>
        <span class="slider round">
        </span>
       </label>
      </div>
     </div>
    </div>
   </div>
   <div class="border-t border-gray-700 mt-4 pt-4 text-center text-gray-400 text-xs">
    &copy; 2023 Your Company
   </div>
  </div>
  <script>
   document.getElementById('toggle').addEventListener('change', function (event) {
            const sidebar = document.getElementById('sidebar');
            if (event.target.checked) {
                sidebar.classList.remove('bg-gray-800', 'text-white');
                sidebar.classList.add('bg-white', 'text-black');
                document.querySelectorAll('.bg-gray-700').forEach(function (element) {
                    element.classList.remove('bg-gray-700');
                    element.classList.add('bg-gray-200');
                });
                document.querySelectorAll('.text-gray-400').forEach(function (element) {
                    element.classList.remove('text-gray-400');
                    element.classList.add('text-gray-600');
                });
                document.querySelectorAll('.hover\\:bg-gray-700').forEach(function (element) {
                    element.classList.remove('hover:bg-gray-700');
                    element.classList.add('hover:bg-gray-300');
                });
                document.querySelectorAll('.border-gray-700').forEach(function (element) {
                    element.classList.remove('border-gray-700');
                    element.classList.add('border-gray-300');
                });
            } else {
                sidebar.classList.remove('bg-white', 'text-black');
                sidebar.classList.add('bg-gray-800', 'text-white');
                document.querySelectorAll('.bg-gray-200').forEach(function (element) {
                    element.classList.remove('bg-gray-200');
                    element.classList.add('bg-gray-700');
                });
                document.querySelectorAll('.text-gray-600').forEach(function (element) {
                    element.classList.remove('text-gray-600');
                    element.classList.add('text-gray-400');
                });
                document.querySelectorAll('.hover\\:bg-gray-300').forEach(function (element) {
                    element.classList.remove('hover:bg-gray-300');
                    element.classList.add('hover:bg-gray-700');
                });
                document.querySelectorAll('.border-gray-300').forEach(function (element) {
                    element.classList.remove('border-gray-300');
                    element.classList.add('border-gray-700');
                });
            }
        });
  </script>
 </body>
</html>