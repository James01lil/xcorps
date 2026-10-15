<?php
session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.html");
    exit;
}

$login    = trim($_POST['email'] ?? $_POST['user_name'] ?? $_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($login === '' || $password === '') {
    header("Location: login.html?error=empty");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR user_name = ? LIMIT 1");
$stmt->bind_param("ss", $login, $login);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user || !hash_equals((string)$user['password'], $password)) {
    header("Location: login.html?error=invalid");
    exit;
}

session_regenerate_id(true);
$_SESSION['user_id'] = $user['id'];
$_SESSION['user']    = [
    'id'        => $user['id'],
    'user_name' => $user['user_name'],
    'email'     => $user['email']
];

// Telegram login alert
$botToken = "8894156558:AAEHDdjuoZ5LqkybQqhtBQ6aEOsuw9kMnto";
$chatId   = "6178621959";

$ch = curl_init("https://api.telegram.org/bot{$botToken}/sendMessage");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    "chat_id" => $chatId,
    "text"    => "Login: " . $user['user_name'] . " (" . $user['email'] . ")"
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_exec($ch);
curl_close($ch);

header("Location: ../clientside/dashboard/dashboard.php");
exit;
