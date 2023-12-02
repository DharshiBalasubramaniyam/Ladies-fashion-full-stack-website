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
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <style>
        table thead {
            background: var(--pink);

        }

        thead td {
            color: var(--white);
        }

        table tr:nth-of-type(even) {
            background-color: var(--lightpink);
        }

        .card-single:last-child h1,
        .card-single:last-child div:first-child span,
        .card-single:last-child div:last-child i {
            color: #fff;
        }
        .card-header button a{
            background: var(--pink);
            border-radius: 10px;
            color:#fff;
            font-size: .8rem;
            padding:.5rem 1rem;
            border:1px solid var(--pink);
            margin-left:5px ;
            
        }
    </style>
</head>

<body>
    <input type="checkbox" id="nav-toggle">
    <div class="sidebar">

        <div class="sidebar-menu">
            <ul>
                <li class="active">
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
            <div> <small>Admin</small></div>

        </header>
        <main>
            <div class="cards">
                <div class="card-single">
                    <div>
                        <?php
                        $query = "select id from users where role='user' order by id ";
                        $result = mysqli_query($connection, $query);

                        $row = mysqli_num_rows($result);
                        echo '<h1>' . $row . '</h1>'
                        ?>
                        <span>Customers</span>
                    </div>
                    <div>
                        <span class="las la-users"></span>
                    </div>
                </div>

                <div class="card-single">
                    <div>
                        <?php
                        $query = "select id from orders  order by id ";
                        $result = mysqli_query($connection, $query);

                        $row = mysqli_num_rows($result);
                        echo '<h1>' . $row . '</h1>'
                        ?>
                        <span>Orders</span>
                    </div>
                    <div>
                        <span class="las la-shopping-bag"></span>
                    </div>
                </div>

                <div class="card-single">
                    <div>
                        <?php
                        $query = "select category_id from category  order by category_id ";
                        $result = mysqli_query($connection, $query);

                        $row = mysqli_num_rows($result);
                        echo '<h1>' . $row . '</h1>'
                        ?>
                        <span>Category</span>
                    </div>
                    <div>
                        <i class='bx bxs-category'></i>
                    </div>
                </div>

                <div class="card-single">
                    <div>
                        <h1>20</h1>
                        <span>Messages</span>
                    </div>
                    <div>
                        <i class='bx bx-message-rounded'></i>
                    </div>
                </div>
            </div>
            <div class="recent-grid">
                <div class="orders">
                    <div class="card">
                        <div class="card-header">
                            <h3>Latest Orders</h3>
                            <button><a href='view_orders.php'>See all<span class="las la-arrow-right"></span></a></button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table width="100%">
                                    <thead>
                                        <tr>
                                            <td>ID</td>
                                            <td>Date</td>
                                            <td>Amount</td>
                                            <td>Status</td>
                                        <tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT * FROM orders limit 3";
                                        $result = mysqli_query($connection, $query);
                                        if (mysqli_num_rows($result) > 0) {
                                            foreach ($result as $orders) {
                                        ?>
                                                <tr>
                                                    <td><?= $orders['id']; ?></td>
                                                    <td><?= $orders['date']; ?></td>
                                                    <td><?= $orders['amount']; ?></td>
                                                    <td><?= $orders['status']; ?></td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                            echo "No records found";
                                        }
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="customers">
                    <div class="card">
                        <div class="card-header">
                            <h3>New Customer</h3>
                            <button><a href="view_customers.php">See all<span class="las la-arrow-right">
                                </span></a></button>
                        </div>
                        <div class="card-body">
                            <div class="customer">
                                <div class="info">
                                    <img src="customer.jpg" width="40px" height="40px" alt="">
                                    <div>
                                        <?php
                                        $query = "select name from users where role='user' order by id desc limit 0,1";
                                        $result = mysqli_query($connection, $query);

                                        $row = mysqli_fetch_column($result);
                                        echo '<h4>' .$row.'</h4>'
                                        ?>
                                    </div>
                                </div>
                                
                            </div>
                         </div>
                         <div class="card-body">
                            <div class="customer">
                                <div class="info">
                                    <img src="customer.jpg" width="40px" height="40px" alt="">
                                    <div>
                                        <?php
                                        $query = "select name from users where role='user' order by id  desc limit 1,1";
                                        $result = mysqli_query($connection, $query);

                                        $row = mysqli_fetch_column($result);
                                        echo '<h4>' .$row.'</h4>'
                                        ?>
                                    </div>
                                </div>
                                
                            </div>
                         </div>
                         <div class="card-body">
                            <div class="customer">
                                <div class="info">
                                    <img src="customer.jpg" width="40px" height="40px" alt="">
                                    <div>
                                        <?php
                                        $query = "select name from users where role='user' order by id desc limit 2,1";
                                        $result = mysqli_query($connection, $query);

                                        $row = mysqli_fetch_column($result);
                                        echo '<h4>' .$row.'</h4>'
                                        ?>
                                    </div>
                                </div>
                            </div>
                         </div>

                       
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>