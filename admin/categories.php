<?php
    session_start();
    if (!isset($_SESSION['admin_id'])) {
        header('location:../account/login.php');exit();
    }
    include('../database/dbconnection.php');

    $category = 'main';

    if (isset($_GET['category'])) {
        $category = $_GET['category'];
    }

    if ($category == "sub") {
        $limit = 9;
        $offset = 0;
        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }
        $sub_category_query = "SELECT mc.category_name as main, sc.category_name as sub, sub_category_id FROM main_category mc, category sc 
                                where mc.main_category_id = sc.main_category_id limit $limit offset $offset";
        $result=mysqli_query($connection,$sub_category_query);
        $totalNoOfProducts = getTotalNoOfProducts($connection);
        $finalOffset = $totalNoOfProducts - ($totalNoOfProducts%$limit);
    }

    
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View categories - pink pearl</title>
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
                    <a href="customers.php"><i class='bx bxs-group'></i>
                    <span>View customers</span></a>
                </li>
                <li>
                    <a href="categories.php" class="active"><i class="fa fa-cubes" aria-hidden="true"></i>
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
            <h1>Categories</h1>

            <div class="top-header" style="margin-bottom: 15px;">
                <a href="categories.php?category=main"><button style="font-size: 16px;padding:5px 12px;" class="<?php if($category=='sub') echo "unactive"; else echo "";?>">Main categories</button></a>
                <a href="categories.php?category=sub"><button style="font-size: 16px;padding:5px 12px;" class="<?php if($category=='main') echo "unactive"; else echo "";?>">Sub categories</button></a>
            </div>

            <?php if ($category == "main") { ?>
                <div class="form-body product-list">
                    <table>
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $query="SELECT * FROM main_category";
                                $result=mysqli_query($connection,$query);
                                if(mysqli_num_rows($result)>0) 
                                {
                                    foreach($result as $items)
                                    {
                                        ?>
                                        <tr>
                                            <td>
                                                <img src="../uploadedImages/<?php echo $items['image_url'];?>" width="80px" height="80px" alt="<?= $items['category_name'];?>">
                                            </td>
                                            <td><?=$items['category_name'];?></td>
                                            <td><?=$items['description'];?></td>
                                            <td align="center" style=>
                                                <a href="editmaincategory.php?id=<?php echo $items['main_category_id'] ?>" style="display:inline-block"><button style='font-size:14px;padding:5px 8px;'><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Edit</button></a>
                                                <a href="editmaincategory.php?delete=<?php echo $items['main_category_id'] ?>" style="display:inline-block" onclick="return confirm('Are you sure? Do you want to delete main category <?php echo $items['category_name'];?> ?')"><button style='font-size:14px;padding:5px 8px;' class='delete'><i class="fa fa-trash-o" aria-hidden="true"></i>Delete</button></a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                else{
                                    echo "No records found";
                                }
                            ?>
                        </tbody>
                    </table>
                </div> 
            
            <?php }  elseif ($category == "sub") { ?>
                <div class="progress" style="justify-content: right;">
                    <span><?php echo $offset+1; ?> - <?php echo $offset+mysqli_num_rows($result); ?> of <?php echo $totalNoOfProducts; ?> records </span>
                    <a href="<?php echo "categories.php?category=sub&offset=". $offset-$limit?>" class="<?php if ($offset==0) echo 'disabled' ?>"><button style="font-size: 25px;padding: 0px 15px" ><</button></a>
                    <a href="<?php echo "categories.php?category=sub&offset=". $offset+$limit?>" class="<?php if ($offset==$finalOffset) echo 'disabled' ?>"><button style="font-size: 25px;padding: 0px 15px" >></button></a>
                </div>

                <div class="product-list">
                    <table>
                        <tr>
                            <th>Sub category</th>
                            <th>Main category</th>
                            <th>Action</th>
                        </tr>
                        <?php
                                if(mysqli_num_rows($result)>0) 
                                {
                                    foreach($result as $items)
                                    {
                                        ?>
                                        <tr>

                                            <td><?php echo $items['sub'] ?></td>
                                            <td><?php echo $items['main'] ?></td>
                                            <td align="center" style=>
                                                <a href="editsubcategory.php?id=<?php echo $items['sub_category_id'] ?>" style="display:inline-block"><button style='font-size:14px;padding:5px 8px;'><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Edit</button></a>
                                                <a href="editsubcategory.php?delete=<?php echo $items['sub_category_id'] ?>" style="display:inline-block" onclick="return confirm('Are you sure? Do you want to delete sub category <?php echo $items['sub'];?> ?')"><button style='font-size:14px;padding:5px 8px;' class='delete'><i class="fa fa-trash-o" aria-hidden="true"></i>Delete</button></a>
                                            </td>                                        
                                        </tr>
                                        <?php
                                    }
                                }
                                else{
                                    echo "No records found";
                                }
                            ?>
                    </table>
                </div>
            <?php } ?>
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
    function getTotalNoOfProducts($connection) {
        $query = "SELECT count(*) FROM category"  ;

        $result = mysqli_query($connection, $query);

        $count = mysqli_fetch_assoc($result)['count(*)'];

        return $count;
    }


    

?>
