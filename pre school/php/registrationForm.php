<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../css/registration.css">
</head>

<body class="background">

    <?php
    $errors = [];
    $success = '';
    function test_input($data)
    {
        return trim($data);
    }

    $fullname = $fathername = $mothername = $age = $phone = $username = $password = $confirmpassword = $email = $class = $address = $gender = '';
    $fullnameErr = $fathernameErr = $mothernameErr = $ageErr = $phoneErr = $usernameErr = $passwordErr = $confirmpasswordErr = $emailErr = $classErr = $addressErr = $genderErr = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fullname = $_POST['fullname'] ?? '';
        $fathername = $_POST['fathername'] ?? '';
        $mothername = $_POST['mothername'] ?? '';
        $age = $_POST['age'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmpassword = $_POST['confirmpassword'] ?? '';
        $email = $_POST['email'] ?? '';
        $class = $_POST['class'] ?? '';
        $address = $_POST['address'] ?? '';
        $gender = $_POST['gender'] ?? '';

        // Validation
        if ($fullname === '') {
            $fullnameErr = '* Full Name is required.';
            $errors[] = $fullnameErr;
        }
        if ($fathername === '') {
            $fathernameErr = '* Father Name is required.';
            $errors[] = $fathernameErr;
        }
        if ($mothername === '') {
            $mothernameErr = '* Mother Name is required.';
            $errors[] = $mothernameErr;
        }
        if ($age === '') {
            $ageErr = '* Age is required.';
            $errors[] = $ageErr;
        }
        if ($phone === '') {
            $phoneErr = '* Phone Number is required.';
            $errors[] = $phoneErr;
        }
        if ($username === '') {
            $usernameErr = '* Username is required.';
            $errors[] = $usernameErr;
        }
        if ($password === '') {
            $passwordErr = '* Password is required.';
            $errors[] = $passwordErr;
        }
        if ($confirmpassword === '') {
            $confirmpasswordErr = '* Confirm Password is required.';
            $errors[] = $confirmpasswordErr;
        }
        if ($email === '') {
            $emailErr = '* Email is required.';
            $errors[] = $emailErr;
        }
        if ($class === '') {
            $classErr = '* Class is required.';
            $errors[] = $classErr;
        }
        if ($address === '') {
            $addressErr = '* Address is required.';
            $errors[] = $addressErr;
        }
        if ($gender === '') {
            $genderErr = '* Gender is required.';
            $errors[] = $genderErr;
        }

        if ($age !== '' && (!ctype_digit($age) || $age < 1 || $age > 10)) {
            $ageErr = '* Valid Age is required.';
            $errors[] = $ageErr;
        }

        if ($phone !== '' && !preg_match('/^[0-9]{10,15}$/', $phone)) {
            $phoneErr = '* Valid Phone Number is required.';
            $errors[] = $phoneErr;
        }

        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = '* Valid Email is required.';
            $errors[] = $emailErr;
        }

        if ($password !== '' && $confirmpassword !== '' && $password !== $confirmpassword) {
            $confirmpasswordErr = '* Passwords do not match.';
            $errors[] = $confirmpasswordErr;
        }

        if (empty($errors)) {

            include '../php/db_connect.php';

            $account_type = 'Student';

            $hash_pass = password_hash($password, PASSWORD_DEFAULT);

            // Escape values for insertion
            $fullnameEsc = $conn->real_escape_string($fullname);
            $fathernameEsc = $conn->real_escape_string($fathername);
            $mothernameEsc = $conn->real_escape_string($mothername);
            $ageEsc = $conn->real_escape_string($age);
            $phoneEsc = $conn->real_escape_string($phone);
            $usernameEsc = $conn->real_escape_string($username);
            $passwordEsc = $conn->real_escape_string($hash_pass);
            $emailEsc = $conn->real_escape_string($email);
            $classEsc = $conn->real_escape_string($class);
            $addressEsc = $conn->real_escape_string($address);
            $genderEsc = $conn->real_escape_string($gender);
            $accountTypeEsc = $conn->real_escape_string($account_type);

            $sql = "INSERT INTO users (fullname, fathername, mothername, age, phone, username, password, email, class, address, gender, account_type) VALUES ('{$fullnameEsc}','{$fathernameEsc}','{$mothernameEsc}','{$ageEsc}','{$phoneEsc}','{$usernameEsc}','{$passwordEsc}','{$emailEsc}','{$classEsc}','{$addressEsc}','{$genderEsc}','{$accountTypeEsc}')";

            if ($conn->query($sql) === TRUE) {
                $success = 'Registration successful!';
            } else {
                $errors[] = 'Error: ' . $conn->error;
            }

            if (isset($conn) && $conn instanceof mysqli) {
                $conn->close();
            }
        }
    }

    ?>

    <div class="form">
        <h2 class="reg-title">Registration form</h2>
        <form action="" method="post">
            <div class="from-divide">
                <div>
                    <label for="name">Full Name : </label><br>
                    <input class="input" type="text" name="fullname" value="<?php echo $fullname; ?>"
                        placeholder="Enter full name"><br>
                    <span class="field-error"><?php echo $fullnameErr; ?></span>

                    <label for="name">Father Name : </label><br>
                    <input class="input" type="text" name="fathername" value="<?php echo $fathername; ?>"
                        placeholder="Enter Father name"><br>
                    <span class="field-error"><?php echo $fathernameErr; ?></span>

                    <label for="name">Mother Name : </label><br>
                    <input class="input" type="text" name="mothername" value="<?php echo $mothername; ?>"
                        placeholder="Enter Mother name"><br>
                    <span class="field-error"><?php echo $mothernameErr; ?></span>

                    <label for="age">Student Age : </label><br>
                    <input class="input" type="text" name="age" value="<?php echo $age; ?>"
                        placeholder="Enter Student Age"><br>
                    <span class="field-error"><?php echo $ageErr; ?></span>

                    <label for="text">Phone Number : </label><br>
                    <input class="input" type="text" name="phone" value="<?php echo $phone; ?>"
                        placeholder="Enter phone number"><br>
                    <span class="field-error"><?php echo $phoneErr; ?></span>

                    <label for="name">Username : </label><br>
                    <input class="input" type="text" name="username" value="<?php echo $username; ?>"
                        placeholder="Enter Username"><br>
                    <span class="field-error"><?php echo $usernameErr; ?></span>
                </div>
                <div>
                    <label for="password">Password :</label><br>
                    <input class="input" type="password" name="password" placeholder="Enter Password"><br>
                    <span class="field-error"><?php echo $passwordErr; ?></span>

                    <label for="confirm password">Confirm Password :</label><br>
                    <input class="input" type="password" name="confirmpassword" placeholder="confirm Password"><br>
                    <span class="field-error"><?php echo $confirmpasswordErr; ?></span>

                    <label for="email">Email : </label><br>
                    <input class="input" type="text" name="email" value="<?php echo $email; ?>"
                        placeholder="Enter email"><br>
                    <span class="field-error"><?php echo $emailErr; ?></span>

                    <label for="class :"> Class</label><br>
                    <select class="select" name="class">
                        <option value="">select</option>
                        <option value="1" <?php echo $class === '1' ? 'selected' : ''; ?>>1</option>
                        <option value="2" <?php echo $class === '2' ? 'selected' : ''; ?>>2</option>
                        <option value="3" <?php echo $class === '3' ? 'selected' : ''; ?>>3</option>
                    </select><br>
                    <span class="field-error"><?php echo $classErr; ?></span>

                    <label for="address">Address : </label><br>
                    <input class="input" type="text" name="address" value="<?php echo $address; ?>"
                        placeholder="Enter Address"> <br>
                    <span class="field-error"><?php echo $addressErr; ?></span>

                    <label for="text">Gender : </label> <br>
                    <input class="radio" type="radio" name="gender" value="Female" <?php echo $gender === 'Female' ? 'checked' : ''; ?>> <span>Female</span>
                    <input class="radio" type="radio" name="gender" value="Male" <?php echo $gender === 'Male' ? 'checked' : ''; ?>> <span>Male</span> <br>
                    <span class="field-error"><?php echo $genderErr; ?></span>
                </div>
            </div>
            <button class="button" type="submit">Confirm Registration</button>
            <button class="button1"><a href="../html/home_page.html">Back to Home</a></button>
            <?php if ($success): ?>
                <div style="color:green; text-align:center; margin-top:10px"><?php echo $success; ?></div>
                <div style="margin-top:10px">
                    <strong>Submitted data</strong><br>
                    Full Name: <?php echo $fullname; ?><br>
                    Father Name: <?php echo $fathername; ?><br>
                    Mother Name: <?php echo $mothername; ?><br>
                    Age: <?php echo $age; ?><br>
                    Phone: <?php echo $phone; ?><br>
                    Username: <?php echo $username; ?><br>
                    Email: <?php echo $email; ?><br>
                    Class: <?php echo $class; ?><br>
                    Address: <?php echo $address; ?><br>
                    Gender: <?php echo $gender; ?><br>
                </div>
            <?php endif; ?>
        </form>
    </div>
</body>

</html>