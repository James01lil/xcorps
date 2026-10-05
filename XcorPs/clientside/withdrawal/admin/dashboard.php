<?php
include("../db.php");
$withdrawals = $conn->query("SELECT w.*, u.user_name FROM withdrawals w JOIN users u ON u.id = w.user_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Approve Withdrawals</title>
  
  <style>
      /* admin-style.css */
body {
  margin: 0;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  background-color: #111;
  color: #f1f1f1;
  padding: 20px;
}

h2 {
  text-align: center;
  margin-bottom: 20px;
  color: #4caf50;
}

table {
  width: 100%;
  border-collapse: collapse;
  background-color: #1e1e1e;
  border-radius: 8px;
  overflow: hidden;
}

th, td {
  padding: 14px;
  text-align: left;
  font-size: 15px;
  border-bottom: 1px solid #333;
}

th {
  background-color: #292929;
  color: #ddd;
  font-weight: 600;
}

td {
  color: #eee;
}

a {
  color: #4caf50;
  text-decoration: none;
  font-weight: bold;
  margin-right: 8px;
}

a:hover {
  text-decoration: underline;
}

a.deny {
  color: #f44336;
}

tr:hover {
  background-color: #2a2a2a;
}

@media (max-width: 768px) {
  table, thead, tbody, th, td, tr {
    display: block;
  }

  thead tr {
    display: none;
  }

  tr {
    margin-bottom: 15px;
    background: #1e1e1e;
    border-radius: 8px;
    padding: 12px;
  }

  td {
    padding: 10px 10px;
    text-align: right;
    position: relative;
    font-size: 14px;
  }

  td::before {
    content: attr(data-label);
    position: absolute;
    left: 10px;
    top: 10px;
    font-weight: bold;
    color: #aaa;
    text-transform: capitalize;
    text-align: left;
  }

  a {
    display: inline-block;
    margin-top: 5px;
  }
}
      
  </style>
</head>
<body style="background:#111; color:white; padding:20px;">
  <h2>Pending Withdrawals</h2>
  <table border="1" style="width:100%; background:#222;">
    <tr><th>User</th><th>Amount</th><th>Method</th><th>Destination</th><th>Status</th><th>Action</th></tr>
    <?php while($w = $withdrawals->fetch_assoc()): ?>
    
      <tr>
<td data-label="User"><?= htmlspecialchars($w['user_name']) ?></td>       
  <td data-label="Amount">$<?= $w['amount'] ?></td>
  <td data-label="Method"><?= $w['method'] ?></td>
  <td data-label="Destination"><?= $w['destination'] ?></td>
  <td data-label="Status"><?= $w['status'] ?></td>
  <td data-label="Action">
    <?php if($w['status'] == 'Pending'): ?>
      <a href="approve.php?id=<?= $w['id'] ?>&status=Approved">✅ Approve</a>
      <a href="approve.php?id=<?= $w['id'] ?>&status=Declined" class="deny">❌ Decline</a>
    <?php else: ?>—
    <?php endif; ?>
  </td>
</tr>

    <?php endwhile; ?>
  </table>
</body>
</html>
