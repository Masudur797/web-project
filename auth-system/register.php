<?php
require 'db.php';
require 'helpers.php';

$name = $email = '';
$err = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if($name==='' || $email==='' || $password==='') $err = "All fields are required.";
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) $err = "Invalid email.";
    elseif($password !== $confirm) $err = "Passwords do not match.";
    else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
        $stmt->bind_param('s',$email); $stmt->execute(); $stmt->store_result();
        if($stmt->num_rows>0) $err = "Email already registered.";
        else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $conn->prepare("INSERT INTO users (name,email,password) VALUES (?,?,?)");
            $ins->bind_param('sss',$name,$email,$hash);
            if($ins->execute()){ header('Location: login.php?registered=1'); exit; }
            else $err = "Registration failed, try again.";
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Register</title>
  <link rel="stylesheet" href="assets/style.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="assets/script.js"></script>
</head>
<body>
<div class="container">
  <div class="nav"><a href="login.php">Login</a></div>
  <h2>Register</h2>

  <?php if($err) echo '<div class="error">'.e($err).'</div>'; ?>

  <form id="registerForm" method="post" onsubmit="return onRegisterSubmit(event);">
    <label>Name</label>
    <input type="text" name="name" id="name" value="<?= e($name) ?>">
    <label>Email</label>
    <input type="email" name="email" id="email" value="<?= e($email) ?>">
    <label>Password</label>
    <input type="password" name="password" id="password">
    <label>Confirm Password</label>
    <input type="password" name="confirm" id="confirm">
    <button type="submit">Register</button>
  </form>
  <p><small>Already have an account? <a href="login.php">Login</a></small></p>
</div>

<script>
function onRegisterSubmit(e){
  e.preventDefault();
  let name = document.getElementById('name'), email = document.getElementById('email');
  let pwd = document.getElementById('password'), conf = document.getElementById('confirm');
  // clear errors
  clearError(name); clearError(email); clearError(pwd); clearError(conf);

  let nerr = validateName(name.value);
  if(nerr){ showError(name,nerr); return false; }
  let eerr = validateEmail(email.value);
  if(eerr){ showError(email,eerr); return false; }
  if(pwd.value.length < 6){ showError(pwd,"Password at least 6 characters"); return false; }
  if(pwd.value !== conf.value){ showError(conf,"Passwords do not match"); return false; }

  // if client validation ok, submit
  e.target.submit();
}
</script>
</body>
</html>
