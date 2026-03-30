<?php
require 'db.php';
require 'helpers.php';
$email = $err = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if($email==='' || $password==='') $err = "Email and password required.";
    else{
        $stmt = $conn->prepare("SELECT id,password,name FROM users WHERE email=?");
        $stmt->bind_param('s',$email); $stmt->execute();
        $res = $stmt->get_result();
        if($user = $res->fetch_assoc()){
            if(password_verify($password, $user['password'])){
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                header('Location: dashboard.php'); exit;
            } else $err = "Invalid credentials.";
        } else $err = "Invalid credentials.";
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8"><title>Login</title>
  <link rel="stylesheet" href="assets/style.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container">
  <div class="nav"><a href="register.php">Register</a></div>
  <h2>Login</h2>
  <?php if(isset($_GET['registered'])) echo '<div style="color:green">Registration successful. Please login.</div>'; ?>
  <?php if($err) echo '<div class="error">'.e($err).'</div>'; ?>

  <form method="post">
    <label>Email</label>
    <input type="email" name="email" value="<?= e($email) ?>">
    <label>Password</label>
    <input type="password" name="password">
    <button type="submit">Login</button>
  </form>
  <p><a href="reset_request.php">Forgot password?</a></p>
</div>
</body>
</html>

