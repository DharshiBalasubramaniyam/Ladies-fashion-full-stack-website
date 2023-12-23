<?php

    include('../main-header/header.php');
    include('../database/dbconnection.php');

    if (!isset($_SESSION['user_id'])) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        header('location:../account/login.php');exit();
    }

    $ordersSql = "select * from placed_order where user_id=" . $_SESSION['user_id'] . " order by date desc";

    $result = mysqli_query($connection, $ordersSql);
    
    if (isset($_GET['cancelorder'])) {
        cancelOrder($connection, $_GET['cancelorder']);
        $_SESSION['message'] = "Your order has been successfully cancelled!";
        echo "<script>window.location.href = 'orders.php';</script>";
    }

    if(isset($_POST['msg'])){
        unset($_SESSION['message']);
    }

    // display messages
    if(isset($_SESSION['message'])){
            echo '
                <form class="message-wrapper" action="orders.php" method="post">
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
    <title>My orders - Pink Pearl</title>
</head>
<body>

    <div class="myaccount">
        <div class="header">
            <p>Welcome, <?php echo getUserName($connection); ?>!</p>
            <h1>Your Orders</h1>
            <div class="options">
                <span><a href="orders.php" class="active">Orders</a></span> 
                <span><a href="wishlist.php">Wishlist</a></span>
                <span><a href="cart.php">Cart</a></span>
                <span><a href="review.php">Reviews</a></span>
                <span><a href="profile.php">Profile</a></span>
            </div>
        </div>

        <div class="content">
            <?php
                if (mysqli_num_rows($result) > 0) {
                echo "<ul>";
                while($row1 = mysqli_fetch_assoc($result)) {
                    echo "<li>
                    <div class='head'><span class='date'>Date: ". $row1['date'] . "</span>";
            ?>
            <?php    
                    if ($row1['status'] == 'pending' || $row1['status'] == 'Cancelled')  {
                        echo "<span class='status pending'>" . $row1['status'] . "</span>";
                    }
                    else if ($row1['status'] == 'shipped') {
                        echo "<span class='status shipped'>" . $row1['status'] . "</span>";
                    }
                    else if ($row1['status'] == 'delivered') {
                        echo "<span class='status delivered'>" . $row1['status'] . "</span>";
                    }
                    
                    
                    echo "</div>
                    <div class='content'>
                        <span>Order No</span>: " . str_pad($row1['order_id'], 6, "0", STR_PAD_LEFT) .  " <br>
                        <span>Items</span>: <br>
                        <table>";
            ?>
            <?php
                         
                         $orderItemSql = "select * from order_items where order_id = " . $row1['order_id'];
                         $result2 = mysqli_query($connection, $orderItemSql);

                         while($row2 = mysqli_fetch_assoc($result2)) {
                            $productSql =  "select * from products where product_id = " . $row2['product_id'];
                            $result3 = mysqli_query($connection, $productSql);
                            $row3 = mysqli_fetch_assoc($result3);
                            $price = $row2['quantity'] * $row3['price'];
                            echo "<tr><td>" . $row3['product_name'] . "</td> <td>" . $row2['quantity'] . " x Rs. " . $row3['price'] . "</td> <td>Rs." . $price . "</td></tr>";
                         }

                    ?>
                
            <?php
                        echo "</table>
                        <span>Total</span>: Rs. " . $row1['total_amount'] .  "<br>
                        <span>Payment</span>: " . $row1['payment_method'];

                        if ($row1['status'] == 'pending')  {
                            echo "<br><span style='margin-top:5px;'>
                                        <a href='orders.php?cancelorder=" . $row1['order_id'] . "' style='color:red;' onclick='return confirm(\"Are you sure? Do you want to cancel this order?\")'>
                                            Cancel the order</a>
                                      </span>";
                        }

                        echo "</div></li>";
                }
            ?>
            </ul>
            <?php
                } else {
                    echo "<div class='empty-order-history'>
                    <h3>No Order History</h3>
                    <p>You haven't purchased anything yet.<br>
                Don't worry. We have lots of great finds.</p>
                    <button>Start shopping</button>
                </div>";
                }
            ?>  
        
        </div>
    </div>


    <?php include('../shop/footer.php') ?>

    
</body>
</html>


<?php

    function getUserName($connection) {

        $query = 'select username from users where user_id = ' . $_SESSION['user_id'];
        $result = mysqli_query($connection, $query);

        $name = mysqli_fetch_assoc($result)['username'];

        return $name;

    }

    function cancelOrder($connection, $order_id) {
        $query1 = "update placed_order set status='Cancelled' where order_id = " . $order_id;
        $result = mysqli_query($connection, $query1);
    }

?>