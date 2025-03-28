<?php
// 1. Connect to Database
include '../db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 2. Capture and sanitize form inputs
    $adonwork_no = mysqli_real_escape_string($conn, $_POST['adonwork_no']);
    $monitor = mysqli_real_escape_string($conn, $_POST['monitor']);
    $monitor2 = mysqli_real_escape_string($conn, $_POST['monitor2']);
    $webcam = mysqli_real_escape_string($conn, $_POST['webcam']);
    $headset = mysqli_real_escape_string($conn, $_POST['headset']);
    $keyboard = mysqli_real_escape_string($conn, $_POST['keyboard']);
    $mouse = mysqli_real_escape_string($conn, $_POST['mouse']);
    $laptop = mysqli_real_escape_string($conn, $_POST['laptop']);
    $mboard = mysqli_real_escape_string($conn, $_POST['mboard']);
    $processor = mysqli_real_escape_string($conn, $_POST['processor']);
    $ram = mysqli_real_escape_string($conn, $_POST['ram']);
    $employee = mysqli_real_escape_string($conn, $_POST['employee']);
    $assign_date = mysqli_real_escape_string($conn, $_POST['assign_date']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);

    // 3. Insert into asset assignment table
    $sql = "INSERT INTO asset (adonwork_no, monitor, monitor2, webcam, headset, keyboard, mouse, laptop, mboard, processor, ram, employee, assign_date, location) 
            VALUES ('$adonwork_no', '$monitor', '$monitor2', '$webcam', '$headset', '$keyboard', '$mouse', '$laptop', '$mboard', '$processor', '$ram', '$employee', '$assign_date', '$location')";

    if (mysqli_query($conn, $sql)) {
        // 4. Calculate total price of assigned assets
        $total_price = 0.0;

        function getPrice($conn, $table, $name) {
            if (!empty($name)) {
                $query = "SELECT price FROM $table WHERE name = '$name'";
                $result = mysqli_query($conn, $query);
                if ($row = mysqli_fetch_assoc($result)) {
                    return floatval($row['price']);
                }
            }
            return 0.0;
        }

        // Get total price from all assigned assets
        $total_price += getPrice($conn, 'monitor', $monitor);
        $total_price += getPrice($conn, 'monitor2', $monitor2);
        $total_price += getPrice($conn, 'webcam', $webcam);
        $total_price += getPrice($conn, 'headset', $headset);
        $total_price += getPrice($conn, 'keyboard', $keyboard);
        $total_price += getPrice($conn, 'mouse', $mouse);
        $total_price += getPrice($conn, 'laptop', $laptop);
        $total_price += getPrice($conn, 'mboard', $mboard);
        $total_price += getPrice($conn, 'processor', $processor);
        $total_price += getPrice($conn, 'ram', $ram);

        // 5. Assign status & update inventory
        $assets = [
            'monitor' => ['table' => 'monitor', 'name' => $monitor],
            'monitor2' => ['table' => 'monitor2', 'name' => $monitor2],
            'webcam' => ['table' => 'webcam', 'name' => $webcam],
            'headset' => ['table' => 'headset', 'name' => $headset],
            'keyboard' => ['table' => 'keyboard', 'name' => $keyboard],
            'mouse' => ['table' => 'mouse', 'name' => $mouse],
            'laptop' => ['table' => 'laptop', 'name' => $laptop],
            'mboard' => ['table' => 'mboard', 'name' => $mboard],
            'processor' => ['table' => 'processor', 'name' => $processor],
            'ram' => ['table' => 'ram', 'name' => $ram]
        ];

        foreach ($assets as $type => $asset) {
            if (!empty($asset['name'])) {
                $updateStatus = "UPDATE {$asset['table']} SET status = 'Assigned' WHERE name = '{$asset['name']}'";
                mysqli_query($conn, $updateStatus);

                $updateStock = "UPDATE inventory SET available_stock = available_stock - 1 
                                WHERE type = '$type' AND available_stock > 0";
                mysqli_query($conn, $updateStock);
            }
        }

        // 6. Fetch the assigned employee's name from the asset table
        $getEmployeeQuery = "SELECT employee FROM asset WHERE adonwork_no = '$adonwork_no' LIMIT 1";
        $result = mysqli_query($conn, $getEmployeeQuery);

        if ($row = mysqli_fetch_assoc($result)) {
            $assignedEmployee = $row['employee'];

            // ✅ Update assign_to field in asset tables
            foreach ($assets as $table => $asset) {
                if (!empty($asset['name'])) {
                    $updateAssignTo = "UPDATE {$asset['table']} SET assign_to = '$assignedEmployee' WHERE name = '{$asset['name']}'";
                    if (!mysqli_query($conn, $updateAssignTo)) {
                        error_log("Error updating assign_to for {$asset['table']}: " . mysqli_error($conn));
                    }
                }
            }
        }

        // 7. Update employee record with total price and adonwork_no
        $updateEmployee = "UPDATE employee 
                           SET total_asset_value = total_asset_value + $total_price, 
                               adonwork_no = '$adonwork_no' 
                           WHERE name = '$employee'";
        mysqli_query($conn, $updateEmployee);

        // 8. Success message
        echo "<script>
                alert('Assigned Successfully! Total Price: $$total_price');
                window.location.href='/mis-v6.1/assets/asset.php';
              </script>";
        exit();
    } else {
        error_log("Error inserting asset: " . mysqli_error($conn));
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }

    // 9. Close database connection
    mysqli_close($conn);
}
?>





<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

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

<div class="container mt-5">
    <h2 class="text-center mb-4">Add New Item</h2>

    <form method="POST">
    <div class="row">
        <!-- Basic Info Section (Left Side) -->
        <div class="col-md-6">
            <div class="form-section mb-4">
                <div class="section-header">Basic Info</div>
                <div class="mb-3">
                    <label for="adonwork_no" class="form-label">PPE-ID</label>
                    <input type="text" class="form-control" name="adonwork_no" required>
                </div>
                <div class="mb-3">
                    <label for="keyboard" class="form-label">Keyboard</label>
                    <select class="form-control" id="keyboard" name="keyboard">
                        <option value="">Select Keyboard</option>
                        <?php
                        $result = mysqli_query($conn, "SELECT name FROM keyboard WHERE status != 'Assigned'");
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='{$row['name']}'>{$row['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="mouse" class="form-label">Mouse</label>
                    <select class="form-control" id="mouse" name="mouse">
                        <option value="">Select Mouse</option>
                        <?php
                        $result = mysqli_query($conn, "SELECT name FROM mouse WHERE status != 'Assigned'");
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='{$row['name']}'>{$row['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="monitor" class="form-label">Monitor</label>
                    <select class="form-control" id="monitor" name="monitor">
                        <option value="">Select Monitor</option>
                        <?php
                        $result = mysqli_query($conn, "SELECT name FROM monitor WHERE status != 'Assigned'");
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='{$row['name']}'>{$row['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="monitor2" class="form-label">Monitor 2</label>
                    <select class="form-control" id="monitor2" name="monitor2">
                        <option value="">Select Monitor</option>
                        <?php
                        $result = mysqli_query($conn, "SELECT name FROM monitor2 WHERE status != 'Assigned'");
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='{$row['name']}'>{$row['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="webcam" class="form-label">Webcam</label>
                    <select class="form-control" id="webcam" name="webcam">
                        <option value="">Select Webcam</option>
                        <?php
                        $result = mysqli_query($conn, "SELECT name FROM webcam WHERE status != 'Assigned'");
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='{$row['name']}'>{$row['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="headset" class="form-label">Headset</label>
                    <select class="form-control" id="headset" name="headset">
                        <option value="">Select Headset</option>
                        <?php
                        $result = mysqli_query($conn, "SELECT name FROM headset WHERE status != 'Assigned'");
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='{$row['name']}'>{$row['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Details Section (Right Side) -->
        <div class="col-md-6">
            <div class="form-section mb-4">
                <div class="section-header">Details</div>
                <div class="mb-3">
                    <label for="mboard" class="form-label">Motherboard</label>
                    <select class="form-control" id="mboard" name="mboard">
                        <option value="">Select Motherboard</option>
                        <?php
                        $result = mysqli_query($conn, "SELECT name FROM mboard WHERE status != 'Assigned'");
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='{$row['name']}'>{$row['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="processor" class="form-label">Processor</label>
                    <select class="form-control" id="processor" name="processor">
                        <option value="">Select Processor</option>
                        <?php
                        $result = mysqli_query($conn, "SELECT name FROM processor WHERE status != 'Assigned'");
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='{$row['name']}'>{$row['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="ram" class="form-label">RAM</label>
                    <select class="form-control" id="ram" name="ram">
                        <option value="">Select RAM</option>
                        <?php
                        $result = mysqli_query($conn, "SELECT name FROM ram WHERE status != 'Assigned'");
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='{$row['name']}'>{$row['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="laptop" class="form-label">Laptop</label>
                    <select class="form-control" id="laptop" name="laptop">
                        <option value="">Select Laptop</option>
                        <?php
                        include '../db_connection.php';
                        // Fetch available laptops
                        $result = mysqli_query($conn, "SELECT name FROM laptop WHERE status != 'Assigned'");
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='{$row['name']}'>{$row['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Location:</label>
                        <select class="form-control" name="location" required>
                            <option value="" disabled selected>Select Location</option>
                            <option value="HR 1">HR 1</option>
                            <option value="HR 2">HR 2</option>
                            <option value="Storage Room">Storage Room</option>
                            <option value="Management">Management</option>
                            <option value="BoardRoom">BoardRoom</option>
                            <option value="AdOn">AdOn</option>
                            <option value="WorkForce">WorkForce</option>
                            <option value="Ikomo5 SolarRay">Ikomo5 SolarRay</option>
                        </select>
                </div>
                <div class="mb-3">
                    <label for="assign_date" class="form-label">Assign Date</label>
                    <input type="date" class="form-control" name="assign_date">
                </div>

                <div class="mb-3">
                    <label for="employee" class="form-label">Assign To</label>
                    <select class="form-control" id="employee" name="employee">
                        <option value="">Select Employee</option>
                        <?php
                        include '../db_connection.php';
                        // Fetch available laptops
                        $result = mysqli_query($conn, "SELECT name FROM employee WHERE status != 'Assigned'");
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='{$row['name']}'>{$row['name']}</option>";
                        }
                        ?>
                    </select>
                </div>

            </div>
        </div>
    </div>

    <!-- Submit & Cancel Buttons -->
    <div class="text-center mt-4">
        <button type="submit" class="btn btn-primary">Add Item</button>
        <a href="/mis-v6.1/assets/asset.php" class="btn btn-secondary">Cancel</a>
    </div>
</form>

</div>
