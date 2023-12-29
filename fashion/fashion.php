<?php
   include '../database/dbconnection.php';

   include '../main-header/header.php';

   if (isset($_GET['main'])) {
         //    fetch main cat
        $main_category_id = $_GET['main'];

        // fetching sub cat from category for above main cat
        $category_query = mysqli_query($connection, "SELECT * FROM category where main_category_id=$main_category_id")
                    or die('Category query failed');
        $categories = mysqli_fetch_all($category_query, MYSQLI_ASSOC); 

        $main_category_res = mysqli_query($connection, "select category_name from main_category where main_category_id=$main_category_id")
                       or die('Category query failed');
        $main_category_name = mysqli_fetch_assoc($main_category_res)['category_name'];

        $selected_sub_category_id = $categories[0]['sub_category_id'];
        $sub_catagory_name = $categories[0]['category_name'];
   }
    if(isset($_GET['sub'])){
        $selected_sub_category_id = $_GET['sub'];
        $sub_category_res = mysqli_query($connection, "select * from category where sub_category_id = $selected_sub_category_id");
        $category_data = mysqli_fetch_assoc($sub_category_res);
        $sub_catagory_name = $category_data['category_name'];
        $main_category_id = $category_data['main_category_id'];

        $category_query = mysqli_query($connection, "SELECT * FROM category where main_category_id=$main_category_id")
                    or die('Category query failed');
        $categories = mysqli_fetch_all($category_query, MYSQLI_ASSOC); 

        $main_category_res = mysqli_query($connection, "select category_name from main_category where main_category_id=$main_category_id")
                       or die('Category query failed');
        $main_category_name = mysqli_fetch_assoc($main_category_res)['category_name'];

    }
    

   // wishlist start
    if(isset($_POST['add_to_wishlist'])){
        if(isset($_POST['product_id'])) {
            $product_id = $_POST['product_id'];

            if(isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
            }
            else {
                $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
                header('Location: ../account/login.php');
                exit();
            }

            $check_wishlist = mysqli_query($connection, "SELECT * FROM `wishlist` WHERE product_id = '$product_id' AND
                                                user_id = '$user_id'") or die('query failed');
        
            if(mysqli_num_rows($check_wishlist) > 0){
                $_SESSION['message'] = "Product already in your to wishlist!";
            } 
            else {
                $insert_wishlist_query = mysqli_query($connection, "INSERT INTO wishlist (user_id, product_id) VALUES ($user_id, $product_id)")
                                    or die('Wishlist insertion failed');
                $_SESSION['message'] = "Product successfully added to wishlist!";
            }
        }
        else {
            $message[] = 'Invalid request. Product ID not set.';
        }
    }
// wishlist end

// cart  begin
    if (isset($_POST['add_to_cart'])) {

        if(isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        }
        else {
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
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
                    echo "<script>window.location.href = 'fashion.php?sub=" . $selected_sub_category_id . "'</script>";
                } 
                else {
                    $query = "INSERT INTO shopping_cart (user_id, product_id, quantity, color_id, size_id, amount) 
                        VALUES ($user_id,$product_id,$quantity, $color_id,$size_id,$amount)" ;
                    mysqli_query($connection, $query);
                    $_SESSION['message'] = "Product successfully added to your cart!";
                    echo "<script>window.location.href = 'fashion.php?sub=" . $selected_sub_category_id . "'</script>";
                    
                    
                }
            
                exit();
            }
        
            
        } 
            
        else {
            $_SESSION['message'] = "Please fill all the fields size, color, quantity!";
        }
    } 

    if(isset($_POST['msg'])){
        unset($_SESSION['message']);
    }

    if(isset($_SESSION['message'])){
            echo '
                <form class="message-wrapper" action="fashion.php?sub=' . $selected_sub_category_id  . '" method="post">
                    <div class="message">
                        <span>' . $_SESSION['message'] . '</span>
                        <button class="fas fa-times" name="msg"></button>
                    </div>
                </form>
            
            ';
    }
?> 

<!DOCTYPE html>
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shop - Pink pearl</title>
   <link rel="stylesheet" href="../index.css">
   <link rel="stylesheet" href="products.css">
   <link rel="stylesheet" href="../shop//style.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

    <?php
        if(isset($selected_sub_category_id)){
    ?>

    <section class="sub_header">
        <div class="flex">
            <a href="fashion.php?main=<?php echo $main_category_id ?>" class="sub-logo"><?php echo $main_category_name ?></a>
        </div>

        <div class="items">
            <div id="menu-btn" class="fas fa-bars"></div> 
            <div>
                <?php 
                    
                    foreach ($categories as $category) { ?>
                        <a href='fashion.php?sub=<?php echo $category['sub_category_id']?>'>
                            <button class="<?php if($category['sub_category_id'] == $selected_sub_category_id) echo 'active'; else ''; ?>">
                                <?php echo $category['category_name']; ?>
                            </button>
                        </a>
                <?php } ?>
            </div>
        </div>   
   </section>

    <section class="products">
            <h1 class="title"><?php echo $sub_catagory_name; ?></h1>  
            <div class="box-container">
            <?php  
                $select_products = mysqli_query($connection, "SELECT p.* FROM products p 
                                                                JOIN product_category pc ON p.product_id = pc.product_id
                                                                where pc.category_id = $selected_sub_category_id")
                                or die('query failed');
                
                if(mysqli_num_rows($select_products) > 0){
                    while($fetch_product = mysqli_fetch_assoc($select_products)){
                        $products[] =$fetch_product;
            ?>

                        <form action="fashion.php?sub=<?php echo $selected_sub_category_id ?>" method="post" class="box" data-name="<?php echo $fetch_product['product_id']; ?>">
                            <img class="image" src="../uploadedImages/<?php echo $fetch_product['image_url']; ?>">
                            
                            <div class="name"><b><?php echo $fetch_product['product_name']; ?></b></div>
                            
                            <div class="price">Rs <?php echo $fetch_product['price']; ?>/-</div>
                            
                            <input type="hidden" name="product_id" value="<?php echo $fetch_product['product_id']; ?>">
                            
                            <button type="submit"  name="<?php echo $fetch_product['product_id']; ?>" class="cart-btn">
                                <i class="fa-solid fa-cart-shopping"></i>Add to Cart
                            </button>
                            
                            <button type="submit" name="add_to_wishlist" class="wishlist-btn">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                        </form>

                    <?php   } ?>

                    <!-- pop up -->
                    <div class="products-preview">
                        <?php foreach ($products as $product) { ?>
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

                                <form action="fashion.php?sub=<?php echo $selected_sub_category_id ?>" method="post">
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

                                    <div class="qty-input-wrapper">
                                        <div onclick="decrease(this)">-</div>
                                        <input type="number" name="quantity" id="quantity" value="0" min="0" required>
                                        <div onclick="increase(this)">+</div>
                                    </div>

                                    <button type="submit" name="add_to_cart" class="cart_btn">
                                        <i class="fa-solid fa-cart-shopping"></i>Add to Cart
                                    </button>
                                </form>
                            </div>
                        <?php } ?>
                    </div> 
 
                <?php 
                } else{
                        echo '<p class="empty">Sorry! No products found.<br>You can try our different product.</p>';
                }
                ?>
            </div>
        </section>


    <?php } else { 
    
            echo "<script>window.location.href = '../shop/index.php'</script>";

        }
        include('../shop/footer.php');
     ?>


   
     <script src="products.js"></script>
</body>
</html>