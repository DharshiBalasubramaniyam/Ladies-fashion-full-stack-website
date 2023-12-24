<?php
    include('../main-header/header.php');

    if (!isset($_SESSION['user_id'])) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        header('location:../account/login.php');exit();
    }

    $cartQuery = "select * from shopping_cart where user_id = " . $_SESSION['user_id'];
    $result = mysqli_query($connection, $cartQuery);

    if (isset($_POST['updateQty'])) {
        $newQty = $_POST['quantity'];
        $cart_item_id = $_GET['cart_item_id'];

        if (checkQty($connection, $newQty, $cart_item_id)) {
            updateQty($connection, $newQty, $cart_item_id);
            updateAmount($connection, $newQty, $cart_item_id);
            header('location:cart.php');

        }else {
            echo "<script>alert('Sorry. We have no such quantity!')</script>";
        }
        
    }

    if (isset($_POST['remove'])) {
        $cart_item_id = $_GET['cart_item_id'];
        removeItemFromCart($connection, $cart_item_id);
        header('location:cart.php');
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../shop/style.css">
    <title>My cart - Pink Pearl</title>
</head>
<body>

    <div class="myaccount">
        <div class="header">
            <p>Welcome, <?php echo getUserName($connection); ?>!</p>
            <h1>Your Cart Items</h1>
            <div class="options">
                <span><a href="rewards.php">Rewards</a></span> 
                <span><a href="orders.php">Orders</a></span> 
                <span><a href="wishlist.php">Wishlist</a></span>
                <span><a href="cart.php" class="active">Cart</a></span>
                <span><a href="review.php">Reviews</a></span>
                <span><a href="profile.php">Profile</a></span>
            </div>
        </div>

        <div class="content">
            

            <?php
                if (mysqli_num_rows($result) > 0) {
                    echo "<div class='total'>
                            <span>Sub Total: Rs. " . getSubtotal($connection, $_SESSION['user_id']) . "</span> <a href='checkout.php'><button>Checkout</button></a>
                        </div>";

                    echo "<div class='cart-items-wrapper'>";

                    while($row = mysqli_fetch_assoc($result)) {
                        $productQuery = "select * from products where product_id = " . $row['product_id'];
                        $productDataResult = mysqli_query($connection, $productQuery);
                        $productData = mysqli_fetch_assoc($productDataResult);

                        echo "<div class='cart-card'>
                                <img src='../uploadedImages/" . $productData['image_url'] . "'>
                                <h3>" . $productData['product_name'] . "<br>" . getColor($connection, $row['color_id']);
                        
                        $size = getSize($connection, $row['size_id']);
                        
                        if ($size != 'none') {
                            echo " | " . $size;
                        }
                        
                        
                        echo "</h3>
                                <form action='cart.php?cart_item_id=" . $row['cart_item_id'] . "' method='post'>
                                    <input type='number' name='quantity' value='" . $row['quantity'] . "' oninput='handleUpdate(" . $row['quantity'] . ", this)' min='1'> &nbsp; x " . $productData['price'] . "
                                    <button type='submit' name='updateQty' id='update'>update</button>
                                    <button class='remove-item' type='submit' name='remove'><i class='fa fa-times' aria-hidden='true'></i></button>
                                </form>
                                <div>Amount: Rs. " . $row['amount'] . "</div>
                            </div> ";
                    }
                    echo "</div>";

                    
                }else {
                    echo "<div class='empty-order-history'>
                            <h3>Your cart is empty :(</h3>
                            <p>Seems like you haven't added anything to your cart yet.<br> Add something to make me happy :)</p>
                            <a href='../shop/index.php'><button>Start shopping</button></a>
                        </div>";
                }

            ?>
        </div>



        
    </div>

    <?php include('../shop/footer.php') ?>


    <script>
        function handleUpdate(n, e) {

            if (e.value != n) {
                e.nextElementSibling.style.opacity = '1';
                e.nextElementSibling.style.pointerEvents = 'auto';
            }else {
                e.nextElementSibling.style.opacity = '0.2';
                e.nextElementSibling.style.pointerEvents = 'none';
            }
            
        } 
    </script>
</body>
</html>

<?php
    function getSubtotal($connection, $id) {
        $totalQuery = "select sum(amount) from shopping_cart where user_id = " . $id;
        $totalResult = mysqli_query($connection, $totalQuery);

        $subtotal = mysqli_fetch_assoc($totalResult)['sum(amount)'];

        return $subtotal;
    }

    function updateQty($connection, $qty, $id) {
        $updateQuery = "update shopping_cart set quantity = " . $qty . " where cart_item_id = " . $id;
        mysqli_query($connection, $updateQuery);
    }

    function updateAmount($connection, $qty, $id) {
        $query1 = "select product_id from shopping_cart where cart_item_id = " . $id;
        $result1 = mysqli_query($connection, $query1);
        $product_id = mysqli_fetch_assoc($result1)['product_id'];

        $query2 = "select price from products where product_id = " . $product_id;
        $result2 = mysqli_query($connection, $query2);

        $price = mysqli_fetch_assoc($result2)['price'];

        $newAmount = $qty * $price;

        $updateQuery = "update shopping_cart set amount = " . $newAmount . " where cart_item_id = " . $id;
        mysqli_query($connection, $updateQuery);
    }

    function removeItemFromCart($connection, $id){
        $removeQuery = "delete from shopping_cart where cart_item_id = " . $id;
        mysqli_query($connection, $removeQuery);

    }

    function getColor($connection, $id){
        $query = "select color_name from product_color where color_id = " . $id;
        $result = mysqli_query($connection, $query);

        return mysqli_fetch_assoc($result)['color_name'];
    }

    function getSize($connection, $id){
        $query = "select size_name from product_size where size_id = " . $id;
        $result = mysqli_query($connection, $query);

        return mysqli_fetch_assoc($result)['size_name'];
    }

    function checkQty($connection, $newQty, $id){
        $query1 = "select * from shopping_cart where cart_item_id = " . $id;
        $result1 = mysqli_query($connection, $query1);
        $row = mysqli_fetch_assoc($result1);

        $query2 = "select stock_qty from product_stock where product_id = " . $row['product_id'] . " and color_id = " . $row['color_id'] . " and size_id = " . $row['size_id'] ;
        $result2 = mysqli_query($connection, $query2);

        $stock = mysqli_fetch_assoc($result2)['stock_qty'];
        

        return $stock > $newQty;


    }
    function getUserName($connection) {

        $query = 'select username from users where user_id = ' . $_SESSION['user_id'];
        $result = mysqli_query($connection, $query);

        $name = mysqli_fetch_assoc($result)['username'];

        return $name;

    }
?>