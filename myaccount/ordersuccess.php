<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('location:../account/login.php');exit();
    }
    include('../database/dbconnection.php');


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
    <title>Order success - Pink pearl</title>
    <style>
        
    </style>
</head>
<body>
    <div class="checkout">

        <div class="header">
            <h2>Order successful</h2>
        </div>

        <div class="progress">
                <div class="progress-bar success">
                    <span><i class="fa fa-check" aria-hidden="true"></i><br><small>Shipping</small></span>
                    <span><i class="fa fa-check" aria-hidden="true"></i><br><small>Payment</small></span>
                    <span><i class="fa fa-check" aria-hidden="true"></i><br><small>Success</small></span>
                    <div class="line"></div>
                </div>
        </div>

        <div class="content">

            <div class="order-success-box">
                <img src="../uploadedImages/order-success.jpg" alt="">
                <h2>Thank you for shopping!</h2>
                <p style="margin: 15px 0; text-align:center">Your order is successfully placed. We are getting started on your order right away.</p>
                <div class="btns" style="display: flex;gap:10px">
                    <a href="orders.php"><button>View order</button></a>
                    <a href="#"><button>Continue Shopping</button></a>
                </div>
                
            </div>
            
        </div>  

        
    </div>


    <?php include('../shop/footer.php') ?>



</body>
</html>
    