<?php
include('/path/to/db_connection.php');



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure all POST variables are being received correctly
    // You can uncomment this to debug
    // var_dump($_POST);

    // Validate and sanitize POST inputs
    $type = $_POST['type'];
    $category = $_POST['category'];
    $quantity = intval($_POST['quantity']);
    $total_value = floatval($_POST['total_value']);
    $available_stock = intval($_POST['available_stock']);
    $purchasedate = !empty($_POST['purchasedate']) ? $_POST['purchasedate'] : date('Y-m-d');
    $warranty = $_POST['warranty'];
    $location = $_POST['location'];
    $size = $_POST['size'] ?? ''; // Monitor Size
    $capacity = $_POST['capacity'] ?? ''; // RAM Capacity
    $price = isset($_POST['laptop_price']) ? floatval($_POST['laptop_price']) : 0;
    $supplier = isset($_POST['laptop_supplier']) ? $_POST['laptop_supplier'] : '';
    $model = $_POST['laptop_model'] ?? ''; // Laptop and Processor Model

    // Auto-calculate stock status
    $percentage = ($available_stock / max($quantity, 1)) * 100;
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

    // Table mapping
    $tableMapping = [
        "Laptop" => "laptop", "Headset" => "headset", "Keyboard" => "keyboard", "Mboard" => "mboard",
        "Monitor" => "monitor", "2nd Monitor" => "monitor2", "Mouse" => "mouse", "Processor" => "processor",
        "RAM" => "ram", "Webcam" => "webcam", "AVR" => "avr", "Adaptor" => "adaptor", "Biometric" => "biometric",
        "Patch Cord" => "patch_cord", "Printer" => "printer", "Router" => "router", "Switch" => "switch",
        "CCTV" => "cctv", "UPS" => "ups", "Modem" => "modem"
    ];

    // Prefix mapping
    $prefixMapping = [
        "Mboard" => "MB", "Monitor" => "MT", "2nd Monitor" => "MTnd", "RAM" => "RM", "Processor" => "PR",
        "AVR" => "AVR", "Adaptor" => "AP", "Biometric" => "BIO", "Patch Cord" => "PTC", "Printer" => "PRNT",
        "Router" => "RT", "Switch" => "SWT", "CCTV" => "CCTV", "UPS" => "UPS", "Modem" => "MDM"
    ];

    if (array_key_exists($type, $tableMapping)) {
        $tableName = $tableMapping[$type];
        $prefix = $prefixMapping[$type] ?? strtoupper(substr($type, 0, 2));

        // Fetch last inserted asset ID
        $queryLast = "SELECT name FROM $tableName WHERE name LIKE '$prefix-%' ORDER BY name DESC LIMIT 1";
        $resultLast = mysqli_query($conn, $queryLast);
        if (!$resultLast) {
            echo "Error fetching last record: " . mysqli_error($conn);
            exit;
        }

        $lastNumber = 0;

        if ($row = mysqli_fetch_assoc($resultLast)) {
            preg_match("/^$prefix-(\d+)$/", $row['name'], $matches);
            if ($matches) {
                $lastNumber = intval($matches[1]);
            }
        }

        // Insert multiple assets with unique names
        for ($i = 1; $i <= $quantity; $i++) {
            $generated_name = sprintf("$prefix-%05d", $lastNumber + $i);

            // Prepare the query based on item type
            if ($type === "Monitor" || $type === "2nd Monitor") {
                $queryInsert = "INSERT INTO $tableName (name, size, price, supplier, purchase_date, warranty) VALUES (?, ?, ?, ?, ?, ?)";
                $stmtInsert = mysqli_prepare($conn, $queryInsert);
                if (!$stmtInsert) {
                    echo "Error preparing query: " . mysqli_error($conn);
                    exit;
                }
                mysqli_stmt_bind_param($stmtInsert, "ssdsss", $generated_name, $size, $price, $supplier, $purchasedate, $warranty);
            } elseif ($type === "Laptop" || $type === "Processor") {
                $queryInsert = "INSERT INTO $tableName (name, model, price, supplier, purchase_date, warranty) VALUES (?, ?, ?, ?, ?, ?)";
                $stmtInsert = mysqli_prepare($conn, $queryInsert);
                if (!$stmtInsert) {
                    echo "Error preparing query: " . mysqli_error($conn);
                    exit;
                }
                mysqli_stmt_bind_param($stmtInsert, "ssdsss", $generated_name, $model, $price, $supplier, $purchasedate, $warranty);
            } elseif ($type === "RAM") {
                $queryInsert = "INSERT INTO $tableName (name, capacity, price, supplier, purchase_date, warranty) VALUES (?, ?, ?, ?, ?, ?)";
                $stmtInsert = mysqli_prepare($conn, $queryInsert);
                if (!$stmtInsert) {
                    echo "Error preparing query: " . mysqli_error($conn);
                    exit;
                }
                mysqli_stmt_bind_param($stmtInsert, "ssdsss", $generated_name, $capacity, $price, $supplier, $purchasedate, $warranty);
            } else {
                $queryInsert = "INSERT INTO $tableName (name, price, supplier, purchase_date, warranty) VALUES (?, ?, ?, ?, ?)";
                $stmtInsert = mysqli_prepare($conn, $queryInsert);
                if (!$stmtInsert) {
                    echo "Error preparing query: " . mysqli_error($conn);
                    exit;
                }
                mysqli_stmt_bind_param($stmtInsert, "sdsss", $generated_name, $price, $supplier, $purchasedate, $warranty);
            }
            if (!mysqli_stmt_execute($stmtInsert)) {
                echo "Error executing query: " . mysqli_error($conn);
                exit;
            }
            mysqli_stmt_close($stmtInsert);
        }

        // Inventory handling
        $queryCheck = "SELECT id, quantity, total_value, available_stock FROM inventory WHERE type = ?";
        $stmtCheck = mysqli_prepare($conn, $queryCheck);
        mysqli_stmt_bind_param($stmtCheck, "s", $type);
        mysqli_stmt_execute($stmtCheck);
        $resultCheck = mysqli_stmt_get_result($stmtCheck);

        if ($row = mysqli_fetch_assoc($resultCheck)) {
            $new_quantity = $row['quantity'] + $quantity;
            $new_total_value = $row['total_value'] + $total_value;
            $new_available_stock = $row['available_stock'] + $available_stock;

            $queryUpdate = "UPDATE inventory SET quantity = ?, total_value = ?, available_stock = ?, stock = ?, status = ? WHERE id = ?";
            $stmtUpdate = mysqli_prepare($conn, $queryUpdate);
            mysqli_stmt_bind_param($stmtUpdate, "iidssi", $new_quantity, $new_total_value, $new_available_stock, $stock, 'Available', $row['id']);
            if (!mysqli_stmt_execute($stmtUpdate)) {
                echo "Error executing inventory update: " . mysqli_error($conn);
                exit;
            }
            mysqli_stmt_close($stmtUpdate);
        } else {
            // Insert new inventory record
            $queryInventory = "INSERT INTO inventory (type, category, quantity, total_value, stock, available_stock, status, purchasedate, warranty, location) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmtInventory = mysqli_prepare($conn, $queryInventory);
            if (!$stmtInventory) {
                echo "Error preparing inventory query: " . mysqli_error($conn);
                exit;
            }
            mysqli_stmt_bind_param($stmtInventory, "ssidsissss", $type, $category, $quantity, $total_value, $stock, $available_stock, 'Available', $purchasedate, $warranty, $location);
            if (!mysqli_stmt_execute($stmtInventory)) {
                echo "Error executing inventory insert: " . mysqli_error($conn);
                exit;
            }
            mysqli_stmt_close($stmtInventory);
        }

        mysqli_stmt_close($stmtCheck);
    }

    mysqli_close($conn);
    echo "<script>alert('Asset added successfully!'); window.location.href = '/AIMS/inventory/inventory.php';</script>";
}
?>




    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item</title>
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


<div class="container mt-5">
    <h2 class="text-center mb-4">Add New Item</h2>
    
    <form method="POST">
        <div class="row">
            <!-- Basic Info Section (Left Side) -->
            <div class="col-md-6">
                <div class="form-section mb-4">
                    <div class="section-header">Basic Info</div>

                    <div class="mb-3">
                        <label class="form-label">Type:</label>
                        <select class="form-control" name="type" id="typeSelect" required>
                            <option value="" disabled selected>Select Type</option>
                            <option value="Furniture">Furniture</option>
                            <option value="Headset">Headset</option>
                            <option value="Keyboard">Keyboard</option>
                            <option value="Mboard">Motherboard</option>
                            <option value="Monitor">Monitor</option>
                            <option value="2nd Monitor">2nd Monitor</option>
                            <option value="Mouse">Mouse</option>
                            <option value="Processor">Processor</option>
                            <option value="RAM">RAM</option>
                            <option value="Webcam">Webcam</option>
                            <option value="Laptop">Laptop</option>
                            <option value="AVR">Avr</option>
                            <option value="Adaptor">Adaptor</option>
                            <option value="Biometric">biometric</option>
                            <option value="Patch Cord">Patch Cord</option>
                            <option value="Printer">Printer</option>
                            <option value="Router">Router</option>
                            <option value="Switch">Switch</option>
                            <option value="CCTV">CCTV</option>
                            <option value="UPS">Ups</option>
                            <option value="Modem">Modem</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Category:</label>
                        <select class="form-control" name="category" required>
                            <option value="" disabled selected>Select Category</option>
                            <option value="IT Equipment">IT Equipment</option>
                            <option value="Furniture">Furniture</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Quantity:</label>
                        <input type="number" class="form-control" name="quantity" id="quantityInput" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Total Value:</label>
                        <input type="number" class="form-control" name="total_value" id="totalValueInput" required>
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
                </div>
            </div>

            <!-- Details Section (Right Side) -->
            <div class="col-md-6">
                <div class="form-section mb-4">
                    <div class="section-header">Details</div>

                    <div class="mb-3">
                        <label class="form-label">Stock:</label>
                        <input type="text" class="form-control" value="Auto-calculated" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Available Stock:</label>
                        <input type="number" class="form-control" name="available_stock" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status:</label>
                        <input type="text" class="form-control" value="Auto-calculated" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Purchase Date:</label>
                        <input type="date" class="form-control" name="purchasedate" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Warranty:</label>
                        <input type="date" class="form-control" name="warranty">
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Details Section (Hidden by Default, Appears at Bottom) -->
        <div id="additionalDetails" class="mt-4" style="display: none;">
            <div class="section-header">Additional Details</div>

            <div class="row">
                <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Name:</label>
                    <input type="text" class="form-control" name="laptop_name" value="LP-0000" readonly>
                </div>


                    <div class="mb-3">
                        <label class="form-label">Model:</label>
                        <input type="text" class="form-control" name="laptop_model">
                    </div>
                </div>
                

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Price:</label>
                        <input type="number" class="form-control" name="laptop_price" id="priceInput" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Supplier:</label>
                        <input type="text" class="form-control" name="laptop_supplier">
                    </div>

                    <div id="sizeField" class="mb-3" style="display: none;">
                        <label class="form-label">Size:</label>
                        <input type="text" class="form-control" name="size">
                    </div>
                    

                    <div id="capacityField" class="mb-3" style="display: none;">
                        <label class="form-label">Capacity:</label>
                        <input type="text" class="form-control" name="capacity">
                    </div>

                </div>
            </div>
        </div>

        <!-- JavaScript -->
        <script>
  document.getElementById('typeSelect').addEventListener('change', function () {
    var additionalDetails = document.getElementById('additionalDetails');
    var nameField = document.querySelector("input[name='laptop_name']");
    var modelField = document.querySelector("input[name='laptop_model']");
    var priceField = document.querySelector("input[name='laptop_price']");
    var supplierField = document.querySelector("input[name='laptop_supplier']");
    
    // Additional fields for specific items
    var sizeField = document.getElementById("sizeField"); // For Monitors
    var capacityField = document.getElementById("capacityField"); // For RAM

    var itemsWithDetails = [
        "Laptop", "Headset", "Mouse", "Keyboard", "Mboard", "Webcam", "Monitor", "2nd Monitor", "RAM", 
        "Processor", "AVR", "Adaptor", "Biometric", "Patch Cord", "Printer", "Router", "Switch",
        "CCTV", "UPS", "Modem" // Added new items
    ];

    if (itemsWithDetails.includes(this.value)) {
        additionalDetails.style.display = 'block';
        nameField.parentElement.style.display = 'block';
        priceField.parentElement.style.display = 'block';
        supplierField.parentElement.style.display = 'block';

        let prefixMapping = {
            "Laptop": "LP",
            "Monitor": "MT",
            "2nd Monitor": "MTnd",
            "RAM": "RM",
            "Processor": "PR",
            "Mboard": "MB",
            "AVR": "AVR",
            "Adaptor": "AP",
            "Biometric": "BIO",
            "Patch Cord": "PTC",
            "Printer": "PRNT",
            "Router": "RT",
            "Switch": "SWT",
            "CCTV": "CCTV",  // New Prefix
            "UPS": "UPS",    // New Prefix
            "Modem": "MDM"   // New Prefix
        };

        let prefix = prefixMapping[this.value] || this.value.substring(0, 2).toUpperCase();
        nameField.value = prefix + "-0000"; // Assign default ID

        // Show or hide specific fields based on item type
        if (this.value === "Laptop" || this.value === "Processor") {
            modelField.parentElement.style.display = 'block';
            sizeField.style.display = 'none';
            capacityField.style.display = 'none';
        } else if (this.value === "Monitor" || this.value === "2nd Monitor") {
            sizeField.style.display = 'block';
            modelField.parentElement.style.display = 'none';
            capacityField.style.display = 'none';
        } else if (this.value === "RAM") {
            capacityField.style.display = 'block';
            modelField.parentElement.style.display = 'none';
            sizeField.style.display = 'none';
        } else {
            modelField.parentElement.style.display = 'none';
            sizeField.style.display = 'none';
            capacityField.style.display = 'none';
        }
    } else {
        additionalDetails.style.display = 'none';
    }
});

// Price Calculation Function
function calculatePrice() {
    var quantity = document.getElementById("quantityInput").value;
    var totalValue = document.getElementById("totalValueInput").value;
    var priceField = document.getElementById("priceInput");

    if (quantity > 0 && totalValue > 0) {
        priceField.value = (totalValue / quantity).toFixed(2);
    } else {
        priceField.value = "";
    }
}

document.getElementById("quantityInput").addEventListener("input", calculatePrice);
document.getElementById("totalValueInput").addEventListener("input", calculatePrice);


</script>




        <!-- Submit & Cancel Buttons -->
        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary">Add Item</button>
            <a href="/AIMS/inventory/inventory.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

