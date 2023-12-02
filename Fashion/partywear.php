<?php
   include 'config.php';
   session_start();

   // fetching types from category
   $category_query = mysqli_query($conn, "SELECT DISTINCT type FROM category where main_category='party wear'")
                      or die('Category query failed');
   $categories = mysqli_fetch_all($category_query, MYSQLI_ASSOC);  

   // wishlist start
   if(isset($_POST['add_to_wishlist'])){
      if(isset($_POST['product_id'])) {
         $product_id = $_POST['product_id'];

      if(isset($_SESSION['user_id'])) {
          $user_id = $_SESSION['user_id'];
         }
      else {
          $_SESSION['redirect_url'] = $_SERVER['HTTP_REFERER'];
          header('Location: login.php');
          exit();
      }

      // $user_id = 4;
      $check_wishlist = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE product_id = '$product_id' AND
                                          user_id = '$user_id'") or die('query failed');
   
      if(mysqli_num_rows($check_wishlist) > 0){
           $message[] = 'Already added to wishlist!';
      } 
      else {
           $insert_wishlist_query = mysqli_query($conn, "INSERT INTO wishlist (user_id, product_id) VALUES ($user_id, $product_id)")
                             or die('Wishlist insertion failed');
           $message[] = 'Product added to wishlist!';
         //   have to redirect to same page after adding... how?
      }
  }
   else {
      $message[] = 'Invalid request. Product ID not set.';
   }
}
// wishlist end

// cart  begin
if (isset($_POST['add_to_cart'])) {
    if (isset($_POST['product_id'], $_POST['quantity'],  $_POST['size'],$_POST['color'], $_POST['price'])) {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        $size_id = $_POST['size'];
        $color_id = $_POST['color'];
        $price = $_POST['price'];
        $amount = $quantity*$price;
         
          
           
    
          //  if (isset($_SESSION['user_id'])) {
          //      $user_id = $_SESSION['user_id'];
     //  }
     //else {
          //      $_SESSION['redirect_url'] = $_SERVER['HTTP_REFERER'];
          //      header('Location: login.php');
          //      exit();
          //  }
           $user_id = 4;
    
               $existing_item_query = mysqli_query($conn, "SELECT * FROM shopping_cart WHERE user_id = '$user_id' AND 
                                                           product_id = '$product_id' AND color_id = '$color_id' AND size_id = '$size_id'");
               $existing_item = mysqli_fetch_assoc($existing_item_query);
    
               if ($existing_item) {
                   $new_quantity = $existing_item['quantity'] + $quantity;
                   mysqli_query($conn, "UPDATE shopping_cart SET quantity = '$new_quantity' WHERE cart_item_id = '{$existing_item['cart_item_id']}'");
               } 
               else {
                   mysqli_query($conn, "INSERT INTO shopping_cart (user_id, product_id, quantity, color_id, size_id, amount) 
                                        VALUES ($user_id,$product_id,$quantity, $color_id,$size_id,$amount)");
               }
    
               // Redirect back to the same page
               header('Location: accessories.php');
               exit();
          } 
          
        else {
           echo 'Invalid request. Required parameters are missing.';
       }
    } 

// display messages
if(isset($message)){
   foreach($message as $message){
      echo '
        <div class="message">
           <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove()"></i>
         </div>
      ';
   }
}
?> 


<!DOCTYPE html>
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Accessories</title>
   <link rel="stylesheet" href="css/index.css">
   <link rel="stylesheet" href="css/products.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <?php
    // include 'header.php';
    ?>

<!-- sub header, items fetched from the database -->
   <section class="sub_header">
    <div class="flex">
        <a href="#" class="sub-logo">party wear</a>
    </div>

    <div class="items">
      <div id="menu-btn" class="fas fa-bars"></div> 
        <form action="" method="post">
            <?php foreach ($categories as $category) { ?>
                    <button type="submit" name="category" value="<?php echo $category['type']; ?>" class="option_btn">
                    <?php echo $category['type']; ?></button>
            <?php } ?>
        </form>
      </div>   
   </section>
   <!-- Sub-header end-->

   <!-- Fetched the category selected by user -->
   <?php
     if(isset($_POST['category'])){
      $selectedCategory = $_POST['category'];
   ?>
    
    <!-- displaying particular category products -->
   <section class="products">
       <h1 class="title"><?php echo $selectedCategory; ?></h1>  
   <div class="box-container">
      <?php  
         $select_products = mysqli_query($conn, "SELECT p.* FROM products p JOIN product_category pc ON p.product_id = pc.product_id
         JOIN category c ON pc.category_id = c.category_id WHERE c.main_category = 'Accessories' AND c.type = '$selectedCategory'") 
         or die('query failed');
         
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_product = mysqli_fetch_assoc($select_products)){
                $products[] =$fetch_product;
        ?>

         <form action="accessories.php" method="post" class="box" data-name="<?php echo $fetch_product['product_id']; ?>">
            <img class="image" src="uploaded_img/<?php echo $fetch_product['image_url']; ?>" alt="">
            <div class="name"><b><?php echo $fetch_product['product_name']; ?></b></div>
            <div class="price">Rs <?php echo $fetch_product['price']; ?>/-</div>
            <input type="hidden" name="product_id" value="<?php echo $fetch_product['product_id']; ?>">
            <button type="submit"  name="<?php echo $fetch_product['product_id']; ?>" class="cart-btn" value="<?php echo $fetch_product['product_id']; ?>">
               <i class="fa-solid fa-cart-shopping"></i>Add to Cart</button>
            <button type="submit" name="add_to_wishlist" class="wishlist-btn">
               <i class="fa-regular fa-heart"></i> </button>
         </form>
     
       <?php
           }
       ?> 

     <!-- pop up while clicking the product name-->    
     <div class="products-preview">
     <?php foreach ($products as $product) { ?>
    <div class="preview" data-target="<?php echo $product['product_id']; ?>">
        <i class="fas fa-times"></i>
        <img class="image" src="uploaded_img/<?php echo $product['image_url']; ?>" alt="">
        <div class="name"><b><?php echo $product['product_name']; ?></b></div>  
        <div class="description">
            <?php
            $description = $product['description']; 
            $descriptionWithLineBreaks = nl2br($description);
            echo $descriptionWithLineBreaks; 
            ?>
        </div>
        <div class="price">Rs <?php echo $product['price']; ?>/-</div> 

        <form action="accessories.php" method="post">
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" id="quantity" value="1" min="1" required>
            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
            <input type="hidden" name="price" value="<?php echo $product['price']; ?>">

            <!-- Size -->
                <label for="size">Size:</label>
                <select name="size" id="size" required>
                    
                    <?php
                    $size_query = mysqli_query($conn, "SELECT * FROM product_size ps 
                    JOIN category c ON ps.category_id = c.category_id
                    WHERE c.type = '$selectedCategory'");
                    while ($size = mysqli_fetch_assoc($size_query)) {
                        echo '<option value="' . $size['size_id'] . '">' . $size['size_name'] . '</option>';
                    }
                    ?>
                </select>
                
            <!-- Color -->
            <label for="color">Color:</label>
                <select name="color" id="color" required>
                    <?php
                        $color_query = mysqli_query($conn, "SELECT * FROM product_color");
                        while ($color = mysqli_fetch_assoc($color_query)) {
                            echo '<option value="' . $color['color_id'] . '">' . $color['color_name'] . '</option>';
                        }
                    ?>
                </select>
            
            <button type="submit" name="add_to_cart" class="cart_btn">
                <i class="fa-solid fa-cart-shopping"></i>Add to Cart</button>
        </form>
    </div>
<?php } ?>
      </div> 
        
       <?php 
          } else{
                echo '<p class="empty">No products added yet!</p>';
          }
       ?>
   </div>
   </section>
     
    <?php
       }
     else{
     ?> 
 
 <section class="Categories">
     <form action="" method="post" class="box">
      <img class="image" src="uploaded_img/Sarees.jpeg" alt="">
      <button type="submit" name="category" value="Sarees" class="option_btn">Sarees</button>
     </form>
     <form action="" method="post" class="box">
      <img class="image" src="uploaded_img/PartyFrocks.jpg" alt="">
      <button type="submit" name="category" value="Frocks" class="option_btn">Frocks</button>
     </form>
     <form action="" method="post" class="box">
      <img class="image" src="uploaded_img/Lehengas.jpeg" alt="">
      <button type="submit" name="category" value="Lehengas" class="option_btn">Lehengas</button>
     </form>
     <form action="" method="post" class="box">
      <img class="image" src="uploaded_img/Kurtis.jpeg" alt="">
      <button type="submit" name="category" value="Kurties" class="option_btn">Kurties</button>
     </form>  
     </section>

     <?php
   }
        //  include 'footer.php';
     ?>
   
     <script src="js/products.js"></script>
</body>
</html>