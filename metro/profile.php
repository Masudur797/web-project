<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'passenger') {
    header("Location: passenger_login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$msg = "";

// Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($name === "" || $email === "") {
        $msg = "❌ Name & Email cannot be empty";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "❌ Invalid email address";
    } else {
        // email unique check
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $user_id);
        $stmt->execute();
        $res_check = $stmt->get_result();

        if ($res_check->num_rows > 0) {
            $msg = "❌ Email already in use";
        } else {
            if ($password !== "") {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt2 = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
                $stmt2->bind_param("sssi", $name, $email, $password_hash, $user_id);
            } else {
                $stmt2 = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
                $stmt2->bind_param("ssi", $name, $email, $user_id);
            }

            if ($stmt2->execute()) {
                $msg = "✅ Profile updated successfully!";
                $_SESSION['user_name'] = $name;
            } else {
                $msg = "❌ Something went wrong";
            }
            $stmt2->close();
        }
        $stmt->close();
    }
}

// Fetch current data
$stmt3 = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt3->bind_param("i", $user_id);
$stmt3->execute();
$res_user = $stmt3->get_result();
$user = $res_user->fetch_assoc();
$stmt3->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile</title>
  <link rel="stylesheet" href="css/profile.css">
</head>
<body>
  <h2>My Profile</h2>

  <?php if ($msg !== ""): ?>
    <p class="msg"><?php echo htmlspecialchars($msg); ?></p>
  <?php endif; ?>

  <form method="POST" action="">
    <label>Name:</label><br>
    <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>

    <label>Password (leave blank to keep current):</label><br>
    <input type="password" name="password" placeholder="New Password"><br><br>

    <button type="submit" name="update">Update Profile</button>
  </form>

  <p><a href="passenger_dashboard.php">Back to Dashboard</a></p>
</body>
</html>
