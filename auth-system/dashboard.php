<?php
require 'db.php';
require 'helpers.php';
require_login();

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name,email,bio,created_at FROM users WHERE id=?");
$stmt->bind_param('i',$user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Dashboard</title>
<link rel="stylesheet" href="assets/style.css"></head>
<body>
<div class="container">
  <div class="nav">
    Welcome <?= e($_SESSION['user_name']) ?> |
    <a href="profile.php">Profile</a> |
    <a href="change_password.php">Change Password</a> |
    <a href="logout.php">Logout</a>
  </div>

  <h2>Dashboard</h2>
  <p><strong>Name:</strong> <?= e($user['name']) ?></p>
  <p><strong>Email:</strong> <?= e($user['email']) ?></p>
  <p><strong>Member since:</strong> <?= e($user['created_at']) ?></p>
  <p><strong>Bio:</strong> <?= nl2br(e($user['bio'])) ?></p>
</div>
</body>
</html>

