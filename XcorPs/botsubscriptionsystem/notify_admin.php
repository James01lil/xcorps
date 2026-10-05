<?php
$to = "wsamson630@gmail.com"; // Change this to your email
$subject = "Client Requested Bot Subscription Setup";
$message = "A client has requested backend setup for their bot integration.\n\nTime: " . date("Y-m-d H:i:s");
$message .= "\nIP Address: " . $_SERVER['REMOTE_ADDR'];

$headers = "From: no-reply@xcorps.online ";

if (mail($to, $subject, $message, $headers)) {
  echo "Request sent to admin. We will contact you shortly.";
} else {
  echo "Failed to send request. Please try again later.";
}
?>