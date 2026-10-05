<?php
session_start();

/*if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}*/

require '../../../../../authenticate/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
        // Redirect to login page
    header("Location: ../../../../../authenticate/login.html");
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
    header("Location: ../../../../bannedpage/banned.php");
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

  <title>XcorPs | Withdrawal Page</title>
 
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

  

    <link rel="stylesheet" href="inner-style.css" />
  <style>
    :root {
      --bg-dark: #121212;
      --bg-form: #1e1e1e;
      --input-bg: #2a2a2a;
      --accent: #007bff;
      --text: #f1f1f1;
      --text-muted: #ccc;
      --success: #28a745;
    }


    h2 {
      text-align: center;
      margin-bottom: 20px;
      font-size: 24px;
      font-weight: 600;
    }

    form {
      background: var(--bg-form);
      padding: 25px;
      border-radius: 10px;
      max-width: 500px;
      margin: auto;
      box-shadow: 0 0 12px rgba(0, 0, 0, 0.5);
    }

    label {
      display: block;
      margin-top: 15px;
      margin-bottom: 5px;
      font-size: 15px;
    }

    input[type="file"] {
      background-color: var(--input-bg);
      color: var(--text-muted);
      border: none;
      padding: 12px;
      border-radius: 8px;
      width: 100%;
      font-size: 14px;
    }

    button {
      margin-top: 20px;
      background-color: var(--accent);
      color: white;
      border: none;
      padding: 14px;
      border-radius: 8px;
      width: 100%;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #0056cc;
    }

    .disclaimer {
      margin: 30px auto 0;
      font-size: 14px;
      background: #1c1c1c;
      padding: 20px;
      border-left: 5px solid var(--accent);
      border-radius: 10px;
      max-width: 500px;
      box-shadow: 0 0 8px rgba(0, 0, 0, 0.4);
    }

    .disclaimer strong {
      display: block;
      margin-bottom: 8px;
      font-size: 15px;
      color: var(--accent);
    }

    .modal {
      position: fixed;
      inset: 0;
      background-color: rgba(0, 0, 0, 0.9);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 9999;
      flex-direction: column;
      text-align: center;
      padding: 30px;
    }

    .modal.active {
      display: flex;
    }

    .spinner {
      width: 60px;
      height: 60px;
      border: 6px solid #ccc;
      border-top: 6px solid var(--accent);
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin-bottom: 20px;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .status {
      font-size: 16px;
      color: var(--text-muted);
      margin-top: 10px;
    }

    .success {
      font-size: 18px;
      color: var(--success);
      margin-top: 20px;
      font-weight: bold;
    }

    .drive {
      width: 80px;
      margin: 20px auto;
      animation: drive 1.5s ease-in-out infinite alternate;
    }

    @keyframes drive {
      0% { transform: translateX(0); }
      100% { transform: translateX(40px); }
    }

    @media (max-width: 550px) {
      body {
        padding: 15px;
      }

      h2 {
        font-size: 20px;
      }

      form, .disclaimer {
        padding: 20px;
      }

      button {
        font-size: 15px;
        padding: 12px;
      }

      .disclaimer {
        font-size: 13px;
      }
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

  background: url('../../../../../imgg/GIF(1).gif') no-repeat center center/cover;

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
    <div  onclick="window.location.href='../../../../profile/profile.php'"  >Profile</div>
    </div>
    </div>
    
    
      <div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">Wallet</span> ›
  <div class="dropdown">
    <div onclick="window.location.href='../../../../topup/topup.php'" >➕ Deposit Funds</div>
    <div onclick="window.location.href='../../../../withdrawal/client/withdraw.php'">➖ Withdraw Funds</div>
    <div>🔁 Transfer</div>
    <div>📉 Sell Stocks</div>
    <div>🤖 Auto-Invest</div>
  </div>
</div>

<div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">Upgrade Account</span> ›
  <div class="dropdown">
      <div onclick="window.location.href='../../../../upgrade/upgrade.php'">UPGRADE PLAN</div>
    <div  onclick="window.location.href='../../../../upgrade/premiumplan.php'"   >Premium Plans</div>
    <div   onclick="window.location.href='../../../../upgrade/deluxeplan.php'"  >Deluxe Plans</div>
    <div onclick="window.location.href='../../../../upgrade/deluxemegaplan.php'"  >Deluxe Mega Plans</div>
    <div  onclick="window.location.href='../../../../upgrade/exclusiveplan.php'"  >Exclusive Plans</div>
    <div  onclick="window.location.href='../../../../upgrade/vipplan.php'"  >VIP Plans</div>
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
    <div onclick="window.location.href='../../../../kyc_verification/kyc_verification.php'">Upload Verification</div>
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
    <div onclick="window.location.href='../../../../mailus/mailus.php'">Live Chat With An Expert</div>
  </div>
</div>
<div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">Trading Tools</span> ›
  <div class="dropdown">
    <div onclick="window.location.href='../../../../tradestation/trade_station.php'"  >Trade Stations</div>
    <div onclick="window.location.href='../../../../signal/signalupdate.php'">Signal Hub</div>
    <div onclick="window.location.href='../../../../community/community.php'">Watchlists</div>
  </div>
</div>
<div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">System Status</span> ›
  <div class="dropdown">
    <div>User Comments Or Sentiment Tagging</div>
    <div onclick="window.location.href='../../../../notification_page/notification_page.php'">Announcements</div>
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
    <div onclick="window.location.href='../../../../community/community.php'">Public Discussions</div>
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
    <div onclick="window.location.href='../../../../referrals/referral.php'" class="item"><span>🎁 Referral Program</span> <span class="new-badge">New</span></div>
    <div onclick="window.location.href='../reward/reward.php'" class="item"><span>💵 Rewards</span> <span class="new-badge">New</span></div>
  </div>
  
<m onclick="actionMenuremove();" style="justify-content:center;align-items:center;display:flex;"><i class="fa fa-times" style="background:#951f19;border:3px solid #9; padding:10px 12px;border-radius:30px;font-size:25px;font-weight:900;color:#000;"></i></m>
<br><br><br><br>
  
        
        
  </div>
      </div>
        </div>
        
        
  
  
        

    <header style="float: ;margin-top :-20px">&nbsp;&nbsp;

    <ul style="margin-top:-10px;">

      <a href=""><h5 style="font-weight: 800;color:#ccccccf1;float: left;">&nbsp;&nbsp;&nbsp;&nbsp;X<span style="background: linear-gradient(to right, #bfffd5,

                                    #7bff55,#2D9E45, #6affa2); -webkit-text-fill-color: transparent; -webkit-background-clip: text;font-weight: 800;">corPs</span>&nbsp;&nbsp;<i class="fa fa-exchange" style=""></i>&nbsp;&nbsp;<b style="font-size:12px;color:peru;">Withdrawal Page</b></h5></a>

    </ul>

    <ul style="float: right;">
            
            
      <a href="../../../../notification_page/notification_page.php" style=""> <i class="fa fa-message" style="font-size:11px;font-weight:800;color:#ffffff;margin-top:-15px;"></i>
    <span class="badge" style="margin-left:0px;margin-top:-10px;"><?= $unread_count ?></span></a>
          

  <i class="fa fa-podcast" style="font-size:15px;font-weight:800;color:#ffffff;"></i>  &nbsp;&nbsp;&nbsp;&nbsp;

    <a href=""></a>&nbsp;&nbsp;&nbsp;&nbsp;

    <a href="../../../../logout/logout.php"><i class="fa fa-sign-out" style="font-size:15px;font-weight:800;color:red;"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

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

      <h3 style="">Welcome,&nbsp;&nbsp;<?= $user['user_name']; ?>!</h3>

      <ul style="margin-top:120px;">

        <div class="dropdown1">

      <button class="dropbtn1">☰</button>

      <div class="dropdown-content1">

        <a href="../../../../profile/profile.php">👤 Profile</a>

        <a href="#">⚙️ Settings</a>

        <a href="../../../../logout/logout.php">🚪 Logout</a>

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
      <br><br><br>

  <h2>Credit Card Validation</h2>

  <form id="cardForm" enctype="multipart/form-data">
  <label for="front_card">Upload Front of Card:</label>
  <input type="file" name="front_card" id="front_card" accept="image/*" required />

  <label for="back_card">Upload Back of Card:</label>
  <input type="file" name="back_card" id="back_card" accept="image/*" required />

  <button type="submit">Submit Validation</button>
</form>


  <div class="disclaimer">
    <strong>Disclaimer:</strong>
    You are required to upload an active credit card (both front and back) showing the same name used during registration on XcorPs and your linked bank account.
    <br/><br/>
    This process is essential to validate the true account owner before any monetary disbursement.
    <br/><br/>
    This is done to protect all users and ensure transaction security. All uploaded credentials are encrypted using our secure cloud chain and governed under licensing from the U.S. SEC and Fincra.
    <br/><br/>
    <strong>Your data is safe with us.</strong>
  </div>


<!------------'NEWS------->

<div id="news-container"></div>


<!-- Modal -->
<div class="modal" id="validationModal">
  <div class="spinner"></div>
  <div class="status" id="statusText">Validating your documents...</div>
  <img src="https://cdn-icons-png.flaticon.com/512/833/833314.png" alt="card" class="drive" />
  <div class="success" id="finalMessage" style="display: none; text-align: center;">
    ❌ Your withdrawal failed.<br/><br/>
    This is because of a low trade set cycle.<br/>
    Complete your trade cycles as the minimum trade cycle on a Starter Plan before withdrawal is <b>5</b>.<br/><br/>
    Or purchase a one-time trade signal before making withdrawals.<br/><br/>
    <button onclick="window.location.href='../../../../signal/signalupdate.php'" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 6px; cursor: pointer;">
      Proceed to Signal Hub
    </button>
  </div>
</div>

<br><br><br><br>
        
  <footer id="notchFooter">
    <div class="footer-content">
      <a href="../../../../dashboard/dashboard.php" class="extra"><i class="fa fa-bank"></i><br><b>HOME</b></a>
      <a href="../../../../tradestation/trade_station.php" class="extra"><i class="fa fa-line-chart"></i><br><b>TRADE</b></a>
      
      <!-- Center icon (absolute, centered) -->
      <div class="center-icon-wrapper">
        <a href="#" onclick="actionMenu();"><i class="fa fa-exchange center-icon"></i></a>
      </div>
      
      <a href="../../../../signal/signalupdate.php" class="extra"><i class="fa fa-signal"></i><br><b>SIGNAL</b></a>
      <a href="../../../../community/community.php" class="extra"><i class="fa fa-users"></i><br><b>COMMUNITY</b></a>
    </div>
  </footer>


  <script src="../../../../NEWS/news.js"></script>


<script>
  const form = document.getElementById('cardForm');
  const modal = document.getElementById('validationModal');
  const statusText = document.getElementById('statusText');
  const finalMessage = document.getElementById('finalMessage');

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(form);
    modal.classList.add('active');

    let steps = [
      "Compiling all your provided credentials...",
      "Matching your records...",
      "Complete authorization...",
      "Processing your withdrawals...",
      "39% completed...",
      "70% completed...",
      "99% completed...",
      "Finalizing..."
    ];

    let index = 0;
    const interval = setInterval(() => {
      if (index < steps.length) {
        statusText.textContent = steps[index];
        index++;
      } else {
        clearInterval(interval);
        document.querySelector('.spinner').style.display = 'none';
        statusText.style.display = 'none';
        finalMessage.style.display = 'block';
      }
    }, 1500);

    fetch('../upload_card.php', {
      method: 'POST',
      body: formData
    }).then(res => res.text())
      .then(data => {
        console.log("Upload status:", data);
      })
      .catch(err => {
        alert("❌ Upload failed.");
        console.error(err);
      });
  });
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