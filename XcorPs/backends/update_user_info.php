<?php

include '../authenticate/db.php';

$id = $_POST['id'];

$first_name = $_POST['first_name'];

$last_name = $_POST['last_name'];

$user_name = $_POST['user_name'];

$email = $_POST['email'];

$password = $_POST['password'];

$confirm_password = $_POST['confirm_password'];

$phone = $_POST['phone'];

$country = $_POST['country'];

$state = $_POST['state'];

$address = $_POST['address'];

$plan = $_POST['plan'];

$currency= $_POST['currency'];

$account = $_POST['account'];

if (!empty($password)) {

  $password = $_POST['password'];

  $conn->query("UPDATE users SET email='$email', first_name='$first_name', last_name='$last_name', user_name='$user_name', phone='$phone', country='$country', state='$state', address='$address', plan='$plan',currency='$currency', account='$account', password='$password' WHERE id=$id");

} else {

  $conn->query("UPDATE users SET email='$email', first_name='$first_name', last_name='$last_name', user_name='$user_name', phone='$phone', country='$country', state='$state', address='$address', plan='$plan',currency='$currency', account='$account', password='$password' WHERE id=$id");

}

header("Location: admin_dashboard.php");

?>