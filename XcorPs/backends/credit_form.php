<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../authenticate/db.php';

// Check if ID is passed and is numeric
if (!isset($_GET['id'])) {
  die("Invalid or missing user ID.");
}
$id = intval($_GET['id']);

// Now use $id as needed

$id = intval($_GET['id']); // Safe cast to integer

// Run query
$result = $conn->query("SELECT * FROM users WHERE id = $id");
if (!$result || $result->num_rows === 0) {
    die("User not found.");
}

$user = $result->fetch_assoc();
?>


<!DOCTYPE html>

<html>

<head>

  <title>Credit User</title>

  <style>

    body {

      background-color: #0d1117;

      color: #e6edf3;

      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;

      margin: 0;

    }
          .container{
                 display: flex;

      justify-content: center;

      align-items: center;

      height: 90vh;
 
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

    .card {

      background-color: #161b22;

      padding: 5rem;

      border-radius: 12px;

      box-shadow: 0 0 12px rgba(0, 0, 0, 0.4);

      max-width: 800px;

      width: 100%;

    }

    h2 {

      text-align: center;

      color: #58a6ff;

      margin-bottom: 1rem;

    }

    p {

      text-align: center;

      font-size: 2.1rem;

      margin-bottom: 1.5rem;
}

    input[type="number"] {

      width: 100%;

      padding: 1.75rem;

      margin-bottom: 1.5rem;

      border: 1px solid #30363d;

      border-radius: 12px;

      background-color: #0d1117;

      color: #e6edf3;

      font-size: 2rem;

    }

    input[type="number"]:focus {

      outline: none;

      border-color: #58a6ff;

      box-shadow: 0 0 0 3px rgba(88, 166, 255, 0.2);

    }

    button {

      width: 100%;

      padding: 1.75rem;

      background-color: #238636;

      color: white;

      border: none;

      border-radius: 12px;

      font-size: 2rem;

      font-weight: bold;
}


    button:hover {

      background-color: #2ea043;

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
        
<div class="container">

        
        
        

<div class="card">
  <h2>💰 Credit User: <?= htmlspecialchars($user['user_name']) ?></h2>

  <p>Current Balance: <strong>$<?= number_format($user['balance'], 2) ?></strong></p>
  <p>Invested: <strong>$<?= number_format($user['deposit'], 2) ?></strong></p>
  <p>Equity: <strong>$<?= number_format($user['equity'], 2) ?></strong></p>
  <p>Margin: <strong>$<?= number_format($user['margin'], 2) ?></strong></p>
  <p>P&L: <strong>$<?= number_format($user['pnl'], 2) ?></strong></p>

  <form action="credit_user.php" method="post">
    <input type="hidden" name="id" value="<?= $id ?>">
    
    <label>Amount to Credit:</label><br>
    <input type="number" name="amount" step="0.01" min="0" required><br><br>

    <label>Select fields to credit:</label><br>
    <label><input type="checkbox" name="fields[]" value="balance"> Balance</label><br>
    <label><input type="checkbox" name="fields[]" value="deposit"> Invested</label><br>
    <label><input type="checkbox" name="fields[]" value="equity"> Equity</label><br>
    <label><input type="checkbox" name="fields[]" value="margin"> Margin</label><br>
    <label><input type="checkbox" name="fields[]" value="pnl"> P&L</label><br><br>

    <button type="submit">Credit Selected Fields</button>
  </form>
</div>
  
        
        
        </div>

</body>

</html>