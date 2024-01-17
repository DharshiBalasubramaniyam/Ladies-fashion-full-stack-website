<?php
    session_start();

    include('../database/dbconnection.php');
    if (!isset($_SESSION['user_id'])) {
        header('location:../account/login.php');exit();
    }

    $payment = $card_holder_name = $card_no = $cvv =  $expiry = "";
    $paymentError = $card_holder_nameError = $card_noError = $cvvError = $expiryError = "";

    $points = $_SESSION['order_shipping']['points_redeemed'];

    if (isset($_POST['spend'])) {
        $points = $_POST['points'];
        $_SESSION['order_shipping']['points_redeemed'] = $_POST['points'];
        $_SESSION['points'] = $_POST['points']; 
        $_SESSION['order_shipping']['total'] = doubleval(getSubtotal($connection, $_SESSION['user_id']) - doubleval($_SESSION['points']));
    }

    if(isset($_POST['confirmPaymentMethod'])) {
        if (isset($_POST['payment']))  $payment = sanitizeMySQL($connection,  $_POST['payment']);

        $paymentError = validate_payment($payment);

        if ($paymentError==""){
            $_SESSION['order_shipping']['payment_method'] = $payment;
            if($payment == "cash at delivary") {
                $_SESSION['order_shipping']['tax'] =  50.0;
                $_SESSION['order_shipping']['total'] += 50.0;
            }else  if($payment == "credit card/debit card") {
                $_SESSION['order_shipping']['tax'] = 0;
            } 
        }
    }

    if (isset($_POST['confirmCardOrder'])) {
        if (isset($_POST['cardholdername']))  $card_holder_name = sanitizeMySQL($connection,  $_POST['cardholdername']);
        if (isset($_POST['cardno']))  $card_no = sanitizeMySQL($connection,  $_POST['cardno']);
        if (isset($_POST['cvv']))  $cvv = sanitizeMySQL($connection,  $_POST['cvv']);
        if (isset($_POST['expiry']))  $expiry = sanitizeMySQL($connection,  $_POST['expiry']);

        $card_holder_nameError = validate_name($card_holder_name);
        $card_noError = validate_cardno($card_no);
        $cvvError = validate_cvv($cvv);
        $expiryError = validate_expiry($expiry);

        if ($card_holder_nameError == "" && $card_noError == "" && $cvvError == "" && $expiryError == ""){
            if(confirmOrder($connection)) {
                $_SESSION['order_shipping'] = [];
                unset($_SESSION['points']);
                header('location:ordersuccess.php');
            }
        }

    }

    if (isset($_POST['confirmAtDelivaryOrder'])) {
        if(confirmOrder($connection)) {
            $_SESSION['order_shipping'] = [];
            unset($_SESSION['points']);
            header('location:ordersuccess.php');
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
    <title>Payment - Pink pearl</title>
</head>
<body>
    <div class="checkout">

        <div class="header">
            <h2>Secure Payment - Payment Information</h2>
        </div>

        <div class="progress">
                <div class="progress-bar payment">
                    <span><i class="fa fa-check" aria-hidden="true"></i><br><small>Shipping</small></span>
                    <span><i class="fa fa-check" aria-hidden="true"></i><br><small>Payment</small></span>
                    <span><i class="fa fa-check" aria-hidden="true"></i><br><small>Success</small></span>
                    <div class="line"></div>
                </div>
        </div>

        <div class="content checkout-wrapper">
            
            <form action="payment.php" method="post" class="payment" novalidate>
                <?php if (($payment == "" || $payment=='select') && !isset($_SESSION['order_shipping']['payment_method'])) { ?>

                    <div class="row">
                        <div class="input-box">
                            <label for="">Payment method</label>
                            <select name="payment" id="" onchange="handlePaymentMethod()">
                                <option value="select">Select payment method</option>
                                <option value="cash at delivary" <?php if (isset($_POST["confirmPaymentMethod"]) && $_POST["payment"]=="cash at delivary") echo "selected"; ?>>Cash at delivary</option>
                                <option value="credit card/debit card" <?php if (isset($_POST["confirmPaymentMethod"]) && $_POST["payment"]=="credit card/debit card") echo "selected"; ?>>Credit card / Debit card</option>
                            </select>
                            <small><?php echo $paymentError; ?></small>
                            <small style="color:blue;font-style:italic">*Cash payment of Rs. 50 applies only to cash on delivary method. There is no extra fee when using online payment methods.</small>
                        </div>
                    </div>
                
                    <button type="submit" name="confirmPaymentMethod" id="confirmPaymentMethod" style="opacity:0.2; pointer-events:none;padding: 7px 15px; font-size:16px;">Confirm payment method</button>

                <?php } else if ($_SESSION['order_shipping']['payment_method']== "credit card/debit card") { ?>
                        <div class="row">
                            <div class="input-box">
                                <label for="">Payment method</label>
                                <input type="text" value="credit card/debit card" disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-box">
                                <label for="">Card holder Name</label>
                                <input type="text" placeholder="Eg. John Smith" name="cardholdername" value="<?php echo $card_holder_name; ?>">
                                <small><?php echo $card_holder_nameError; ?></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-box">
                                <label for="">Card number</label>
                                <input type="text" placeholder="xxxx xxxx xxxx xxxx" name="cardno" value="<?php echo $card_no ?>">
                                <small><?php echo $card_noError; ?></small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-box">
                                <label for="">CVV</label>
                                <input type="text" placeholder="xxx" name="cvv" value="<?php echo $cvv ?>">
                                <small><?php echo $cvvError; ?></small>
                            </div>
                            <div class="input-box">
                                <label for="">Expiry Date</label>
                                <input type="date" name="expiry" value="<?php echo $expiry ?>">
                                <small><?php echo $expiryError; ?></small>
                            </div>
                        </div>
                        <button type="submit" name="confirmCardOrder" style="padding: 7px 15px; font-size:16px;">Confirm Order</button>

                <?php }else if  ($_SESSION['order_shipping']['payment_method'] == "cash at delivary")  { ?>
                        <div class="row">
                            <div class="input-box">
                                <label for="">Payment method</label>
                                <input type="text" value="cash at delivary" disabled>
                            </div>
                        </div>
                        <div class="row">
                            Cash payment of Rs. 50 is added with your sub total.
                        </div>
                        <button type="submit" name="confirmAtDelivaryOrder" style="padding: 7px 15px; font-size:16px;">Confirm order</button>
                <?php } ?>
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
                        if ($payment == 'cash at delivary') {
                            echo "<h5 style='margin-top:10px;'>Charge for cash at delivery: Rs. 50.00</h5>";
                        }
                        echo "<h5 style='font-weight:600;font-size:16px'>Sub total&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp : Rs. " . getSubtotal($connection, $_SESSION['user_id']) . "</h5>";
                        echo "<h5 style='font-weight:600;font-size:16px'>Discount&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp : Rs. " . $points . "</h5>";
                        if  (isset($_SESSION['order_shipping']['payment_method']) && $_SESSION['order_shipping']['payment_method'] == "cash at delivary") {
                            echo "<h5 style='font-weight:600;font-size:16px'>Cash at delivary : Rs. 50.0</h5>";
                        }
                        echo "<h4 style='font-weight:600;font-size:18px'>GRAND TOTAL&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp : Rs. " . $_SESSION['order_shipping']['total'] . "</h4>";
                        echo "<h4 style='margin:15px 0;'>YOU WILL EARN " . getPoints($connection) . " POINTS FROM THIS ORDER.</h4>";
                        
                    ?>
                </div>
                <div class="summary-box">
                        <h3 style="margin-bottom: 10px;">Shipping summary</h3>
                        <small >First Name: <?php echo $_SESSION['order_shipping']['fname'] ?></small><br>
                        <small>Last Name: <?php echo $_SESSION['order_shipping']['lname'] ?></small><br>
                        <small>Email: <?php echo $_SESSION['order_shipping']['email'] ?></small><br>
                        <small>Phone: <?php echo $_SESSION['order_shipping']['phone'] ?></small><br>
                        <small>District: <?php echo $_SESSION['order_shipping']['district'] ?></small><br>
                        <small>City: <?php echo $_SESSION['order_shipping']['city'] ?></small><br>
                        <small>postal code: <?php echo $_SESSION['order_shipping']['postal'] ?></small><br>
                        <small>Address line 1: <?php echo $_SESSION['order_shipping']['address1'] ?></small><br>
                        <small>Address line 2: <?php echo $_SESSION['order_shipping']['address2'] ?></small><br>
                        <a href="checkout.php" style="font-size: 15px;">Edit Shipping details</a>
                </div>
            </div>
        </div>

        
    </div>


    <?php include('../shop/footer.php') ?>



    <script>
        function handlePaymentMethod(){
            const btn = document.getElementById('confirmPaymentMethod');
            console.log(btn);
            btn.style.opacity = '1';
            btn.style.pointerEvents = 'auto';
        }
        function handlePoints(e) {
            const points = document.querySelector(".points_display");
            points.textContent =  "Rs. " + e.value;

        }
    </script>


</body>
</html>

<?php
function getPoints($connection) {
    $total = getSubtotal($connection, $_SESSION['user_id']);
    $points = intval($total/100);
    return $points;
}
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
    
    function validate_payment($field) {
        if ($field == "select") {
            return "This field is required!<br>";
        }
        return "";
    }
    function validate_name($field) {
        if ($field == "") {
            return "This field is required!";
        }
        else if ((preg_match("/[^a-zA-Z .]/", $field))) {
            return "This field can have only letters!";
        }
        return "";
    }

    function validate_cardno($field) {
        if ($field == "") {
            return "This field is required!";
        }
        else if ((!preg_match("/^\d{4}\d{4}\d{4}\d{4}$/", $field))) {
            return "Card number must have 16 digits!";
        }
        return "";
    }

    function validate_cvv($field) {
        if ($field == "") {
            return "This field is required!";
        }
        else if ((!preg_match("/^\d{3}$/", $field))) {
            return "CVV must in format xxx!";
        }
        return "";
    }

    function validate_expiry($field) {
        if ($field == "") {
            return "This field is required!";
        }
        return "";
    }

    function confirmOrder($connection) {
        $orderQuery = "insert into placed_order(user_id, total_amount, payment_method, points_earned, points_redeemed) values 
                        (" .  $_SESSION['user_id'] . ", " . $_SESSION['order_shipping']['total'] . ", '" . $_SESSION['order_shipping']['payment_method'] . "', " . $_SESSION['order_shipping']['points_earned'] . ", " . $_SESSION['order_shipping']['points_redeemed'] . ")";
        echo $orderQuery;
        mysqli_query($connection, $orderQuery);
        $points = $_SESSION['order_shipping']['points_earned'] - $_SESSION['order_shipping']['points_redeemed'];
        mysqli_query($connection, "update users set current_point_balance = current_point_balance + $points where user_id = " . $_SESSION['user_id']);

            $orderIdQuery = "select order_id from placed_order where user_id=" .  $_SESSION['user_id'] . " order by date desc limit 1";
            $result = mysqli_query($connection, $orderIdQuery);
            $orderId = mysqli_fetch_assoc($result)['order_id'];

            $cartItemSql = "select * from shopping_cart where user_id = " . $_SESSION['user_id'];
            $result2 = mysqli_query($connection, $cartItemSql);
            while($row = mysqli_fetch_assoc($result2)) {
                $orderItemSql = "insert into order_items(order_id, product_id, quantity, size_id, color_id, amount) 
                values (" . $orderId . ", " . $row['product_id'] . ", " . $row['quantity'] . " , " . $row['size_id'] . ", " . $row['color_id'] . ", " . $row['amount'] . ")";
                mysqli_query($connection, $orderItemSql);

                $getStockSql = "select * from product_stock where product_id = " . $row['product_id'] . " and size_id = " . $row['size_id'] . " and color_id = " . $row['color_id'] . "";
                echo $getStockSql;
                $stockResult = mysqli_query($connection, $getStockSql);
                $stock = mysqli_fetch_assoc($stockResult);
                $stock_id = $stock['stock_id'];
                $quantity = $stock['stock_qty'] - $row['quantity'];
                
                $reduceItemQtySql = "update product_stock set stock_qty = " . $quantity . " where stock_id = " . $stock_id . "";
                mysqli_query($connection, $reduceItemQtySql);

                $deleteCartItemSql = "delete from shopping_cart where cart_item_id = " . $row['cart_item_id'];
                mysqli_query($connection, $deleteCartItemSql);
            }

            $shippingSql = "insert into order_shipping(order_id, first_name, last_name, email, phone, district, postal_code, city, address_line1, address_line2) 
            VALUES ($orderId,'" . $_SESSION['order_shipping']['fname'] . "','" . $_SESSION['order_shipping']['lname'] . "','" . $_SESSION['order_shipping']['email'] . "','" . $_SESSION['order_shipping']['phone'] . 
            "','" . $_SESSION['order_shipping']['district'] . "','" . $_SESSION['order_shipping']['postal'] . "','" . $_SESSION['order_shipping']['city'] . "','" . $_SESSION['order_shipping']['address1'] . "','" . $_SESSION['order_shipping']['address2'] .   "')";
            
            if(mysqli_query($connection, $shippingSql)) {
                return true;
            }
        return false;
    }

?>