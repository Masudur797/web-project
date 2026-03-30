<?php
require 'db.php';
require 'helpers.php';
require_login();
$err = $msg = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $old = $_POST['old_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    if($new !== $confirm) $err = "New passwords do not match.";
    else {
        $stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
        $stmt->bind_param('i', $_SESSION['user_id']); $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if(!password_verify($old, $row['password'])) $err = "Old password incorrect.";
        else {
            $hash = password_hash($new, PASSWORD_DEFAULT);
            $upd = $conn->prepare("UPDATE users SET password=? WHERE id=?");
            $upd->bind_param('si',$hash,$_SESSION['user_id']);
            if($upd->execute()) $msg = "Password changed successfully.";
            else $err = "Failed to change password.";
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Change Password</title>
<link rel="stylesheet" href="assets/style.css"></head>
<body>
<div class="container">
  <div class="nav"><a href="dashboard.php">Back</a></div>
  <h2>Change Password</h2>
  <?php if($err) echo '<div class="error">'.e($err).'</div>'; ?>
  <?php if($msg) echo '<div style="color:green">'.e($msg).'</div>'; ?>
  <form method="post">
    <label>Old Password</label>
    <input type="password" name="old_password">
    <label>New Password</label>
    <input type="password" name="new_password">
    <label>Confirm New Password</label>
    <input type="password" name="confirm_password">
    <button type="submit">Change</button>
  </form>
</div>
</body>
</html>

