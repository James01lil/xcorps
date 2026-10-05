<?php
include '../authenticate/db.php';

$id = $_POST['id'];
$amount = floatval($_POST['amount']);
$fields = $_POST['fields'] ?? [];

$allowedFields = ['balance', 'deposit', 'equity', 'margin', 'pnl'];
$updateParts = [];

foreach ($fields as $field) {
    if (in_array($field, $allowedFields)) {
        $updateParts[] = "$field = $field - $amount";
    }
}

if (!empty($updateParts)) {
    $updateSQL = implode(', ', $updateParts);
    $sql = "UPDATE users SET $updateSQL WHERE id = $id";

    if ($conn->query($sql)) {
        header("Location: admin_dashboard.php?debit_success=1");
        exit();
    } else {
        echo "Database error: " . $conn->error;
    }
} else {
    echo "No fields selected to debit.";
}
?>