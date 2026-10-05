<?php
include '../../authenticate/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $user_name = trim($_POST['user_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $bonus = 20;
    $balance = 0;
    $status = 'active';

    // Check if username or email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE user_name = ? OR email = ?");
    $check->bind_param("ss", $user_name, $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "❌ Username or Email already exists. Please use another.";
        $check->close();
        $conn->close();
        exit();
    }
    $check->close();

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, user_name, email, password, balance, bonus, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("❌ Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssssiis", $first_name, $last_name, $user_name, $email, $password, $balance, $bonus, $status);

    if ($stmt->execute()) {
        header("Location: ../admin_dashboard.php");
        exit();
    } else {
        echo "❌ Insert failed: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>