<?php
include '../db_connection.php';

if (isset($_GET['id'])) {
    $asset_id = $_GET['id'];

    // Fetch asset details from inventory
    $query = "SELECT * FROM inventory WHERE id = '$asset_id'";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $asset_type = strtolower(trim($row['type'])); // Normalize type to lowercase
        $asset_type = str_replace(' ', '_', $asset_type); // Convert spaces to underscores

        // Map asset type to correct database table (ALL KEYS in lowercase with underscores)
        $table_map = [
            'mboard' => 'mboard',
            'keyboard' => 'keyboard',
            'mouse' => 'mouse',
            'monitor' => 'monitor',
            '2nd_monitor' => 'monitor2', // Correct mapping for "2nd Monitor"
            'webcam' => 'webcam',
            'headset' => 'headset',
            'processor' => 'processor',
            'ram' => 'ram',
            'laptop' => 'laptop',
            'biometric' => 'biometric', // Ensure lowercase key
            'patch_cord' => 'patch_cord',
            'printer' => 'printer', 
            'router' => 'router',
             'switch' => 'switch',
             'avr' => 'avr',
             'adaptor' => 'adaptor',
                'cctv' => 'cctv',
                'ups' => 'ups',
                'modem' => 'modem'

        ];

        if (!isset($table_map[$asset_type])) {
            die("❌ ERROR: Asset type '$asset_type' is NOT found in table mapping!");
        }

        $table_name = $table_map[$asset_type];

        // Delete all records from the corresponding asset table
        $delete_asset_query = "DELETE FROM `$table_name`";
        if (!mysqli_query($conn, $delete_asset_query)) {
            die("❌ ERROR: Failed to delete assets from `$table_name`: " . mysqli_error($conn));
        }

        // Delete the asset from inventory table
        $delete_inventory_query = "DELETE FROM inventory WHERE id = '$asset_id'";
        if (!mysqli_query($conn, $delete_inventory_query)) {
            die("❌ ERROR: Failed to delete from inventory: " . mysqli_error($conn));
        }

        mysqli_close($conn);
        echo "<script>alert('Asset Deleted Successfully'); window.location.href='/AIMS/inventory/inventory.php';</script>";
        exit();
    } else {
        die("❌ ERROR: Asset ID `$asset_id` not found in inventory.");
    }
} else {
    die("❌ ERROR: Invalid request. No asset ID provided.");
}
?>
