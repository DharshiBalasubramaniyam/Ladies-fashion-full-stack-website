<?php
include('../includes/dbconnection.php');
session_start();


$admin_id = $_SESSION['admin_id'];
if (isset($_POST['add_user'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phonenumber = $_POST['phonenumber'];
    $role = $_POST['role']== true ? 'User' : 'Admin';
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password' ";
    $result = mysqli_query($connection, $sql);

    if (empty($name)  or empty($email) or empty($phonenumber) or empty($role) or empty($password) or empty($confirm_password)) {
        $error[] = 'Please fill all the available fields';
    }

    else{

        if (mysqli_num_rows($result) > 0) {
            $error[] = 'user already exist!';
        }
        elseif (strlen(trim($password)) < 8) {
            $error[] = 'Password should have atleast 8 characters';
        }
        
        if ($password != $confirm_password) {
            $error[] = 'password not matched';
        } 
        if (!preg_match("/^\d{3}-\d{3}-\d{4}$/", $phonenumber)) {
            $error[] = 'Invalid telephone number';  
        }


        
        else {
            $query = "insert into users(name,email,phone,role,password) values
               ('$name','$email','$phonenumber','$role','$password')";
            $results = mysqli_query($connection, $query);
            if ($results) {
                header("Location:index.php");
            }
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
        .container h4 {
            color: #FF039E;
            text-align: center;
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

        .container {
            margin-top: 130px;
            margin-left: 400px;
        }

        .container h4 {
            text-align: left;
            font-weight: bolder;
            font-size: 20px;
        }

        .container .error-msg {
            margin: 10px 0;
            display: block;
            background: crimson;
            color: #fff;
            border-radius: 5px;
            font-size: 15px;
            padding: 10px;
            width: 30%;
            text-align: center;
        }

        .input-group {
            margin-top: 10px;
        }

        .input-group .form {
            padding: 10px;
            border-radius: 5px;
            border: 2px solid var(--pink);
            width: 35%;
            margin-top: 30px;
        }


        .input-group .form-control {
            background: #FF039E;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 10px;
            margin-top: 15px;
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
                <li class="active">
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
                    <a href=""><i class='bx bx-log-out'></i>
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

    <div class="container">
        <?php
        if (isset($error)) {
            foreach ($error as $error) {
                echo '<span class="error-msg">' . $error . '</span>';
            }
        }
        ?>
        <div>
            <h4>Add user</h4>
        </div>
        <div class="form-body">
            <form action="" method="post">

                <div class="input-group">

                    <input type="text" class="form" name="name" placeholder="Enter your name">
                </div>

                <div class="input-group">
                    <input type="email" class="form" name="email" placeholder="Enter your email address">
                </div>

                <div class="input-group">
                    <input type="text" class="form" name="phonenumber" placeholder="Enter your Tel-Number:XXX-XXX-XXXX">
                </div>

                <div class="input-group">
                    <select class="form" name="role">
                        <option>User</option>
                        <option>Admin</option>
                    </select>
                </div>

                <div class="input-group">
                    <input type="password" class="form" name="password" placeholder="Enter your password">
                </div>

                <div class="input-group">
                    <input type="password" class="form" name="confirm_password" placeholder="Confirm your password">
                </div>


                <div class="input-group">
                    <input type="submit" class="form-control" name="add_user" value="Add User">
                </div>
            </form>

        </div>
    </div>

</body>

</html>