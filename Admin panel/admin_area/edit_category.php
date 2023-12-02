<?php

include('../includes/dbconnection.php');
session_start();
$admin_id = $_SESSION['admin_id'];

if(isset($_POST["update_category"]))
{
    $category_id=$_POST["category_id"];
    $name=$_POST["category_title"];
     //accessing image
    $new_image=$_FILES['category_image']['name'];

    //accessing image temp name
     $temp_image=$_FILES['category_image']['tmp_name'];

     $old_image=$_POST['old_image'];

    //checking empty condition
       if( $name=='' or $new_image=='' or $temp_image=='')
        {
            $error[]='Please fill all the available fields';
        }
        else
        {
            
          move_uploaded_file($temp_image,"./category_images/$new_image");

          //update query
          $update_category="UPDATE category SET category_name = '$name' ,image='$new_image'
                            WHERE category_id='$category_id'";
          $result_query=mysqli_query($connection,$update_category);
          if($result_query)
          {
            if(file_exists("./category_images/$old_image"))
            {
                unlink("./category_images/$old_image");
            }
            header("Location:index.php");
          }
        }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css" type="text/css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link  rel="stylesheet"href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    
    <style>
       
        header{
            background: #fff;
            display:flex;
            justify-content: space-between;
            padding:1rem;
            box-shadow: 4px 4px 10px rgba(0,0,0,0.2);
            position:fixed;
            left:345px;
            width:78%;
            top:0;
            z-index:100;   
        }
        .container{
            margin-top:150px;
            margin-left:400px;
        }
        .container h4{
            color:#FF039E;
            text-align: center;
            text-align: left;
            font-weight: bolder;
            font-size: 20px;
        }
        .input-group{
            margin-top:10px;
        }
        .input-group .form{
            padding:10px;
            border-radius: 5px;
            border:2px solid var(--pink);
            width:35%; 
            margin-top: 30px;  
        }
        

        .input-group .form-control{
            background: #FF039E;
            color:#fff;
            padding:10px;
            border:none;
            border-radius: 10px;
            margin-top: 15px; 
        }
        .container .error-msg{
            margin:10px 0;
            display: block;
            background: crimson;
            color:#fff;
            border-radius: 5px;
            font-size: 15px;
            padding:10px;
            width:30%;
            text-align: center;
        }
    </style>
</head>
<body>
   <input type="checkbox" id="nav-toggle">
    <div class="sidebar">
        
        <div class="sidebar-menu">
            <ul>
                <li>
                <a href="index.php"><i class='bx bxs-dashboard'></i>
                <span>Dashboard</span></a>
                </li>
                <li>
                <a href=""><i class='bx bxl-product-hunt'></i>
                <span>View products</span></a>
                </li>
                <li>
                <a href="view_categories.php"><i class='bx bxs-category'></i>
                <span>View categories</span></a>
                </li>
                <li>
                <a href="view_orders.php"><i class='bx bxs-show'></i>
                <span>View orders</span></a>
                </li>
                <li>
                <a href=""><i class='bx bxs-caret-down-square' ></i>
                <span>Insert products</span></a>
                </li>
                <li class="active">
                <a href="edit_category.php"><i class='bx bx-edit'></i>
                <span>Edit categories</span></a>
                </li>
                <li>
                <a href="view_customers.php"><i class='bx bxs-group'></i>
                <span>Customers</span></a>
                </li>
                <li>
                <a href="add_user.php"><i class='bx bx-user-plus'></i>
                <span>Add users</span></a>
                </li>
                <li>
                <a href=""><i class='bx bx-message-detail'></i>
                <span>Customer Message</span></a>
                </li>
                <li>
                <a href="admin_profile.php"><i class='bx bx-user-circle'></i>
                <span>Admin Profile</span></a>
                </li>
                <li>
                <a href="logout.php"><i class='bx bx-log-out'></i>
                <span>Logout</span></a>
                </li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <header>
        
            <h1>
                <label for="nav-toggle">
                    <span class="las la-bars"></span>
                </label>
                Dashboard
            </h1>
            <div class="sidebar-brand">
            <img src="pearl.jpg"  alt="">
            </div>
            <div class="search-wrapper">
                <span class="las la-search"></span>
                <input type="search" placeholder="Search here">
            </div>
            <div class="user-wrapper">
            <?php
                $select = mysqli_query($connection, "SELECT * FROM users WHERE id='$admin_id'") or die('query failed');
                if (mysqli_num_rows($select) > 0) {
                    $fetch = mysqli_fetch_assoc($select);
                }
                ?>
                <img src="Admin.png" width="30px" height="30px" alt="">
                <h4><?php
                    if (empty($fetch['name'])) {
                        echo "No Admin";
                    } else {

                        echo $fetch['name'];
                    }
                    ?></h4>
            </div>
               <div> <small>Admin</small>
            </div>
               
        </header>
    </div>
    
   <div class="container"> 
        <?php
           if(isset($_GET['id']))
           {
              $id=$_GET['id'];

              $query="SELECT * FROM category WHERE category_id=$id";
              $result=mysqli_query($connection,$query);

              if(mysqli_num_rows($result)>0) 
              {
                $data =mysqli_fetch_array($result); 
                ?>
                 <?php
           if(isset($error))
           {
            foreach($error as $error)
            {
               echo '<span class="error-msg">'.$error.'</span>';
            }
           }
           ?>
           
        <div>
           <h4>Edit Category</h4>
        </div>
        <div class="form-body">
            <form action="" method="post"  enctype="multipart/form-data">
                
                <div class="input-group">
                    <input type="hidden" name="category_id" value="<?= $data['category_id']?>"> 
                    <input type="text" class="form" name="category_title" placeholder="Insert categories">   
                </div>

                <div class="input-group">
                    <input type="file" class="form" name="category_image" placeholder="Upload the image">
                    
                </div>

                <div class="input-group">
                <label>Current Image</label><br>
                <input type="hidden" name="old_image" value= '<?=$data['image']?>'>
                <img src="./category_images/<?=$data['image']?>" height=150px width='150px' alt="No image">
                </div>

                <div class="input-group">
                     <input type="submit" class="form-control" name="update_category" value="Update Category"> 
                </div> 
            </form>
        </div> 
        <?php
           }
           else{
             echo"Category not found";
           }
        }
           else
           {
            echo "Id missing from url";
           }
           ?> 
    </div>
   
</body>
</html>
