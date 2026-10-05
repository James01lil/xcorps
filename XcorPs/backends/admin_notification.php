<?php

include '../authenticate/db.php';

$result = $conn->query("

  SELECT n.id, u.user_name, n.message, n.created_at 

  FROM notifications n 

  JOIN users u ON n.user_id = u.id 

  ORDER BY n.created_at DESC

");

?>

<!DOCTYPE html>

<html>

<head>

  <title>All Sent Notifications</title>

  <style>

    body {

      background-color: #0d1117;

      color: #e6edf3;

      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;


      margin: 0;

    }
          .container{
                  padding:2rem;
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
        margin-top:;

}
          

    h2 {

      font-size: 1.8rem;

      margin-bottom: 1rem;

      color: #58a6ff;

      border-bottom: 2px solid #30363d;

      padding-bottom: 0.5rem;

    }

    table {

      width: 100%;

      border-collapse: collapse;

      background-color: #161b22;

      box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);

      border-radius: 8px;

      overflow: hidden;

    }

    th, td {

      padding: 1rem;

      text-align: left;

    }

    th {

      background-color: #21262d;

      color: #c9d1d9;

      font-weight: 600;

      text-transform: uppercase;

      font-size: 0.85rem;

    }

    td {

      border-top: 1px solid #30363d;

      font-size: 23px;
            font-weight:800;

    }

    tr:hover {

      background-color: #1f2937;

    }

    @media (max-width: 768px) {

      table, thead, tbody, th, td, tr {

        display: block;

      }

      thead tr {

        display: none;

      }

      tr {

        margin-bottom: 1rem;

        border: 1px solid #30363d;

        border-radius: 8px;

        padding: 1rem;

      }

      td {

        border: none;

        padding: 0.5rem 0;

        position: relative;

      }

      td:before {

        content: attr(data-label);

        position: absolute;

        left: 0;

        top: 0;

        font-weight: bold;

        color: #8b949e;

        text-transform: uppercase;

        font-size: 0.75rem;

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
        
      <br><br><br><br>  
        <div class="container">

  <h2>📨 All Sent Notifications</h2>

  <table>

    <thead>

      <tr>

        <th>ID</th>

        <th>User</th>

        <th>Message</th>

        <th>Time Sent</th>

      </tr>

    </thead>

    <tbody>

      <?php while ($row = $result->fetch_assoc()): ?>

      <tr>

        <td data-label="ID"><?= $row['id'] ?></td>

        <td data-label="User"><?= htmlspecialchars($row['user_name']) ?></td>

        <td data-label="Message"><?= nl2br(htmlspecialchars($row['message'])) ?></td>

        <td data-label="Time Sent"><?= date('F j, Y H:i', strtotime($row['created_at'])) ?></td>

      </tr>

      <?php endwhile; ?>

    </tbody>

  </table>
                
                </div>

</body>

</html>