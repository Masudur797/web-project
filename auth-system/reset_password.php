<?php
require 'db.php';
require 'helpers.php';
$token = $_GET['token'] ?? $_POST['token'] ?? '';
$err=$msg='';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $token = $_POST['token'] ?? '';
    $new = $_POST['new_password'] ?? ''; $confirm = $_POST['confirm_password'] ?? '';
    if($new !== $confirm) $err = "Passwords do not match.";
    else {
        $stmt = $conn->prepare("SELECT id,reset_expires FROM users WHERE reset_token=?");
        $stmt->bind_param('s',$token); $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if(!$row) $err = "Invalid token.";
        elseif(strtotime($row['reset_expires']) < time()) $err = "Token expired.";
        else {
            $hash = password_hash($new, PASSWORD_DEFAULT);
            $upd = $conn->prepare("UPDATE users SET password=?, reset_token=NULL, reset_expires=NULL WHERE id=?");
            $upd->bind_param('si',$hash,$row['id']); if($upd->execute()) $msg = "Password updated. You can login now.";
            else $err = "Failed to update password.";
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Reset Password</title>
<link rel="stylesheet" href="assets/style.css"></head>
<body>
<div class="container">
  <div class="nav"><a href="login.php">Back</a></div>
  <h2>Reset Password</h2>
  <?php if($err) echo '<div class="error">'.e($err).'</div>'; ?>
  <?php if($msg) echo '<div style="color:green">'.e($msg).'</div>'; ?>
  <?php if(!$msg): ?>
  <form method="post">
    <input type="hidden" name="token" value="<?= e($token) ?>">
    <label>New password</label>
    <input type="password" name="new_password">
    <label>Confirm password</label>
    <input type="password" name="confirm_password">
    <button type="submit">Reset Password</button>
  </form>
  <?php endif; ?>
</div>
</body>
</html>
