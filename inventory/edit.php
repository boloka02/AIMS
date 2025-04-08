
<?php
include '../db_connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch asset details from inventory
    $query = "SELECT * FROM inventory WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "<script>alert('Asset not found!'); window.location.href='/misv6/sidedisplay/sidedisplay.php?page=inventory';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid Request!'); window.location.href='/misv6/sidedisplay/sidedisplay.php?page=inventory';</script>";
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];
    $category = $_POST['category'];
    $new_quantity = intval($_POST['quantity']);
    $available_stock = intval($_POST['available_stock']);
    $total_value = floatval($_POST['total_value']);
    $purchasedate = $_POST['purchasedate'];
    $warranty = $_POST['warranty'];
    $location = $_POST['location'];

    // Define table mappings
    $tableMapping = [
        "Laptop" => "laptop",
        "Headset" => "headset",
        "Keyboard" => "keyboard",
        "Mboard" => "mboard",
        "Monitor" => "monitor",
        "2nd Monitor" => "monitor2",
        "Mouse" => "mouse",
        "Processor" => "processor",
        "RAM" => "ram",
        "Webcam" => "webcam"
    ];

    if (!array_key_exists($type, $tableMapping)) {
        echo "<script>alert('Invalid asset type!');</script>";
        exit();
    }

    $table_name = $tableMapping[$type];

    // Get the current quantity from the database
    $current_quantity = intval($row['quantity']);

    // Assign correct prefix based on asset type
    $prefix = strtoupper(substr($type, 0, 2));
    if ($type == "Mboard") {
        $prefix = "MB";
    } elseif ($type == "Monitor") {
        $prefix = "MT";
    } elseif ($type == "2nd Monitor") {
        $prefix = "MTnd";
    }

    // If quantity increased, generate new asset names and insert them
    if ($new_quantity > $current_quantity) {
        $diff = $new_quantity - $current_quantity;

        // Fetch the last used ID in the specific asset table
        $last_id_query = "SELECT name FROM $table_name WHERE name LIKE '$prefix-%' ORDER BY name DESC LIMIT 1";
        $last_id_result = mysqli_query($conn, $last_id_query);
        $last_number = 0;

        if ($row_id = mysqli_fetch_assoc($last_id_result)) {
            preg_match("/^$prefix-(\d+)$/", $row_id['name'], $matches);
            if ($matches) {
                $last_number = intval($matches[1]);
            }
        }

        // Insert new assets with unique IDs
        $insert_query = "INSERT INTO $table_name (name) VALUES (?)";
        $stmt = mysqli_prepare($conn, $insert_query);

        for ($i = 1; $i <= $diff; $i++) {
            $new_id = sprintf("$prefix-%05d", $last_number + $i);
            mysqli_stmt_bind_param($stmt, "s", $new_id);
            mysqli_stmt_execute($stmt);
        }
        mysqli_stmt_close($stmt);
    }

    // If quantity decreased, remove assets from the database
    if ($new_quantity < $current_quantity) {
        $diff = $current_quantity - $new_quantity;

        // Fetch and delete the last N rows from the correct table
        $delete_query = "DELETE FROM $table_name WHERE name LIKE '$prefix-%' ORDER BY name DESC LIMIT ?";
        $stmt = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($stmt, "i", $diff);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // ✅ **Stock Status Update**
    $percentage = ($available_stock / max($new_quantity, 1)) * 100;
    if ($available_stock == 0) {
        $stock = "Out of Stock";
        $status = "On Order";
    } elseif ($percentage <= 49) {
        $stock = "Low Stock";
        $status = "Available";
    } else {
        $stock = "In Stock";
        $status = "Available";
    }

    // ✅ **Update inventory table**
    $updateQuery = "UPDATE inventory SET 
                    type=?, category=?, quantity=?, available_stock=?, 
                    total_value=?, stock=?, status=?, purchasedate=?, warranty=?, location=? 
                    WHERE id=?";
    $stmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($stmt, "ssiidsssssi", 
        $type, $category, $new_quantity, $available_stock, 
        $total_value, $stock, $status, $purchasedate, $warranty, $location, $id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
        alert('Inventory updated successfully!');
        window.location.href = '/AIMS/inventory/inventory.php?page=inventory';
        </script>";
        exit();
    } else {
        echo "<script>alert('Error updating inventory!');</script>";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="icon" type="image/jpeg" href="https://adongroup.com.au/wp-content/uploads/2020/12/aog-favicon-192px.svg">
    <title>ADON PH</title>
    <style>
        .form-section {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .section-header {
            font-size: 1.2rem;
            font-weight: bold;
            color: #2E86C1;
            border-bottom: 2px solid #2E86C1;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Update</h2>
    
    <form method="POST">
        <!-- Basic Info Section -->
        <div class="form-section mb-4">
            <div class="section-header">Basic Info</div>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Type:</label>
                    <input type="text" class="form-control" name="type" value="<?= $row['type'] ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Category:</label>
                    <input type="text" class="form-control" name="category" value="<?= $row['category'] ?>" required>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Status:</label>
                    <input type="text" class="form-control" value="<?= $row['status'] ?>" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Quantity:</label>
                    <input type="number" class="form-control" name="quantity" value="<?= $row['quantity'] ?>" required>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Total Value:</label>
                    <input type="number" class="form-control" name="total_value" value="<?= $row['total_value'] ?>" required>
                </div>
            </div>
        </div>

        <!-- Details Section -->
        <div class="form-section mb-4">
            <div class="section-header">Details</div>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Stock:</label>
                    <input type="text" class="form-control" value="<?= $row['stock'] ?>" disabled>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Available Stock:</label>
                    <input type="number" class="form-control" name="available_stock" value="<?= $row['available_stock'] ?>" required>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Purchase Date:</label>
                    <input type="date" class="form-control" name="purchasedate" value="<?= $row['purchasedate'] ?>" required>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Warranty:</label>
                    <input type="text" class="form-control" name="warranty" value="<?= $row['warranty'] ?>">
                </div>

                <div class="col-md-12 mt-3">
                    <label class="form-label">Location:</label>
                    <input type="text" class="form-control" name="location" value="<?= $row['location'] ?>" required>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="/AIMS/inventory/inventory.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</body>
</html>
