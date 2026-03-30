<?php
require 'db.php';
require 'helpers.php';
$err=$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $email = trim($_POST['email'] ?? '');
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $err = "Invalid email.";
    else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
        $stmt->bind_param('s',$email); $stmt->execute();
        $res = $stmt->get_result();
        if($res->num_rows===0) $err = "If that email exists you'll receive reset instructions.";
        else {
            $user = $res->fetch_assoc();
            $token = bin2hex(random_bytes(16));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            $upd = $conn->prepare("UPDATE users SET reset_token=?, reset_expires=? WHERE id=?");
            $upd->bind_param('ssi',$token,$expires,$user['id']); $upd->execute();
            // For local testing show the link
            $msg = "Reset link (testing): http://localhost/wabt/auth-system/reset_password.php?token=".$token;
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Password Reset</title>
<link rel="stylesheet" href="assets/style.css"></head>
<body>
<div class="container">
  <div class="nav"><a href="login.php">Back</a></div>
  <h2>Password Reset</h2>
  <?php if($err) echo '<div class="error">'.e($err).'</div>'; ?>
  <?php if($msg) echo '<div style="color:green">'.e($msg).'</div>'; ?>
  <form method="post">
    <label>Enter your email</label>
    <input name="email" type="email">
    <button type="submit">Send reset link</button>
  </form>
</div>
</body>
</html>

