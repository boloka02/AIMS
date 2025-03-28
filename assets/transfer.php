<?php
// transfer.php

include '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    // Get the asset ID from the URL
    $assetId = $_GET['id'];

    // Fetch the asset details using the asset ID (only adonwork_no and employee)
    $query = "SELECT adonwork_no, employee FROM asset WHERE id = '$assetId'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $asset = mysqli_fetch_assoc($result);
    } else {
        echo "Error fetching asset: " . mysqli_error($conn);
        exit;
    }

    // Fetch employees who do not have an asset assigned (assuming adonwork_no is not assigned to them)
    $employeeQuery = "SELECT id, name FROM employee WHERE adonwork_no = ''"; // Check for empty adonwork_no
    $employeeResult = mysqli_query($conn, $employeeQuery);

    if (!$employeeResult) {
        echo "Error fetching employees: " . mysqli_error($conn);
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_employee_id'])) {
    // Handle the transfer (POST request)
    $newEmployeeId = $_POST['new_employee_id'];
    $assetId = $_POST['asset_id'];

    // Fetch the asset data again to get the adonwork_no and current employee's name
    $query = "SELECT adonwork_no, employee FROM asset WHERE id = '$assetId'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $asset = mysqli_fetch_assoc($result);
    } else {
        echo "Error fetching asset: " . mysqli_error($conn);
        exit;
    }

    // Fetch the previous employee's name using their ID
    $previousEmployeeId = $asset['employee'];
    $adonwork_no = $asset['adonwork_no'];
    
    // Fetch the name of the previous employee
    $previousEmployeeQuery = "SELECT name FROM employee WHERE id = '$previousEmployeeId'";
    $previousEmployeeResult = mysqli_query($conn, $previousEmployeeQuery);
    if ($previousEmployeeResult) {
        $previousEmployee = mysqli_fetch_assoc($previousEmployeeResult);
    } else {
        echo "Error fetching previous employee: " . mysqli_error($conn);
        exit;
    }
    
    // Step 1: Remove the adonwork_no from the previous employee (set it to an empty string)
    $removePreviousEmployeeQuery = "UPDATE employee SET adonwork_no = '' WHERE id = '$previousEmployeeId'";
    if (!mysqli_query($conn, $removePreviousEmployeeQuery)) {
        echo "Error removing adonwork_no from previous employee: " . mysqli_error($conn);
        exit;
    }

    // Step 2: Fetch the new employee's name
    $newEmployeeQuery = "SELECT name FROM employee WHERE id = '$newEmployeeId'";
    $newEmployeeResult = mysqli_query($conn, $newEmployeeQuery);
    if ($newEmployeeResult) {
        $newEmployee = mysqli_fetch_assoc($newEmployeeResult);
    } else {
        echo "Error fetching new employee: " . mysqli_error($conn);
        exit;
    }
    
    // Step 3: Update the asset record with the new employee's name
    $updateAssetQuery = "UPDATE asset SET employee = '" . $newEmployee['name'] . "' WHERE id = '$assetId'";
    if (mysqli_query($conn, $updateAssetQuery)) {
        // Step 4: Now, assign the adonwork_no to the selected new employee
        $updateEmployeeQuery = "UPDATE employee SET adonwork_no = '$adonwork_no' WHERE id = '$newEmployeeId'";
        if (mysqli_query($conn, $updateEmployeeQuery)) {
            // Redirect back to the inventory page after successful transfer
            header('Location: /mis-v6.1/assets/asset.php');
            exit();
        } else {
            echo "Error updating new employee with adonwork_no: " . mysqli_error($conn);
        }
    } else {
        echo "Error updating asset: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Asset</title>
</head>

<body>
    <h3>Transfer Asset (PPE No: <?php echo htmlspecialchars($asset['adonwork_no']); ?>)</h3>

    <form method="POST" action="transfer.php">
        <input type="hidden" name="asset_id" value="<?php echo $assetId; ?>">

        <label for="new_employee">Select Employee:</label>
        <select id="new_employee" name="new_employee_id">
            <?php
            // Check if employee result exists and has rows
            if (mysqli_num_rows($employeeResult) > 0) {
                while ($row = mysqli_fetch_assoc($employeeResult)): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                <?php endwhile;
            } else {
                echo "<option value=''>No employees available</option>";
            }
            ?>
        </select>
            
        <button type="submit">Transfer</button>
    </form>

</body>

</html>
