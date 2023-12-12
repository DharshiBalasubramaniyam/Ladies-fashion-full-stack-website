<?php
    session_start();
    if (!isset($_SESSION['admin_id'])) {
        header('location:../account/login.php');exit();
    }
    include('../database/dbconnection.php');

    $name = $price = $description = $main_category = $sub_category = $image_path = "";
    $nameError = $priceError = $descriptionError = $main_categoryError = $sub_categoryError = $imageError = $categoryError = $sizeError = $colorError = "";
    // $_SESSION['stock'] = array();
    // unset($_SESSION['stock']);


    if(isset($_POST['add-product'])) {
        $name = sanitizeMySQL($connection, $_POST['name']); 
        $price = sanitizeMySQL($connection, $_POST['price']);
        $description = sanitizeMySQL($connection, $_POST['description']);
        $main_category = sanitizeMySQL($connection, $_POST['main-category']);
        $sub_category = sanitizeMySQL($connection, $_POST['sub_category']);

        $nameError = validate_name($name);
        $priceError = validate_price($price);
        $descriptionError = validate_description($description);
        $main_categoryError = validateSelect($main_category);
        $sub_categoryError = validateSelect($sub_category);
        if ($sub_categoryError=="" && $main_categoryError=="") {
            $categoryError = validate_category($connection, $main_category, $sub_category);
        }
        $imageError = validate_image($_FILES['image']);
        if ($imageError == "" && $_FILES['image']['size']!=0) {
            $image_path = basename($_FILES['image']['name']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], '../uploadedImages/'.$image_path)) {
            }else {
                $imageError = "Error occured in uploading image";
            }
        }

        if (!isset($_SESSION['stock']) || count($_SESSION['stock'])==0) {
            $sizeError = "stock is required!";
        }

        if ($nameError=="" && $priceError ==""  && $descriptionError =="" && $imageError == "" && $sub_categoryError=="" && $main_categoryError=="" && $categoryError=="") {
            $product_id = add_product($connection, $name, $price, $description, $image_path, $main_category, $sub_category);
            if (isset($_SESSION['stock'])) {
                add_stock($connection, $product_id);
            }
            unset($_SESSION['stock']);
            echo "<script>alert('New product added successfully!');window.location.href = 'addnewproduct.php';</script>";

        } 
    }

    if (isset($_POST['add-stock'])) {
        $name = sanitizeMySQL($connection, $_POST['name']);
        $price = sanitizeMySQL($connection, $_POST['price']);
        $description = sanitizeMySQL($connection, $_POST['description']);
        $main_category = sanitizeMySQL($connection, $_POST['main-category']);
        $sub_category = sanitizeMySQL($connection, $_POST['sub_category']);
        $size = sanitizeMySQL($connection, $_POST['size']);
        $color = sanitizeMySQL($connection, $_POST['color']);
        $qty = sanitizeMySQL($connection, $_POST['stock']);

        $sizeError = validateSelect($size);
        $colorError = validateSelect($color);
        if ($colorError=="" && $sizeError=="") {
            $stk = array('size'=>$size, 'color'=>$color, 'qty'=>$qty);
            // array_push($_SESSION['stock'], $stk);
            $_SESSION['stock'][] = $stk;

        }

    }

    if (isset($_GET['delstk'])) {
        unset($_SESSION['stock'][$_GET['delstk']]);
        echo "<script>window.location.href = 'addnewproduct.php';</script>";

    }

    if(isset($_POST['add-color'])) {
        $new_color = sanitizeMySQL($connection, $_POST['new-color']);
        if ($new_color=="") {
            echo "<script>alert('color name is required!')</script>";
        }elseif(preg_match("/[^a-zA-Z -]/", $new_color)){
            echo "<script>alert('Invalid color name!')</script>";
        }else {
            addColor($connection, $new_color);
        }
    }

    if(isset($_POST['add-size'])) {
        $new_size = sanitizeMySQL($connection, $_POST['new-size']);
        if ($new_size=="") {
            echo "<script>alert('size name is required!')</script>";
        }elseif(preg_match("/[^a-zA-Z0-9 -'\"]/", $new_size)){
            echo "<script>alert('Invalid size name!')</script>";
        }else {
            addSize($connection, $new_size);
        }
    }


    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add new product - pink pearl</title>
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
                    <a href="categories.php"><i class="fa fa-cubes" aria-hidden="true"></i>
                    <span>View Categories</span></a>
                </li>
                <li>
                    <a href="addnewproduct.php" class="active"><i class='bx bxs-caret-down-square' ></i>
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
            <h1>Add new product</h1>
        
                <form action="" method="post" class="form" enctype="multipart/form-data">

                    <div class="input-group">
                        <label for="name">Product Name</label><br>
                        <input type="text" id="name" name="name" value="<?php echo $name; ?>">
                        <small><?php echo $nameError ?></small>
                    </div>

                    <div class="input-box">
                        <label for="price">Unit price</label><br>
                        <input type="text" id="price" name="price" value="<?php echo $price; ?>">
                        <small><?php echo $priceError ?></small>
                    </div>

                    <div class="input-group">
                        <label for="description">Description</label><br>
                        <textarea type="text" id="description" name="description" rows="3"><?php echo $description; ?></textarea>
                        <small><?php echo $descriptionError ?></small>
                    </div>

                    <div class="input-box">
                        <label for="">Edit product image</label>
                        <input type="file" name="image" accept=".jpeg, .jpg, .png, .webp">
                        <small><?php echo $imageError ?></small>
                    </div>

                    <div class="input-box">
                        <label for="">Category</label><br>
                        <div class="row">
                            
                            <select name="main-category" id="">
                                <option value="select">Select main category</option>
                                <?php
                                    $mainCategories = mysqli_query($connection, 'select * from main_category');
                                    while($main = mysqli_fetch_assoc($mainCategories)) {
                                        echo "<option value='" . $main['main_category_id'] . "'";
                                        if ($main['main_category_id'] == $main_category) echo 'selected';
                                        echo ">" . $main['category_name'] . "</option>";
                                    }
                                ?>
                            </select>
                            <select name="sub_category" id="">
                                <option value="select">Select sub category</option>
                                <?php
                                    $types = mysqli_query($connection, 'select * from category');
                                    while($ty = mysqli_fetch_assoc($types)) {
                                        echo "<option value='" . $ty['sub_category_id'] . "'";
                                        if ($ty['sub_category_id'] == $sub_category) echo 'selected';
                                        echo ">" . $ty['category_name'] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <small><?php echo $main_categoryError . " " .  $sub_categoryError . " " . $categoryError; ?></small>
                    </div>

                    <div class="input-box">
                        <label for="">Add stock</label>
                        <?php if (isset($_SESSION['stock'])) { ?>
                            <table border="1px">

                            <?php
                                foreach($_SESSION['stock'] as $key => $stk) {
                                    echo "<tr>
                                        <td>" . getSize($connection, $stk['size']) . "</td>
                                        <td>" . getColor($connection,  $stk['color']) . "</td>
                                        <td style=''>
                                            <input type='text' name='" .  $stk['qty'] ."' value='" . $stk['qty'] . "' disabled>
                                        </td>
                                        <td><a href='addnewproduct.php?delstk=$key' style='color:var(--pink)'>Remove</a></td>
                                    </tr>";
                                }
                            ?>

                        </table>
                        <?php } ?>
                        <div class="row">
                            <select name="size" id="">
                                <option value="select">Select size</option>
                                <?php
                                    $sizeResult = mysqli_query($connection, "select * from product_size");
                                    while($size = mysqli_fetch_assoc($sizeResult)) {
                                        echo "<option value='" . $size['size_id'] . "'>" . $size['size_name'] . "</option>";
                                    }
                                ?>
                            </select>
                            <select name="color" id="">
                                <option value="select">Select color</option>
                                <?php
                                    $colorResult = mysqli_query($connection, "select * from product_color");
                                    while($color = mysqli_fetch_assoc($colorResult)) {
                                        echo "<option value='" . $color['color_id'] . "'>" . $color['color_name'] . "</option>";
                                    }
                                ?>
                            </select>
                            <input type="number" name="stock" id="" placeholder="Enter stock quantity" value="0" min="0">
                        </div>
                        <small><?php echo $sizeError . " " . $colorError ?></small>
                        <div class="row" style="gap:20px;margin-top:10px;">
                            <button style="font-size: 14px;padding:5px 5px;width:max-content;" name="add-stock" class="add-stock">Add stock</button>
                            <a href="#" style="color: var(--pink);" onclick="openSize()">Add new size</a>
                            <a href="#" style="color: var(--pink);" onclick="openColor()">Add new color</a>
                        </div>
                    </div>


                    <div class="input-group">
                        <button type="submit" name="add-product" style="margin:0 auto;">Add product</button>
                    </div>
                </form>
        </div>
    </main>

    <div class="pop-up-container">
        <form class="add-color" method="post" action="addnewproduct.php">
            <i class="fas fa-times"></i>
            <h2>Add new color</h2>
            <input type="text" name="new-color" placeholder="Enter color name">
            <button type="submit" name="add-color">Add color</button>
        </form>

        <form class="add-size" method="post" action="addnewproduct.php">
            <i class="fas fa-times"></i>
            <h2>Add new size</h2>
            <input type="text" name="new-size" placeholder="Enter size name">
            <button type="submit" name="add-size">Add size</button>
        </form>
    </div>

    <footer>
        &copy; copyright  @ <?php echo date('Y'); ?> by <span style="color: var(--pink);">Pink Pearl</span>
    </footer>

    <script>
        // const sidebar = document.querySelector('.side-bar');
        // function handleNav() {
        //     if (sidebar.classList.contains("open")) {
        //         sidebar.classList.remove("open");
        //     }else {
        //         sidebar.classList.add("open");
        //     }
        // }
    </script>

    <script src="admin.js"></script>
</body>
</html>


<?php
    function sanitizeString($var) {
        $var = stripslashes($var);
        $var = strip_tags($var);
        $var = htmlentities($var);
        $var = trim($var);
        return $var;
    }
    function sanitizeMySQL($connection, $var){ 
        $var = mysqli_real_escape_string($connection, $var);
        $var = sanitizeString($var);
        return $var;
    }

    function validate_name($field) {
        if ($field == "") {
            return "Product name is required!";
        }
        else if ((preg_match("/[^a-zA-Z0-9 -']/", $field))) {
            return "Name cannot have special characters!";
        }
        return "";
    }
    function validate_price($field) {
        if ($field == "") {
            return "Price is required!";
        }
        else if ((preg_match("/[^0-9.]/", $field))) {
            return "price cannot have letters or special characters";
        }
        return "";
    }
    function validate_description($field) {
        return ($field == "") ? "Description is required!" : "";
    }
    function validateSelect($field) {
        return ($field == "select") ? "This field is required!" : "";
    }

    function getCategory($connection, $main_category, $type) {
        $category = mysqli_query($connection, "select sub_category_id from category where main_category_id = $main_category and sub_category_id = $type");

        if (mysqli_num_rows($category)==0) {
            return -1;
        }else {
            return mysqli_fetch_assoc($category)['sub_category_id'];
        }
    }

    function validate_category($connection, $main_category, $type) {
        if (getCategory($connection, $main_category, $type)>0) {
            return "";
        }
        return "Invalid category!";
    }

    function validate_image($image) {
        if ($image['size']==0) {
            return "Image is required!";
        }
        else{
            $extension =strtolower(pathinfo($image['name'], PATHINFO_EXTENSION)) ;
            if ($extension!='jpg' && $extension!='jpeg' && $extension!='png' && $extension!='webp') {
                return "You can only upload .jpg, .jpeg, .png files only!";
            }
        }
        return "";

    }

    function add_product($connection, $name, $price, $description, $image_path, $main_category, $type){
        mysqli_query($connection, "insert into products(product_name, price, description, image_url) 
                                    values ('$name', $price, '$description', '$image_path')");
        
        $product_id_res = mysqli_query($connection, "select product_id from products order by reg_date desc, product_id desc limit 1");
        $product_id = mysqli_fetch_assoc($product_id_res)['product_id'];
        $category_id = getCategory($connection, $main_category, $type);

        echo $product_id;

        mysqli_query($connection, "insert into product_category values ($product_id, $category_id)");

        return $product_id;
    }

    function add_stock($connection, $product_id) {

        foreach($_SESSION['stock'] as $stk) {
            $size = $stk['size'];
            $color = $stk['color'];
            $qty = $stk['qty'];
            mysqli_query($connection, "insert into product_stock(product_id, size_id, color_id, stock_qty) values ($product_id, $size, $color, $qty)");
        }
    }


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

    function addColor($connection, $new_color) {
        $color_exists = mysqli_query($connection, "select * from product_color where color_name = '$new_color'");
        if (mysqli_num_rows($color_exists)>0) {
            echo "<script>alert('The color is already exists!')</script>";
        }else {
            mysqli_query($connection, "insert into product_color(color_name) values ('$new_color')");
            echo "<script>alert('New color added successfully!')</script>";

        }
    }
    function  addSize($connection, $new_size) {
        $size_exists = mysqli_query($connection, "select * from product_size where size_name = '$new_size'");
        if (mysqli_num_rows($size_exists)>0) {
            echo "<script>alert('The size is already exists!')</script>";
        }else {
            mysqli_query($connection, "insert into product_size(size_name) values ('$new_size')");
            echo "<script>alert('New size added successfully!')</script>";

        }
    }

?>
