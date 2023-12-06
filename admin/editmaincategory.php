<?php
    session_start();
    if (!isset($_SESSION['admin_id'])) {
        header('location:../account/login.php');exit();
    }
    include('../database/dbconnection.php');


    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $categoryResult = mysqli_query($connection, "select * from main_category where main_category_id = $id");
        $category = mysqli_fetch_assoc($categoryResult);

        $name = $category['category_name'];
        
    } 
    
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];

        mysqli_query($connection, "delete from main_category where main_category_id = $id");
        echo "<script>alert('Main category deleted successfully!');window.location.href = 'categories.php'</script>";
    } 
    $imageError = "";$nameError = "";

    
    if (isset($_POST['update'])) {
        $name = sanitizeMySQL($connection, $_POST['name']);
        $nameError = validate_name($name);

        $image_path = "";
        if (isset($_FILES['image']) ) {
            $imageError = validate_image($_FILES['image']);
            if ($imageError == "" && $_FILES['image']['size']!=0) {
                $image_path = basename($_FILES['image']['name']);
                if (move_uploaded_file($_FILES['image']['tmp_name'], '../uploadedImages/'.$image_path)) {
                }else {
                    $imageError = "Error occured in uploading image";
                }
            }
        }

        if($nameError=="" && $imageError=="") {
            if ($image_path=="") {
                mysqli_query($connection, "update main_category set category_name = '$name' where main_category_id = $id");
            }else {
                mysqli_query($connection, "update main_category set category_name = '$name', image_url= '$image_path' where main_category_id = $id");
            }
            echo "<script>alert('Main category updated successfully!');window.location.href = 'categories.php'</script>";
        }

    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit main category - pink pearl</title>
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

            <h1>Edit main category</h1>
            
            <form action="" method="post" enctype="multipart/form-data" class="form">
                <a href="categories.php" style="color: #FF039E;">< Go back</a>
                <div class="input-group">
                    <label for="">Main category name</label>
                    <input type="text" name="name" value="<?php echo $name ?>">
                    <small><?php echo $nameError ?></small>
                </div>
                <div class="input-group">
                    <label for="">New image</label>
                    <input type="file" name="image" accept=".jpeg, .jpg, .png, .webp">
                    <small><?php echo $imageError ?></small>
                </div>
                <div class="input-group">
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
    function validate_image($image) {
        if ($image['size']!=0) {
            $extension =strtolower(pathinfo($image['name'], PATHINFO_EXTENSION)) ;
            if ($extension!='jpg' && $extension!='jpeg' && $extension!='png' && $extension!='webp') {
                return "You can only upload .jpg, .jpeg, .png files only!";
            }
        }
        return "";

    }


    

?>
