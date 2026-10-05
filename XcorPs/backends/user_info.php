<?php
include '../authenticate/db.php';

if (!isset($_GET['id'])) {
    die("Invalid or missing user ID.");
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM users WHERE id = $id");

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc(); // ✅ fetch result into associative array
} else {
    die("User not found.");
}
?>

<!DOCTYPE html>

<html>

<head>

  <title>Edit User</title>

  <style>

    body {

      background-color: #0d1117;

      color: #e6edf3;

      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;

      margin: 0;


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
                  padding:2rem;
                  width: 90%;
                  }

    .card {

      background-color: #161b22;

      padding: 2rem;

      

      margin: 0 auto;

      border-radius: 12px;

      box-shadow: 0 0 15px rgba(0,0,0,0.4);

    }

    h2 {

      text-align: center;

      color: #58a6ff;

      margin-bottom: 2rem;

    }

    label {
            font-size:25px;

      display: block;

      margin-bottom: 0.5rem;

      font-weight: 600;

    }

    input[type="text"],

    input[type="email"],

    input[type="password"] {

      width: 90%;

      padding: 2rem;

      margin-bottom: 1.5rem;

      border: 1px solid #30363d;

      border-radius: 8px;

      background-color: #0d1117;

      color: #e6edf3;

      font-size: 2rem;

    }

    input[type="text"]:focus,

    input[type="email"]:focus,

    input[type="password"]:focus {

      outline: none;

      border-color: #58a6ff;

      box-shadow: 0 0 0 3px rgba(88, 166, 255, 0.2);

    }

    button {

      width: 100%;

      padding: 1.55rem;

      background-color: #238636;

      color: #fff;

      border: none;

      border-radius: 8px;

      font-size: 2rem;

      font-weight: bold;

      cursor: pointer;

      transition: background-color 0.3s ease;

    }

    button:hover {

      background-color: #2ea043;

    }

    @media (max-width: 600px) {

      .card {

        padding: 1.5rem;

      }

    }

  </style>

</head>

<body>
        <header style="float: ;">&nbsp;&nbsp;

    <ul style="margin-top:-10px;">

      <a href=""><h5 style="font-weight: 800;color:#ccccccf1;float: left;">&nbsp;&nbsp;&nbsp;&nbsp;wealth<span style="background: linear-gradient(to right, #bfffd5,

                                    #7bff55,#2D9E45, #6affa2); -webkit-text-fill-color: transparent; -webkit-background-clip: text;font-weight: 800;">LOOM</span>&nbsp;&nbsp;<i class="fa fa-exchange" style=""></i>&nbsp;&nbsp;<b style="font-size:12px;color:peru;">Admin Dashboard</b></h5></a>

    </ul>

    <ul style="float: right;">

  <i class="fa fa-podcast" style="font-size:15px;font-weight:800;color:#ffffff;"></i>  &nbsp;&nbsp;&nbsp;&nbsp;

    <a href="logout.php"><i class="fa fa-sign-out" style="font-size:15px;font-weight:800;color:red;"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

    </ul>

</header>
        <br><br><br><br>
        <div class="container">

  <div class="card">

    <h2>✏️ Edit User: <?= htmlspecialchars($user['user_name']) ?></h2>

    <form action="update_user_info.php" method="post">

      <input type="hidden" name="id" value="<?= $id ?>">

      <label for="first_name">First Name:</label>

      <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>

      <label for="last_name">Last Name:</label>

      <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>

      <label for="user_name">User Name:</label>

      <input type="text" name="user_name" value="<?= htmlspecialchars($user['user_name']) ?>" required>
            
      <label for="email">Email:</label>

      <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

      <label for="password">Password:</label>

      <input type="text" name="password" value="<?= htmlspecialchars($user['password']) ?>" required>

      <label for="confirm_password">Confirm Password:</label>

      <input type="text" name="confirm_password" value="<?= htmlspecialchars($user['confirm_password']) ?>" required>

      <label for="phone">Phone:</label>

      <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>

      <label for="country">Country:</label>

      <input type="text" name="country" value="<?= htmlspecialchars($user['country']) ?>" required>
            
       <label for="state">State:</label>

      <input type="text" name="state" value="<?= htmlspecialchars($user['state']) ?>" required>

      <label for="address">Address:</label>

      <input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>" required>

      <label for="plan">Plan:</label>

      <input type="text" name="plan" value="<?= htmlspecialchars($user['plan']) ?>" required>           

      <label for="currency">Currency:</label>

      <input type="text" name="currency" value="<?= htmlspecialchars($user['currency']) ?>" required>

      <label for="account">Account:</label>

      <input type="text" name="account" value="<?= htmlspecialchars($user['account']) ?>" required>

      <label for="balance">Balance:</label>

      <input type="text" name="balance" value="<?= htmlspecialchars($user['balance']) ?>" required>

      <label for="invested">Invested:</label>

      <input type="text" name="deposit" value="<?= htmlspecialchars($user['deposit']) ?>" required>

      <label for="PandL">PandL:</label>

      <input type="text" name="pnl" value="<?= htmlspecialchars($user['pnl']) ?>" required>

      <label for="equity">Equity:</label>

      <input type="text" name="equity" value="<?= htmlspecialchars($user['equity']) ?>" required>

      <label for="margin">Margin:</label>

      <input type="text" name="margin" value="<?= htmlspecialchars($user['margin']) ?>" required>

      
       <!-- <label for="acct_id">Account ID:</label>-->

      <!--<input type="text" name="acct_id" value="<?//= htmlspecialchars($user['acct_id']) ?>" required>-->

      <label for="open_trades">Open Trades:</label>

      <input type="text" name="open_trades" value="<?= htmlspecialchars($user['open_trades']) ?>" required>

      
            
              
           
            
            
            
            
      <button type="submit">Update</button>

    </form>

  </div>
                
                </div>

</body>

</html>