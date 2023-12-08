<?php
    session_start();
    if (!isset($_SESSION['admin_id'])) {
        header('location:../account/login.php');exit();
    }
    include('../database/dbconnection.php');

    $nameError = $typeError = $emailError = $passwordError = $cpasswordError = $phoneError = "";
    $name = $type = $email = $password = $cpassword = $phone = "";

    if(isset($_POST['add_user'])) {

        if (isset($_POST['name']))  $name = sanitizeMySQL($connection,  $_POST['name']);
        if (isset($_POST['type']))  $type = sanitizeMySQL($connection,  $_POST['type']);
        if (isset($_POST['email']))   $email = sanitizeMySQL($connection,  $_POST['email']);
        if (isset($_POST['password']))  $password = sanitizeMySQL($connection,  $_POST['password']);
        if (isset($_POST['cpassword']))   $cpassword = sanitizeMySQL($connection,  $_POST['cpassword']);
        if (isset($_POST['phone']))  $phone = sanitizeMySQL($connection,  $_POST['phone']);


        $nameError = validate_name($name);
        $emailError = validate_email($email);
        $typeError = validate_type($type);
        $passwordError = validate_password($password);
        $cpasswordError = validate_cpassword($password, $cpassword);
        $phoneError = validate_phone($phone);


        if ($nameError==""  && $emailError =="" && $passwordError=="" && $cpasswordError =="" && $phoneError =="") {
            $emailError = checkEmail($connection, $email);

            if ($emailError == "") {
                $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
                if(addUser($connection, $name, $email, $type, $hashed_pass, $phone)) {
                    $email = $password = $cpassword = $phone = "";
                    echo "<script>alert(' New user successfully added to the system!');</script>";

                }   
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
                    <a href="addnewuser.php" class="active"><i class='bx bx-user-plus'></i>
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
                    <a href="profile.php"><i class='bx bx-user-circle'></i>
                    <span>Your Profile</span></a>
                </li>
                <li>
                    <a href="../account/logout.php"><i class='bx bx-log-out'></i>
                    <span>Logout</span></a>
                </li>
            </ul>
        </div>
        <div class="container products-container">
            <h1>Add new user</h1>
        
                <form action="" method="post" class="form">

                    <div class="input-group">
                        <label for="">Username</label><br>
                        <input type="text" class="form" name="name" value="<?php echo "$name"; ?>">
                        <small><?php echo $nameError;  ?></small>
                    </div>

                    <div class="input-group">
                        <label for="">Email address</label><br>
                        <input type="email" class="form" name="email" value="<?php echo "$email"; ?>">
                        <small><?php echo $emailError ?></small>
                    </div>

                    <div class="input-group">
                        <label for="">Phone No</label><br>
                        <input type="text" class="form" name="phone" placeholder="07xxxxxxxx" value="<?php echo "$phone"; ?>">
                        <small><?php  echo $phoneError  ?></small>
                    </div>

                    <div class="input-group">
                        <label for="">User role</label><br>
                        <select class="form" name="type">
                            <option value="select">Select role</option>
                            <option value="customer" <?php if (isset($_POST["add_user"]) && $_POST["type"]=="customer") echo "selected"; ?>>Customer</option>
                            <option value="admin" <?php if (isset($_POST["add_user"]) && $_POST["type"]=="admin") echo "selected"; ?>>Admin</option>
                        </select>
                        <small><?php echo $typeError ?></small>
                    </div>

                    <div class="input-group">
                    <label for="">Password</label><br>
                        <input type="password" class="form" name="password" value="<?php echo "$password"; ?>">
                        <small><?php echo  $passwordError ?></small>
                    </div>

                    <div class="input-group">
                        <label for="">Confirm password</label><br>
                        <input type="password" class="form" name="cpassword" value="<?php echo "$cpassword"; ?>">
                        <small><?php echo $cpasswordError  ?></small>
                    </div>


                    <div class="input-group">
                        <button type="submit" name="add_user">Add user</button>
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

    function validate_type($field) {
        if ($field == "select") {
            return "User role is required!";
        }
        
        return "";
    }

    function validate_password($field) {
        if ($field == "") {
            return "password is required!";
        }
        else if (strlen($field)<8) {
            return "Password must contain atleast 8 characters!";
        }
        return "";
    }

    function validate_cpassword($pass, $cpass) {
        if ($cpass == "") {
            return "Confirm password is required!";
        }
        else if (strlen($cpass)<8) {
            return "Password must contain atleast 8 characters!";
        }
        else if ($pass != $cpass) {
            return "Confirm password does not match!";
        }
        return "";
    }

    function checkEmail($connection, $field) {
        $sql = "SELECT email from users where email='$field'";
        $result = mysqli_query($connection, $sql);
        if (mysqli_num_rows($result)>0) {
            return "Given Email already exists";
        }
        return "";
    }

    function addUser($connection, $name, $email, $type, $password, $phone) {
        $insertSql = "INSERT INTO users (username, email, phone, user_type, password)
                    VALUES('$name', '$email', '$phone','$type', '$password')";
        $result = mysqli_query($connection, $insertSql);
        if ($result) {
            return true;
        }else {
            echo "cannot add user to the system!";
            return false;
        }
                
    }

    

?>
