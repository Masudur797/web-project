<?php
session_start();
include "config.php";

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $name             = trim($_POST['name'] ?? '');
    $email            = trim($_POST['email'] ?? '');
    $password         = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    if ($name === '' || $email === '' || $password === '' || $confirm_password === '') {
        $msg = "❌ All fields are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "❌ Invalid email";
    } elseif ($password !== $confirm_password) {
        $msg = "❌ Passwords do not match";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $check = $stmt->get_result();

        if ($check->num_rows > 0) {
            $msg = "❌ Email already registered";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt2 = $conn->prepare(
                "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'passenger')"
            );
            $stmt2->bind_param("sss", $name, $email, $hashed_password);

            if ($stmt2->execute()) {
                $msg = "✅ Registration successful. You can <a href='passenger_login.php'>login now</a>";
            } else {
                $msg = "❌ Registration failed. Try again";
            }
            $stmt2->close();
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Passenger Register</title>
  <link rel="stylesheet" href="css/passenger_register.css">
</head>
<body>
  <form method="post" action="">
    <h2>Passenger Sign Up</h2>

    <?php if ($msg !== ""): ?>
      <p class="msg"><?php echo $msg; ?></p>
    <?php endif; ?>

    <input type="text" name="name" placeholder="Name" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
    <button type="submit" name="register">Sign Up</button>
    <p>Already have an account? <a href="login.php">Sign In</a></p>
  </form>
</body>
</html>