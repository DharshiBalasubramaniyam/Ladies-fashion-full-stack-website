<?php
   //include 'config.php';
   include('database/dbconnection.php');

   session_start();
   $_SESSION['user_id']=1;
   $_SESSION["search_word"]="lehe";
   
   // wishlist start
   // wishlist start
   if(isset($_POST['add_to_wishlist'])){
      
         $product_id = $_POST['add_to_wishlist'];

      if(isset($_SESSION['user_id'])) {
         $user_id = $_SESSION['user_id'];
        // $user_id = 1;
         }
      else {
          $_SESSION['redirect_url'] = $_SERVER['HTTP_REFERER'];
          header('Location: login.php');
          exit();
      }

      
      $check_wishlist = mysqli_query($connection, "SELECT * FROM `wishlist` WHERE product_id = '$product_id' AND
                                          user_id = '$user_id'") or die('query failed');
   
      if(mysqli_num_rows($check_wishlist) > 0){
           $message[] = 'Already added to wishlist!';
      } 
      else {
           $insert_wishlist_query = mysqli_query($connection, "INSERT INTO wishlist (user_id, product_id) VALUES ($user_id, $product_id)")
                             or die('Wishlist insertion failed');
           $message[] = 'Product added to wishlist!';
         //   have to redirect to same page after adding... how?
         header("Location: search_bar.php");
        exit();
      }
  }
   else {
      $message[] = 'Invalid request. Product ID not set.';
   }

//wishlist end

?> 

<!DOCTYPE html>
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>search bar</title>
   <link rel="stylesheet" href="css/index.css">
   <link rel="stylesheet" href="css/products.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <?php
    // include 'header.php';
    ?>

   <section class="sub_header">
    <div class="flex">
        <a href="#" class="sub-logo"><?php echo $_SESSION["search_word"]; ?></a>
    </div>

   </section>

   <?php
   if(isset( $_SESSION["search_word"])){
    
  ?>
    
   <section class="products">
       <h1 class="title"><?php  ?></h1>  
   <div class="box-container">
      <?php  
         $searchWord = mysqli_real_escape_string($connection, $_SESSION["search_word"]);
         $select_products = mysqli_query($connection, "SELECT p.* FROM products p 
             JOIN product_category pc ON p.product_id = pc.product_id
             JOIN category c ON pc.category_id = c.category_id 
             WHERE c.type LIKE '%$searchWord%'") 
             or die('query failed');
         
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_product = mysqli_fetch_assoc($select_products)){
                $products[] =$fetch_product;
        ?>
            
         <form action="search_bar.php" method="post" class="box"  >
         <div class=pro data-name="<?php echo $fetch_product['product_id']; ?>">
         <img class="image" src="uploaded_img/<?php echo $fetch_product['image_url']; ?>" alt="">
            <div class="name"><b><?php echo $fetch_product['product_name']; ?></b></div>
            <div class="price">Rs <?php echo $fetch_product['price']; ?>/-</div>
            </div>
            <button type="submit" name="add_to_cart" class="cart-btn" value='<?php echo $fetch_product['product_id']; ?>'>
               <i class="fa-solid fa-cart-shopping"></i>Add to Cart</button>
            <button type="submit" name="add_to_wishlist" class="wishlist-btn" value='<?php echo $fetch_product['product_id']; ?>'>
               <i class="fa-regular fa-heart"></i> </button>
         </form>
     
       <?php
           }
       ?> 

       <div class="products-preview">
           <?php foreach ($products as $product) { ?>
               <div class="preview" data-target="<?php echo $product['product_id']; ?>">
                   <i class="fas fa-times"></i>
                   <img class="image" src="uploaded_img/<?php echo $product['image_url']; ?>" alt="">
                   <div class="name"><b><?php echo $product['product_name']; ?></b></div>  
                   <div class="description">
                     <?php $description = $product['description']; 
                           $descriptionWithLineBreaks = nl2br($description);
                           echo $descriptionWithLineBreaks; 
                     ?>
                  </div>
                   <div class="price">Rs <?php echo $product['price']; ?>/-</div>

                   <form action="search_bar.php" method="post">
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" id="quantity" value="1" min="1" required>
            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
            <input type="hidden" name="price" value="<?php echo $product['price']; ?>">

            <!-- Size -->
                <label for="size">Size:</label>
                <select name="size" id="size" required>
                    
                    <?php
                    $size_query = mysqli_query($connection, "SELECT * FROM product_size ");
                    while ($size = mysqli_fetch_assoc($size_query)) {
                        echo '<option value="' . $size['size_id'] . '">' . $size['size_name'] . '</option>';
                    }
                    ?>
                </select>
                
            <!-- Color -->
            <label for="color">Color:</label>
                <select name="color" id="color" required>
                    <?php
                        $color_query = mysqli_query($connection, "SELECT * FROM product_color");
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
 <?php echo "No products mutch"; ?>
  
   
   ?>
    

     <?php
        }
        //  include 'footer.php';
     ?>
   
     <script src="js/products.js"></script>
</body>
</html>