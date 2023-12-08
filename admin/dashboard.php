<?php
    session_start();
    if (!isset($_SESSION['admin_id'])) {
        header('location:../account/login.php');exit();
    }
    include('../database/dbconnection.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - pink pearl</title><link rel="stylesheet" href="../index.css">
    <script src="https://kit.fontawesome.com/c732ec9339.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link  rel="stylesheet"href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>

    <header>
        <div style="display: flex;align-items: center;gap: 10px;"><i class="fa fa-bars toggle-menu" aria-hidden="true" onclick="handleNav()" style="font-size: 20px;"></i><img src="../uploadedImages/logo.png" alt=""></div>
        <h1>Welcome <?php echo $_SESSION['admin_name'] ?>!</h1>
    </header>

    <main>
        <div class="side-bar">
            <ul>
                <li class="active">
                    <a href="dashboard.php" class="active"><i class='bx bxs-dashboard'></i>
                    <span>Dashboard</span></a>
                </li>
                <li>
                    <a href="products.php"><i class="fa fa-gift" aria-hidden="true" style="font-size: 16.5px;"></i>
                    <span>View products</span></a>
                </li>
                <li>
                    <a href="orders.php"><i class='bx bxs-show'></i>
                    <span>View orders</span></a>
                </li>
                <li>
                    <a href="customers.php"><i class='bx bxs-group'></i>
                    <span>View customers</span></a>
                </li>
                <li>
                    <a href="categories.php"><i class="fa fa-cubes" aria-hidden="true"></i>
                    <span>View Categories</span></a>
                </li>
                <li>
                    <a href="addnewproduct.php"><i class='bx bxs-caret-down-square' ></i>
                    <span>Add new product</span></a>
                </li>
                <li>
                    <a href="addnewuser.php"><i class='bx bx-user-plus'></i>
                    <span>Add new user</span></a>
                </li>
                <li>
                    <a href="addNewCategory.php"><i class='bx bxs-chevron-down-square'></i>
                    <span>Add new category</span></a>
                </li>
                <li>
                    <a href="messages.php"><i class='bx bx-message-detail'></i>
                    <span>Customer Messages</span></a>
                </li>
                <li>
                    <a href="profile.php"><i class='bx bx-user-circle'></i>
                    <span>Your Profile</span></a>
                </li>
                <li>
                    <a href="../account/logout.php"><i class='bx bx-log-out'></i>
                    <span>Logout</span></a>
                </li>
            </ul>
        </div>

        <div class="container dashboard">

            <h1>Dashboard</h1>

            <div class="cards">
                

                <div class="card-single">
                    <div>
                        <span style="color: #FF039E; font-size:21px">Total sales</span><br>
                        <?php
                        $query = "select sum(total_amount) from placed_order where MONTH(date) = " . date('n');
                        $result = mysqli_query($connection, $query);

                        $sum = mysqli_fetch_assoc($result)['sum(total_amount)'];
                        echo "<h2 style='font-weight:600;'>Rs. " . $sum . '</h2>'
                        ?>
                        <span style="color: grey; font-size:14px">This month</span>
                    </div>
                    <div>
                        <i class="fa fa-inr" aria-hidden="true"></i>
                    </div>
                </div>

                <div class="card-single">
                    <div>
                        <span style="color: #FF039E;font-size:21px">Total Orders</span><br>
                        <?php
                        $query = "select * from placed_order where MONTH(date) = " . date('n');
                        $result = mysqli_query($connection, $query);

                        $row = mysqli_num_rows($result);
                        echo "<h2 style='font-weight:600;'>" . $row . '</h2>'
                        ?>
                        <span style="color: grey; font-size:14px">This month</span>

                    </div>
                    <div>
                        <i class="las la-shopping-bag"></i>
                    </div>
                </div>

                <div class="card-single">
                    <div>
                        <span style="color: #FF039E;font-size:21px">Total customers</span><br>
                        <?php
                        $query = "select * from users where user_type='customer' and MONTH(reg_date) = " . date('n');
                        $result = mysqli_query($connection, $query);

                        $row = mysqli_num_rows($result);
                        echo "<h2 style='font-weight:600;'>" . $row . '</h2>';
                        ?>
                        <span style="color: grey; font-size:14px">This month</span>

                    </div>
                    <div>
                        <i class="las la-users"></i>
                    </div>
                </div>

                <div class="card-single">
                    <div>
                        <span style="color: #FF039E; font-size:22px">Total messages</span>
                        <?php
                            $query = "select * from messages where MONTH(date) = " . date('n');
                            $result = mysqli_query($connection, $query);

                            $row = mysqli_num_rows($result);
                            echo "<h2 style='font-weight:600;'>" . $row . '</h2>';
                        ?>
                      <span style="color: grey; font-size:14px">This month</span>

                    </div>
                    <div>
                        <i class='bx bx-message-rounded'></i>
                    </div>
                </div>
            </div>
            <div class="recent-grid">
                <div class="recent orders">
                    <div class="card">
                        <div class="card-header">
                            <h3>Latest Orders</h3>
                            <a href='orders.php'><button>See all<i class='fa fa-external-link' aria-hidden='true'></i></button></a>
                        </div>
                        <div class="card-body">
                            <div class="order-list">
                                <table>
                                    <thead><th>Order ID</th><th>Date</th><th>User ID</th><th>Status</th><th>Amount</th></thead>

                                    <?php 
                                        $ordersSelectQuery = "select * from placed_order order by date desc limit 4";

                                        $ordersResult = mysqli_query($connection, $ordersSelectQuery);

                                        while ($order = mysqli_fetch_assoc($ordersResult)) {
                                            echo "<tr>";
                                            echo "<td>" . $order['order_id'] . "</td>";
                                            echo "<td>" . $order['date']  . "</td>";
                                            echo "<td>" . $order['user_id']  . "</td>";

                                            if ($order['status'] == 'pending')
                                                echo "<td><span class='pending'>pending</span></td>";
                                            else if ($order['status'] == 'shipped')
                                                echo "<td><span class='shipped'>shipped</span></td>";
                                            else if ($order['status'] == 'delivered')
                                                echo "<td><span class='delivered'>delivered</span></td>";
                                            else if ($order['status'] == 'Cancelled')
                                                echo "<td><span class='cancelled'>cancelled</span></td>";

                                            echo "<td>" . $order['total_amount']  . "</td>";
                                            echo "</tr>";
                                        }
                                    ?>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="recent customers">
                    <div class="card">
                        <div class="card-header">
                            <h3>Latest customers</h3>
                            <a href='customers.php'><button>See all<i class='fa fa-external-link' aria-hidden='true'></i></button></a>

                        </div>
                        <div class="card-body">
                                    <div class="order-list">
                                        <table>
                                            <thead><th>Customer ID</th><th>Username</th><th>Email</th></thead>

                                            <?php 
                                                $customerSelectQuery = "select * from users order by reg_date desc, user_id desc limit 5";

                                                $customersResult = mysqli_query($connection, $customerSelectQuery);

                                                while ($customer = mysqli_fetch_assoc($customersResult)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $customer['user_id'] . "</td>";
                                                    echo "<td>" . $customer['username'] . "</td>";
                                                    echo "<td>" . $customer['email'] . "</td>";
                                                    echo "</tr>";
                                                }
                                            ?>

                                        </table>
                                    </div>
                                
                         </div>
                    </div>
                </div>
            </div>

        </div>
        
    </main>

    <footer>
        &copy; copyright  @ <?php echo date('Y'); ?> by <span style="color: var(--pink);">Pink Pearl</span>
    </footer>

    <script>
        const sidebar = document.querySelector('.side-bar');
        function handleNav() {
            if (sidebar.classList.contains("open")) {
                sidebar.classList.remove("open");
            }else {
                sidebar.classList.add("open");
            }
        }
    </script>

</body>
</html>