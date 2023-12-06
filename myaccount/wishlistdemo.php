<?php
    include('header.php');

    $product_id = $size = $color = $qty = "";

    $wishlistQuery = "select * from wishlist where user_id = " . $_SESSION['user_id']; 
    $result1 = mysqli_query($connection, $wishlistQuery);

    if (isset($_POST['addToCart'])) {
        if (isset($_POST['size'])) {
            $size = $_POST['size'];
        }
        if(isset($_GET['product_id'])) {
            $product_id = $_GET['product_id'];
        }
        
        $color = $_POST['color'];
        $qty = $_POST['quantity'];

        if ($size == "select") {
           echo "<script>alert('Please select size!')</script>";
        }
        else if ($color == "select") {
            echo "<script>alert('Please select color!')</script>";
        }
        else if ($qty == 0) {
            echo "<script>alert('Please select quantity!')</script>";
        }
        else {
            echo "<script>alert('check whether already in cart if so alert, else not add to cart!!')</script>";
        }

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>My wishlist - Pink Pearl</title>
</head>
<body>

    <div class="myaccount">
        <div class="header">
            <p>Wecome, John Smith!</p>
            <h1>Your Favourite Items</h1>
            <div class="options">
                <span><a href="orders.php">Orders</a></span> 
                <span><a href="profile.php">Profile</a></span>
                <span><a href="wishlist.php" class="active">Wishlist</a></span>
                <span><a href="cart.php">Cart</a></span>
            </div>
        </div>
        
            <?php
                if (mysqli_num_rows($result1) > 0) {
                    echo "<div class='content wishlist-wrapper'>";
                    while($row1 = mysqli_fetch_array($result1)) {
                        $productQuery = "select * from products where product_id = " . $row1['product_id'];
                        $result2 = mysqli_query($connection, $productQuery);
                        $row2 = mysqli_fetch_assoc($result2);

                        $sizeIdQuery = "select distinct size_id from product_stock where product_id = " . $row1['product_id'];
                        $sizeIdResult = mysqli_query($connection, $stockQuery1);

                        $stockQuery2 = "select distinct color_id from product_stock where product_id = " . $row1['product_id'];
                        $result4 = mysqli_query($connection, $stockQuery2);

                        echo "
                                <div class='wishlist-card'>
                                        <img src='../uploadedImages/". $row2['image_url'] . "'>
                                        <div>". $row2['product_name'] . "</div>
                                        <div>Rs. ". $row2['price'] . "</div>
                                        <form action='wishlist.php?product_id=". $row1['product_id'] . "' method='post'>
                                            <div class='input'>"; 
                                            $row3 = mysqli_fetch_assoc($result3);
                                            if ($row3['size_id']!=5) {
                                                echo "<select name='size' id='size'><option value='select'>Select size</option>";
                                                do {
                                                    $sizeQuery = "select * from product_size where size_id = " . $row3['size_id'];
                                                    $result5 = mysqli_query($connection, $sizeQuery);
                                                    $row4 = mysqli_fetch_assoc($result5);
                                                    echo "<option value='" . $row4['size_name'] . "'>" . $row4['size_name'] . "</option>";
                                                    
                                                }while($row3 = mysqli_fetch_assoc($result3));
                                                echo "</select>";
                                            }

                                            
                                            echo "<select name='color' id='color'><option value='select'>Select color</option>";
                                            while($row5 = mysqli_fetch_assoc($result4)){
                                                $colorQuery = "select * from product_color where color_id = " . $row5['color_id'];
                                                $result6 = mysqli_query($connection, $colorQuery);
                                                $row6 = mysqli_fetch_assoc($result6);
                                                echo "<option value='" . $row6['color_name'] . "'>" . $row6['color_name'] . "</option>";
                                                
                                             }
                                            echo "</select>";


                                            echo "
                                                </div>
                                                <button class='remove-item'><i class='fa fa-times' aria-hidden='true'></i></button>
                                                <input type='number' name='quantity' value='0'>
                                                <button type='submit' name='addToCart'>Add to cart</button>
                                        </form>
                                </div>
                        ";
                    }
                    echo "</div>";
                }
                else {
                    echo "<div class='empty-order-history'>
                    <h3>Your wishlist is empty!</h3>
                    <p>Seems like you don't have wishes here.<br> Make a wish!</p>
                    <button>Start shopping</button>
                </div>";
                }
            ?>
            
    </div>


    
</body>
</html>




