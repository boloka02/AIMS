<?php
include '../db_connection.php';

if (!isset($_GET['id']) || !isset($_GET['type'])) {
    die("<h3 class='text-danger text-center mt-4'>Invalid request.</h3>");
}

$id = intval($_GET['id']);
$type = strtolower(trim($_GET['type']));

// Asset type mapping
$typeMapping = [
    '2nd monitor' => 'monitor2'
];

// Check if type needs remapping
if (array_key_exists($type, $typeMapping)) {
    $type = $typeMapping[$type];
}

// Allowed asset types
$allowedTypes = ['mboard', 'keyboard', 'mouse', 'monitor', 'monitor2', 'webcam', 'headset', 'processor', 'ram', 'laptop', 'biometric', 'patch_cord', 'printer', 'router', 'switch', 'avr', 'adaptor', 'cctv', 'ups', 'modem'];

if (!in_array($type, $allowedTypes)) {
    die("<h3 class='text-danger text-center mt-4'>Invalid asset type.</h3>");
}

// Fetch asset details
$query = "SELECT * FROM `$type` WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    die("<h3 class='text-danger text-center mt-4'>Asset not found.</h3>");
}

$asset = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $updateFields = [];
    $updateValues = [];

    foreach ($asset as $field => $value) {
        if ($field === "id" || strpos($field, 'history') !== false) continue; // Make history fields read-only

        if (isset($_POST[$field])) {
            $updateFields[] = "`$field` = ?";
            $updateValues[] = $_POST[$field];
        }
    }

    if (!empty($updateFields)) {
        $updateQuery = "UPDATE `$type` SET " . implode(", ", $updateFields) . " WHERE id = ?";
        $stmt = mysqli_prepare($conn, $updateQuery);

        $types = str_repeat("s", count($updateValues)) . "i";
        $updateValues[] = $id;

        mysqli_stmt_bind_param($stmt, $types, ...$updateValues);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>
                alert('Asset updated successfully!');
                window.location.replace('/mis-v6.1/inventory/inventory.php?type=$type');
            </script>";
            exit;
        } else {
            echo "<div class='alert alert-danger'>Error updating asset: " . mysqli_stmt_error($stmt) . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpeg" href="https://adongroup.com.au/wp-content/uploads/2020/12/aog-favicon-192px.svg">
    <title>ADON PH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            if (!$.fn.DataTable.isDataTable('#assetTable')) {
                $('#assetTable').DataTable();
            }
        });
    </script>
</head>
<body>

<div class="container mt-4">
    <h2 class="text-center">Edit <?php echo ucfirst(htmlspecialchars($type)); ?></h2>

    <form method="POST" class="p-4 border rounded shadow-sm">
        <?php foreach ($asset as $field => $value): ?>
            <?php if ($field === "id") continue; ?>

            <div class="mb-3">
                <label class="form-label"><?php echo ucfirst(str_replace("_", " ", htmlspecialchars($field))); ?></label>
                <input type="text" class="form-control" name="<?php echo htmlspecialchars($field); ?>" 
                    value="<?php echo htmlspecialchars($value); ?>" 
                    <?php echo (strpos($field, 'history') !== false) ? 'readonly' : 'required'; ?>>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="view.php?type=<?php echo urlencode($type); ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</body>
</html>
<?php mysqli_close($conn); ?>
