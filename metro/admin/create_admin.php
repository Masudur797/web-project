<?php
require_once '../config.php';

$success = '';
$error = '';


session_start();
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($name) || empty($password)) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } else {
        // Check if email already exists
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        if ($check_stmt->get_result()->num_rows > 0) {
            $error = 'Email already exists.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')");
            $stmt->bind_param("sss", $name, $email, $hashed_password);
            
            if ($stmt->execute()) {
                $success = "Admin created! Email: $email, Password: $password";
            } else {
                $error = 'Error: ' . $stmt->error;
            }
            $stmt->close();
        }
        $check_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 50px auto; padding: 20px; }
        input { width: 100%; padding: 12px; margin: 10px 0; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #28a745; color: white; border: none; cursor: pointer; }
        .error { color: red; background: #ffe6e6; padding: 10px; border-radius: 4px; }
        .success { color: green; background: #e6ffe6; padding: 10px; border-radius: 4px; }
    </style>
</head>
<body>
    <h2>Create Admin Account</h2>
    
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
        <p><a href="login.php" style="display: inline-block; margin-top: 10px; padding: 10px 20px; background: #007cba; color: white; text-decoration: none; border-radius: 4px;">Go to Login</a></p>
    <?php endif; ?>

    <?php if (!$success): ?>
    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name" placeholder="Admin User" required>
        
        <label>Email:</label>
        <input type="email" name="email" placeholder="admin@example.com" required>
        
        <label>Password:</label>
        <input type="password" name="password" placeholder="Enter strong password" required>
        
        <button type="submit">Create Admin</button>
    </form>
    <?php endif; ?>
    
    <p style="margin-top: 20px;"><a href="login.php">Back to Login</a></p>
</body>
</html>

<?php $conn->close(); ?>
