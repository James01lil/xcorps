<?php
// Show PHP errors (for debugging)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session to store user info
session_start();

// Connect to DB
require_once 'db.php'; // adjust path as needed

function sendEmail($to, $subject, $body) {

    $apiKey = "re_your_actual_api_key_here"; // Get this from https://resend.com/api-keys

    $payload = [
        "from" => "XcorPs Teams <onboarding@resend.dev>", // ⚠️ Sandbox sender — only delivers to your own Resend account email until you verify a domain
        "to" => is_array($to) ? $to : [$to],
        "subject" => $subject,
        "html" => $body
    ];

    $ch = curl_init("https://api.resend.com/emails");

    // Production: SSL verification enabled
    // curl_setopt_array($ch, [
    //     CURLOPT_HTTPHEADER => [
    //         "Authorization: Bearer " . $apiKey,
    //         "Content-Type: application/json"
    //     ],
    //     CURLOPT_POST => true,
    //     CURLOPT_POSTFIELDS => json_encode($payload),
    //     CURLOPT_RETURNTRANSFER => true,
    //     CURLOPT_TIMEOUT => 10,
    //     CURLOPT_SSL_VERIFYPEER => true,
    //     CURLOPT_SSL_VERIFYHOST => 2,
    // ]);

    // Local: SSL verification disabled (AWebServer has no SSL certs bundle)
    curl_setopt_array($ch, [
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer " . $apiKey,
            "Content-Type: application/json"
        ],
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    $curlError = null;
    if (curl_errno($ch)) {
        $curlError = curl_error($ch);
    }

    curl_close($ch);

    $decoded = json_decode($response, true);

    // 🔥 HARD FAILURE CHECK (IMPORTANT)
    if ($curlError) {
        return [
            "status" => "error",
            "type" => "curl_error",
            "message" => $curlError
        ];
    }

    if ($httpCode !== 200 && $httpCode !== 202) {
        return [
            "status" => "error",
            "type" => "http_error",
            "http_code" => $httpCode,
            "response" => $decoded ?? $response
        ];
    }

    if (!isset($decoded["id"])) {
        return [
            "status" => "error",
            "type" => "resend_error",
            "response" => $decoded ?? $response
        ];
    }

    return [
        "status" => "success",
        "id" => $decoded["id"]
    ];
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and validate input
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $user_name = trim($_POST['user_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $phone = trim($_POST['phone'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $plan = trim($_POST['plan'] ?? '');
    $currency = trim($_POST['currency'] ?? '');
    $account = trim($_POST['account'] ?? '');
    $comment = trim($_POST['comment'] ?? '');

    // Validate required fields
    if (
        empty($first_name) || empty($last_name) || empty($user_name) ||
        empty($email) || empty($password) || empty($confirm_password)
    ) {
        die("❌ Please fill in all required fields.");
    }

    // Check password match
    if ($password !== $confirm_password) {
        die("❌ Passwords do not match.");
    }

    // No password hashing for test mode
    $plain_password = trim($_POST['password'] ?? '');

    if (empty($plain_password)) {
        die("❌ Password field is empty.");
    }

    // Check for duplicate username/email
    $check = $conn->prepare("SELECT id FROM users WHERE user_name = ? OR email = ?");
    $check->bind_param("ss", $user_name, $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        die("❌ Username or email already exists.");
    }
    $check->close();


    // reCAPTCHA check removed


    // Insert into database
    $stmt = $conn->prepare("INSERT INTO users (
        first_name, last_name, user_name, email, password, phone, country, state, address, plan, currency, account, comment
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("❌ Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssssssssssss", 
        $first_name, $last_name, $user_name, $email, $plain_password,
        $phone, $country, $state, $address, $plan, $currency, $account, $comment
    );

    if ($stmt->execute()) {

        // ✅ Send Telegram report to admin upon successful registration
        $botToken = "8929516825:AAGw1XNyT3U4H_RNHI21depIh4wrrYAQk00";
        $chatId   = "1843218039";

        $tgMessage = "📥 *New User Registered!*\n\n" .
                     "👤 *Name:* {$first_name} {$last_name}\n" .
                     "🆔 *Username:* {$user_name}\n" .
                     "📧 *Email:* {$email}\n" .
                     "📱 *Phone:* {$phone}\n" .
                     "🌍 *Country:* {$country}\n" .
                     "💼 *Plan:* {$plan}\n\n" .
                     "Login to your admin dashboard to view more:\nhttps://www.xcorps.online/backends/admin_login.html";

        $ch = curl_init("https://api.telegram.org/bot{$botToken}/sendMessage");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            "chat_id"    => $chatId,
            "text"       => $tgMessage,
            "parse_mode" => "Markdown"
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_exec($ch);
        curl_close($ch);


        // ✅ Send registration success email to the client via Resend
        sendEmail(
            $email,
            "Welcome, {$first_name}! Your registration was successful",
            "<h2>🎉 Welcome, {$first_name}!</h2>
            <p>Your account has been created successfully.</p>
            <p><b>Username:</b> {$user_name}</p>
            <p><b>Plan:</b> {$plan}</p>
            <p>You can now log in and get started.</p>"
        );


        // ✅ Store user info in session (to auto-login)
        $_SESSION['user'] = [
            'id' => $conn->insert_id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'user_name' => $user_name,
            'email' => $email,
            'phone' => $phone,
            'country' => $country,
            'state' => $state,
            'address' => $address,
            'plan' => $plan,
            'currency' => $currency,
            'account' => $account,
            'comment' => $comment
        ];

        // ✅ Redirect to overview page
        header("Location: ../clientside/overview.php");
        exit;
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "❌ Invalid request.";
}
?>
