<?php
include('../includes/dbconnection.php');
if(isset($_POST['insert_product']))
{
    $product_title=$_POST['product_name'];
    $product_description=$_POST['product_description'];
    $product_category=$_POST['product_categories'];
    $product_price=$_POST['product_price'];
    $product_status="true";
    //accessing image
    $product_image=$_FILES['product_image']['name'];

    //accessing image temp name
    $temp_image=$_FILES['product_image']['tmp_name'];

    //checking empty condition
    if( $product_title=='' or  $product_description=='' or $product_category==''or $product_price=='' or
        $product_image=='' or $temp_image=='')
        {
            echo "<script>alert('Please fill all the available fields')</script>";
            exit();
        }
        else
        {
          move_uploaded_file($temp_image,"./product_images/$product_image");

          //insert query
          $insert_products="insert into products(product_name,description,category_id,product_image,price,status) values
                            ('$product_title','$product_description','$product_category','$product_image','$product_price',
                            '$product_status')";
          $result_query=mysqli_query($connection,$insert_products);
          if($result_query)
          {
            echo"<script>alert('Successfully inserted the products')</script>";
          }
        }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Products</title>
    <link rel="stylesheet" href="style.css" type="text/css">
    <style>
        .container{
        justify-content: center;
        text-align:center;
        background-color:whitesmoke;  
        margin-left:250px;
        margin-right: 250px;
        margin-top: 60px;
        border-radius: 10px;
        height:65vh;
        border:3px solid black ;
       /* background-color: whitesmoke;
        width: 40%;
        min-height: 450px;
        margin: 20vh auto;
        border-radius: 8px;
        padding: 10px 10px 20px;
        border: 2px solid var(--black);*/
    }
       
        .insert .container .product
        {
        border-radius:5px ;
        padding: 5px;
        width:30%;
        margin:10px;  
        }
    .insert .container h1{
        color: var(--pink);
        text-align: center;
        margin:10px;
    }
    
    .insert .container .product_category
    {
        width:30%; 
        border-radius:5px ;
        padding: 5px;
    }
    .image{
        color:black;
    }
    .product_category{
        padding: 5px;
    }
    .product_image{
        background: white;
        width:30%; 
        border-radius:5px ;
        padding: 5px;
        margin:10px;
        border:1px solid black;
    }
    .product_price{
        color:black;
    }
    .value{
        width:30%; 
        border-radius:5px ;
        padding: 5px;
        margin:10px; 
    }
    .inserts{
        
        padding:5px;
        background:var(--pink);
        color:black;
        font-size: 15px;
        border-radius: 5px;
    }
    </style>
</head>
<body class="insert">
    <div class="container">
        <h1>Insert Products</h1>
        <form action="" method="post" enctype="multipart/form-data">

        
          <input type="text" name="product_name" placeholder="Enter product title" class="product" autocomplete="off"><br>

          
          <input type="text" name="product_description" placeholder="Enter product description" class="product" autocomplete="off"><br>

          <select name="product_categories" class="product_category">
            <option value="">Select the category</option>
            <?php
            $select_query="select * from category";
            $result_query=mysqli_query($connection,$select_query);
            while($row=mysqli_fetch_assoc($result_query))
            {
                $category_title=$row['category_name'];
                $category_id=$row['category_id'];
                echo "<option value='$category_id'>$category_title</option>";
            }
            ?>
          </select><br><br>

          
          <input type="file" name="product_image" class="product_image" value="Enter product image"> <br>

        
          <label class="product_price">Product price</label><br>
          <input type="text" name="product_price" class="value"><br>

          <input type="submit" name="insert_product" class="inserts" value="Insert Products">


         </form>
    </div>
 
</body>
</html>
