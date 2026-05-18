<?php
session_start();
include "../config.php";

$msg = "";

if(isset($_POST['login'])){
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if($email=="" || $password==""){
        $msg = "❌ Email & Password required";
    } else {
        $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email=? AND role='admin'");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc();
        $stmt->close();

        if($user && password_verify($password, $user['password'])){
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_name'] = $user['name'];
            header("Location:/admin/dashboard.php");
            exit;
        } else {
            $msg = "❌ Invalid admin credentials";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Admin Login</title></head>
<link rel="stylesheet" href="css/admin_login.css">
<body>
<h2>Admin Login</h2>
<?php if($msg!=""){ echo "<p>$msg</p>"; } ?>
<form method="post">
<input type="email" name="email" placeholder="Email" required><br>
<input type="password" name="password" placeholder="Password" required><br>
<button type="submit" name="login">Login</button>
</form>
</body>
</html>