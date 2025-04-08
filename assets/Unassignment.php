<?php
include '../db_connection.php';

if (isset($_GET['id'])) {
    $asset_id = $_GET['id'];

    // Fetch asset details
    $query = "SELECT * FROM asset WHERE id = '$asset_id'";
    $result = mysqli_query($conn, $query);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $fields = ['mboard', 'keyboard', 'mouse', 'monitor', 'monitor2', 'webcam', 'headset', 'processor', 'ram', 'laptop'];

        foreach ($fields as $field) {
            if (!empty($row[$field])) {
                $asset_name = $row[$field];

                // **Update asset status to "Available" and remove assign_to**
                $update_query = "UPDATE `$field` SET status = 'Available', assign_to = NULL WHERE name = '$asset_name'";
                if (!mysqli_query($conn, $update_query)) {
                    die("Error updating asset status in $field: " . mysqli_error($conn));
                }

                // **Ensure 2nd Monitor updates correctly**
                $inventory_type = ($field == 'monitor2') ? '2nd Monitor' : $field;
                $stock_update_query = "UPDATE `inventory` SET available_stock = available_stock + 1 WHERE type = '$inventory_type'";
                
                if (!mysqli_query($conn, $stock_update_query)) {
                    die("Error updating available_stock in inventory for $inventory_type: " . mysqli_error($conn));
                }
            }
        }

        // **Update employee table by removing AdonWork No**
        $update_employee = "UPDATE employee SET adonwork_no = '', total_asset_value = 0 WHERE adonwork_no = '{$row['adonwork_no']}'";
        if (!mysqli_query($conn, $update_employee)) {
            die("Error updating employee record: " . mysqli_error($conn));
        }

        // **Delete the asset from asset table**
        $delete_asset = "DELETE FROM asset WHERE id = '$asset_id'";
        if (!mysqli_query($conn, $delete_asset)) {
            die("Error deleting asset: " . mysqli_error($conn));
        }
    }

    mysqli_close($conn);

    // Success alert and redirection
    echo "<script>alert('Unassigned Successfully'); window.location.href = '../assets/asset.php';</script>";
    exit();
} else {
    echo "Invalid request.";
}
?>
