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

  <title>XcorPs | KYC Hub</title>

    <!-- FAVIVON -->

        <link rel="shortcut icon" href="images/favicon.png" type="image/png">

        <meta charset="UTF-8">

        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="preconnect" href="https://fonts.gstatic.com">

        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

	      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        
        

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  

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

  height: 250px;

  background: url('../../imgg/GIF(1).gif') no-repeat center center/cover;

}
.image-header h3{
    margin-top :150px;
    position:absolute;
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
  text-decoration:none;
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
      text-decoration:none;
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
      text-decoration:none;
    }

    .extra i {
      font-size: 13px;
      margin-bottom: 4px;
      text-decoration:none;
      outline-color:#ddd;
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

.main {

  margin-left: 200px;

  padding: 0px;

}

@media (max-width: 1000px) {

  .main {

  margin-left: 0px;

}

  }

.header {

  display: flex;

  justify-content: space-between;

  align-items: center;

}

.badge {

  background: red;

  border-radius: 12px;

  padding: 5px 10px;

  color: white;

}

.cards {

  display: grid;

  grid-template-columns: repeat(4, 1fr);

  gap: 15px;

  margin-top: 20px;

}

@media (max-width: 700px) {

 .cards {

  grid-template-columns: repeat(2, 1fr);

  }

  }

  

  @media (max-width: 400px) {

 .cards {

  grid-template-columns: repeat(1, 1fr);

  }

  }

  

.card {

  background: #131313;

  padding: 20px;

  border-radius: 10px;

  text-align: center;

}

table {

  width: 100%;

  margin-top: 20px;

  background: #161b22;

  border-collapse: collapse;

  overflow-x:scroll;

  min-width:600px;

}

 .table_container{

     max-width:100%;

     overflow-x:auto;

 }

th, td {

  padding: 12px;

  text-align: center;

  border-bottom: 1px solid #30363d;

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

.account-summary {

  font-size: 0.9rem;

  margin-top: 5px;

  color: #8b949e;

}

.account-summary p{

  font-weight:800;

  font-size:15px;

}

.status.verified {

  color: #2ea043;

  font-weight: bold;

}

.user-profile {

  display: flex;

  align-items: center;

  gap: 10px;

  position: relative;

}

.avatar {

  width: 45px;

  height: 45px;

  border-radius: 50%;

  border: 2px solid #58a6ff;

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


#news-container {

  display: grid;

  grid-template-columns: repeat(auto-fill, minmax(400px, 2fr));

  gap: 20px;

  margin-left:-15px;

}





 .kyc-container {
     /* background-color: #1f2937;*/
      padding: 30px;
      border-radius: 16px;
      max-width: 500px;
      width: 100%;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    }

    .kyc-container h2 {
      margin-bottom: 25px;
      font-size: 24px;
      text-align: center;
      color: #fff;
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-size: 15px;
      color: #d1d5db;
    }

    input[type="text"],
    input[type="date"],
    input[type="file"] {
      width: 100%;
      padding: 12px;
      background-color: #374151;
      border: 1px solid #4b5563;
      border-radius: 8px;
      color: #f9fafb;
      font-size: 14px;
    }

    input[type="file"] {
      padding: 10px;
    }

    input[type="submit"] {
      background-color: #3b82f6;
      color: #fff;
      padding: 12px;
      width: 100%;
      border: none;
      border-radius: 10px;
      font-size: 16px;
      cursor: pointer;
      margin-top: 10px;
      transition: background-color 0.3s ease;
    }

    input[type="submit"]:hover {
      background-color: #2563eb;
    }

    @media screen and (max-width: 500px) {
      .kyc-container {
        padding: 20px;
      }
    }
  



/* Hide warning by default */
#desktopWarning {
  display: none;
}

/* Show warning and hide content on screens wider than 500px */
@media (min-width: 501px) {
  #desktopWarning {
    display: flex;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #111;
    color: white;
    font-size: 1.2rem;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 20px;
    z-index: 9999;
  }

  #mainContent {
    display: none;
  }
}
</style>

  

</head>

<body>
    
    
    
    <div id="desktopWarning">
  ⚠️ This website is only accessible on mobile or small screens.<br>
  Please resize your browser or use a mobile device.
</div>

<div id="mainContent">
  <!-- Your actual website content goes here -->
</div>

    
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
      <div onclick="window.location.href='upgrade/upgrade.php'">UPGRADE PLAN</div>
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
    <div onclick="window.location.href='kyc_verification.php'">Upload Verification</div>
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
    <div onclick="window.location.href='../community/community.php'">Watchlists</div>
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
    <div onclick="window.location.href='../community/community.php'">Public Discussions</div>
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

    <ul style="margin-top:-10px;">

      <a href=""><h5 style="font-weight: 800;color:#ccccccf1;float: left;">&nbsp;&nbsp;&nbsp;&nbsp;X<span style="background: linear-gradient(to right, #bfffd5,

                                    #7bff55,#2D9E45, #6affa2); -webkit-text-fill-color: transparent; -webkit-background-clip: text;font-weight: 800;">corPs</span>&nbsp;&nbsp;<i class="fa fa-exchange" style=""></i>&nbsp;&nbsp;<b style="font-size:12px;color:peru;">KYC Hub</b></h5></a>

    </ul>

    <ul style="float: right;">
            
            
      <a href="../notification_page/notification_page.php" style=""> <i class="fa fa-message" style="font-size:11px;font-weight:800;color:#ffffff;margin-top:-15px;"></i>
    <span class="badge" style="margin-left:0px;margin-top:-10px;"><?= $unread_count ?></span></a>
          

  <i class="fa fa-podcast" style="font-size:15px;font-weight:800;color:#ffffff;"></i>  &nbsp;&nbsp;&nbsp;&nbsp;

    <a href=""></a>&nbsp;&nbsp;&nbsp;&nbsp;

    <a href="../logout/logout.php"><i class="fa fa-sign-out" style="font-size:15px;font-weight:800;color:red;"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

    </ul>

</header>

<br><br>

  <div class="sidebar">

    <h2>📈 TradeDesk</h2>

    <ul>

      <li>Dashboard</li>

      <li>New Trade</li>

      <li>Deposit</li>

      <li>Withdraw</li>

      <li>Profile</li>

      <li>Logout</li>

    </ul>

  </div>

<div class="image-header">

    <small id="google_translate_element" style="margin-top:0px;"></small>

      <h3 style=""> Welcome,&nbsp;&nbsp;<?= $user['user_name']; ?>!</h3>

      <ul style="margin-top:120px;">

        <div class="dropdown1">

      <button class="dropbtn1">☰</button>

      <div class="dropdown-content1">

        <a href="../profile/profile.php">👤 Profile</a>

        <a href="#">⚙️ Settings</a>

        <a href="../logout/logout.php">🚪 Logout</a>

      </div>

    </div>

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

      <div class="main">



<!-- KYC Instruction Modal -->
<div id="kycModal" style="
  position: fixed;
  top: 0;
  left: 0;
  z-index: 9999;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0,0,0,0.85);
  display: flex;
  justify-content: center;
  align-items: center;
  font-family: 'Roboto', sans-serif;
">
  <div style="
    background-color: transparent ;
    padding: 30px;
    border-radius: 12px;
    max-width: 500px;
    width: 90%;
    color: #ffffff;
    text-align: left;
    box-shadow: 0 8px 30px rgba(0,0,0,0.5);
  ">
    <h3 style="color: peru; margin-bottom: 20px;">KYC Instructions</h3>
    <ul style="line-height: 1.8; font-size: 15px; padding-left: 18px;">
      <li>Upload a clear image of the <b>front side</b> of your government-issued ID (e.g., Passport, Driver's License).</li>
      <li>Upload a clear image of the <b>back side</b> of the same ID.</li>
      <li>Upload a <b>selfie</b> of you holding the same ID and a paper with today's date.</li>
      <li>Ensure all uploads are in <b>JPG, PNG, or PDF</b> format and not blurred.</li>
      <li>Use your <b>real full name</b> and accurate <b>Date of Birth</b>.</li>
    </ul>
    <button onclick="closeKYCModal()" style="
      margin-top: 20px;
      width: 100%;
      padding: 12px;
      background-color: #3b82f6;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
    ">I Understand</button>
  </div>
</div>



      
<form class="kyc-container" action="submit_kyc.php" method="POST" enctype="multipart/form-data">
    <h2>KYC Verification</h2>

    <div class="form-group">
      <label for="full_name">Full Name</label>
      <input type="text" name="full_name" id="full_name" required>
    </div>

    <div class="form-group">
      <label for="dob">Date of Birth</label>
      <input type="date" name="dob" id="dob" required>
    </div>

    <div class="form-group">
      <label for="id_front">Upload ID (Front)</label>
      <input type="file" name="id_front" id="id_front" accept="image/*,.pdf" required>
    </div>

    <div class="form-group">
      <label for="id_back">Upload ID (Back)</label>
      <input type="file" name="id_back" id="id_back" accept="image/*,.pdf" required>
    </div>

    <div class="form-group">
      <label for="selfie">Upload Selfie Holding ID</label>
      <input type="file" name="selfie" id="selfie" accept="image/*" required>
    </div>

    <input type="submit" value="Submit for Review">
  </form>
    

  

  


          
          
          
    



    

    

    

    

    
          
          
          
          
         

       

  

  

  

  

  

    

    

    

    

    

    

    

    




<!------------'NEWS------->

<div id="news-container"></div>

</div>
</div>

<br><br><br><br>
        
  <footer id="notchFooter">
    <div class="footer-content">
      <a href="../dashboard/dashboard.php" class="extra"><i class="fa fa-bank"></i><br><b>HOME</b></a>
      <a href="../tradestation/trade_station.php" class="extra"><i class="fa fa-line-chart"></i><br><b>TRADE</b></a>
      
      <!-- Center icon (absolute, centered) -->
      <div class="center-icon-wrapper">
        <a href="#" onclick="actionMenu();"><i class="fa fa-exchange center-icon"></i></a>
      </div>
      
      <a href="../signal/signalupdate.php" class="extra"><i class="fa fa-signal"></i><br><b>SIGNAL</b></a>
      <a href="../community/community.php" class="extra"><i class="fa fa-users"></i><br><b>COMMUNITY</b></a>
    </div>
  </footer>


  <script src="../NEWS/cachedNEWS/gnews.js"></script>

  

  

  

  

  

  

  

  

  

  

 

  
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


  
  
  
  
  
  <script>
  function closeKYCModal() {
    document.getElementById("kycModal").style.display = "none";
  }

  // Optional: Prevent KYC form interaction until modal is dismissed
  window.onload = function () {
    document.querySelector(".kyc-container").style.display = "none";
    document.getElementById("kycModal").style.display = "flex";
  };

  function closeKYCModal() {
    document.getElementById("kycModal").style.display = "none";
    document.querySelector(".kyc-container").style.display = "block";
  }
</script>


  

</body>

</html>