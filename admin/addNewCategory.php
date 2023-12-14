<?php
    session_start();
    if (!isset($_SESSION['admin_id'])) {
        header('location:../account/login.php');exit();
    }
    include('../database/dbconnection.php');


    $main1 = $main2 = $mainError1 = $mainError2 = $sub = $subError = $image_path = $imageError = $description = $descriptionError = "";

    if (isset($_POST['add-main'])) {
        $main1 = sanitizeMySQL($connection, $_POST['main1']);
        $mainError1 = validate_name($main1);
        $imageError = validate_image($_FILES['image']);
        $description = sanitizeMySQL($connection, $_POST['description']);
        $descriptionError = validate_description($description);

        if ($imageError == "" && $_FILES['image']['size']!=0) {
            $image_path = basename($_FILES['image']['name']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], '../uploadedImages/'.$image_path)) {
            }else {
                $imageError = "Error occured in uploading image";
            }
        }
        if($mainError1=="" && $imageError=="") {
            mysqli_query($connection, "insert into main_category(category_name, description, image_url) values('$main1', '$description', '$image_path')");
            echo "<script>alert('Main category added successfully!');window.location.href = 'addNewCategory.php'</script>";
        }
    }
    if (isset($_POST['add-sub'])) {
        $main2 = $_POST['main-category'];
        $sub = $_POST['sub-category'];
        $subError = validate_name($sub);
        if ($main2 == "select") {
            $mainError2 = "Main category is required!";
        }
        if ($mainError2=="" && $subError==""){
            mysqli_query($connection, "insert into category (main_category_id, category_name) values ($main2, '$sub')");
            echo "<script>alert('New sub category added succesfully!');window.location.href = 'addNewCategory.php';</script>";
        }
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add new category - pink pearl</title><link rel="stylesheet" href="../index.css">
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
                    <a href="addNewCategory.php" class="active"><i class='bx bxs-chevron-down-square'></i>
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

        <div class="container">

            <form action="" method="post" enctype="multipart/form-data" class="form">
                <h2 style="font-weight: 500;color:var(--pink);text-align:center;">Add new main category</h2>

                <div class="input-group">
                    <label for="">Main category name</label><br>
                    <input type="text" name="main1" value="<?php echo $main1 ?>">
                    <br><small><?php echo $mainError1 ?></small>
                </div>
                <div class="input-group">
                    <label for="">Main category description</label>
                    <input type="text" name="description" value="<?php echo $description ?>">
                    <small><?php echo $descriptionError ?></small>
                </div>
                <div class="input-group">
                    <label for="">New image</label><br>
                    <input type="file" name="image" accept=".jpeg, .jpg, .png, .webp">
                    <br><small><?php echo $imageError ?></small>
                </div>
                <div class="input-group">
                    <button style="font-size: 16px;padding: 7px 20px;" name="add-main">Add category</button>
                </div>
            </form>

            <form action="" method="post" class="form">
                <br><h2 style="font-weight: 500;color:var(--pink);text-align:center;">Add new sub category</h2>
                <div class="input-group">
                    <label for="">Sub category name</label><br>
                    <input type="text" name="sub-category" value="<?php echo $sub ?>">
                    <br><small><?php echo $subError ?></small>
                </div>
                <div class="input-group">
                    <label for="">Main category</label><br>
                    <select name="main-category">
                        <option value='select'>Select main category</option>
                        <?php 
                            $categoryQuery = "select * from main_category";
                            $categoryResult = mysqli_query($connection, $categoryQuery);
                            while($categoryData = mysqli_fetch_assoc($categoryResult)) {
                                echo "<option value='" . $categoryData['main_category_id'] . "'";
                                if ($main2==$categoryData['main_category_id']) echo "selected";
                                echo ">" . $categoryData['category_name'] . "</option>";
                            }
                        ?>
                    </select>
                    <br><small><?php echo $mainError2 ?></small>
                </div>
                <div class="input-group">
                    <button style="font-size: 16px;padding: 7px 20px;" name="add-sub">Add category</button>
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
    if ($image['size']==0) {
        return "Image is required!";
    }
     if ($image['size']!=0) {
        $extension =strtolower(pathinfo($image['name'], PATHINFO_EXTENSION)) ;
        if ($extension!='jpg' && $extension!='jpeg' && $extension!='png' && $extension!='webp') {
            return "You can only upload .jpg, .jpeg, .png files only!";
        }
    }
    return "";

}
function validate_description($field) {
    if ($field == "") {
        return "Description is required!";
    }
    else if (strlen($field)>100) {
        return "Decription can have atmost 100 characters!";
    }
    return "";
}

?>