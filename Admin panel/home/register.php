<?php

include '../includes/dbconnection.php';
$error = 0;

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    $confirmPassword = $_POST['cpassword'];
    $tel_num = mysqli_real_escape_string($connection, $_POST['telephone']);
    $role= $_POST['role'];
    $stored_password=$password;


    $select = mysqli_query($connection, "SELECT * FROM users WHERE email='$email'") or die('query failed');
    if (mysqli_num_rows($select) > 0) {
        $row = mysqli_fetch_assoc($select);
        $stored_password = $row['password'];

        if (password_verify($password, $stored_password)) {
            $already_exists_message = 'user already exists!';
            $error = 1;
        }
    } else {
        if (empty($name)) {
            $username_error = 'Username is required';
            $error = 1;
        }

        if (empty($email)) {
            $email_error = 'Email is required';
            $error = 1;
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $invalid_email_error = 'Invalid Email address!';
            $error = 1;
        }

        if (empty($password)) {
            $password_error = 'Password is required';
            $error = 1;
        } if (empty($confirmPassword)) {
            $cpassword_error = 'Confirm password is required';
            $error = 1;
        } 
        
        elseif (strlen(trim($password)) < 8) {
            $password_length_error = 'Password should have atleast 8 characters';
            $error = 1;
        } elseif ($confirmPassword != $password){
            $password_not_match_error = 'Confirm password does not match!';
            $error = 1;
        }
    
        if (empty($tel_num)) {
            $tel_num_error = 'Telephone number is required';
            $error = 1;
        } elseif (!preg_match("/^\d{3}-\d{3}-\d{4}$/", $tel_num)) {
            $invalid_tel_num_error = 'Invalid telephone number';
            $error = 1;
        }
    }

    if ($error == 0) {
        mysqli_query($connection, "insert into users (name,email,password,phone,role) VALUES ('$name','$email','$password','$tel_num','$role')") or die('query failed' . mysqli_error($connection));
        $successful_message = 'Registered successfully!';
    } else {
        $failed_message = 'Registration failed!!!!';
    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>

<body>
    <section class="form_container">

        <form action="" class="form" method="post">
            <h2>Register Now</h2>

            <div class="input">
                <span><?php if (isset($already_exists_message)) echo  $already_exists_message;  ?></span>
                <span><?php if (isset($successful_message)) echo  $successful_message;  ?></span>
                <span><?php if (isset($failed_message)) echo  $failed_message;  ?></span>
            </div>


            <div class="input">
                <input type="text" name="name" id="name" placeholder="Enter your name">
                <span class="error"><?php if (isset($username_error)) echo $username_error;  ?></span>
            </div>


            <div class="input">
                <input type="email" name="email" id="email" placeholder="Enter your Email">
                <span class="error"><?php if (isset($email_error)) echo $email_error;  ?></span>
                <span class="error"><?php if (isset($invalid_email_error)) echo $invalid_email_error;  ?></span>
            </div>

            <div class="input">
                <input type="text" name="telephone" id="telephone" placeholder="Enter your Tel-Number:XXX-XXX-XXXX">
                <span><?php if (isset($tel_num_error)) echo $tel_num_error;  ?></span>
                <span><?php if (isset($invalid_tel_num_error)) echo $invalid_tel_num_error;  ?></span>

            </div>

            <div class="input">
                <input type="password" name="password" id="password" placeholder="Enter the password">
                <span class="error"><?php if (isset($password_error)) echo $password_error;  ?></span>
                <span class="error"><?php if (isset($password_length_error)) echo $password_length_error;  ?></span>


            </div>

            <div class="input">
                <input type="password" name="cpassword" id="cpassword" placeholder="Confirm your password">
                <span class="error"><?php if (isset($cpassword_error)) echo $cpassword_error;  ?></span>
                <span class="error"><?php if (isset($password_not_match_error)) echo $password_not_match_error; ?></span>

            </div>

            <div class="input">
                <select class="form" name="role">
                    <option>User</option>
                    <option>Admin</option>
                </select>
            </div>

            <input type="submit" name="submit" value="Register" class="btn">


            <p class="login">Already have an account? <a href="login.php">Login here</a></p>

        </form>

    </section>

</body>

</html>