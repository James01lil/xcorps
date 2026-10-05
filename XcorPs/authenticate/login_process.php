<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$botToken = "8929516825:AAGw1XNyT3U4H_RNHI21depIh4wrrYAQk00";
$chatId   = "1843218039";

echo "curl loaded: ";
var_dump(function_exists('curl_init'));

$ch = curl_init("https://api.telegram.org/bot{$botToken}/sendMessage");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    "chat_id" => $chatId,
    "text"    => "test"
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$res = curl_exec($ch);
echo "\nHTTP: " . curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "\nCurl error: " . curl_error($ch);
echo "\nResponse: " . $res;
curl_close($ch);