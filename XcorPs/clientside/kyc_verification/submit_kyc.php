<?php
// Enable full error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'db2.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  die("Unauthorized access");
}

$user_id = $_SESSION['user_id'];

// If the request body exceeded post_max_size, PHP empties BOTH $_POST and
// $_FILES with no warning of its own — detect that case explicitly here
// instead of letting it surface as confusing "undefined array key" notices.
if (empty($_POST) && empty($_FILES) && (int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
  die("Upload too large: your files exceeded the server's upload limit (post_max_size). Try smaller images or ask the admin to raise the limit in php.ini.");
}

$full_name = $_POST['full_name'] ?? '';
$dob = $_POST['dob'] ?? '';

// Setup upload
$upload_dir = 'kyc_documents/';
$allowed_ext = ['jpg', 'jpeg', 'png', 'pdf'];

if (!is_dir($upload_dir)) {
  mkdir($upload_dir, 0777, true);
}

// Helper function to save file
function saveFile($file, $prefix, $upload_dir, $allowed_ext) {
  if (!isset($file) || !is_array($file)) {
    die("Missing upload for: $prefix. This usually means the file was too large (check upload_max_filesize / post_max_size in php.ini).");
  }

  if ($file['error'] !== UPLOAD_ERR_OK) {
    $php_upload_errors = [
      UPLOAD_ERR_INI_SIZE   => 'File exceeds upload_max_filesize in php.ini',
      UPLOAD_ERR_FORM_SIZE  => 'File exceeds the form\'s max file size',
      UPLOAD_ERR_PARTIAL    => 'File was only partially uploaded',
      UPLOAD_ERR_NO_FILE    => 'No file was uploaded',
      UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder on the server',
      UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
      UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the upload',
    ];
    $reason = $php_upload_errors[$file['error']] ?? "Unknown error code {$file['error']}";
    die("File upload error for $prefix: $reason");
  }

  $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
  if (!in_array($ext, $allowed_ext)) {
    die("Invalid file type: .$ext");
  }

  $filename = $prefix . '_' . uniqid() . '.' . $ext;
  $destination = $upload_dir . $filename;

  if (!move_uploaded_file($file['tmp_name'], $destination)) {
    die("Failed to move uploaded file: $prefix");
  }

  return $filename;
}

// Save uploaded files
$id_front = saveFile($_FILES['id_front'] ?? null, 'front', $upload_dir, $allowed_ext);
$id_back  = saveFile($_FILES['id_back'] ?? null, 'back', $upload_dir, $allowed_ext);
$selfie   = saveFile($_FILES['selfie'] ?? null, 'selfie', $upload_dir, $allowed_ext);

// Insert into DB
$stmt = $conn->prepare("INSERT INTO kyc_submissions (user_id, full_name, dob, id_front, id_back, selfie) VALUES (?, ?, ?, ?, ?, ?)");
if (!$stmt) {
  die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("isssss", $user_id, $full_name, $dob, $id_front, $id_back, $selfie);
$stmt->execute();

// Send Email
$to = "wsamson630@gmail.com";
$subject = "New KYC Submission: $full_name";
$body = "A new KYC has been submitted by:\n\nFull Name: $full_name\nDate of Birth: $dob\n\nFiles are attached.";
$from = "kyc@xcorps.online";
$headers = "From: $from";
$attachments = [$upload_dir . $id_front, $upload_dir . $id_back, $upload_dir . $selfie];
$boundary = md5(time());
$headers .= "\r\nMIME-Version: 1.0";
$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"{$boundary}\"";

$message = "--{$boundary}\r\n";
$message .= "Content-Type: text/plain; charset=\"utf-8\"\r\n";
$message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
$message .= $body . "\r\n\r\n";

foreach ($attachments as $path) {
  if (file_exists($path)) {
    $filename = basename($path);
    $filedata = chunk_split(base64_encode(file_get_contents($path)));
    $message .= "--{$boundary}\r\n";
    $message .= "Content-Type: application/octet-stream; name=\"{$filename}\"\r\n";
    $message .= "Content-Transfer-Encoding: base64\r\n";
    $message .= "Content-Disposition: attachment; filename=\"{$filename}\"\r\n\r\n";
    $message .= $filedata . "\r\n\r\n";
  }
}

$message .= "--{$boundary}--";
@mail($to, $subject, $message, $headers);

// Now show success screen
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>KYC Submission Status</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    body {
      background-color: #111827;
      color: #f9fafb;
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 20px;
    }

    .status-box {
      background-color: #1f2937;
      padding: 35px 25px;
      border-radius: 16px;
      text-align: center;
      max-width: 420px;
      width: 100%;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    }

    .status-box h2 {
      font-size: 22px;
      margin-bottom: 15px;
      color: #10b981;
    }

    .status-icon {
      font-size: 40px;
      margin-bottom: 15px;
      color: #10b981;
      animation: pulse 1.6s infinite;
    }

    @keyframes pulse {
      0%, 100% {
        transform: scale(1);
        opacity: 0.9;
      }
      50% {
        transform: scale(1.1);
        opacity: 1;
      }
    }

    .badge {
      display: inline-block;
      padding: 6px 12px;
      border-radius: 12px;
      font-size: 13px;
      background-color: #fbbf24;
      color: #111827;
      margin-bottom: 10px;
      animation: fadein 1s ease-in-out;
    }

    @keyframes fadein {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    .dots::after {
      content: '';
      display: inline-block;
      animation: dots 1.5s steps(3, end) infinite;
    }

    @keyframes dots {
      0%   { content: ''; }
      33%  { content: '.'; }
      66%  { content: '..'; }
      100% { content: '...'; }
    }

    .status-box p {
      font-size: 15px;
      color: #d1d5db;
      margin-top: 10px;
      margin-bottom: 25px;
    }

    .btn {
      display: inline-block;
      background-color: #3b82f6;
      color: #fff;
      padding: 12px 18px;
      border-radius: 10px;
      font-size: 15px;
      text-decoration: none;
      transition: background-color 0.3s ease;
    }

    .btn:hover {
      background-color: #2563eb;
    }
  </style>
</head>
<body>
  <div class="status-box">
    <div class="status-icon">✔️</div>
    <h2>KYC Submitted Successfully</h2>
    <div class="badge">Status: Pending<span class="dots"></span></div>
    <p>Your documents have been received and are under review.<br>
    You’ll be notified once verification is complete.</p>
    <a href="../dashboard/dashboard.php" class="btn">Go to Dashboard</a>
  </div>
</body>
</html>
