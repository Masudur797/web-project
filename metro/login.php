<?php
session_start();
include "config.php";

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $msg = "❌ Email and password required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "❌ Invalid email";
    } else {
        $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res  = $stmt->get_result();
        $user = $res->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role']      = $user['role'];

            if (ob_get_level()) ob_clean();
            
            // YOUR REQUESTED LOGIC - FIXED
            if ($user['role'] === 'admin') {
                header("Location: ../metro/admin/dashboard.php");
            } elseif ($user['role'] === 'passenger') {
                header("Location: passenger_dashboard.php");
            }
            exit();
        } else {
            $msg = "❌ Email or password incorrect";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <title>Sign In</title>
 <link rel="stylesheet" href="css/login.css">
</head>
<body>
 <form method="post" action="">
   <h2>Sign In</h2>
   <?php if ($msg !== ""): ?>
     <p class="msg"><?php echo htmlspecialchars($msg); ?></p>
   <?php endif; ?>
   <input type="email" name="email" placeholder="Email" required><br>
   <input type="password" name="password" placeholder="Password" required><br>
   <button type="submit" name="login">Login</button>
   
   <p>New passenger? <a href="passenger_register.php">Create account</a></p>
 </form>
</body>
</html>
