<?php
$host = '5aod4o.h.filess.io';
$port = 61031;
$user = 'xcorps_gobendcoal';
$pass = '8d626996527f9edf10ab6f7b9df3099367964e9b';
$db   = 'xcorps_gobendcoal';

$conn = new mysqli($host, $user, $pass, $db, $port);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
