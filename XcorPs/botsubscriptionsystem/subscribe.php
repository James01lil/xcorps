<?php
include '../authenticate/db.php';
$sql = "SELECT * FROM bots";
$result = $conn->query($sql);
?>

<h2>Select a Bot to Subscribe</h2>
<ul>
<?php while ($row = $result->fetch_assoc()): ?>
    <li>
        <strong><?php echo $row['bot_name']; ?></strong> - <?php echo $row['description']; ?> - $<?php echo $row['price']; ?>
        <form method="POST" action="payment.php">
            <input type="hidden" name="bot_id" value="<?php echo $row['id']; ?>">
            <button type="submit">Subscribe</button>
        </form>
    </li>
<?php endwhile; ?>
</ul>