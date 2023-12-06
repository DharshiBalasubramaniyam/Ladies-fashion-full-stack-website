<?php
    session_start();
    if (!isset($_SESSION['admin_id'])) {
        header('location:../account/login.php');exit();
    }
    include('../database/dbconnection.php');

    $userSql = "select * from users where user_id = " . $_SESSION['admin_id'];

    $result = mysqli_query($connection, $userSql);

    $user = mysqli_fetch_assoc($result);

    $usernameError = $emailError = $phoneError = $fnameError = $lnameError = $address1Error = $address2Error = "";
    $username = $user['username'];
    $email = $user['email'];
    $phone = $user['phone'];

    if(isset($_POST['update'])) {
        if (isset($_POST['username']))  $username = sanitizeMySQL($connection,  $_POST['username']);
        if (isset($_POST['email']))   $email = sanitizeMySQL($connection,  $_POST['email']);
        if (isset($_POST['phone']))  $phone = sanitizeMySQL($connection,  $_POST['phone']);
        
        $usernameError = validate_name($username);
        $emailError = validate_email($email);
        $phoneError = validate_phone($phone);
        

        if ($usernameError==""  && $emailError =="" &&  $phoneError =="" && $fnameError == "" && $lnameError=="") {
            $_SESSION['admin_name'] = $username;
            $sql = "update users set username='" . $username . "', email='" . $email . "', phone = '" . $phone . "' where user_id = " . $_SESSION['admin_id'];
            $result = mysqli_query($connection, $sql);
            echo "<script>alert('your new dedails updatded successfully!');</script>";
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
                echo "<script>alert('you have successfully changed your password!');</script>";
            }   
        }    
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add new user - pink pearl</title>
    <link rel="stylesheet" href="../index.css">
    <script src="https://kit.fontawesome.com/c732ec9339.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link  rel="stylesheet"href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <div style="display: flex;align-items: center;gap: 10px;"><i class="fa fa-bars toggle-menu" aria-hidden="true" onclick="handleNav()" style="font-size: 20px;"></i><img src="../uploadedImages/logo.png" alt=""></div>
        <h1>Welcome <?php echo $_SESSION['admin_name'] ?>!</h1>
    </header>

    <main>
        <div class="side-bar">
            <ul>
                <li class="active">
                    <a href="dashboard.php"><i class='bx bxs-dashboard'></i>
                    <span>Dashboard</span></a>
                </li>
                <li>
                    <a href="products.php"><i class="fa fa-gift" aria-hidden="true" style="font-size: 16.5px;"></i>
                    <span>View products</span></a>
                </li>
                <li>
                    <a href="orders.php"><i class='bx bxs-show'></i>
                    <span>View orders</span></a>
                </li>
                <li>
                    <a href="customers.php"><i class='bx bxs-group'></i>
                    <span>View customers</span></a>
                </li>
                <li>
                    <a href="categories.php"><i class="fa fa-cubes" aria-hidden="true"></i>
                    <span>View Categories</span></a>
                </li>
                <li>
                    <a href="addnewproduct.php"><i class='bx bxs-caret-down-square' ></i>
                    <span>Add new product</span></a>
                </li>
                <li>
                    <a href="addnewuser.php"><i class='bx bx-user-plus'></i>
                    <span>Add new user</span></a>
                </li>
                <li>
                    <a href="addNewCategory.php"><i class='bx bxs-chevron-down-square'></i>
                    <span>Add new category</span></a>
                </li>
                <li>
                    <a href="messages.php"><i class='bx bx-message-detail'></i>
                    <span>Customer Messages</span></a>
                </li>
                <li>
                    <a href="profile.php" class="active"><i class='bx bx-user-circle'></i>
                    <span>Your Profile</span></a>
                </li>
                <li>
                    <a href="../account/logout.php"><i class='bx bx-log-out'></i>
                    <span>Logout</span></a>
                </li>
            </ul>
        </div>
        <div class="container products-container">
            <h1>Your profile</h1>
        
                <form action="" method="post" class="form">

                    <div class="input-group">
                        <label for="username">Username</label><br>
                        <input type="text" id="username" name="username" value="<?php echo $username; ?>">
                        <small><?php echo $usernameError ?></small>
                    </div>

                    <div class="input-group">
                        <label for="email">Email</label><br>
                        <input type="email" id="email" name="email"  value="<?php echo $email; ?>" placeholder="Eg. example@gmail.com">
                        <small><?php echo $emailError ?></small>
                    </div>

                    <div class="input-group">
                        <label for="phone">Phone</label><br>
                        <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" placeholder="Eg. 07xxxxxxxx">
                        <small><?php echo $phoneError ?></small>
                    </div>
                    <div class="input-group">
                        <button type="submit" name="update">Update profile</button>
                    </div>

                    <h2 style="padding:5px 0;border-bottom:1px solid rgb(186, 186, 186);margin:10px 0;width:100%;max-width:500px;cursor:pointer;">Change password</h2>

                        <div class="input-group">
                            <label>Current Password</label><br>
                            <input type="password" name="curpass" value="<?php echo $curpass; ?>"><br>
                            <small class="error"><?php echo "$curpassError"; ?></small>
                        </div>
                        <div class="input-group">
                            <label>New Password</label><br>
                            <input type="password" name="newpass" value="<?php echo $newpass; ?>" ><br>
                            <small class="error"><?php echo "$newpassError"; ?></small>
                        </div>
                        <div class="input-group">
                            <label>Confirm New Password</label><br>
                            <input type="password" name="confirm" value="<?php echo $confirmpass; ?>" ><br>
                            <small class="error"><?php echo "$confirmpassError"; ?></small>
                        </div>
                        <div class="input-group">
                            <button type="submit" name="change-pass">Change password</button>
                        </div>

                    
                </form>

        </div>
    </main>


    <footer>
        &copy; copyright  @ <?php echo date('Y'); ?> by <span style="color: var(--pink);">Pink Pearl</span>
    </footer>

    
    <script>
        const sidebar = document.querySelector('.side-bar');
        function handleNav() {
            if (sidebar.classList.contains("open")) {
                sidebar.classList.remove("open");
            }else {
                sidebar.classList.add("open");
            }
        }
    </script>
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

    function validate_name($field) {
        if ($field == "") {
            return "username is required!";
        }
        if ((preg_match("/[^a-zA-Z ]/", $field))) {
            return "user cannot have special characters or numbers!";
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
                        WHERE user_id = " . $_SESSION['admin_id'];
        $result = mysqli_query($connection, $updateSql);
        if ($result) {
            return true;
        }else {
            echo "cannot change the password!";
            return false;
        }
    }

    function getPassword($connection) {

        $query = 'select password from users where user_id = ' . $_SESSION['admin_id'];
        $result = mysqli_query($connection, $query);

        $password = mysqli_fetch_assoc($result)['password'];

        return $password;

    }


    

?>
