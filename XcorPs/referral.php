<?php
session_start();

// Extract from URI (e.g., /refWB-9F8Z-X4d7/28)
$uri = $_SERVER['REQUEST_URI'];
preg_match('/ref([A-Za-z0-9\\-]+)\\/(\\d+)/', $uri, $matches);

if (isset($matches[1])) {
    $ref_code = $matches[1]; // THGY4TREZ-
    $_SESSION['referral'] = $ref_code;

    // Optional: log to database or file here
}

// Redirect to static homepage
header("Location: /index.html");
exit;