<?php

include('../includes/dbconnection.php');
session_start();
$admin_id = $_SESSION['admin_id'];

if (isset($_POST['update_profile'])) {

   $update_name = mysqli_real_escape_string($connection, $_POST['update_name']);
   $update_email = mysqli_real_escape_string($connection, $_POST['update_email']);

   mysqli_query($connection, "UPDATE users SET name = '$update_name', email = '$update_email' WHERE id = '$admin_id'") or die('query failed');

   $old_pass = $_POST['old_pass'];
   $update_pass = mysqli_real_escape_string($connection, $_POST['update_pass']);
   $new_pass = mysqli_real_escape_string($connection, $_POST['new_pass']);
   $confirm_pass = mysqli_real_escape_string($connection, $_POST['confirm_pass']);

   if (!empty($update_pass) || !empty($new_pass) || !empty($confirm_pass)) {
      if ($update_pass != $old_pass) {
         $message[] = 'old password not matched!';
      } elseif ($new_pass != $confirm_pass) {
         $message[] = 'confirm password not matched!';
      } else {
         mysqli_query($connection, "UPDATE users SET password = '$confirm_pass' WHERE id = '$admin_id'") or die('query failed');
         $message[] = 'password updated successfully!';
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
   <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
   <style>
      :root {
         --red: #e74c3c;
         --dark-red: #c0392b;
         --black: #333;
         --white: #fff;
         --light-bg: #eee;
         --box-shadow: 0 5px 10px rgba(0, 0, 0, .1);
      }

      header {
         background: #fff;
         display: flex;
         justify-content: space-between;
         padding: 1rem;
         box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.2);
         position: fixed;
         left: 345px;
         width: 78%;
         top: 0;
         z-index: 100;
      }

      .update-profile {
         min-height: 100vh;
         display: flex;
         align-items: center;
         justify-content: center;
         padding: 20px;
         margin-left: 400px;

      }

      .update-profile form {
         padding: 20px;
         background-color: var(--white);
         box-shadow: var(--box-shadow);
         text-align: center;
         width: 700px;
         text-align: center;
         border-radius: 5px;
      }

      .update-profile h4 {
         color: #FF039E;
         text-align: center;
         text-align: center;
         font-weight: bolder;
         font-size: 20px;
      }

      .update-profile form .flex {
         display: flex;
         justify-content: space-between;
         margin-bottom: 20px;
         gap: 15px;
      }

      .update-profile form .flex .inputBox {
         width: 49%;
      }

      .update-profile form .flex .inputBox span {
         text-align: left;
         display: block;
         margin-top: 15px;
         font-size: 17px;
         color:black;
         font-weight: 450;
      }

      .update-profile form .flex .inputBox .box {
         width: 100%;
         border-radius: 5px;
         background-color: var(--light-bg);
         padding: 12px 14px;
         font-size: 17px;
         color: var(--black);
         margin-top: 10px;
         border:2px solid var(--pink);
      }

      .btn,
      .delete-btn {
         width: 30%;
         border-radius: 5px;
         padding: 2px ;
         color: var(--white);
         display: block;
         text-align: center;
         cursor: pointer;
         font-size: 20px;
         margin-top: 10px;
         margin-left:250px
      }

      .btn {
         background-color: var(--pink);

      }

      .btn:hover {
         background-color:green;
      }

      .delete-btn {
         background-color: var(--red);
      }

      .delete-btn:hover {
         background-color: var(--dark-red);
      }

      .message {
         margin: 10px 0;
         width: 100%;
         border-radius: 5px;
         padding: 10px;
         text-align: center;
         background-color: var(--red);
         color: var(--white);
         font-size: 20px;
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
               <a href=""><i class='bx bxs-caret-down-square'></i>
                  <span>Insert products</span></a>
            </li>
            <li>
               <a href="insert_categories.php"><i class='bx bxs-chevron-down-square'></i>
                  <span>Insert categories</span></a>
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
            <li class="active">
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
            <img src="pearl.jpg" alt="">
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

   <div class="update-profile">

      <?php
      $select = mysqli_query($connection, "SELECT * FROM users WHERE id='$admin_id'") or die('query failed');
      if (mysqli_num_rows($select) > 0) {
         $fetch = mysqli_fetch_assoc($select);
      }
      ?>

      <form action="" method="post">
         <?php
         if (isset($message)) {
            foreach ($message as $message) {
               echo '<div class="message">' . $message . '</div>';
            }
         }
         ?>
         <div>
            <h4>Update Profile</h4>
         </div>
         <div class="flex">
            <div class="inputBox">
               <span>username :</span>
               <input type="text" name="update_name" value="<?php echo $fetch['name']; ?>" class="box">
               <span>your email :</span>
               <input type="email" name="update_email" value="<?php echo $fetch['email']; ?>" class="box">
               <input type="hidden" name="old_pass" value="<?php echo $fetch['password']; ?>">
               <span>old password :</span>
               <input type="password" name="update_pass" placeholder="enter previous password" class="box">
            </div>
            <div class="inputBox">


               <span>new password :</span>
               <input type="password" name="new_pass" placeholder="enter new password" class="box">
               <span>confirm password :</span>
               <input type="password" name="confirm_pass" placeholder="confirm new password" class="box">
            </div>
         </div>
         <input type="submit" value="update profile" name="update_profile" class="btn">
         <a href="index.php" class="delete-btn">go back</a>
      </form>

   </div>

</body>

</html>