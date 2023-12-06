<?php
    session_start();
    if (!isset($_SESSION['admin_id'])) {
        header('location:../account/login.php');exit();
    }
    include('../database/dbconnection.php');

    $limit = 8;
    $offset = 0;
    $searchword = ""; $filterword = "";

    if (isset($_GET['offset'])) {
        $offset = $_GET['offset'];
    }
    if (isset($_GET['searchword'])) {
        $searchword = $_GET['searchword'];
    }
    if (isset($_GET['filterword'])) {
        $filterword = $_GET['filterword'];
    }

    $ordersSelectQuery = "select * from placed_order where order_id like '%" . $searchword . "%' and status  like '%" . $filterword . "%' limit $limit offset $offset";

    $ordersResult = mysqli_query($connection, $ordersSelectQuery);

    $totalNoOfOrders = getTotalNoOfProducts($connection, $searchword, $filterword);
    $finalOffset = $totalNoOfOrders - ($totalNoOfOrders%$limit) ;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - pink pearl</title>
    <link rel="stylesheet" href="../index.css">
    <script src="https://kit.fontawesome.com/c732ec9339.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link  rel="stylesheet"href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link rel="stylesheet" href="style.css">
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
                    <a href="dashboard.php"><i class='bx bxs-dashboard'></i>
                    <span>Dashboard</span></a>
                </li>
                <li>
                    <a href="products.php"><i class="fa fa-gift" aria-hidden="true" style="font-size: 16.5px;"></i>
                    <span>View products</span></a>
                </li>
                <li>
                    <a href="orders.php" class="active"><i class='bx bxs-show'></i>
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
        <div class="container products-container">
            <h1>Our Orders</h1>
        
            <form class="actions" action="orders.php" method="get">
                    <input type="text" placeholder="Enter order ID to search" name="searchword" value="<?php echo $searchword ?>">
                    <select name="filterword">
                        <option value=''>All status</option>
                        <option value="pending" <?php if (isset($_GET['filterword']) && $_GET['filterword']=='pending') echo "selected" ?>>pending</option>
                        <option value="shipped" <?php if (isset($_GET['filterword']) && $_GET['filterword']=='shipped') echo "selected" ?>>shipped</option>
                        <option value="delivered" <?php if (isset($_GET['filterword']) && $_GET['filterword']=='delivered') echo "selected" ?>>delivered</option>
                        <option value="cancelled" <?php if (isset($_GET['filterword']) && $_GET['filterword']=='cancelled') echo "selected" ?>>cancelled</option>
                    </select>
                    <button type="submit" name="search"><i class="fa fa-search" aria-hidden="true"></i></button>
            </form>

            <div class="progress">
                <div>
                    <?php
                        if ($searchword != "" || $filterword != "") {
                            echo "<a href='orders.php'><button>< Back to all records</button></a>";
                        }
                    ?>
                </div>
                <span><?php echo $offset+1; ?> - <?php echo $offset+mysqli_num_rows($ordersResult); ?> of <?php echo $totalNoOfOrders; ?> records </span>
                <a href="<?php echo "orders.php?offset=". $offset-$limit . "&searchword=$searchword&filterword=$filterword" ?>" class="<?php if ($offset==0) echo 'disabled' ?>"><button style="font-size: 25px;padding: 0px 15px" ><</button></a>
                <a href="<?php echo "orders.php?offset=". $offset+$limit . "&searchword=$searchword&filterword=$filterword" ?>" class="<?php if ($offset==$finalOffset) echo 'disabled' ?>"><button style="font-size: 25px;padding: 0px 15px" >></button></a>
            </div>

            <div class="product-list">
                <table>
                    <thead><th>Order ID</th><th>Date</th><th>User ID</th><th>Status</th><th>Amount</th><th>action</th></thead>

                    <?php 
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
                            echo "<td style='display:flex; justify-content:center;'><a href='vieworder.php?id=". $order['order_id'] . "'><button>View full info<i class='fa fa-external-link' aria-hidden='true'></i></button></a></td>";
                            echo "</tr>";
                        }
                    ?>

                </table>
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

<?php
    function getTotalNoOfProducts($connection, $searchword, $filterword) {
        $query = "select count(*) from placed_order where order_id like '%" . $searchword . "%' and status  like '%" . $filterword . "%'";

        $result = mysqli_query($connection, $query);

        $count = mysqli_fetch_assoc($result)['count(*)'];

        return $count;
    }
?>