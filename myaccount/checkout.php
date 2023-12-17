
<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('location:../account/login.php');exit();
    }
    include('../database/dbconnection.php');

    $userSql = "select * from users where user_id = " . $_SESSION['user_id'];
    $result = mysqli_query($connection, $userSql);
    $user = mysqli_fetch_assoc($result);

    $email = $user['email'];
    $phone = $user['phone'];
    $fname= $user['first_name'];
    $lname = $user['last_name'];
    $address1 = $user['address_line1'];
    $address2  = $user['address_line2'];

    $district = $postal = $city =  "";
    $fnameError = $lnameError = $emailError = $phoneError = $districtError = $postalError = $cityError = $address1Error = $address2Error = "";


    if(isset($_POST['proceed'])) {
        if (isset($_POST['fname']))  $fname = sanitizeMySQL($connection,  $_POST['fname']);
        if (isset($_POST['lname']))  $lname = sanitizeMySQL($connection,  $_POST['lname']);
        if (isset($_POST['email']))  $email = sanitizeMySQL($connection,  $_POST['email']);
        if (isset($_POST['phone']))  $phone = sanitizeMySQL($connection,  $_POST['phone']);
        if (isset($_POST['district']))  $district = sanitizeMySQL($connection,  $_POST['district']);
        if (isset($_POST['postal']))  $postal = sanitizeMySQL($connection,  $_POST['postal']);
        if (isset($_POST['city']))  $city = sanitizeMySQL($connection,  $_POST['city']);
        if (isset($_POST['address1']))  $address1 = sanitizeMySQL($connection,  $_POST['address1']);
        if (isset($_POST['address2']))  $address2 = sanitizeMySQL($connection,  $_POST['address2']);

        $fnameError = validate_name($fname);
        $lnameError = validate_name($lname);
        $emailError = validate_email($email);
        $phoneError = validate_phone($phone);
        $districtError = validate_name($district);
        $cityError = validate_name($city);
        $postalError = validate_postal($postal);
        $address1Error = validate_address($address1);


        if ($fnameError == "" && $lnameError=="" && $emailError=="" && $phoneError=="" && $districtError=="" && $cityError=="" && $postalError=="" && $paymentError=="" && $address1Error==""){
            $_SESSION['order_shipping'] = [];
            $_SESSION['order_shipping']['fname'] = $fname;
            $_SESSION['order_shipping']['lname'] = $lname;
            $_SESSION['order_shipping']['email'] = $email;
            $_SESSION['order_shipping']['phone'] = $phone;
            $_SESSION['order_shipping']['district'] = $district;
            $_SESSION['order_shipping']['city'] = $city;
            $_SESSION['order_shipping']['postal'] = $postal;
            $_SESSION['order_shipping']['address1'] = $address1;
            $_SESSION['order_shipping']['address2'] = $address2;
            $_SESSION['order_shipping']['total'] = getSubtotal($connection, $_SESSION['user_id']);

            header('location:payment.php');
            // print_r($_SESSION['order_shipping']);
        }



    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/c732ec9339.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../index.css">
    <link rel="stylesheet" href="../shop/style.css">
    <title>Checkout - Pink pearl</title>
</head>
<body>
    <div class="checkout">

        <div class="header">
            <h2>Secure Checkout - Shipping Information</h2>
        </div>

        <div class="progress">

                <div class="progress-bar shipping">
                    <span><i class="fa fa-check" aria-hidden="true"></i><br><small>Shipping</small></span>
                    <span><i class="fa fa-check" aria-hidden="true"></i><br><small>Payment</small></span>
                    <span><i class="fa fa-check" aria-hidden="true"></i><br><small>Success</small></span>
                    <div class="line"></div>
                </div>
        </div>


        <div class="content checkout-wrapper">
            
            <form action="checkout.php" method="post">

                <div class="row">
                    <div class="input-box">
                        <label for="">First Name</label>
                        <input type="text" placeholder="Eg. John" name="fname" value="<?php echo "$fname"; ?>">
                        <small><?php echo $fnameError; ?></small>
                    </div>
                    <div class="input-box">
                        <label for="">Last Name</label>
                        <input type="text" placeholder="Eg. Smith" name="lname" value="<?php echo "$lname"; ?>">
                        <small><?php echo $lnameError; ?></small>
                    </div>
                </div>
                <div class="row">
                    <div class="input-box">
                        <label for="">Email</label>
                        <input type="email" placeholder="Eg. example@gmail.com" name="email" value="<?php echo $email ?>">
                        <small><?php echo $emailError; ?></small>
                    </div>
                    <div class="input-box">
                        <label for="">Phone no</label>
                        <input type="text" placeholder="Eg. 07xxxxxxxx" name="phone" value="<?php echo $phone; ?>">
                        <small><?php echo $phoneError; ?></small>
                    </div>
                </div>
                <div class="row">
                    <div class="input-box">
                        <label for="">District</label>
                        <input type="text" placeholder="Eg. gampaha" name="district" value="<?php echo $district; ?>">
                        <small><?php echo $districtError; ?></small>
                    </div>
                    <div class="input-box">
                        <label for="">Postal code</label>
                        <input type="text" placeholder="Eg. 11500" name='postal' value="<?php echo $postal; ?>">
                        <small><?php echo $postalError; ?></small>
                    </div>
                </div>
                <div class="row">
                    <div class="input-box">
                        <label for="">City</label>
                        <input type="text" placeholder="Eg. Negombo" name="city" value="<?php echo $city; ?>">
                        <small><?php echo $cityError; ?></small>
                    </div>
                </div>
                <div class="row">
                    <div class="input-box">
                        <label for="">Address line 1</label>
                        <input type="text" placeholder="House No, Street No, Area" name="address1" value="<?php echo $address1; ?>">
                        <small><?php echo $address1Error; ?></small>
                    </div>
                </div>
                <div class="row">
                    <div class="input-box">
                        <label for="">Address line 2 <small style="color: red;font-style:italic;">*optional</small></label>
                        <input type="text" placeholder="Suite no / floor No " name="address2"  value="<?php echo $address2; ?>">
                        <small><?php echo $address2Error; ?></small>
                    </div>
                </div>
                <div style="width:100%;display:flex;justify-content:right;gap:15px;align-items:end;">
                    <a href="cart.php" style="padding: 7px 15px; font-size:16px;color:var(--pink)">Cancel</a>
                    <button type="submit" name="proceed" style="padding: 7px 15px; font-size:16px;">Proceed to payment</button>
                </div>
                
            </form>
            <div class="summary-wrapper">
                <div class="summary-box">
                    <h3>Order Summary</h3>
                    <?php
                        echo "<table>";
                        $cartItemSql = "select * from shopping_cart where user_id = " . $_SESSION['user_id'];
                        $result = mysqli_query($connection, $cartItemSql);
                        while($row = mysqli_fetch_assoc($result)) {
                            $productSql =  "select * from products where product_id = " . $row['product_id'];
                            $result2 = mysqli_query($connection, $productSql);
                            $row2 = mysqli_fetch_assoc($result2);
                            echo "<tr><td>" . $row2['product_name'] . "</td> <td>" . $row['quantity'] . " x Rs. " . $row2['price'] . "</td> <td>Rs. " . $row['amount']  . "</td></tr>";
                        }
                        echo "</table>";
                        echo "<h4 style='font-weight:600;'>Sub total: Rs. " . getSubtotal($connection, $_SESSION['user_id']) . "</h4>";
                    ?>
                    <a href="cart.php" style="font-size: 15px;">Edit cart</a>
                </div>
            </div>
        </div>

        
    </div>

    <?php include('../shop/footer.php') ?>
</body>
</html>

<?php
    function getSubtotal($connection, $id) {
        $totalQuery = "select sum(amount) from shopping_cart where user_id = " . $id;
        $totalResult = mysqli_query($connection, $totalQuery);

        $subtotal = mysqli_fetch_assoc($totalResult)['sum(amount)'];

        return $subtotal;
    }
    function sanitizeString($var) {
        $var = stripslashes($var);
        $var = strip_tags($var);
        $var = htmlentities($var);
        return $var;
    }
    function sanitizeMySQL($connection, $var){ 
        $var = mysqli_real_escape_string($connection, $var);
        $var = sanitizeString($var);
        return $var;
    }
    function validate_name($field) {
        if ($field == "") {
            return "This field is required!";
        }
        else if ((preg_match("/[^a-zA-Z ]/", $field))) {
            return "This field can have only letters!";
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
    function validate_postal($field) {
        if ($field == "") {
            return "This field is required!";
        }
        else if ((preg_match("/[^0-9]/", $field))) {
            return "This field can have only letters!";
        }
        return "";
    }
    function validate_payment($field) {
        if ($field == "select") {
            return "This field is required!";
        }
        return "";
    }
    function validate_address($field) {
        if ($field == "") {
            return "Address is required!";
        }
        return "";
    }
?>