<?php
    session_start();
    if (!isset($_SESSION['admin_id'])) {
        header('location:../account/login.php');exit();
    }
    include('../database/dbconnection.php');

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $orderSql = "select * from placed_order where order_id=" . $id;
        $orderResult = mysqli_query($connection, $orderSql);
        $order = mysqli_fetch_assoc($orderResult);

        $orderItemsSql = "select * from order_items where order_id = " . $id;
        $orderItemResult = mysqli_query($connection, $orderItemsSql);

        $orderSql = "select * from order_shipping where order_id=" . $id;
        $orderShipppingResult = mysqli_query($connection, $orderSql);
        $orderShipping = mysqli_fetch_assoc($orderShipppingResult);

    }
    if (isset($_POST['update-status'])) {
        $update =  "update placed_order set status = '" . $_POST['status'] . "' where order_id = $id";
        mysqli_query($connection, $update);
        echo "<script>alert('Order status updated successfully!');window.location.href = 'vieworder.php?id=$id';</script>";

    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>view order - pink pearl</title>
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

            <h1 class="h1"><span>View Order</span><a href="orders.php"><button>< Back to orders</button></a></h1>

            <form action="vieworder.php?id=<?php echo $id ?>" method="post">
                <p><span>Order id </span> : <?php echo $order['order_id']; ?></p>
                <p><span>Affected date </span> : <?php echo $order['date'] ?></p>
                <p><span>User id </span> : <?php echo $order['user_id'] ?></p>
                <p><span>Bill amount </span> : <?php echo $order['total_amount'] ?></p>
                <p><span>Payment method </span> : <?php echo $order['payment_method'] ?></p>
                <p>
                    <span>Order Items:</span>
                    <div class="orders-list">
                        <table border="1px">
                            <thead><th>Product</th><th>Quantity</th><th>Amount</th></thead>
                            <?php
                                while($row = mysqli_fetch_assoc($orderItemResult)) {
                                echo "<tr>";
                                echo "<td>" . getProductName($connection, $row['product_id']) . " | " . getSize($connection, $row['size_id']) . " | " . getColor($connection, $row['color_id']) . "</td>";
                                echo "<td>" . $row['quantity'] . "</td>";
                                echo "<td>" . $row['amount'] . "</td>";
                                echo "</tr>";
                                }
                            ?>
                        </table>
                    </div>
                </p>
                <p>
                    <br><span>Shipping Details</span>
                    <p><span>&nbsp;&nbsp; First Name </span>: <?php echo $orderShipping['first_name'] ?></p>
                    <p><span>&nbsp;&nbsp; Last Name </span>: <?php echo $orderShipping['last_name'] ?></p>
                    <p><span>&nbsp;&nbsp; Email </span>: <?php echo $orderShipping['email'] ?></p>
                    <p><span>&nbsp;&nbsp; Phone </span>: <?php echo $orderShipping['phone'] ?></p>
                    <p><span>&nbsp;&nbsp; District </span>: <?php echo $orderShipping['district'] ?></p>
                    <p><span>&nbsp;&nbsp; City </span>: <?php echo $orderShipping['city'] ?></p>
                    <p><span>&nbsp;&nbsp; postal code </span>: <?php echo $orderShipping['postal_code'] ?></p>
                    <p><span>&nbsp;&nbsp; Address line 1 </span>: <?php echo $orderShipping['address_line1'] ?></p>
                    <p><span>&nbsp;&nbsp; Address line 2 </span>: <?php echo $orderShipping['address_line2'] ?></p>

                </p>
                <p>
                    <span>Status</span>
                    : <select name="status">
                        <option value="pending" <?php if (isset($_GET['id']) && $order['status']=='pending') echo "selected" ?>>pending</option>
                        <option value="shipped" <?php if (isset($_GET['id']) && $order['status']=='shipped') echo "selected" ?>>shipped</option>
                        <option value="delivered" <?php if (isset($_GET['id']) && $order['status']=='delivered') echo "selected" ?>>delivered</option>
                        <option value="Cancelled" <?php if (isset($_GET['id']) && $order['status']=='Cancelled') echo "selected" ?>>cancelled</option>
                    </select>
                </p>
                <button type="submit" name="update-status" style='font-size:14px;padding:5px 8px;margin-top:10px;'>update status</button>
            </form>
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

function getColor($connection, $id){
    $query = "select color_name from product_color where color_id = " . $id;
    $result = mysqli_query($connection, $query);

    return mysqli_fetch_assoc($result)['color_name'];
}

function getSize($connection, $id){
    $query = "select size_name from product_size where size_id = " . $id;
    $result = mysqli_query($connection, $query);

    return mysqli_fetch_assoc($result)['size_name'];
}
function getProductName($connection, $id){
    $query = "select product_name from products where product_id = " . $id;
    $result = mysqli_query($connection, $query);

    return mysqli_fetch_assoc($result)['product_name'];
}

?>