<?php

session_start();

if (!isset($_SESSION['is_admin'])) {
    header("Location: ../backends/admin_login.html");
    exit;
}

include '../authenticate/db.php';

// ✅ Handle per-user toggle enable/disable

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {

    $userId = (int)$_POST['user_id'];

    if (isset($_POST['enable_toggle'])) {

        $stmt = $conn->prepare("UPDATE users SET toggle_enabled = 1 WHERE id = ?");

    } elseif (isset($_POST['disable_toggle'])) {

        $stmt = $conn->prepare("UPDATE users SET toggle_enabled = 0 WHERE id = ?");

    }

    if (isset($stmt)) {

        $stmt->bind_param("i", $userId);

        $stmt->execute();

        header("Location: admin_dashboard.php");

        exit;

    }

}

// ✅ Handle subscription approval

if (isset($_POST['approve'], $_POST['user_id'], $_POST['bot_id'], $_POST['subscription_id'])) {

    $sub_id = (int)$_POST['subscription_id'];

    $user_id = (int)$_POST['user_id'];

    $bot_id = (int)$_POST['bot_id'];

    $conn->query("UPDATE subscriptions SET payment_status = 'paid' WHERE id = $sub_id");

    $conn->query("UPDATE users SET current_bot_id = $bot_id, toggle_enabled = 1 WHERE id = $user_id");

    header("Location: admin_dashboard.php");

    exit;

}

// ✅ Global toggle (optional)

if (isset($_POST['global_toggle'])) {

    file_put_contents("global_toggle.txt", $_POST['global_toggle']);

}

// ✅ Fetch pending subscriptions

$sql = "SELECT s.id AS sub_id, u.id AS user_id, u.user_name, b.bot_name, s.payment_status, s.bot_id

        FROM subscriptions s

        JOIN users u ON s.account_id = u.id

        JOIN bots b ON s.bot_id = b.id

        ORDER BY s.id DESC";

$subs = $conn->query($sql);

// ✅ Fetch all users and their toggle status

$users = $conn->query("SELECT id, user_name, email, toggle_enabled FROM users ORDER BY id DESC");

// ✅ Get global toggle status

$globalStatus = file_exists("global_toggle.txt") ? file_get_contents("global_toggle.txt") : '1';

?>

<!DOCTYPE html>
<html>
  <head>
    <title>XcorPs ADMIN PANEL</title>
        <!-- FAVIVON -->
        <link rel="shortcut icon" href="images/favicon.png" type="image/png">
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
	      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    
header{

  background:#01010ff5;

  color:#ddd;

  padding-top:-30px;

  padding-bottom:6px;

  box-shadow: 0 4px 6px #111111;

  z-index:99;

  position:fixed;

  width:100%;

        margin-top:-50px;

}
    :root {
      --bg-dark: #111;
      --card-bg: #1a1a1a;
      --text-light: #f0f0f0;
      --accent: #0af;
      --danger: #f33;
      --success: #2ecc71;
    }

    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: var(--bg-dark);
      color: var(--text-light);
      padding: px;
    }

    h5{
      margin-bottom: 10px;
    }

    
    
      .table-card {
  background: #1a1a1a;
  border-radius: 10px;
  margin-bottom: 40px;
  padding: 16px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.3);
}

.table-wrapper {
  max-height: 280px;
  overflow-y: auto;
  border-radius: 8px;
}

table {
  width: 100%;
  border-collapse: collapse;
  background-color: #222;
}

thead th {
  position: sticky;
  top: 0;
  background: #333;
  color: #0af;
  padding: 12px;
  border-bottom: 1px solid #444;
}

td {
  padding: 10px;
  border-bottom: 1px solid #444;
  color: #ddd;
}

button {
  padding: 6px 14px;
  background: #0af;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  color: white;
}

button.disable-btn {
  background: #f33;
}

button:hover {
  background: #08c;
}

@media (max-width: 768px) {
  table, thead, tbody, th, td, tr {
    display: block;
  }

  thead {
    display: none;
  }

  tr {
    margin-bottom: 1rem;
    background: #222;
    border-radius: 10px;
    padding: 10px;
  }

  td {
    padding: 8px;
    text-align: right;
    position: relative;
  }

  td::before {
    content: attr(data-label);
    position: absolute;
    left: 12px;
    color: #999;
    font-size: 14px;
    text-align: left;
  }
}
        
  </style>
</head>
<body>
        
        
        
        
        <header style="float: ;">&nbsp;&nbsp;

    <ul style="margin-top:-10px;">

      <a href=""><h5 style="font-weight: 800;color:#ccccccf1;float: left;">&nbsp;&nbsp;&nbsp;&nbsp;X<span style="background: linear-gradient(to right, #bfffd5,

                                    #7bff55,#2D9E45, #6affa2); -webkit-text-fill-color: transparent; -webkit-background-clip: text;font-weight: 800;">corPs</span>&nbsp;&nbsp;<i class="fa fa-exchange" style=""></i>&nbsp;&nbsp;<b style="font-size:12px;color:peru;">Bot-subscription-control</b></h5></a>

    </ul>

    <ul style="float: right;">

  <i class="fa fa-podcast" style="font-size:15px;font-weight:800;color:#ffffff;"></i>  &nbsp;&nbsp;&nbsp;&nbsp;

    <a href="logout.php"><i class="fa fa-sign-out" style="font-size:15px;font-weight:800;color:red;"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

    </ul>

</header>
        
        
     
        <div style="padding:20px;margin-top:50px;">

  <h6>Admin Dashboard – Bot Subscription Control</h6>

  <h5>🟡 Pending Bot Subscriptions</h5>
<div style="max-height: 300px; overflow-y: auto; border: 1px solid #333; border-radius: 10px; margin-bottom: 30px;">
  <table>
    <thead>
      <tr>
        <th>User</th>
        <th>Bot</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $subs->fetch_assoc()): ?>
      <tr>
        <td data-label="User"><?= htmlspecialchars($row['user_name']) ?></td>
        <td data-label="Bot"><?= htmlspecialchars($row['bot_name']) ?></td>
        <td data-label="Status"><?= $row['payment_status'] ?></td>
        <td data-label="Action">
          <?php if ($row['payment_status'] !== 'paid'): ?>
          <form method="post">
            <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
            <input type="hidden" name="subscription_id" value="<?= $row['sub_id'] ?>">
            <input type="hidden" name="bot_id" value="<?= $row['bot_id'] ?>">
            <button type="submit" name="approve">✅ Approve</button>
          </form>
          <?php else: ?>
            <span class="approved">Approved</span>
          <?php endif; ?>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<h5>🔁 User Toggle Controls</h5>
<div style="max-height: 300px; overflow-y: auto; border: 1px solid #333; border-radius: 10px; margin-bottom: 30px;">
  <table>
    <thead>
      <tr>
        <th>User</th>
        <th>Email</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($user = $users->fetch_assoc()): ?>
      <tr>
        <td data-label="User"><?= htmlspecialchars($user['user_name']) ?></td>
        <td data-label="Email"><?= htmlspecialchars($user['email']) ?></td>
        <td data-label="Status"><?= $user['toggle_enabled'] ? '✅ Enabled' : '❌ Disabled' ?></td>
        <td data-label="Action">
          <form method="post">
            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
            <?php if ($user['toggle_enabled']): ?>
              <button class="disable-btn" type="submit" name="disable_toggle">Disable</button>
            <?php else: ?>
              <button type="submit" name="enable_toggle">Enable</button>
            <?php endif; ?>
          </form>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
      



<h5>🌐 Global Toggle (All Users)</h5>
<form method="post" style="margin-top: 20px;">
  <select name="global_toggle" style="padding: 10px; background: #222; color: white; border: 1px solid #444; border-radius: 8px;">
    <option value="1" <?= $globalStatus === '1' ? 'selected' : '' ?>>Enable All</option>
    <option value="0" <?= $globalStatus === '0' ? 'selected' : '' ?>>Disable All</option>
  </select>
  <button type="submit" style="padding: 10px 20px; margin-left: 10px; background: #0af; border: none; color: white; border-radius: 8px;">Set Global</button>
</form>  
        
        
  </div>

</body>
</html>
            
            
    