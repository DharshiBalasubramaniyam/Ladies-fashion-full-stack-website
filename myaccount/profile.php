<?php

    include('../main-header/header.php');
    if (!isset($_SESSION['user_id'])) {
        header('location:../account/login.php');exit();
    }

    $userSql = "select * from users where user_id = " . $_SESSION['user_id'];

    $result = mysqli_query($connection, $userSql);

    $user = mysqli_fetch_assoc($result);

    $usernameError = $emailError = $phoneError = $fnameError = $lnameError = $address1Error = $address2Error = "";
    $username = $user['username'];
    $email = $user['email'];
    $phone = $user['phone'];
    $fname= $user['first_name'];
    $lname = $user['last_name'];
    $address1 = $user['address_line1'];
    $address2  = $user['address_line2'];

    if(isset($_POST['update'])) {
        if (isset($_POST['username']))  $username = sanitizeMySQL($connection,  $_POST['username']);
        if (isset($_POST['email']))   $email = sanitizeMySQL($connection,  $_POST['email']);
        if (isset($_POST['phone']))  $phone = sanitizeMySQL($connection,  $_POST['phone']);
        if (isset($_POST['fname']))  $fname = sanitizeMySQL($connection,  $_POST['fname']);
        if (isset($_POST['lname']))  $lname = sanitizeMySQL($connection,  $_POST['lname']);
        if (isset($_POST['address1']))  $address1 = sanitizeMySQL($connection,  $_POST['address1']);
        if (isset($_POST['address2']))  $address2 = sanitizeMySQL($connection,  $_POST['address2']);

        $usernameError = validate_username($username);
        $emailError = validate_email($email);
        $phoneError = validate_phone($phone);
        $fnameError = validate_name($fname);
        $lnameError = validate_name($lname);

        if ($usernameError==""  && $emailError =="" &&  $phoneError =="" && $fnameError == "" && $lnameError=="") {
            $sql = "update users set username='" . $username . "', email='" . $email . "', phone = '" . $phone . "', first_name='" . $fname . "', last_name='" . $lname . "', address_line1 = '" . $address1 . "', address_line2 = '" . $address2 . "' where user_id = " . $_SESSION['user_id'];
            $result = mysqli_query($connection, $sql);
            $_SESSION['message'] = "your new details updated successfully";

        } 
    }

    $curpassError = $newpassError = $confirmpassError = $curpass = $newpass = $confirmpass = "";

    if(isset($_POST['change-pass'])) {

        if (isset($_POST['curpass']))  $curpass = sanitizeMySQL($connection,  $_POST['curpass']);
        if (isset($_POST['newpass']))  $newpass = sanitizeMySQL($connection,  $_POST['newpass']);
        if (isset($_POST['confirm']))   $confirmpass = sanitizeMySQL($connection,  $_POST['confirm']);

        $curpassError = validate_curpass($connection, $curpass);
        $newpassError = validate_newpass($newpass);
        $confirmpassError = validate_confirmpass($newpass, $confirmpass);
        

        if ($curpassError=="" && $newpassError ==""  && $confirmpassError =="") {
            $hashed_newpass = password_hash($newpass, PASSWORD_DEFAULT);

            if(changePassword($connection, $hashed_newpass)) {
                $curpass = $newpass = $confirmpass = "";
                $_SESSION['message'] = "you have successfully changed your password!";

                // echo "<script>alert('you have successfully changed your password!');</script>";
            }   
        }    
    }

    if(isset($_POST['msg'])){
        unset($_SESSION['message']);
    }

    // display messages
    if(isset($_SESSION['message'])){
            echo '
                <form class="message-wrapper" action="profile.php" method="post">
                    <div class="message">
                        <span>' . $_SESSION['message'] . '</span>
                        <button class="fas fa-times" name="msg"></button>
                    </div>
                </form>
            
            ';
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../shop/style.css">
    <title>My profile - Pink Pearl</title>
</head>
<body>

    <div class="myaccount">
        <div class="header">
            <p>Welcome, <?php echo getUserName($connection); ?>!</p>
            <h1>Your Profile</h1>
            <div class="options">
                <span><a href="rewards.php">Rewards</a></span> 
                <span><a href="orders.php">Orders</a></span> 
                <span><a href="wishlist.php">Wishlist</a></span>
                <span><a href="cart.php">Cart</a></span>
                <span><a href="review.php">Reviews</a></span>
                <span><a href="profile.php" class="active">Profile</a></span>
            </div>
        </div>
        
        <form action="profile.php" method="post">
            <h2 style="padding:5px 0;border-bottom:1px solid rgb(186, 186, 186);margin-bottom:10px;">Personal Information</h2>
            <div class="input-box">
                <label for="username">Username</label><br>
                <input type="text" id="username" name="username" value="<?php echo $username; ?>">
                <small><?php echo $usernameError ?></small>
            </div>
            <div class="input-box">
                <label for="email">Email</label><br>
                <input type="email" id="email" name="email"  value="<?php echo $email; ?>" placeholder="Eg. example@gmail.com">
                <small><?php echo $emailError ?></small>
            </div>
            <div class="input-box">
                <label for="phone">Phone</label><br>
                <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" placeholder="Eg. 07xxxxxxxx">
                <small><?php echo $phoneError ?></small>
            </div>
            <p style="color: red;font-style:italic;">*Below fields are optional used for checkout purposes!</p>
            <div class="input-box">
                <label for="fname">First name</label><br>
                <input type="text" id="fname" name="fname" value="<?php echo $fname; ?>" placeholder="Eg. John">
                <small><?php echo $fnameError ?></small>
            </div>
            <div class="input-box">
                <label for="lname">Last name</label><br>
                <input type="text" id="lname" name="lname" value="<?php echo $lname; ?>" placeholder="Eg. Smith">
                <small><?php echo $lnameError ?></small>
            </div>
            <div class="input-box">
                    <label for="">Address line 1</label>
                    <input type="text" placeholder="House No, Street No, Area" name="address1" value="<?php echo $address1; ?>">
                    <small><?php echo $address1Error; ?></small>
            </div>
            
            <div class="input-box">
                <label for="">Address line 2</label>
                <input type="text" placeholder="Suite no / floor No " name="address2"  value="<?php echo $address2; ?>">
                <small><?php echo $address2Error; ?></small>
            </div>
            <button type="submit" name="update">Update Information</button>
        </form>

        <h2 style="padding:5px 0;border-bottom:1px solid rgb(186, 186, 186);margin:10px 0;width:100%;max-width:500px;cursor:pointer;">Change password</h2>

        <form action="profile.php" method="post">
            <div class="input-box">
                <label>Current Password</label><br>
                <input type="password" name="curpass" value="<?php echo $curpass; ?>"><br>
                <small class="error"><?php echo "$curpassError"; ?></small>
            </div>
            <div class="input-box">
                <label>New Password</label><br>
                <input type="password" name="newpass" value="<?php echo $newpass; ?>" ><br>
                <small class="error"><?php echo "$newpassError"; ?></small>
            </div>
            <div class="input-box">
                <label>Confirm New Password</label><br>
                <input type="password" name="confirm" value="<?php echo $confirmpass; ?>" ><br>
                <small class="error"><?php echo "$confirmpassError"; ?></small>
            </div>
            <button type="submit" name="change-pass">Change password</button>
        </form>
    </div>

    <?php include('../shop/footer.php') ?>

    
</body>
</html>

<?php

    function sanitizeString($var) {
        $var = stripslashes($var);
        $var = strip_tags($var);
        $var = htmlentities($var);
        $var = trim($var);
        return $var;
    }
    function sanitizeMySQL($connection, $var){ 
        $var = mysqli_real_escape_string($connection, $var);
        $var = sanitizeString($var);
        return $var;
    }


    function validate_username($field) {
        if ($field == "") {
            return "Name is required!";
        }
        else if ((preg_match("/[^a-zA-Z ]/", $field))) {
            return "Username cannot have special characters or numbers!";
        }
        return "";
    }
    function validate_name($field) {
        if ((preg_match("/[^a-zA-Z]/", $field))) {
            return "Name cannot have special characters or numbers!";
        }
        return "";
    }
    function validate_email($field) {
        if ($field == "") {
            return "Email is required!";
        }
        else if (!filter_var($field, FILTER_VALIDATE_EMAIL)) {
            return "Invalid Email format";
        }
        return "";
    }

    function validate_phone($field) {
        if ($field == "") {
            return "Phone number is required!";
        }
        else if (!preg_match("/^07\d{8}$/", $field)) {
            return "Phone number must in a format 07xxxxxxxx";
        }
        return "";
    }
    
    function validate_address($field) {
        if ($field == "") {
            return "Address is required!";
        }
        return "";
    }
    function getUserName($connection) {

        $query = 'select username from users where user_id = ' . $_SESSION['user_id'];
        $result = mysqli_query($connection, $query);

        $name = mysqli_fetch_assoc($result)['username'];

        return $name;

    }

    function validate_curpass($connection, $field) {
        if ($field == "") {
            return "Current password is required!";
        }
        else if (!password_verify($field, getPassword($connection))){
            return "Incorrect current password!";
        }
        return "";
    }

    function validate_newpass($field) {
        if ($field == "") {
            return "New password is required!";
        }
        else if (strlen($field)<8) {
            return "Password must contain atleast 8 characters!";
        }
        return "";
    }

    function validate_confirmpass($pass, $cpass) {
        if ($cpass == "") {
            return "Confirm new password is required!";
        }
        else if (strlen($cpass)<8) {
            return "Password must contain atleast 8 characters!";
        }
        else if ($pass != $cpass) {
            return "Confirm new password does not match!";
        }
        return "";
    }

    function changePassword($connection, $newpassword) {
        $updateSql = "UPDATE users set password = '$newpassword'
                        WHERE user_id = " . $_SESSION['user_id'];
        $result = mysqli_query($connection, $updateSql);
        if ($result) {
            return true;
        }else {
            echo "cannot change the password!";
            return false;
        }
    }

    function getPassword($connection) {

        $query = 'select password from users where user_id = ' . $_SESSION['user_id'];
        $result = mysqli_query($connection, $query);

        $password = mysqli_fetch_assoc($result)['password'];

        return $password;

    }

?>