<?php
include '../php/db_connect.php';

$errors = [];
$success = '';

// initialize 
$fullname = $age = $phone = $username = $password = $confirmpassword = $email = $qualification = $salary = $address = $gender = '';
$fullnameErr = $ageErr = $phoneErr = $usernameErr = $passwordErr = $confirmpasswordErr = $emailErr = $qualificationErr = $salaryErr = $addressErr = $genderErr = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'] ?? '';
    $age = $_POST['age'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmpassword = $_POST['confirmpassword'] ?? '';
    $email = $_POST['email'] ?? '';
    $qualification = $_POST['qualification'] ?? '';
    $salary = $_POST['salary'] ?? '';
    $address = $_POST['address'] ?? '';
    $gender = $_POST['gender'] ?? '';

    // validation
    if ($fullname === '') {
        $fullnameErr = '* Full Name is required.';
        $errors[] = $fullnameErr;
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
    if ($qualification === '') {
        $qualificationErr = '* Qualification is required.';
        $errors[] = $qualificationErr;
    }
    if ($salary === '') {
        $salaryErr = '* Salary is required.';
        $errors[] = $salaryErr;
    }
    if ($address === '') {
        $addressErr = '* Address is required.';
        $errors[] = $addressErr;
    }
    if ($gender === '') {
        $genderErr = '* Gender is required.';
        $errors[] = $genderErr;
    }

    if ($age !== '' && (!ctype_digit($age) || intval($age) < 18)) {
        $ageErr = '* Valid Age (>=18) is required.';
        $errors[] = $ageErr;
    }
    if ($phone !== '' && !preg_match('/^[0-9]{7,15}$/', $phone)) {
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
    if ($salary !== '' && !is_numeric($salary)) {
        $salaryErr = '* Salary must be a number.';
        $errors[] = $salaryErr;
    }

    if (empty($errors)) {
        $account_type = 'Teacher';
        $hash_pass = password_hash($password, PASSWORD_DEFAULT);

        $fullname_e = $conn->real_escape_string($fullname);
        $age_e = (int) $age;
        $phone_e = $conn->real_escape_string($phone);
        $username_e = $conn->real_escape_string($username);
        $password_e = $conn->real_escape_string($hash_pass);
        $email_e = $conn->real_escape_string($email);
        $class_e = 'NULL';
        $qualification_e = $conn->real_escape_string($qualification);
        $salary_e = is_numeric($salary) ? $conn->real_escape_string($salary) : '0';
        $address_e = $conn->real_escape_string($address);
        $gender_e = $conn->real_escape_string($gender);
        $account_type_e = $conn->real_escape_string($account_type);

        $sql = "INSERT INTO users (fullname, age, phone, username, password, email, class, qualification, salary, address, gender, account_type) VALUES ('{$fullname_e}', {$age_e}, '{$phone_e}', '{$username_e}', '{$password_e}', '{$email_e}', NULL, '{$qualification_e}', {$salary_e}, '{$address_e}', '{$gender_e}', '{$account_type_e}')";

        if ($conn->query($sql) === TRUE) {
            $success = 'Registration successful!';
        } else {
            $errors[] = 'Database error: ' . $conn->error;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Registration</title>
    <link rel="stylesheet" href="../css/registration.css">
</head>

<body class="background">

    <div class="form">
        <h2 class="reg-title">Teacher Registration</h2>
        <form action="" method="post">
            <div class="from-divide">
                <div>
                    <label for="name">Full Name : </label><br>
                    <input class="input" type="text" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>"
                        placeholder="Enter full name"><br>
                    <span class="field-error"><?php echo $fullnameErr; ?></span>

                    <label for="age">Age : </label><br>
                    <input class="input" type="text" name="age" value="<?php echo htmlspecialchars($age); ?>"
                        placeholder="Enter Age"><br>
                    <span class="field-error"><?php echo $ageErr; ?></span>

                    <label for="text">Phone Number : </label><br>
                    <input class="input" type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>"
                        placeholder="Enter phone number"><br>
                    <span class="field-error"><?php echo $phoneErr; ?></span>

                    <label for="name">Username : </label><br>
                    <input class="input" type="text" name="username" value="<?php echo htmlspecialchars($username); ?>"
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
                    <input class="input" type="text" name="email" value="<?php echo htmlspecialchars($email); ?>"
                        placeholder="Enter email"><br>
                    <span class="field-error"><?php echo $emailErr; ?></span>

                    <label for="qualification">Qualification : </label><br>
                    <input class="input" type="text" name="qualification"
                        value="<?php echo htmlspecialchars($qualification); ?>" placeholder="Enter Qualification"><br>
                    <span class="field-error"><?php echo $qualificationErr; ?></span>

                    <label for="salary">Salary : </label><br>
                    <input class="input" type="text" name="salary" value="<?php echo htmlspecialchars($salary); ?>"
                        placeholder="Enter Salary"><br>
                    <span class="field-error"><?php echo $salaryErr; ?></span>

                    <label for="address">Address : </label><br>
                    <input class="input" type="text" name="address" value="<?php echo htmlspecialchars($address); ?>"
                        placeholder="Enter Address"> <br>
                    <span class="field-error"><?php echo $addressErr; ?></span>

                    <label for="text">Gender : </label> <br>
                    <input class="radio" type="radio" name="gender" value="Female" <?php echo $gender === 'Female' ? 'checked' : ''; ?>> <span>Female</span>
                    <input class="radio" type="radio" name="gender" value="Male" <?php echo $gender === 'Male' ? 'checked' : ''; ?>> <span>Male</span> <br>
                    <span class="field-error"><?php echo $genderErr; ?></span>
                </div>
            </div>
            <button class="button" type="submit">Add Teacher</button>
            <button class="button1"><a href="../View/Principal_Dashboard.html">Back to Home</a></button>
            <?php if ($success): ?>
                <div style="color:green; text-align:center; margin-top:10px"><?php echo $success; ?></div>
            <?php endif; ?>
        </form>
    </div>
</body>

</html>