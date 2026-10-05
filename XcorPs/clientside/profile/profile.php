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

  <title>XcorPs | User Profile</title>
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
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>

	  h3{
           font-size:14px;
	  }
	  
      .curved-body{
    
    background-color: rgba(255, 255, 255, 0.1); /* or use transparent black/gray */
  backdrop-filter: blur(2px);
  -webkit-backdrop-filter: blur(2px); /* Safari support */
}
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
    position :absolute;
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





    * {
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
      margin: 0;
      padding: 0;
    }

   

   
    .dashboard {
      max-width: 1200px;
      margin: 2rem auto;
      padding: 1rem;
    }

    .grid {
      display: grid;
      gap: 1rem;
    }

    .stats {
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    }

    .card {
      background: #000000;
      border:1px solid #111111;
      border-radius: 10px;
      padding: 1rem;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      text-align: center;
    }

    .card h3 {
      font-size: 1.2rem;
      color: #0e76fd;
    }

    .section {
      margin-top: 2rem;
    }

    canvas {
      background: #dfdfdf;
      padding: 1rem;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .activity-log, .plans, .referrals, .security, .actions {
      background: white;
      padding: 1rem;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      font-size :13px;
    }

    .log-entry {
      padding: 0.5rem 0;
      border-bottom: 1px solid #eee;
    }

    .log-entry:last-child {
      border-bottom: none;
    }

    .btn {
      padding: 0.6rem 1.2rem;
      background: #0e76fd;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }
    
    .profile-card {
  background: #000000;
  border:1px solid #111111;
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1.2rem;
  border-radius: 12px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
  max-width: 600px;
  margin: 2rem auto;
}

.avatar {
  width: 80px;
  height: 75px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid #0e76fd;
}

.profile-info h2 {
  margin-bottom: 0.2rem;
  font-size: 1.4rem;
  color: #0e76fd;
}

.profile-info p {
  font-weight: 600;
  color: #fff;
  margin-bottom: 0.3rem;
  font-size: 11px;
}

.status {
  display: inline-block;
  font-size: 0.9rem;
  color: #ddd;
  font-weight:800;
}
    
    
    
    .kyc-status {
  margin-top: 0.6rem;
  font-size: 0.95rem;
  color: #ddd;
}

.kyc-badge {
  padding: 0.2rem 0.6rem;
  border-radius: 6px;
  font-weight: 600;
  margin-left: 0.5rem;
  font-size: 0.85rem;
}

.kyc-badge.verified {
  background: #d4f6d9;
  color: #1a8f2f;
}

.kyc-badge.pending {
  background: #fff4cc;
  color: #b58900;
}

.kyc-badge.unverified {
  background: #fde2e2;
  color: #d63333;
}

.kyc-actions {
  margin-top: 0.8rem;
}

.btn.small {
  padding: 0.4rem 0.8rem;
  font-size: 0.85rem;
  margin-right: 0.5rem;
}

.btn.outline {
  background: transparent;
  border: 1px solid #0e76fd;
  color: #0e76fd;
}


/* Modal styles */
.modal {
  position: fixed;
  top: 0; left: 0; right:0; bottom:0;
  background: rgba(0,0,0,0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  padding: 1.5rem 2rem;
  border-radius: 12px;
  max-width: 350px;
  width: 100%;
  box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

/* Reuse your btn styles and add small, outline in your CSS as provided before */
    
    
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
        margin-top:30px;
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
      font-size: 13px;
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
  <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-storage-compat.js"></script>
  
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
    <div  onclick="window.location.href='profile.php'"  >Profile</div>
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
        
  
  </div>



      <header style="float: ;">&nbsp;&nbsp;
    <ul style="margin-top:-10px;">
      <a href=""><h5 style="font-weight: 800;color:#ccccccf1;float: left;">&nbsp;&nbsp;&nbsp;&nbsp;X<span style="background: linear-gradient(to right, #bfffd5,
                                    #7bff55,#2D9E45, #6affa2); -webkit-text-fill-color: transparent; -webkit-background-clip: text;font-weight: 800;">corPs</span>&nbsp;&nbsp;<i class="fa fa-exchange" style=""></i>&nbsp;&nbsp;<b style="font-size:12px;color:peru;">Portfolio: profile</b></h5></a>
    </ul>
    <ul style="float: right;">
  <i class="fa fa-podcast" style="font-size:15px;font-weight:800;color:#ffffff;"></i>  &nbsp;&nbsp;&nbsp;&nbsp;
    <a href=""><?php echo $user['user_name']; ?></a>&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="../logout/logout.php"><i class="fa fa-sign-out" style="font-size:15px;font-weight:800;color:red;"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </ul>
</header>



<div class="hood">
  <!-- Image at the top of the page -->
  <div class="image-header">
    <small id="google_translate_element" style="margin-top:0px;"></small>
       <h3 style=""> Welcome,&nbsp;&nbsp;<?= $user['user_name']; ?>!</h3>
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
      
      <h3 style="text-align:center;color:peru;font-weight:800;">Your personal profile</h3>
  
  <!-- Profile Card with KYC -->
<div class="profile-card">
  <img src="../../imgg/profilepanda.jpg" alt="User Photo" class="avatar">
  <div class="profile-info">
    <h2><?= $user['user_name']; ?>!</h2>
    <p><?= $user['plan']; ?>! Investor</p>
    <span class="status"><?= $user['status']; ?> since <?= $user['created_at']; ?></span>

    <div class="kyc-status">
      <strong>KYC Status:</strong>
      <span id="kycBadge" class="kyc-badge unverified">Not Verified</span>
    </div>
    <div class="kyc-actions">
      <button id="openKycBtn" class="btn small">Verify Now</button>
      <button id="viewDocsBtn" class="btn small outline" style="display:none;">View Submitted</button>
    </div>
  </div>
</div>

<!-- KYC Modal -->
<div id="kycModal" class="modal" style="display:none;">
  <div class="modal-content">
    <h3>KYC Verification</h3>
    
    <div>
      <label for="docUpload">Upload Valid Document (e.g., ID, Passport):</label><br/>
      <input type="file" id="docUpload" accept=".jpg,.jpeg,.png,.pdf" multiple />
    </div>
    
    <div style="margin-top:1rem;">
      <label>Take a Selfie:</label><br/>
      <video id="selfieVideo" autoplay playsinline width="280" height="210" style="border:1px solid #ddd; border-radius:8px;"></video><br/>
      <button id="captureSelfieBtn" class="btn small" style="margin-top:0.5rem;">Capture Selfie</button>
      <canvas id="selfieCanvas" width="280" height="210" style="display:none; margin-top: 1rem; border-radius:8px; border:1px solid #0e76fd;"></canvas>
    </div>

    <div style="margin-top: 1rem;">
      <button id="submitKycBtn" class="btn" disabled>Submit Verification</button>
      <button id="closeModalBtn" class="btn outline" style="margin-left:0.5rem;">Cancel</button>
    </div>
  </div>
</div>

  <div class="dashboard">

    <!-- Financial Summary -->
    <div class="grid stats">
      <div class="card"><h3><?= $user['currency']; ?><?= $user['balance']; ?></h3><p>Current Balance</p></div>
      <div class="card"><h3><?= $user['currency']; ?><?= $user['deposit']; ?></h3><p>Total Deposits</p></div>
      <div class="card"><h3><?= $user['currency']; ?><?= $user['pnl']; ?></h3><p>Total Profits</p></div>
      <div class="card"><h3><?= $user['referral']; ?></h3><p>Total Referrals</p></div>
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

                        

    <!-- Investment Chart -->
    <div class="section">
      <h2>Investment Growth</h2>
      <canvas id="growthChart" height="100"></canvas>
    </div>
    

    <!-- Activity Log -->
    <div class="section activity-log">
      <h2>Recent Activity</h2>
      <div class="log-entry">Deposited - <?= $user['deposit']; ?></div>
      <div class="log-entry">Earned Signed up Bonus <?= $user['bonus']; ?> - <?= $user['created_at']; ?></div>
      <div class="log-entry">Registered at <?= $user['plan']; ?> - <?= $user['created_at']; ?></div>
    </div>

    <!-- Plan Info -->
    <div class="section plans">
      <h2>Your Plan: <?= $user['plan']; ?></h2>
      <p>ROI: 15% Monthly | Duration: 12 months</p>
      <button class="btn" onclick="window.location.href='../upgrade/upgrade.php'">Upgrade Plan</button>
    </div>

    <!-- Referral Program -->
    <div class="section referrals">
      <h2>Referral Program</h2>
      <p>Your Referral Link:</p>
      <code style="background:#333;color:greenyellow;font-weight:800;">https://xcorps.online/refWB-9F8Z-X4d7/28</code>
    </div>

    <!-- Security Section -->
    <div class="section security">
      <h2>Security</h2>
      <button class="btn">Change Password</button>
      <button class="btn">Enable 2FA</button>
    </div>

    <!-- Deposit & Withdraw -->
    <div class="section actions">
      <h2>Account Actions</h2>
      <button class="btn" onclick="window.location.href='../topup/topup.php'">Deposit</button>
      <button class="btn" onclick="window.location.href='../withdrawal/client/withdraw.php'">Withdraw</button>
    </div>

  </div>
  
  
  
  <div class="section kyc-upload">
  <h2>KYC Verification</h2>
  <p id="kyc-status">KYC Status: <strong id="kyc-status-text">Not Verified</strong></p>
  <input type="file" id="kyc-id" accept=".jpg,.jpeg,.png,.pdf" />
  <button class="btn" onclick="uploadToFirebase()">Upload Government ID</button>
  <p id="kyc-message" style="margin-top: 1rem;"></p>
</div>

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
      <a href="../community/community.php" class="extra"><i class="fa fa-users"></i><br><b>COMMUNITY</b></a>
    </div>
  </footer>
  
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
  // Your Firebase configuration (replace with your actual config)
  const firebaseConfig = {
  apiKey: "AIzaSyBkAdBHdBD0Qkw9N0LYyghn_wxeZcz3rdE",
  authDomain: "realtime-chat-9f9c8.firebaseapp.com",
  projectId: "realtime-chat-9f9c8",
  storageBucket: "realtime-chat-9f9c8.appspot.com",
  messagingSenderId: "690622612833",
  appId: "1:690622612833:web:3781edb0595fbe3fc67120"
};

  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
  const storage = firebase.storage();
</script>


<script>
  // Load status on page load
  window.addEventListener('DOMContentLoaded', () => {
    const isVerified = localStorage.getItem('kyc_verified') === 'true';
    document.getElementById('kyc-status-text').textContent = isVerified ? 'Verified' : 'Not Verified';
    document.getElementById('kyc-status-text').style.color = isVerified ? 'green' : 'red';
  });

  function uploadToFirebase() {
    const fileInput = document.getElementById('kyc-id');
    const file = fileInput.files[0];
    const status = document.getElementById('kyc-message');

    if (!file) {
      status.textContent = 'Please select a file.';
      status.style.color = 'red';
      return;
    }

    const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
    if (!allowedTypes.includes(file.type)) {
      status.textContent = 'Invalid file type.';
      status.style.color = 'red';
      return;
    }

    const filePath = `kyc_uploads/${Date.now()}_${file.name}`;
    const uploadTask = storage.ref(filePath).put(file);

    status.textContent = 'Uploading...';
    status.style.color = 'black';

    uploadTask.on('state_changed',
      null,
      (error) => {
        status.textContent = 'Upload failed: ' + error;
        status.style.color = 'red';
      },
      () => {
        uploadTask.snapshot.ref.getDownloadURL().then((url) => {
          localStorage.setItem('kyc_verified', 'true');
          localStorage.setItem('kyc_url', url);
          document.getElementById('kyc-status-text').textContent = 'Verified';
          document.getElementById('kyc-status-text').style.color = 'green';
          status.textContent = 'KYC successfully verified!';
          status.style.color = 'green';
        });
      }
    );
  }
</script>

  <script>
    const ctx = document.getElementById('growthChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['May', 'Jun', 'Jul', 'Aug', 'Sep'],
        datasets: [{
          label: 'Investment Value',
          data: [1000, 1500, 2200, 3100, 5200],
          borderColor: '#0e76fd',
          backgroundColor: 'rgba(14, 118, 253, 0.1)',
          tension: 0.3,
          fill: true
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: { beginAtZero: true }
        }
      }
    });
  </script>

</body>
</html>
