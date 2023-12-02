<?php
include('../includes/dbconnection.php');
session_start();
$admin_id = $_SESSION['admin_id'];
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
        .container h4{
            color:#FF039E;
            text-align: center;
        }
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
            text-align: center;
            font-weight: bolder;
            font-size: 20px;
        }
        table{
            height:1000px;
            width:800px;
            margin-left:150px;
            margin-top: 30px;
        }
        table thead {
            background: var(--pink);
            
        }
        thead th {
            color:var(--white);
            text-align: center;
        }
        tbody td{
            text-align: center;
            font-weight: 500;
        }
        tbody .btn{
            background-color: #FF039E;
            border-radius:5px;
            padding:5px;
            border:2px solid white;
            width:70px;
        } 
        a{color:white;}
        table tr:nth-of-type(even){
            background-color: var(--lightpink);
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
                <li class="active">
                <a href="view_orders.php"><i class='bx bxs-show'></i>
                <span>View orders</span></a>
                </li>
                <li>
                <a href=""><i class='bx bxs-caret-down-square' ></i>
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
        <div>
           <h4>All Orders</h4>
        </div>
       <!-- <div class="form-body">
            <table>
            <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Product image</th>
                        <th>Colour</th>
                        <th>Qty</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
            </table>
           
        </div>  -->
    </div>
   
</body>
</html>
