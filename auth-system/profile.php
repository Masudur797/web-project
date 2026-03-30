<?php
require 'db.php';
require 'helpers.php';
require_login();
$id = $_SESSION['user_id'];

if(isset($_POST['delete'])){
    $del = $conn->prepare("DELETE FROM users WHERE id=?");
    $del->bind_param('i',$id); $del->execute();
    session_unset(); session_destroy();
    header('Location: register.php'); exit;
}

$stmt = $conn->prepare("SELECT name,email,bio,created_at FROM users WHERE id=?");
$stmt->bind_param('i',$id); $stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Profile</title>
<link rel="stylesheet" href="assets/style.css"></head>
<body>
<div class="container">
  <div class="nav"><a href="dashboard.php">Back</a> | <a href="profile_edit.php">Edit</a></div>
  <h2>Profile</h2>
  <p><strong>Name:</strong> <?= e($user['name']) ?></p>
  <p><strong>Email:</strong> <?= e($user['email']) ?></p>
  <p><strong>Bio:</strong> <?= nl2br(e($user['bio'])) ?></p>
  <p><strong>Joined:</strong> <?= e($user['created_at']) ?></p>

  <form method="post" onsubmit="return confirm('Delete account? This cannot be undone.')">
    <button name="delete" type="submit" class="secondary">Delete Account</button>
  </form>
</div>
</body>
</html>

