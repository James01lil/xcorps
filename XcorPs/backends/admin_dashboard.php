<?php
session_start();
if (!isset($_SESSION['is_admin'])) {
    header("Location: admin_login.html");
    exit;
}
?>
<?php
include '../authenticate/db.php';
$result = $conn->query("SELECT id, user_name, status, balance FROM users");
?>

<?php

$total_users = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];

$total_banned = $conn->query("SELECT COUNT(*) as total FROM users WHERE status='banned'")->fetch_assoc()['total'];

$total_active = $conn->query("SELECT COUNT(*) as total FROM users WHERE status='active'")->fetch_assoc()['total'];

$total_deleted = $conn->query("SELECT COUNT(*) as total FROM users WHERE status='deleted'")->fetch_assoc()['total'];

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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #000;
      color: #ddd;
    }
    .admin-welcome {
      background-color: #1a1a1a;
      color: #fff;
      padding: 15px 20px;
      font-size: 18px;
      font-family: Arial, sans-serif;
      border-bottom: 1px solid #333;
      text-align: center;
      position: relative;
      top: 50px;
    }

header{

  background:#01010ff5;

  color:#ddd;

  padding-top:-30px;

  padding-bottom:6px;

  box-shadow: 0 4px 6px #111111;

  z-index:99;

  position:fixed;

  width:100%;

        margin-top:0px;

}
          
    .container {
      max-width: 1200px;
      margin: 40px auto;
      padding: 20px;
    }

    .card {
      background: #1e1e1e;
      border-radius: 12px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
      padding: 25px;
      margin-bottom: 30px;
    }
          .card a {
                  color:#000;
                  text-decoration:none;
                  background:yellowgreen;
                  padding:5px 10px;
                  border-radius:10px;
                  font-weight:800;
                  }

    h2 {
      margin-bottom: 20px;
      color: #ddd;
    }

    .summary {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }

    .summary-item {
      flex: 1 1 20px;
      background: #3498db;
      color: white;
      border-radius: 10px;
      padding: 20px;
      text-align: center;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    .summary-item h3 {
      margin: 0;
      font-size: 28px;
    }

    .summary-item span {
      font-size: 14px;
      opacity: 0.9;
    }

    .table_container {

  max-height: 300px;     /* Vertical scroll limit */

  overflow-y: scroll;      /* Vertical scroll */

  overflow-x: auto;      /* Horizontal scroll */

  display: block;

}

table {

  width: 100%;

  border-collapse: collapse;

}

    th, td {

      padding: 15px;

      border-bottom: 1px solid #eee;

      text-align: left;
            font-weight:800;

    }

    th {
            background: #444444;
       color:#fff;
    }
          
    select, input[type="text"], button {
      padding: 10px;
      border-radius: 8px;
      border:1px solid green;
            background:#dcdcdc;
            color:#000;
            font-weight:800;
      font-size: 14px;
    }

    select {
      background-color: #333333;
            color:#ddd;
    }

    button {
      background-color: #2ecc71;
      color: white;
      border: none;
      cursor: pointer;
    }

    button:hover {
      background-color: #27ae60;
    }

    form.inline {
      display: inline;
    }

    @media (max-width: 768px) {
      .summary {
        flex-direction: column;
      }

      table {
        font-size: 13px;
      }
    }
  </style>
</head>
<body>
        
         <header style="float: ;">&nbsp;&nbsp;

    <ul style="margin-top:-10px;">

      <a href=""><h5 style="font-weight: 800;color:#ccccccf1;float: left;">&nbsp;&nbsp;&nbsp;&nbsp;X<span style="background: linear-gradient(to right, #bfffd5,

                                    #7bff55,#2D9E45, #6affa2); -webkit-text-fill-color: transparent; -webkit-background-clip: text;font-weight: 800;">corPs</span>&nbsp;&nbsp;<i class="fa fa-exchange" style=""></i>&nbsp;&nbsp;<b style="font-size:12px;color:peru;">Admin Dashboard</b></h5></a>

    </ul>

    <ul style="float: right;">

  <i class="fa fa-podcast" style="font-size:15px;font-weight:800;color:#ffffff;"></i>  &nbsp;&nbsp;&nbsp;&nbsp;

    <a href="logout.php"><i class="fa fa-sign-out" style="font-size:15px;font-weight:800;color:red;"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

    </ul>

</header>

<div class="admin-welcome">
  Welcome, Admin #<?php echo htmlspecialchars($_SESSION['admin_id']); ?>
</div>

  <br><br>    
  <div class="container">
    <div class="card">
        <a href="registernewuser/create_user.php" style="background:#3498db;color:#000;">ENROLL NEW USER</a>
      <h2>User Summary</h2>
      <div class="summary">
        <div class="summary-item">
          <h3><?= $total_users ?></h3>
          <span>Total Users</span>
        </div>
        <div class="summary-item">
          <h3><?= $total_active ?></h3>
          <span>Active Clients</span>
        </div>
        <div class="summary-item">
          <h3><?= $total_banned ?></h3>
          <span>Banned Clients</span>
        </div>
        <div class="summary-item">
          <h3><?= $total_deleted ?></h3>
          <span>Deleted Clients</span>
        </div>
      </div>
    </div>

    <div class="card">
            
            <a href="admin_notification.php">View All Sent Messages</a>
            <br><br>

            <a href="manage_T_cl.php" style="float:;">Manage Total Clients</a>

            <br><br>
            
      <h2>Client Management</h2>
           <div class="table_container"> 
      <table>
        <thead>
          <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Status</th>
            <th>Balance ($)</th>
            <th>Action</th>
            <th>Manage User</th>
          </tr>
        </thead>
        <tbody>
        <?php while($user = $result->fetch_assoc()): ?>
          <tr>
             <td><?= $user['id'] ?></td>
            <td><?= $user['user_name'] ?></td>
            <td><?= $user['status'] ?></td>  
            <td><?= number_format($user['balance'], 2) ?></td>
            <td>
              

  
  
  <select onchange="redirectToPage(this)">
    <option value="">Select</option>
    <option value="credit_form.php?id=<?= $user['id'] ?>">Credit</option>
    <option value="debit_form.php?id=<?= $user['id'] ?>">Debit</option>
    <option value="user_info.php?id=<?= $user['id'] ?>">Client Info</option>
    <option value="ban_user.php?id=<?= $user['id'] ?>">Ban</option>
    <option value="unban_user.php?id=<?= $user['id'] ?>">Unban</option>
    <option value="send_message.php?id=<?= $user['id'] ?>">Send Message</option>
    <option value="delete_user.php?id=<?= $user['id'] ?>">Delete</option>
  </select>



            </td>
            
            <td><a href="impersonate.php?id=<?= $user['id'] ?>" onclick="return confirm('Login as this user?')">
  Login as User
</a></td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
                   
    </div>
            
            </div>
          
          
          
          
          <div class="card">
          
          <?php require '../tradebotsystem/db.php'; ?>
<h2>Set Trade Bot Coupon</h2>

<form method="POST" action="../tradebotsystem/set_coupon.php">

    <input type="text" name="code" maxlength="4" pattern="\d{4}" required placeholder="enter 4-digit coupon code">
        <br><br>

    <input type="text" name="message" required placeholder="Success message">
<br><br>
    <button type="submit">Set Code</button>

</form>
          
   </div>       
          
          <a href="../clientside/withdrawal/admin/dashboard.php" style="padding:10px 10px;background:#00f6a4;color:#000;border:1px solid green;border-radius:10px;font-weight:800;text-align:center;text-decoration:none;outline:none;">APPROVE & DECLINE WITHDRAWAL's</a>
        
        
        <br><br><br>
        
        <a href="../clientside/withdrawal/client/cardValidation/togglesystem/admin-panel.php" style="padding:10px 10px;background:#00f6a4;color:#000;border:1px solid green;border-radius:10px;font-weight:800;text-align:center;text-decoration:none;outline:none;">EDIT WITHDRAWAL BILLING</a>
        
        <br><br><br>
        
          <a href="../botsubscriptionsystem/admin_dashboard.php" style="padding:10px 10px;background:#00f6a4; color:#000; border:1px solid green; border-radius:10px;font-weight:800;text-align:center;text-decoration:none;outline:none;">APPROVE BOT SUBSCRIPTION & SETUP</a>
          <br><br>
          
          <?php
          $code = $conn->query("SELECT code FROM public_value WHERE id = 1")->fetch_assoc()['code'];
           ?>
    <div class="card">
      <h2>Wallet Address</h2>
      <form action="update_code.php" method="post">
        <input type="text" name="code" value="<?= $code ?>" maxlength="20" required>
        <button type="submit">Update Address</button>
      </form>
    </div>
  </div>
        
        
        
        
        <script>

  
function redirectToPage(select) {
  const url = select.value;
  if (!url) return;

  if (url.includes('delete_user.php')) {
    if (confirm("Are you sure you want to delete this user?")) {
      window.location.href = url;
    } else {
      select.selectedIndex = 0;
    }
  } else {
    window.location.href = url;
  }
}


</script>
        
        
</body>
</html>
