<?php
session_start();

/*if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}*/

require '../../authenticate/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
        // Redirect to login page
    header("Location: ../../authenticate/login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch full user record
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Check if user is banned
if ($user && $user['status'] === 'banned') {
    header("Location: ../bannedpage/banned.php");
    exit();
}
?>

<?php /*if (isset($_SESSION['impersonating']) && $_SESSION['impersonating'] === true): ?>
  <a href="../../backends/stop_impersonation.php" class="impersonation-banner">Back to Admin</a>
<?php endif; */?>

<!--NOTIFICATION MESSAGE-->

<?php
$unread = $conn->prepare("SELECT COUNT(*) AS unread FROM notifications WHERE user_id=? AND is_read=0");
$unread->bind_param("i", $user_id);
$unread->execute();
$count_result = $unread->get_result()->fetch_assoc();
$unread_count = $count_result['unread'];
?>         
 <?php if ($unread_count > 0): ?>
 <?php endif; ?>

<!----END-->

<!DOCTYPE html>

<html lang="en">

<head>

  <meta charset="UTF-8">

  <title>XcorPs | Community Hub</title>

    <!-- FAVIVON -->
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
        
        <!--START CANDLE STICK SCRIPT-->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-financial"></script>
  <!--END CANDLE STICK SCRIPT-->
<script src="https://unpkg.com/lightweight-charts/dist/lightweight-charts.standalone.production.js"></script>

    <link rel="stylesheet" href="css/inner-style.css" />
    
  <style>
  .impersonation-banner {
  position: fixed;
  top: 40%;
  left: 0;
  writing-mode: vertical-rl;
  transform: rotate(180deg);
  background-color: #e74c3c;
  color: white;
  padding: 10px 15px;
  text-align: center;
  text-decoration: none;
  font-weight: bold;
  border-radius: 0 8px 8px 0;
  z-index: 9999;
  box-shadow: 2px 2px 10px rgba(0,0,0,0.3);
  font-family: sans-serif;
  transition: background 0.3s ease;
}

.impersonation-banner:hover {
  background-color: #c0392b;
}

  
  .image-header {

  width: 100%;

  height: 300px;

  background: url('../../imgg/GIF(1).gif') no-repeat center center/cover;

}
.image-header h3{
    margin-top :150px;
    position:absolute ;
}

@media (max-width: 500px) {
.image-header h3{
    font-size:17px;
    color:peru;
}
}
     
      #notchFooter {
  position: fixed;
  bottom: 30px;
  left: 50%;
  transform: translateX(-50%);
  background: linear-gradient(135deg, rgba(26, 5, 7, 0.95), rgba(50, 15, 15, 0.95), rgba(74, 19, 17, 0.95));
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.4);
  border-radius: 50%;
  height: 60px;
  width: 60px;
  transition: all 0.5s ease-in-out;
  z-index: 999;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0;
}

    #notchFooter.expanded {
      width: 370px;
      height: 60px;
      border-radius: 40px;
      justify-content: center;
    }

    .footer-content {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 30px;
      width: 100%;
      transition: all 0.3s ease;
    }

    .extra {
      opacity: 0;
      transform: scale(0);
      pointer-events: none;
      color: white;
      text-align: center;
      text-decoration: none;
      transition: opacity 0.3s ease, transform 0.3s ease;
      width: 50px;
    }

    .extra i {
      font-size: 13px;
      margin-bottom: 4px;
    }

    .extra b {
      font-size: 11px;
    }

    #notchFooter.expanded .extra {
      opacity: 1;
      transform: scale(1);
      pointer-events: auto;
    }

    .center-icon-wrapper {
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      z-index: 2;
    }

    .center-icon {
      background: #951f19;
      border: 3px solid #951f19;
      border-radius: 50%;
      font-size: 22px;
      color: #111111;
      text-decoration:none;
      width: 44px;
      height: 44px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    @media (max-width: 500px) {
      .center-icon {
        font-size: 25px;
        width: 40px;
        height: 40px;
      }
    }

    .section {
      margin-bottom: 30px;
      background: #000000;
      padding: 15px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
	 @media (max-width: 500px) {
       .section {
        font-size: 13px;
      } 

    h4 {
      margin-top: 0;
    }
	  @media (max-width: 500px) {
       h4 {
        font-size: 13px;
      }


    /* Chat */
    #chatBox {
      height: 300px;
      overflow-y: auto;
      border: 1px solid #000;
      color:#fff;
      font-weight:800;
      padding: 10px;
      background: #000000;
      border-radius: 10px;
    }
    .message {
      padding: 5px;
      margin-bottom: 5px;
      border-bottom: 1px solid #eee;
    }

    /* Reviews */
    .carousel-container {
      position: relative;
      overflow: hidden;
    }
	  @media (max-width:500px){
    .carousel-container {
      width:100%;
    }
  }

    #reviewCarousel {
      display: flex;
      transition: transform 0.3s ease-in-out;
    }

    .review {
      width: 300px;
      margin-right: 10px;
      background: #e9f7ed;
      padding: 15px;
      border-radius: 10px;
      flex-shrink: 0;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      background:#222222;
    }

    .vote-controls {
      margin-top: 10px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .vote-btn {
      background: #ddd;
      border: none;
      padding: 5px 10px;
      border-radius: 6px;
      cursor: pointer;
    }

    .vote-btn:hover {
      background: #ccc;
    }

    .carousel-buttons {
      text-align: center;
      margin-top: 10px;
    }

    .carousel-buttons button {
      padding: 8px 15px;
      margin: 0 10px;
      background: #444;
      color: #fff;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    /* Activities */
    #activityList {
      max-height: 300px;
      overflow-y: auto;
      background: #000000;
      color:#ddd;
      padding: 10px;
      border-radius: 10px;
      border: 1px solid #000000;
      font-weight:900;
    }

    .activity {
      border-bottom: 1px dashed #ccc;
      padding: 6px 0;
    }
    
    
    
     .container {
      max-width: 800px;
      margin: 2rem auto;
      padding: 0 1rem;
    }
    .letstalk h4{
        font-weight:800;
    }

    

    button {
      background-color: #2563eb;
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 0.375rem;
      color: white;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }

    button:hover {
      background-color: #1e40af;
    }

    /* Search bar */
    .search-bar {
      display: flex;
      margin-bottom: 2rem;
    }

    .search-bar input[type="search"] {
      flex-grow: 1;
      padding: 0.9rem 0.75rem;
      border: 1px solid #d1d5db;
      border-radius: 0.375rem 0 0 0.375rem;
      font-size: 1.25rem;
      outline: none;
      background:#d2d2d2;
      color:#000;
      font-weight:800;
    }

    .search-bar button {
      border-radius: 0 0.375rem 0.375rem 0;
      background:green;
      color:#ddd;
      font-weight:800;
    }

    /* Post composer */
    .post-composer {
      background: #ddd;
      border: 1px solid #d1d5db;
      border-radius: 0.5rem;
      padding: 1rem;
      margin-bottom: 2rem;
      box-shadow: 0 1px 3px rgb(0 0 0 / 0.1);
    }

    .post-composer input,
    .post-composer textarea {
      width: 100%;
      padding: 1.5rem 0.75rem;
      margin-bottom: 1rem;
      border: 1px solid #d1d5db;
      border-radius: 0.375rem;
      font-size: 1.25rem;
      font-family: inherit;
      resize: vertical;
      color:#000;
      font-weight:800;
    }

    .post-composer textarea {
      min-height: 150px;
    }

    /* Posts feed */
    .post {
      background: #000000;
      border: 1px solid #d1d5db;
      border-radius: 0.5rem;
      padding: 2rem 1.25rem;
      margin-bottom: 1.5rem;
      box-shadow: 0 1px 3px rgb(0 0 0 / 0.05);
    }

    .post-header {
      display: flex;
      justify-content: space-between;
      font-size: 1.2rem;
      color: orangered;
      font-weight:800;
      margin-bottom: 0.5rem;
    }

    .post-author {
      font-weight: 800;
      color: peru;
    }

    .post-title {
      font-weight: 800;
      font-size: 2.8rem;
      margin-bottom: 0.5rem;
      color: #2563eb;
    }

    .post-content {
      font-size: 1.3rem;
      line-height: 1.4;
      margin-bottom: 0.75rem;
      color: #ddd;
      font-weight:900;
    }

    .post-actions {
      display: flex;
      gap: 1rem;
      font-size: 1.4rem;
      font-weight:900;
      color: #fff;
      cursor: pointer;
      user-select: none;
    }

    .post-actions span:hover {
      color: #2563eb;
      text-decoration: underline;
    }

    /* Responsive */
    @media (max-width: 600px) {
      header h1 {
        font-size: 1.5rem;
      }

      .post-title {
        font-size: 1rem;
      }
    }


   #news-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(400px, 2fr));
  gap: 20px;
  margin-left:-15px;
   }

 .blur-overlayaction {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background-color: rgba(255, 255, 255, 0.1); /* or use transparent black/gray */
  backdrop-filter: blur(2px);
  -webkit-backdrop-filter: blur(2px); /* Safari support */
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      display:non;
       padding:10px 10px;
       overflow-x:scroll;
       display:none;
  }


   
   
    .gab{
        position:fixed;
        width:80%;
        color:#ddd;
        margin-left:27px;
        margin-top:50px;
        color:#ddd;
  opacity: 0;
  transform: translateY(100px);
  animation: slideUp 1s ease-out forwards;
}

@keyframes slideUp {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
    
 



    .section {
      background: #000000;
      border-radius: 12px;
      padding: 16px;   
      margin-bottom: 20px;
    }
    .section h2 {
      margin-bottom: 12px;
      font-size: 18px;
      font-weight:900;
      color:peru;
    }
    .item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 0;
      border-top: 1px solid #2a2a3b;
      font-size:14px;
      font-weight:900;
    }
    .item:first-child {
      border-top: none;
    }
    .item span {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .new-badge {
      background: orange;
      color: black;
      font-size: 12px;
      padding: 2px 8px;
      border-radius: 12px;
    }

    .bottom-nav {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      background: #1a1a2b;
      display: flex;
      justify-content: space-around;
      align-items: center;
      padding: 12px 0;
      border-top: 1px solid #2a2a3b;
    }
    .bottom-nav div {
      text-align: center;
      font-size: 12px;
      color: #aaa;
    }
    .fab {
      background: linear-gradient(to right, #a855f7, #ec4899);
      color: white;
      border-radius: 50%;
      padding: 16px;
      position: relative;
      top: -20px;
      font-size: 24px;
    }
    .notif-dot {
      position: absolute;
      top: 0;
      right: 8px;
      background: orange;
      width: 8px;
      height: 8px;
      border-radius: 50%;
      
    }



    .dropdown {
  display: none;
  background: #2a2a3b;
  margin-top: 8px;
  border-radius: 8px;
  padding: 8px;
  font-size: 14px;
  width:50%;
}
.dropdown div {
  padding: 6px 10px;
  border-bottom: 1px solid #444;
  cursor: pointer;
}
.dropdown div:last-child {
  border-bottom: none;
}
.dropdown div:hover {
  background: #3c3c4e;
}
.dropdown-item.open .dropdown {
  display: block;
}
.sidebar {

  width: 200px;

  background: #161b22;

  position: fixed;

  height: 100vh;

  padding: 20px;

  box-sizing: border-box;

}

@media (max-width: 1000px) {

  .sidebar {

    display:none;

  }

  }

.sidebar h2 {

  color: #58a6ff;

}

.sidebar ul {

  list-style: none;

  padding: 0;

}

.sidebar li {

  margin: 20px 0;

  cursor: pointer;

}


.badge {

  background: red;

  border-radius: 12px;

  padding: 5px 10px;

  color: white;

}

.actions {

  margin-top: 20px;

}

button {

  background: #238636;

  color: white;

  padding: 10px 20px;

  margin-right: 10px;

  border: none;

  border-radius: 6px;

  cursor: pointer;

}
.dropdown1 {

  position: relative;

  display: inline-block;

}

.dropbtn1 {

  background-color: #161b22;

  color: #c9d1d9;

  border: none;

  font-size: 24px;

  cursor: pointer;

}

.dropdown-content1 {

  display: none;

  position: absolute;

  background-color: #21262d;

  box-shadow: 0px 8px 16px rgba(0,0,0,0.3);

  right: 0;

  min-width: 120px;

  border-radius: 6px;

  z-index: 1;

}

.dropdown-content1 a {

  color: #c9d1d9;

  padding: 10px 12px;

  text-decoration: none;

  display: block;

}

.dropdown-content1 a:hover {

  background-color: #30363d;

}

.dropdown1:hover .dropdown-content1 {

  display: block;

}




  </style>
</head>
<body>




    <div class="blur-overlayaction" id="actionTab">
<div class="gab">
  <!-- Toggle -->
        
  <!-- Section: Invest -->
  <div class="section">
    <h2>Account Management</h2>
<div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">My Account</span> ›
  <div class="dropdown">
    <div  onclick="window.location.href='../profile/profile.php'"  >Profile</div>
    </div>
    </div>
    
    
      <div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">Wallet</span> ›
  <div class="dropdown">
    <div onclick="window.location.href='../topup/topup.php'" >➕ Deposit Funds</div>
    <div onclick="window.location.href='../withdrawal/client/withdraw.php'">➖ Withdraw Funds</div>
    <div>🔁 Transfer</div>
    <div>📉 Sell Stocks</div>
    <div>🤖 Auto-Invest</div>
  </div>
</div>

<div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">Upgrade Account</span> ›
  <div class="dropdown">
      <div onclick="window.location.href='../upgrade/upgrade.php'"> UPGRADE PLAN</div>
    <div  onclick="window.location.href='../upgrade/premiumplan.php'"   >Premium Plans</div>
    <div   onclick="window.location.href='../upgrade/deluxeplan.php'"  >Deluxe Plans</div>
    <div onclick="window.location.href='../upgrade/deluxemegaplan.php'"  >Deluxe Mega Plans</div>
    <div  onclick="window.location.href='../upgrade/exclusiveplan.php'"  >Exclusive Plans</div>
    <div  onclick="window.location.href='../upgrade/vipplan.php'"  >VIP Plans</div>
  </div>
</div>

<div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">Balance Summary</span> ›
  <div class="dropdown">
    <div>Deposited -- <?= $user['currency'] ?><?= $user['deposit']; ?>!</h3>
</div>
    <div>Total Balance -- <?= $user['currency'] ?><?= $user['balance']; ?>!</h3>
</div>
    <div>Bonus -- <?= $user['currency'] ?><?= $user['bonus']; ?>!</h3>
</div>
    <div>Total Profits -- <?= $user['currency'] ?><?= $user['pnl']; ?>!</h3>
</div>
    <div>Trading Statistics</div>
  </div>
</div>

<div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">Security Settings</span> ›
  <div class="dropdown">
    <div>Passwords</div>
    <div>2FA (2 Factor Authentication)</div>
    <div>Device Management</div>
  </div>
</div>

<div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">Tax Documentation</span> ›
  <div class="dropdown">
    <div>Annual Statements</div>
    <div>Export Options</div>
  </div>
</div>

<div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">KYC Verification</span> ›
  <div class="dropdown">
    <div onclick="window.location.href='../kyc_verification/kyc_verification.php'">Upload Verification</div>
    <div>Check Verification Status</div>
  </div>
</div>
 </div>
 
  
  
  <!------ SUPPORT AND TOOLS--------->
  <div class="section">
    <h2>Support & Tools</h2>    
    <div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">Help Center</span> ›
  <div class="dropdown">
    <div>FAQ</div>
  </div>
</div>
  <div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">Contact Support</span> ›
  <div class="dropdown">
    <div onclick="window.location.href='../mailus/mailus.php'">Live Chat With An Expert</div>
  </div>
</div>
<div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">Trading Tools</span> ›
  <div class="dropdown">
    <div onclick="window.location.href='../tradestation/trade_station.php'"  >Trade Stations</div>
    <div onclick="window.location.href='../signal/signalupdate.php'">Signal Hub</div>
    <div onclick="window.location.href='community.php'">Watchlists</div>
  </div>
</div>
<div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">System Status</span> ›
  <div class="dropdown">
    <div>User Comments Or Sentiment Tagging</div>
    <div onclick="window.location.href='../notification_page/notification_page.php'">Announcements</div>
  </div>
</div>   
  </div>


  <!-- Section: Research -->
  <div class="section">
    <h2>Research</h2>    
    <div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">📰 News</span> ›
  <div class="dropdown">
    <div>📰 Market News</div>
    <div>Financial News Feed</div>
    <div>Alerts</div>
  </div>
</div>
  <div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">🔍 Stock Screener</span> ›
  <div class="dropdown">
    <div>Broker Research</div>
    <div>Stock Analysis</div>
  </div>
</div>
<div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">Learning Center</span> ›
  <div class="dropdown">
    <div>Articles</div>
    <div>Video Tutorials on Investing</div>
  </div>
</div>
<div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">📑 Webminars / Events</span> ›
  <div class="dropdown">
    <div>Join</div>
    <div>Register</div>
  </div>
</div>   
  </div>
  
  <!------ COMMUNITY FEAUTURES--------->
  <div class="section">
    <h2>Community Feautures</h2>    
    <div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">Community Forum</span> ›
  <div class="dropdown">
    <div onclick="window.location.href='community.php'">Public Discussions</div>
    <div>Private User Discussions</div>
  </div>
</div>
  <div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">Leaderboards</span> ›
  <div class="dropdown">
    <div>Top Traders</div>
  </div>
</div>
<div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">Groups / Clubs</span> ›
  <div class="dropdown">
    <div>Join Based On Assets & Strategies</div>
  </div>
</div>
<div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">Comment On Stocks</span> ›
  <div class="dropdown">
    <div>User Comments Or Sentiment Tagging</div>
  </div>
</div>   
  </div>
  

  <!-- Section: Earn -->
  <div class="section">
    <h2>Earn</h2>
    <div onclick="window.location.href='../referrals/referral.php'" class="item"><span>🎁 Referral Program</span> <span class="new-badge">New</span></div>
    <div onclick="window.location.href='../reward/reward.php'" class="item"><span>💵 Rewards</span> <span class="new-badge">New</span></div>
  </div>
  
<m onclick="actionMenuremove();" style="justify-content:center;align-items:center;display:flex;"><i class="fa fa-times" style="background:#951f19;border:3px solid #9; padding:10px 12px;border-radius:30px;font-size:25px;font-weight:900;color:#000;"></i></m>
<br><br><br><br>
  
        
        
  </div>
      </div>
        </div>
        
        
     <header style="float: ;">&nbsp;&nbsp;
    <ul style="margin-top:5px;">
      <a href=""><h4 style="font-weight: 800;color:#ccccccf1;float: left;">&nbsp;&nbsp;&nbsp;&nbsp;X<span style="background: linear-gradient(to right, #bfffd5,
                                    #7bff55,#2D9E45, #6affa2); -webkit-text-fill-color: transparent; -webkit-background-clip: text;font-weight: 800;">corPs</span></h4></a>
    </ul>
    <ul style="float: right;">
  <i class="fa fa-podcast" style="font-size:15px;font-weight:800;color:#ffffff;"></i>  &nbsp;&nbsp;&nbsp;&nbsp;
    <a href=""><?php echo $user['user_name']; ?></a>&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="logout.php"><i class="fa fa-sign-out" style="font-size:15px;font-weight:800;color:red;"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </ul>
</header>


  <!-- Image at the top of the page -->
  <div class="image-header">
    <small id="google_translate_element" style="margin-top:0px;"></small>
       <h3 style="">Welcome,&nbsp;&nbsp;<?= $user['user_name']; ?>!</h3>
      <ul style="">
        <?= $user['email']; ?>
      </ul>

  <!-- TradingView Widget BEGIN -->
<div class="tradingview-widget-container">
  <div class="tradingview-widget-container__widget"></div>
  <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js" async>
  {
  "symbols": [
    {
      "proName": "FOREXCOM:SPXUSD",
      "title": "S&P 500 Index"
    },
    {
      "proName": "FOREXCOM:NSXUSD",
      "title": "US 100 Cash CFD"
    },
    {
      "proName": "FX_IDC:EURUSD",
      "title": "EUR to USD"
    },
    {
      "proName": "BITSTAMP:BTCUSD",
      "title": "Bitcoin"
    },
    {
      "proName": "BITSTAMP:ETHUSD",
      "title": "Ethereum"
    }
  ],
  "showSymbolLogo": true,
  "isTransparent": true,
  "displayMode": "adaptive",
  "colorTheme": "dark",
  "locale": "en"
}
  </script>
</div>
<!-- TradingView Widget END -->

     </div>
     
     
     
     
      
  <!-- Main content area with curved top -->
  <div class="curved-body">
<h4 style="text-align:center;color:peru;font-weight:800;">XCORPS COMMUNITY FORUM</h4>
<div class="section">
    <h4>📈 User Activities</h4>
    <div id="activityList"></div>
  </div>


  <div class="section">
    <h2>🌟 Broker Reviews</h2>
    <div class="carousel-container">
      <div id="reviewCarousel"></div>
    </div>
    <div class="carousel-buttons">
      <button onclick="slide(-1)">⬅️ Prev</button>
      <button onclick="slide(1)">Next ➡️</button>
    </div>
  </div>


 <!-- TradingView Widget BEGIN -->
<div class="tradingview-widget-container">
  <div class="tradingview-widget-container__widget"></div>
  <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js" async>
  {
  "symbols": [
    {
      "proName": "FOREXCOM:SPXUSD",
      "title": "S&P 500 Index"
    },
    {
      "proName": "FOREXCOM:NSXUSD",
      "title": "US 100 Cash CFD"
    },
    {
      "proName": "FX_IDC:EURUSD",
      "title": "EUR to USD"
    },
    {
      "proName": "BITSTAMP:BTCUSD",
      "title": "Bitcoin"
    },
    {
      "proName": "BITSTAMP:ETHUSD",
      "title": "Ethereum"
    }
  ],
  "showSymbolLogo": true,
  "isTransparent": true,
  "displayMode": "adaptive",
  "colorTheme": "dark",
  "locale": "en"
}
  </script>
</div>
<!-- TradingView Widget END -->



 <div class="container">
    <div class="letspost">
      <h4>Community Forum</h4>
      <button id="new-post-btn" style="float:right;">New Post</button>
    </div>
<br><br><br>
    <div class="search-bar">
      <input type="search" id="search-input" placeholder="Search posts..." />
      <button id="search-btn">Search</button>
    </div>

    <!-- Post Composer -->
    <div class="post-composer" id="post-composer" style="display:none;">
      <input type="text" id="post-title" placeholder="Post Title" />
      <textarea id="post-content" placeholder="Write your post here..."></textarea>
      <button id="submit-post-btn">Post</button>
    </div>

    <!-- Posts feed -->
    <div id="posts-container">
      <!-- Posts will be injected here -->
    </div>
  </div>

	  


  <div class="section">
    <h4>💬 Community Chat</h4>
    <div id="chatBox"></div>
  </div>

    <!-----------NEWS--------------->
  <div id="news-container"></div>

<br><br><br><br>
  


</div>


 
      <footer id="notchFooter">
    <div class="footer-content">
      <a href="../dashboard/dashboard.php" class="extra"><i class="fa fa-bank"></i><br><b>HOME</b></a>
      <a href="../tradestation/trade_station.php" class="extra"><i class="fa fa-line-chart"></i><br><b>TRADE</b></a>
      
      <!-- Center icon (absolute, centered) -->
      <div class="center-icon-wrapper">
        <a href="#" onclick="actionMenu();"><i class="fa fa-exchange center-icon"></i></a>
      </div>
      
      <a href="../signal/signalupdate.php" class="extra"><i class="fa fa-signal"></i><br><b>SIGNAL</b></a>
      <a href="community.php" class="extra"><i class="fa fa-users"></i><br><b>COMMUNITY</b></a>
    </div>
  </footer>









  

   <script src="../NEWS/cachedNEWS/gnews.js"></script>
  <script>
    const usernames = ['CryptoKing', 'HODLerJane', 'AltFiGuru', 'SatoshiSeeker', 'PumpQueen', 'DegenDan', 'StakerMike', 'Web3Wendy'];
    const assets = ['BTC', 'ETH', 'SOL', 'DOGE', 'ADA', 'AVAX', 'XRP', 'MATIC'];
    const actions = ['bought', 'sold', 'staked', 'reviewed', 'followed', 'commented on', 'joined'];

    const reviews = [
      "Absolutely love the UI and instant trade execution!",
      "The best broker I've used — fast deposits and withdrawals!",
      "Could use better customer support, but solid platform.",
      "Fees are lower than most platforms. Great experience.",
      "Superb experience staking ETH with them.",
      "I recommend this broker to all beginners.",
      "Mobile app is smooth, no bugs so far.",
      "Reliable and trustworthy for over a year now."
    ];

    let reviewVotes = JSON.parse(localStorage.getItem('review_votes')) || new Array(reviews.length).fill(0);

    let chatHistory = JSON.parse(localStorage.getItem('chat_history')) || [];
    let activityHistory = JSON.parse(localStorage.getItem('activity_history')) || [];

    const chatBox = document.getElementById('chatBox');
    const reviewCarousel = document.getElementById('reviewCarousel');
    const activityList = document.getElementById('activityList');

    function getRandomItem(arr) {
      return arr[Math.floor(Math.random() * arr.length)];
    }

    function addChatMessage() {
      const msg = {
        user: getRandomItem(usernames),
        text: `${getRandomItem(actions)} ${getRandomItem(assets)} at $${(Math.random() * 3000).toFixed(2)}`,
        time: new Date().toLocaleTimeString()
      };
      chatHistory.push(msg);
      if (chatHistory.length > 100) chatHistory.shift();
      localStorage.setItem('chat_history', JSON.stringify(chatHistory));

      const div = document.createElement('div');
      div.className = 'message';
      div.textContent = `[${msg.time}] ${msg.user}: ${msg.text}`;
      chatBox.appendChild(div);
      chatBox.scrollTop = chatBox.scrollHeight;
    }

    function addActivity() {
      const activity = {
        text: `${getRandomItem(usernames)} ${getRandomItem(actions)} ${getRandomItem(assets)}.`,
        time: new Date().toLocaleTimeString()
      };
      activityHistory.push(activity);
      if (activityHistory.length > 500) activityHistory.shift();
      localStorage.setItem('activity_history', JSON.stringify(activityHistory));

      const div = document.createElement('div');
      div.className = 'activity';
      div.textContent = `[${activity.time}] ${activity.text}`;
      activityList.appendChild(div);
      activityList.scrollTop = activityList.scrollHeight;
    }

    function populateReviews() {
      reviewCarousel.innerHTML = '';
      reviews.forEach((review, index) => {
        const div = document.createElement('div');
        div.className = 'review';
        div.innerHTML = `
          "${review}"
          <div class="vote-controls">
            <button class="vote-btn" onclick="vote(${index}, 1)">👍</button>
            <button class="vote-btn" onclick="vote(${index}, -1)">👎</button>
            <span id="vote-${index}">${reviewVotes[index]}</span> votes
          </div>
        `;
        reviewCarousel.appendChild(div);
      });
    }

    function vote(index, change) {
      reviewVotes[index] += change;
      localStorage.setItem('review_votes', JSON.stringify(reviewVotes));
      document.getElementById(`vote-${index}`).textContent = reviewVotes[index];
    }

    let currentIndex = 0;
function slide(direction) {
  const reviewsPerSlide = 1;
  const totalSlides = reviews.length;
  currentIndex += direction;

  if (currentIndex < 0) currentIndex = 0;
  if (currentIndex > totalSlides - reviewsPerSlide) currentIndex = totalSlides - reviewsPerSlide;

  const width = 310; // review width + margin
  reviewCarousel.style.transform = `translateX(-${currentIndex * width}px)`;
}


    function loadSavedData() {
      chatHistory.forEach(msg => {
        const div = document.createElement('div');
        div.className = 'message';
        div.textContent = `[${msg.time}] ${msg.user}: ${msg.text}`;
        chatBox.appendChild(div);
      });

      activityHistory.forEach(activity => {
        const div = document.createElement('div');
        div.className = 'activity';
        div.textContent = `[${activity.time}] ${activity.text}`;
        activityList.appendChild(div);
      });
    }

    // Initialize
    populateReviews();
    loadSavedData();
    setInterval(addChatMessage, 2000);
    setInterval(addActivity, 1000);
  </script>
  <script>
    const posts = [
      {
        id: 1,
        author: "Alex Morgan",
        title: "Market outlook this week?",
        content: "Curious about everyone’s thoughts on the tech sector this week. Seems like some volatility ahead...",
        time: new Date(Date.now() - 7200000), // 2 hours ago
        likes: 5,
        comments: 2,
        reported: false,
      },
      {
        id: 2,
        author: "Jamie Lee",
        title: "Investment tips for beginners",
        content: "Starting with a small monthly budget is key. Avoid putting all your eggs in one basket!",
        time: new Date(Date.now() - 36000000), // 10 hours ago
        likes: 8,
        comments: 5,
        reported: false,
      },
      {
        id: 3,
        author: "Taylor Smith",
        title: "Crypto vs Stocks: Which is safer?",
        content: "I’m torn between investing in crypto or stocks. Any advice?",
        time: new Date(Date.now() - 86400000), // 1 day ago
        likes: 2,
        comments: 1,
        reported: false,
      },
    ];

    const postsContainer = document.getElementById('posts-container');
    const postComposer = document.getElementById('post-composer');
    const newPostBtn = document.getElementById('new-post-btn');
    const submitPostBtn = document.getElementById('submit-post-btn');
    const searchInput = document.getElementById('search-input');
    const searchBtn = document.getElementById('search-btn');

    function timeSince(date) {
      const seconds = Math.floor((new Date() - date) / 1000);
      let interval = Math.floor(seconds / 86400);
      if (interval > 1) return interval + " days ago";
      interval = Math.floor(seconds / 3600);
      if (interval > 1) return interval + " hours ago";
      interval = Math.floor(seconds / 60);
      if (interval > 1) return interval + " minutes ago";
      return "Just now";
    }

    function renderPosts(filteredPosts) {
      postsContainer.innerHTML = '';
      filteredPosts.forEach(post => {
        const postEl = document.createElement('div');
        postEl.className = 'post';

        postEl.innerHTML = `
          <div class="post-header">
            <div class="post-author">${post.author}</div>
            <div class="post-time">${timeSince(post.time)}</div>
          </div>
          <div class="post-title">${post.title}</div>
          <div class="post-content">${post.content}</div>
          <div class="post-actions">
            <span class="like-btn" data-id="${post.id}">👍 Like (${post.likes})</span>
            <span class="comment-btn" data-id="${post.id}">💬 Comment (${post.comments})</span>
            <span class="report-btn" data-id="${post.id}">🚩 Report</span>
          </div>
        `;

        postsContainer.appendChild(postEl);
      });

      // Add event listeners for like/report
      document.querySelectorAll('.like-btn').forEach(btn => {
        btn.onclick = () => {
          const id = parseInt(btn.dataset.id);
          const post = posts.find(p => p.id === id);
          if (post) {
            post.likes++;
            renderPosts(posts);
          }
        };
      });

      document.querySelectorAll('.report-btn').forEach(btn => {
        btn.onclick = () => {
          const id = parseInt(btn.dataset.id);
          const post = posts.find(p => p.id === id);
          if (post && !post.reported) {
            alert('Post reported. Thank you for your feedback.');
            post.reported = true;
          } else {
            alert('You have already reported this post.');
          }
        };
      });

      // Comments button - placeholder
      document.querySelectorAll('.comment-btn').forEach(btn => {
        btn.onclick = () => alert('Comments feature coming soon!');
      });
    }

    newPostBtn.onclick = () => {
      if (postComposer.style.display === 'none') {
        postComposer.style.display = 'block';
        newPostBtn.textContent = 'Cancel';
      } else {
        postComposer.style.display = 'none';
        newPostBtn.textContent = 'New Post';
      }
    };

    submitPostBtn.onclick = () => {
      const titleInput = document.getElementById('post-title');
      const contentInput = document.getElementById('post-content');

      if (!titleInput.value.trim() || !contentInput.value.trim()) {
        alert('Please enter a title and content for your post.');
        return;
      }

      posts.unshift({
        id: posts.length + 1,
        author: 'You',
        title: titleInput.value.trim(),
        content: contentInput.value.trim(),
        time: new Date(),
        likes: 0,
        comments: 0,
        reported: false,
      });

      titleInput.value = '';
      contentInput.value = '';
      postComposer.style.display = 'none';
      newPostBtn.textContent = 'New Post';
      renderPosts(posts);
    };

    searchBtn.onclick = () => {
      const query = searchInput.value.trim().toLowerCase();
      if (!query) {
        renderPosts(posts);
        return;
      }
      const filtered = posts.filter(p =>
        p.title.toLowerCase().includes(query) ||
        p.content.toLowerCase().includes(query) ||
        p.author.toLowerCase().includes(query)
      );
      renderPosts(filtered);
    };

    // Initial render
    renderPosts(posts);
  </script>
  
  

<script>
const messagess = document.getElementById('messagess');
const totalMessagess = messagess.children.length;
let currentIndex = 0;

setInterval(() => {
  currentIndex = (currentIndex + 1) % totalMessagess;
  messagess.style.transform = `translateX(-${currentIndex * 100}%)`;
}, 3000); // Change every 3 seconds
  </script>
  
  
  
  <script>
    const footer = document.getElementById("notchFooter");
    let scrollTimeout;
  
    function expandFooter() {
      footer.classList.add("expanded");
  
      clearTimeout(scrollTimeout);
      scrollTimeout = setTimeout(() => {
        footer.classList.remove("expanded");
      }, 2000); // Shrink after 2 seconds idle
    }
  
    window.addEventListener("scroll", expandFooter);
  </script>
      
      
      
      
      
      
      <script>
        function actionMenu(){     
       document.getElementById("actionTab").style.display = "block";
        document.getElementById("actionMenufooter").style.display = "none";
       }
       function actionMenuremove(){     
       document.getElementById("actionTab").style.display = "none";
         document.getElementById("actionMenufooter").style.display = "block";
       }
        
        
        
        
       function toggleDropdown(element) {
  const parent = element.closest('.dropdown-item');
  document.querySelectorAll('.dropdown-item').forEach(item => {
    if (item !== parent) item.classList.remove('open');
  });
  parent.classList.toggle('open');
} 
        
        
    </script>
  


</body>
</html>
