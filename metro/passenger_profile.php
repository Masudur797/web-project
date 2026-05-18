<?php
session_start();
include "../config/db.php";

// Login check
if(!isset($_SESSION['user_id']) || $_SESSION['role']!='passenger'){
    header("Location: passenger_login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$msg = "";

// Handle form submission (Update Profile)
if(isset($_POST['update'])){
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = trim($_POST['password']);

    if($name=="" || $email==""){
        $msg = "❌ Name & Email cannot be empty";
    } else{
        // Check email uniqueness
        $res_check = mysqli_query($conn,"SELECT * FROM users WHERE email='$email' AND id!='$user_id'");
        if(mysqli_num_rows($res_check) > 0){
            $msg = "❌ Email already in use";
        } else{
            if($password!=""){
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                mysqli_query($conn,"UPDATE users SET name='$name', email='$email', password='$password_hash' WHERE id='$user_id'");
            } else {
                mysqli_query($conn,"UPDATE users SET name='$name', email='$email' WHERE id='$user_id'");
            }
            $msg = "✅ Profile updated successfully!";
            $_SESSION['user_name'] = $name;
        }
    }
}

// Fetch user data
$res = mysqli_query($conn,"SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($res);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Passenger Profile</title>
<link rel="stylesheet" href="css/profile.css">
</head>
<body>
<h2>My Profile</h2>

<?php if($msg!=""){ echo "<p class='msg'>$msg</p>"; } ?>

<form method="POST">
    <label>Name:</label>
    <input type="text" name="name" value="<?php echo $user['name']; ?>" required>

    <label>Email:</label>
    <input type="email" name="email" value="<?php echo $user['email']; ?>" required>

    <label>Password (leave blank to keep current):</label>
    <input type="password" name="password" placeholder="New Password">

    <button type="submit" name="update">Update Profile</button>
</form>

<p><a href="dashboard.php">Back to Dashboard</a></p>
</body>
</html>
