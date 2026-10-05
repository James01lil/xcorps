<?php

include '../authenticate/db.php';

$result = $conn->query("SELECT id, first_name, last_name, user_name, email, password, confirm_password, phone, country, state, address,  status, plan, currency, account, comment, balance, deposit, equity, pnl, margin, open_trades FROM users");

?>

<!DOCTYPE html>
<html>
  <head>
    <title>XCORPS ADMIN PANEL</title>
          
          <!-- FAVIVON -->

                
        
  <style>

    body {

      background-color: #121212;

      color: #e0e0e0;

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

        margin-top:px;

}
          
          .container{
                 padding:50px;
                

                  
                   }

    .card {

      background-color: #1f1f1f;

      padding: 30px;

      border-radius: 16px;

      box-shadow: 0 0 12px rgba(0, 0, 0, 0.5);

      max-width: 1000px;

      margin: 0 auto;

    }

    h2 {

      margin-top: 0;

      font-size: 50px;

      border-bottom: 1px solid #333;

      padding-bottom: 10px;

    }

    .table_container {
  max-height: 100vh;         /* Set vertical scroll limit */
  overflow-y: auto;          /* Enables vertical scroll */
  overflow-x: auto;          /* Enables horizontal scroll */
  display: block;
  border: 1px solid #444;    /* Optional: helps visually separate the container */
}

/* Optional enhancements */
.table_container table {
  width: 100%;
  border-collapse: collapse;
  min-width: 1200px;          /* Ensures table scrolls horizontally if needed */
}

.table_container th,
.table_container td {
  padding: 50px 50px;
  border: 1px solid #ccc;
  text-align: left;
  white-space: nowrap;       /* Prevents cells from wrapping */
}

    thead {

      background-color: #272727;


    }

    th {

      font-weight: bold;
            font-size:25px;

      color: #ffffff;

    }
          td {
                  font-size:30px;
                  font-weight:800;


                  }
       

    tr:nth-child(even) {

      background-color: #1a1a1a;

    }

    tr:hover {

      background-color: #2a2a2a;

    }

    .action-btn {

      padding: 20px 30px;

      border: none;

      border-radius: 6px;

      background-color: #3f51b5;

      color: white;

      cursor: pointer;

      font-size: 20px;

      transition: background 0.3s ease;

    }

    .action-btn:hover {

      background-color: #5c6bc0;

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
      <br><br><br><br>
        <div class="container">
        
  <div class="card">

    <h2>Client Management</h2>
          <div class="table_container">

    <table>

      <thead>

        <tr>

          <th>User ID</th>

          <th>First Name</th>

          <th>Last Name</th>

          <th>User Name</th>

          <th>Email</th>
                
          <th>Password</th>

          <th>Confirm Password</th>

          <th>Phone</th>

          <th>Country</th>

          <th>State</th>
                
          <th>Address</th>

          <th>Plan</th>

          <th>Currency (£)</th>

          <th>Account</th>

          <th>Balance (€)</th>      

          <th>Invested ($)</th>

          <th>Equity</th>

          <th>PandL (¥)</th>

          <th>Margin</th>
                
         <!--  <th>Account ID</th>-->

          <th>Open Trades</th>

          <th>Action</th>

        </tr>

      </thead>

      <tbody>

        <?php while($user = $result->fetch_assoc()): ?>

          <tr>

            <td><?= $user['id'] ?></td>

            <td><?= $user['first_name'] ?></td>

            <td><?= $user['last_name'] ?></td>

            <td><?= $user['user_name'] ?></td>

           <td><?= $user['email'] ?></td>

            <td><?= $user['password'] ?></td>

            <td><?= $user['confirm_password'] ?></td>

            <td><?= $user['phone'] ?></td>

           <td><?= $user['country'] ?></td>

            <td><?= $user['state'] ?></td>

            <td><?= $user['address'] ?></td>

            <td><?= $user['plan'] ?></td>

           <td><?= $user['currency'] ?></td>

            <td><?= $user['account'] ?></td>

          <td><?= number_format($user['balance'], 2) ?></td>

            <td><?= number_format($user['deposit'], 2) ?></td>
                  
           <td><?= number_format($user['equity'], 2) ?></td>
            
          <td><?= number_format($user['pnl'], 2) ?></td> 
                  
             <td><?= number_format($user['margin'], 2) ?></td>
            
            <!--<td><?//= $user['acct_id'] ?></td>-->
                  
              <td><?= $user['open_trades'] ?></td>   
                  
                  
                  <td>

              <!-- Example Button -->

              <button class="action-btn" onclick="alert('Manage user <?= $user['id'] ?>')">Manage</button>

            </td>

          </tr>

        <?php endwhile; ?>

      </tbody>

    </table>
                  </div>

  </div>

                
                </div>
</body>

</html>