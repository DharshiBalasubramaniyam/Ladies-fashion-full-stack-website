<?php
    session_start();
    if (!isset($_SESSION['admin_id'])) {
        header('location:../account/login.php');exit();
    }
    include('../database/dbconnection.php');

    $limit = 9;
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

    $productsSelectQuery = "select * from products where product_name like '%" . $searchword . "%' and product_id in
                                (
                                    select product_id from product_category where category_id in (
                                        select sub_category_id from category where main_category_id like '%$filterword%'
                                    )
                                )  order by product_id desc, product_id asc limit $limit offset $offset";

    $productsResult = mysqli_query($connection, $productsSelectQuery);

    $totalNoOfProducts = getTotalNoOfProducts($connection, $searchword, $filterword);
    $finalOffset = $totalNoOfProducts - ($totalNoOfProducts%$limit);
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - pink pearl</title>
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
                <li >
                    <a href="products.php" class="active"><i class="fa fa-gift" aria-hidden="true" style="font-size: 16.5px;"></i>
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
        <div class="container products-container">
            <h1>Our products</h1>
        
            <form class="actions" action="products.php" method="get">
                    <input type="text" placeholder="search all products" name="searchword" value="<?php echo $searchword ?>">
                    <select name="filterword">
                        <option value=''>All categories</option>
                        <?php 
                            $categoryQuery = "select * from main_category";
                            $categoryResult = mysqli_query($connection, $categoryQuery);
                            while($categoryData = mysqli_fetch_assoc($categoryResult)) {
                                echo "<option value='" . $categoryData['main_category_id'] . "'";
                                if (isset($_GET['filterword']) && $_GET['filterword']==$categoryData['main_category_id']) echo "selected";
                                echo ">" . $categoryData['category_name'] . "</option>";
                            }
                        ?>
                    </select>
                    <button type="submit" name="search"><i class="fa fa-search" aria-hidden="true"></i></button>
            </form>

            <div class="progress">
                <div>
                    <?php
                        if ($searchword != "" || $filterword != "") {
                            echo "<a href='products.php'><button>< Back to all records</button></a>";
                        }
                    ?>
                </div>
                <span><?php echo $offset+1; ?> - <?php echo $offset+mysqli_num_rows($productsResult); ?> of <?php echo $totalNoOfProducts; ?> records </span>
                <a href="<?php echo "products.php?offset=". $offset-$limit . "&searchword=$searchword&filterword=$filterword" ?>" class="<?php if ($offset==0) echo 'disabled' ?>"><button style="font-size: 25px;padding: 0px 15px" ><</button></a>
                <a href="<?php echo "products.php?offset=". $offset+$limit . "&searchword=$searchword&filterword=$filterword" ?>" class="<?php if ($offset+mysqli_num_rows($productsResult) == $totalNoOfProducts) echo 'disabled' ?>"><button style="font-size: 25px;padding: 0px 15px" >></button></a>
            </div>

            <div class="product-list main-table">
                <table>
                    <thead><th>Product ID</th><th>Product name</th><th>Unit price (Rs.)</th><th>Category</th><th>action</th></thead>

                    <?php 
                        while ($product = mysqli_fetch_assoc($productsResult)) {
                            echo "<tr>";
                            echo "<td>" . $product['product_id'] . "</td>";
                            echo "<td>" . $product['product_name'] . "</td>";
                            echo "<td>" . $product['price'] . "</td>";
                            echo "<td>" . getProductCategory($connection, $product['product_id']) . "</td>";
                            echo "<td style='display:flex; justify-content:center;'><a href='viewproduct.php?id=". $product['product_id'] . "'><button>View full info<i class='fa fa-external-link' aria-hidden='true'></i></button></a></td>";
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
        $query = "select count(*) from products where product_name like '%" . $searchword . "%' and product_id in
                    (
                        select product_id from product_category where category_id in (
                            select sub_category_id from category where main_category_id like '%$filterword%'
                        )
                    )"  ;

        $result = mysqli_query($connection, $query);

        $count = mysqli_fetch_assoc($result)['count(*)'];

        return $count;
    }

    function getProductCategory($connection, $id) {
        $query = "select * from product_category where product_id = $id";
        $result = mysqli_query($connection, $query);
        $categoryId = mysqli_fetch_assoc($result)['category_id'];

        $query = "select sc.category_name as sub, mc.category_name as main from category sc, main_category mc where sc.main_category_id = mc.main_category_id and sub_category_id = $categoryId";
        $result = mysqli_query($connection, $query);
        $row = mysqli_fetch_assoc($result);
        $main = $row['main'];
        $type = $row['sub'];
        $category =  $main .  " - ". $type;

        return $category;
    }
?>