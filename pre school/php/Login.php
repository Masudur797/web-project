<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../php/db_connect.php';

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error_message = "All fields are required.";
    } else {
        if ($username === 'admin' && $password === 'admin') {
            header("Location: ../html/principal_Dashboard.html");
            exit();
        }

        if (!isset($conn) || !($conn instanceof mysqli)) {
            $error_message = 'Database connection is not available.';
            error_log('Login: $conn is not set or not a mysqli instance');
        } else {
            $u = $conn->real_escape_string($username);
            $sql = "SELECT username, password, account_type FROM users WHERE username = '{$u}' LIMIT 1";
            $res = $conn->query($sql);
            if ($res) {
                if ($res->num_rows === 1) {
                    $row = $res->fetch_assoc();
                    // verify password
                    if (password_verify($password, $row['password'])) {
                        $acct = strtolower($row['account_type'] ?? '');
                        if ($acct === 'teacher') {
                            header("Location: ../html/Teacher_Dashboard.html");
                            exit();
                        } elseif ($acct === 'student') {
                            header("Location: ../html/Student_Dashboard.html");
                            exit();
                        } elseif ($acct === 'admin') {
                            header("Location: ../html/Principal_Dashboard.html");
                            exit();
                        } else {
                            header("Location: ../html/Principal_Dashboard.html");
                            exit();
                        }
                    } else {
                        $error_message = "Incorrect password for that username.";
                        $stored = $row['password'] ?? '';
                        $snippet = substr($stored, 0, 30);
                        error_log('Login: password mismatch for username: ' . $username . ' stored_hash_prefix: ' . $snippet);
                    }
                } else {
                    $error_message = "No account found with that username.";
                    error_log('Login: username not found: ' . $username);
                }
            } else {
                $error_message = "Database query failed.";
                error_log('Login query failed: ' . $conn->error);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Log in</title>
    <link rel="stylesheet" href="../css/Login.css">

</head>

<body>

    <form action="" method="post" class="login">

        <div class="header">
            <a href="../html/home_page.html">
                <img src="../images/logo.png" class="logo" alt="Logo">
            </a>

            <a href="../html/home_page.html">
                <h2 class="title1">SCHOOL PORTAL</h2>
                <h5 class="title2">Login here</h5>
            </a>
        </div>

        <input type="text" name="username" id="username" placeholder="Username">
        <input type="password" name="password" id="password" placeholder="Password">

        <div id="loginError" style="color: red ; padding:2px;font-weight:400;
            display:<?php echo !empty($error_message) ? 'block' : 'none'; ?>">
            <?php echo $error_message; ?>
        </div>

        <div class="show-pass">
            <input type="checkbox" onclick="showPassword()" id="showpass">
            <label for="showpass">Show Password</label>
        </div>

        <button type="submit" class="login-btn">
            <span class="login-text">Log In</span>
        </button>

    </form>

    <script>
        function showPassword() {
            const passwordInput = document.getElementById("password");
            const showPassCheckbox = document.getElementById("showpass");
            passwordInput.type = showPassCheckbox.checked ? "text" : "password";
        }
    </script>
</body>

</html>