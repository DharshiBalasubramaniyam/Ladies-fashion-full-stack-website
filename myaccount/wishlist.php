<?php

    include('../main-header/header.php');
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        header('location:../account/login.php');exit();
    }

    $product_id = $size = $color = $qty = "";

    $wishlistQuery = "select * from wishlist w, products p where w.product_id = p.product_id and user_id = " . $_SESSION['user_id']; 
    $wisthlist_res = mysqli_query($connection, $wishlistQuery);

    if (isset($_POST['remove'])) {
        $wishlist_id = $_GET['wishlist_id'];
        removeItemFromWish($connection, $wishlist_id);
        header('location:wishlist.php');
    }

    if (isset($_POST['add_to_cart'])) {

        if(isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        }
        else {
            header('Location: ../account/login.php');
            exit();
        }

        if ($_POST['size'] != 'select' && $_POST['color'] != 'select' && $_POST['quantity']!=0) {
            $product_id = $_POST['product_id'];
            $quantity = $_POST['quantity'];
            $size_id = $_POST['size'];
            $color_id = $_POST['color'];
            $price = $_POST['price'];
            $amount = $quantity*$price;

            $check_qty_sql = "select stock_qty from product_stock where product_id = " . $product_id . " and color_id = " . $color_id . " and size_id = " . $size_id ;
            $check_qty_res = mysqli_query($connection, $check_qty_sql);

            $available_qty = mysqli_fetch_assoc($check_qty_res)['stock_qty'];

            if ($available_qty < $quantity) {
                echo "<script>alert('Sorry. We have no such quantity!')</script>";
            }else {
                $existing_item_query = mysqli_query($connection, "SELECT * FROM shopping_cart WHERE user_id = '$user_id' AND 
                                                            product_id = '$product_id' AND color_id = '$color_id' AND size_id = '$size_id'");
                $existing_item = mysqli_fetch_assoc($existing_item_query);
            
                if ($existing_item) {
                    $new_quantity = $existing_item['quantity'] + $quantity;
                    $amount = $new_quantity*$price;
                    mysqli_query($connection, "UPDATE shopping_cart SET quantity = '$new_quantity', amount = $amount WHERE cart_item_id = '{$existing_item['cart_item_id']}'");
                    $_SESSION['message'] = "Product already in your cart! So that the quantity is updated.";
                    echo "<script>window.location.href = 'wishlist.php';</script>";
                } 
                else {
                    $query = "INSERT INTO shopping_cart (user_id, product_id, quantity, color_id, size_id, amount) 
                        VALUES ($user_id,$product_id,$quantity, $color_id,$size_id,$amount)" ;
                    mysqli_query($connection, $query);
                    echo $query;
                    $_SESSION['message'] = "Product successfully added to your cart!";

                    echo "<script>window.location.href = 'wishlist.php';</script>";
                    
                }
            
                exit();
            }
        
        } 
            
        else {
            echo "<script>alert('Please fill all the fields size, color, quantity!');</script>";
        }
    }

    if(isset($_POST['msg'])){
        unset($_SESSION['message']);
    }

    // display messages
    if(isset($_SESSION['message'])){
            echo '
                <form class="message-wrapper" action="wishlist.php" method="post">
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
    <title>My wishlist - Pink Pearl</title>
</head>
<body>

    <div class="myaccount">
        <div class="header">
            <p>Welcome, <?php echo getUserName($connection); ?>!</p>
            <h1>Your Favourite Items</h1>
            <div class="options">
                <span><a href="rewards.php">Rewards</a></span> 
                <span><a href="orders.php">Orders</a></span> 
                <span><a href="wishlist.php" class="active">Wishlist</a></span>
                <span><a href="cart.php">Cart</a></span>
                <span><a href="review.php">Reviews</a></span>
                <span><a href="profile.php">Profile</a></span>
            </div>
        </div>
        
            <?php
                if (mysqli_num_rows($wisthlist_res) > 0) {
                    echo "<div class='content wishlist-wrapper'>";
                    while($wishlist_item = mysqli_fetch_array($wisthlist_res)) {
                        $wishlist[] =$wishlist_item;

                        echo "
                                <div class='wishlist-card'>
                                        <img src='../uploadedImages/". $wishlist_item['image_url'] . "'>
                                        <div style='margin-top:7px;'>". $wishlist_item['product_name'] . "</div>
                                        <div style='text-align:center;font-weight:600;'>Rs. ". $wishlist_item['price'] . "</div>
                                        <form action='wishlist.php?wishlist_id=" . $wishlist_item['wishlist_id'] . "' method='post'>
                                            <button class='remove-item' type='submit' name='remove'><i class='fa fa-times' aria-hidden='true'></i></button>
                                        </form>
                                        <button class='cart-btn' name=" . $wishlist_item['product_id'] . "><i class='fa fa-shopping-cart' aria-hidden='true'></i>Add to cart</button>
                                </div>
                            ";
                    }
                    echo "</div>";
            ?>
                    <div class="products-preview">
                        <?php foreach ($wishlist as $product) { ?>
                            <div class="preview" data-target="<?php echo $product['product_id']; ?>">
                                <i class="fas fa-times"></i>
                                <img class="image" src="../uploadedImages/<?php echo $product['image_url']; ?>" alt="">
                                <div class="name"><b><?php echo $product['product_name']; ?></b></div>  
                                <div class="description">
                                    <?php
                                    $description = $product['description']; 
                                    $descriptionWithLineBreaks = nl2br($description);
                                    echo $descriptionWithLineBreaks; 
                                    ?>
                                </div>
                                <div class="price">Rs <?php echo $product['price']; ?>/-</div> 

                                <form action="wishlist.php" method="post">
                                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                    <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                                    
                                    <?php

                                        $sizeIdQuery = "select distinct size_id from product_stock where product_id = " . $product['product_id'];
                                        $sizeIdResult = mysqli_query($connection, $sizeIdQuery );

                                        if (mysqli_num_rows($sizeIdResult)>0) {
                                            $sizeId = mysqli_fetch_assoc($sizeIdResult);
                                        }
                                        else {
                                            break;
                                        }
                                        
                                        if ($sizeId['size_id']==3) {
                                           echo "<input type='hidden' name='size' value='3'>";
                                        }else {
                                            echo "<select name='size' id='size' required><option value='select'>Select size</option>";
                                            do {
                                                $sizeQuery = "select * from product_size where size_id = " . $sizeId['size_id'];
                                                $sizeResult = mysqli_query($connection, $sizeQuery);
                                                $size = mysqli_fetch_assoc($sizeResult);
                                                echo "<option value='" . $size['size_id'] . "'>" . $size['size_name'] . "</option>";
                                                                        
                                            }while($sizeId = mysqli_fetch_assoc($sizeIdResult));
                                            echo "</select>";
                                        }
                                    ?>

                                    <?php
                                        $colorIdQuery = "select distinct color_id from product_stock where product_id = " . $product['product_id'];
                                        $colorIdResult = mysqli_query($connection, $colorIdQuery );

                                        if(mysqli_num_rows($colorIdResult)>0) {
                                            $colorId = mysqli_fetch_assoc($colorIdResult);
                                        }else {
                                            break;
                                        }

                                        if ($colorId['color_id']==9) {
                                            echo "<input type='hidden' name='color' value='9'>";
                                        }else {
                                            echo "<select name='color' id='color' style='margin:10px auto;' required><option value='select'>Select color</option>";
                                            do {
                                                $colorQuery = "select * from product_color where color_id = " . $colorId['color_id'];
                                                $colorResult = mysqli_query($connection, $colorQuery);
                                                $color = mysqli_fetch_assoc($colorResult);
                                                echo "<option value='" . $color['color_id'] . "'>" . $color['color_name'] . "</option>";
                                                                        
                                            }while($colorId = mysqli_fetch_assoc($colorIdResult));
                                            echo "</select>";
                                        }
                                    ?>
                                    <input type="number" name="quantity" id="quantity" value="0" min="0" required>

                                    <button type="submit" name="add_to_cart" class="cart_btn">
                                        <i class="fa-solid fa-cart-shopping"></i>Add to Cart
                                    </button>
                                </form>
                            </div>
                        <?php } ?>
                    </div> 
            <?php
                }
                else {
                    echo "<div class='empty-order-history'>
                            <h3>Your wishlist is empty!</h3>
                            <p>Seems like you don't have wishes here.<br> Make a wish!</p>
                            <a href='../shop/index.php'><button>Start shopping</button></a>
                        </div>";
                }
            ?>
    </div>

    <?php include('../shop/footer.php') ?>

    <script src="myaccount.js"></script>

</body>
</html>

<?php
    function removeItemFromWish($connection, $id){
        $removeQuery = "delete from wishlist where wishlist_id = " . $id;
        mysqli_query($connection, $removeQuery);

    }
    function getUserName($connection) {

        $query = 'select username from users where user_id = ' . $_SESSION['user_id'];
        $result = mysqli_query($connection, $query);

        $name = mysqli_fetch_assoc($result)['username'];

        return $name;

    }
?>