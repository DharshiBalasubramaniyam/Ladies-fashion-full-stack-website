<?php
    session_start();
    if (!isset($_SESSION['admin_id'])) {
        header('location:../account/login.php');exit();
    }
    include('../database/dbconnection.php');


    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $categoryResult = mysqli_query($connection, "select * from category where sub_category_id = $id");
        $category = mysqli_fetch_assoc($categoryResult);

        $name = $category['category_name'];
        $main_id = $category['main_category_id'];
        
    }   

    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];

        mysqli_query($connection, "delete from category where sub_category_id = $id");
        echo "<script>alert('Sub category deleted successfully!');window.location.href = 'categories.php?category=sub'</script>";
    } 


    $nameError = "";$mainError = "";

    
    if (isset($_POST['update'])) {
        $name = sanitizeMySQL($connection, $_POST['name']);
        $main_id = sanitizeMySQL($connection, $_POST['main-category']);
        $nameError = validate_name($name);
        $mainError = validate_main($main_id);

        if($nameError=="" && $mainError=="") {
            mysqli_query($connection, "update category set category_name = '$name', main_category_id= $main_id where sub_category_id = $id");
            echo "<script>alert('Sub category updated successfully!');window.location.href = 'categories.php?category=sub'</script>";
        }

    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit sub category - pink pearl</title>
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
        <div class="container category-container">

            <h1>Edit sub category</h1>
            
            <form action="" method="post" class="form">
                <a href="categories.php?category=sub" style="color: #FF039E;">< Go back</a>
                <div>
                    <label for="">Sub category name</label>
                    <input type="text" name="name" value="<?php echo $name ?>">
                    <small><?php echo $nameError ?></small>
                </div>
                <div>
                    <label for="">Main category</label>
                    <select name="main-category">
                        <option value='select'>Select main category</option>
                        <?php 
                            $categoryQuery = "select * from main_category";
                            $categoryResult = mysqli_query($connection, $categoryQuery);
                            while($categoryData = mysqli_fetch_assoc($categoryResult)) {
                                echo "<option value='" . $categoryData['main_category_id'] . "'";
                                if ($main_id==$categoryData['main_category_id']) echo "selected";
                                echo ">" . $categoryData['category_name'] . "</option>";
                            }
                        ?>
                    </select>
                    <small><?php echo $mainError ?></small>
                </div>
                <div>
                    <button style="font-size: 16px;padding: 7px 20px;" name="update">Update</button>
                </div>
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
   function sanitizeString($var) {
        $var = stripslashes($var);
        $var = strip_tags($var);
        $var = htmlentities($var);
        return $var;
    }
    function sanitizeMySQL($connection, $var){ 
        $var = mysqli_real_escape_string($connection, $var);
        $var = sanitizeString($var);
        return $var;
    }
    function validate_name($field) {
        if ($field == "") {
            return "Name is required!";
        }
        else if ((preg_match("/[^a-zA-Z0-9 -]/", $field))) {
            return "Name cannot have special characters!";
        }
        return "";
    }
    function validate_main($field) {
        if ($field == "select") {
            return "Main category is required!";
        }
        
        return "";
    }

?>
