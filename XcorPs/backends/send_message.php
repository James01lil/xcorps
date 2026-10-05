<?php

include '../authenticate/db.php';

$user_id = $_GET['id'] ?? null;

if (!$user_id) {

  die("Invalid user.");

}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $message = trim($_POST['message']);

  if (!empty($message)) {

    $stmt = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");

    $stmt->bind_param("is", $user_id, $message);

    $stmt->execute();

    header("Location: admin_dashboard.php");

    exit();

  }

}

?>

<!DOCTYPE html>

<html>

<head>

  <title>Send Message</title>

  
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
        
        
    label {

      display: block;

      margin-bottom: 0.5rem;

      font-weight: 600;

      color: #c9d1d9;
            font-size:2rem;

    }

    textarea {

      width: 100%;

      height: 150px;

      padding: 1rem;

      font-size: 2rem;

      background-color: #0d1117;

      border: 1px solid #30363d;

      color: #e6edf3;

      border-radius: 8px;

      resize: vertical;

    }

    textarea:focus {

      outline: none;

      border-color: #58a6ff;

      box-shadow: 0 0 0 3px rgba(88, 166, 255, 0.2);

    }

    button{

      margin-top: 1rem;

      width: 100%;

      padding: 1.75rem;

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

      <a href=""><h5 style="font-weight: 800;color:#ccccccf1;float: left;">&nbsp;&nbsp;&nbsp;&nbsp;X<span style="background: linear-gradient(to right, #bfffd5,

                                    #7bff55,#2D9E45, #6affa2); -webkit-text-fill-color: transparent; -webkit-background-clip: text;font-weight: 800;">corPs</span>&nbsp;&nbsp;<i class="fa fa-exchange" style=""></i>&nbsp;&nbsp;<b style="font-size:12px;color:peru;">Admin Dashboard</b></h5></a>

    </ul>

    <ul style="float: right;">

  <i class="fa fa-podcast" style="font-size:15px;font-weight:800;color:#ffffff;"></i>  &nbsp;&nbsp;&nbsp;&nbsp;

    <a href="logout.php"><i class="fa fa-sign-out" style="font-size:15px;font-weight:800;color:red;"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

    </ul>

</header>

        

<div class="container">

        

  <div class="card">
        

  
    <h2>📩 Send Message to User #<?= htmlspecialchars($user_id) ?></h2>

    <form method="POST">

      <label for="message">Message:</label>

      <textarea name="message" required></textarea>

      <button type="submit">Send</button>

    </form>

  </div>
        </div>

</body>

</html>