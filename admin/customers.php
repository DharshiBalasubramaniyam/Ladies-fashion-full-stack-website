<?php
    session_start();
    if (!isset($_SESSION['admin_id'])) {
        header('location:../account/login.php');exit();
    }
    include('../database/dbconnection.php');

    $limit = 8;
    $offset = 0;
    $searchword = "";

    if (isset($_GET['offset'])) {
        $offset = $_GET['offset'];
    }
    if (isset($_GET['searchword'])) {
        $searchword = $_GET['searchword'];
    }

    $customerSelectQuery = "select * from users where user_id like '%" . $searchword . "%' and user_type='customer' order by reg_date desc limit $limit offset $offset";

    $customersResult = mysqli_query($connection, $customerSelectQuery);

    $totalNoOfCustomers = getIotalNoOfCustomers($connection, $searchword);
    $finalOffset = $totalNoOfCustomers - ($totalNoOfCustomers%$limit) ;

    if (isset($_GET['remove'])) {
        deleteCustomer($connection, $_GET['remove']);
        echo "<script>alert('The user deleted succesfully!');window.location.href = 'customers.php';</script>";
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers - pink pearl</title>
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
                    <a href="orders.php"><i class='bx bxs-show'></i>
                    <span>View orders</span></a>
                </li>
                <li>
                    <a href="customers.php" class="active"><i class='bx bxs-group'></i>
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

            <h1>Our customers</h1>
        
            <form class="actions" action="customers.php" method="get">
                    <input type="text" placeholder="Enter customer ID to search" name="searchword" value="<?php echo $searchword ?>">
                    <button type="submit" name="search"><i class="fa fa-search" aria-hidden="true"></i></button>
            </form>

            <div class="progress">
                <div>
                    <?php
                        if ($searchword != "") {
                            echo "<a href='customers.php'><button>< Back to all records</button></a>";
                        }
                    ?>
                </div>
                <span><?php echo $offset+1; ?> - <?php echo $offset+mysqli_num_rows($customersResult); ?> of <?php echo $totalNoOfCustomers; ?> records </span>
                <a href="<?php echo "customers.php?offset=". $offset-$limit . "&searchword=$searchword" ?>" class="<?php if ($offset==0) echo 'disabled' ?>"><button style="font-size: 25px;padding: 0px 15px" ><</button></a>
                <a href="<?php echo "customers.php?offset=". $offset+$limit . "&searchword=$searchword" ?>" class="<?php if ($offset==$finalOffset) echo 'disabled' ?>"><button style="font-size: 25px;padding: 0px 15px" >></button></a>
            </div>

            <div class="product-list">
                <table>
                    <thead><th>Customer ID</th><th>Username</th><th>Email</th><th>Phone</th><th>action</th></thead>

                    <?php 
                        while ($customer = mysqli_fetch_assoc($customersResult)) {
                            echo "<tr>";
                            echo "<td>" . $customer['user_id'] . "</td>";
                            echo "<td>" . $customer['username'] . "</td>";
                            echo "<td>" . $customer['email'] . "</td>";
                            echo "<td>" . $customer['phone'] . "</td>";
                            echo "<td style='display:flex; justify-content:center;'><a href='customers.php?remove=". $customer['user_id'] . "'><button class='delete'><i class='fa fa-trash-o' aria-hidden='true'></i>Remove</button></a></td>";
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
    function getIotalNoOfCustomers($connection, $searchword) {
        $query = "select count(*) from users where username like '%" . $searchword . "%' and user_type='customer'";

        $result = mysqli_query($connection, $query);

        $count = mysqli_fetch_assoc($result)['count(*)'];

        return $count;
    }

    function deleteCustomer($connection, $id) {
        mysqli_query($connection, "delete from users where user_id = $id");
    }
?>