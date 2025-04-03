<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}
?>

<?php
include '../db_connection.php';

if (isset($_GET['type'])) {
    $type = strtolower(trim($_GET['type']));

    // Mapping specific types to database table names
    $typeMapping = ['2nd monitor' => 'monitor2'];
    if (array_key_exists($type, $typeMapping)) {
        $type = $typeMapping[$type];
    }

    // Allowed asset types
    $allowedTypes = ['mboard', 'keyboard', 'mouse', 'monitor', 'monitor2', 'webcam', 'headset', 'processor', 'ram', 'laptop', 'biometric', 'patch_cord', 'printer', 'router', 'switch', 'avr', 'adaptor', 'cctv', 'ups', 'modem'];
    if (!in_array($type, $allowedTypes)) {
        die("<h3 class='text-danger text-center mt-4'>Invalid asset type.</h3>");
    }

    $query = "SELECT * FROM `$type`";
    $result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>View Inventory - <?php echo htmlspecialchars($type); ?></title>
    <style>.search-box { max-width: 200px; }</style>
</head>
<body>
<?php include "../sidebar/sidebar.php"; ?>

<div class="container mt-3">
    <h2><?php echo ucfirst(htmlspecialchars($type)); ?> Details</h2>

    <?php if ($result && mysqli_num_rows($result) > 0) { ?>
        <table id="assetTable" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <?php
                    $fields = mysqli_fetch_fields($result);
                    foreach ($fields as $field) {
                        $colName = strtolower($field->name);
                        if ($colName === "id") continue;
                        if ($colName === "model" && !in_array($type, ['laptop', 'processor'])) continue;
                        echo "<th>" . ucfirst(htmlspecialchars($field->name)) . "</th>";
                    }
                    ?>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <?php 
                        $assetId = null;
                        foreach ($row as $key => $value) { 
                            $colName = strtolower($key);
                            if ($colName === "id") {
                                $assetId = htmlspecialchars($value);
                                continue;
                            }
                            if ($colName === "model" && !in_array($type, ['laptop', 'processor'])) continue;

                            // Apply color formatting to status column
                            if ($colName === "status") {
                                $status = strtolower($value);
                                $colorClass = ($status === 'available') ? 'text-success fw-bold' : 
                                            (($status === 'assigned') ? 'text-danger fw-bold' : 
                                            (($status === 'damage') ? 'text-warning fw-bold' : 'text-secondary'));
                                echo "<td class='$colorClass'>" . ucfirst(htmlspecialchars($status)) . "</td>";
                            } else {
                                echo "<td>" . htmlspecialchars($value) . "</td>";
                            }
                        } 
                        ?>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="../inventory/view_edit.php?id=<?php echo $assetId; ?>&type=<?php echo urlencode($type); ?>">
                                            <i class="fas fa-pencil-alt"></i> Edit
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger delete-asset" href="#" data-id="<?php echo $assetId; ?>" data-type="<?php echo htmlspecialchars($type); ?>">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <h5 class="text-muted text-center">No records found for this asset type.</h5>
    <?php } ?>
</div>

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    $('#assetTable').DataTable({
        "paging": true,      
        "searching": true,   
        "ordering": true,    
        "lengthMenu": [5, 10, 25, 50], 
        "pageLength": 10     
    });

    // Handle delete
    $('.delete-asset').click(function(e) {
        e.preventDefault();
        
        let assetId = $(this).data('id');
        let assetType = $(this).data('type');
        let row = $(this).closest('tr');

        if (!assetId || !assetType) {
            alert('Invalid asset ID or type.');
            return;
        }

        if (confirm('Are you sure you want to delete this item?')) {
            $.ajax({
                url: '../inventory/delete_view.php',
                type: 'POST',
                data: { id: assetId, type: assetType },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        row.fadeOut(300, function() { $(this).remove(); });
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        }
    });
});
</script>

<?php 
mysqli_close($conn); 
} else {
    echo "<h3 class='text-danger text-center mt-4'>No asset type specified.</h3>";
}
?>
</body>
</html>