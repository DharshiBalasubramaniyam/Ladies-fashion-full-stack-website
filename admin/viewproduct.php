<?php
    session_start();
    if (!isset($_SESSION['admin_id'])) {
        header('location:../account/login.php');exit();
    }
    include('../database/dbconnection.php');

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

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $productResult = mysqli_query($connection, "select * from products where product_id = $id");
        $product_data = mysqli_fetch_assoc($productResult);
        $name = $product_data['product_name'];
        $price = $product_data['price'];
        $description = $product_data['description'];
        $image_url = $product_data['image_url'];

        $catergoryIdResult = mysqli_query($connection, "select * from product_category where product_id = $id");
        $category_id = mysqli_fetch_assoc($catergoryIdResult)['category_id'];

        $types = mysqli_query($connection, 'select sc.sub_category_id, sc.category_name as sub, mc.category_name as main from category sc 
                                    inner join main_category mc on  sc.main_category_id = mc.main_category_id
                                    order by sc.sub_category_id asc');

        $productStockResult = mysqli_query($connection, "select * from product_stock where product_id = $id");

        $sizeResult = mysqli_query($connection, "select * from product_size order by size_name asc");
        $colorResult = mysqli_query($connection, "select * from product_color order by color_name asc");


    }

    $nameError = $priceError = $descriptionError = $main_categoryError = $typeError = $imageError = $sizeError = $colorError = "";

    if (isset($_POST['update-details'])) {
        $name = sanitizeMySQL($connection, $_POST['name']);
        $price = sanitizeMySQL($connection, $_POST['price']);
        $description = sanitizeMySQL($connection, $_POST['description']);
        $type = sanitizeMySQL($connection, $_POST['type']);
        $image_path = "";
        $imageError = "";
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

        $nameError = validate_name($name);
        $priceError = validate_price($price);
        $descriptionError = validate_description($description);
        $typeError = validateSelect($type);

        if ($nameError=="" && $priceError ==""  && $descriptionError =="" && $imageError == "" && $typeError=="") {
            updateDetails($connection, $id, $name, $price, $description, $image_path, $type);
            echo "<script>alert('Product details updated successfully!');window.location.href = 'viewproduct.php?id=$id';</script>";

        }  

    }

    if (isset($_POST['add-stock'])) {
        $size = sanitizeMySQL($connection, $_POST['size']);
        $color = sanitizeMySQL($connection, $_POST['color']);
        $stock = sanitizeMySQL($connection, $_POST['stock']);

        $sizeError = validateSelect($size);
        $colorError = validateSelect($color);
        if ($colorError=="" && $sizeError=="") {
            $sizeError = checkStock($connection, $id, $size, $color, $stock);
            if ($sizeError == "") {
                addStock($connection, $id, $size, $color, $stock);
                echo "<script>alert('Product details updated successfully!');window.location.href = 'viewproduct.php?id=$id';</script>";
            }
        }
    }
    if (isset($_GET['id']) && isset($_GET['stock'])) {
        $stock_id = $_GET['stock'];
        updateQuantity($connection, $stock_id, $_POST[$stock_id]);
        echo "<script>alert('Product details updated successfully!');window.location.href = 'viewproduct.php?id=$id';</script>";
    }

    if (isset($_GET['id']) && isset($_GET['remove'])) {
        deleteStock($connection, $_GET['remove']);
        echo "<script>alert('Product details updated successfully!');window.location.href = 'viewproduct.php?id=$id';</script>";
    }
    if (isset($_POST['delete-product'])) {
        deleteProduct($connection, $id);
        echo "<script>alert('The product deleted succesfully!');window.location.href = 'products.php';</script>";
    }

    
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>view product - pink pearl Admin</title>
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
            <h1 class="h1"><span>View product</span><a href="products.php"><button>< Back to products</button></a></h1>
            <div class="detail-box">
                <img src="../uploadedImages/<?php echo $image_url ?>" alt="">
                <form class="details" action="viewproduct.php?id=<?php echo $id ?>" method="post"  enctype="multipart/form-data">
                    <h2 style="text-transform: uppercase;color:var(--pink)"><?php echo $name; ?></h2>
                    <div class="input-box">
                        <label for="id">Product ID</label><br>
                        <input type="text" id="id" name="id" value="<?php echo $id; ?>" disabled>
                    </div>
                    <div class="input-box">
                        <label for="name">Product Name</label><br>
                        <input type="text" id="name" name="name" value="<?php echo $name; ?>">
                        <small><?php echo $nameError ?></small>
                    </div>
                    <div class="input-box">
                        <label for="price">Unit price</label><br>
                        <input type="text" id="price" name="price" value="<?php echo $price; ?>">
                        <small><?php echo $priceError ?></small>
                    </div>
                    <div class="input-box">
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
                        <div>
                            <select name="type" id="">
                                <option value="select">Select sub category</option>
                                <?php
                                    while($ty = mysqli_fetch_assoc($types)) {
                                        echo "<option value='" . $ty['sub_category_id'] . "'";
                                        if ($ty['sub_category_id'] == $category_id) echo 'selected';
                                        echo ">" . $ty['main'] . " - " . $ty['sub'] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <small><?php echo   $typeError ?></small>
                    </div>

                    <button style="font-size: 16px;padding:10px 15px;margin-bottom:20px;" name="update-details">Update details</button>

                    <div class="input-box">
                        <label for="">Available stock quantity</label>
                        <table border="1px">

                            <?php
                                while($stock = mysqli_fetch_assoc($productStockResult)) {
                                    echo "<tr><td>" . getSize($connection, $stock['size_id']) . "</td>
                                    <td>" . getColor($connection, $stock['color_id']) . "</td>
                                    <td style=''>
                                        <input type='number' name='" . $stock['stock_id'] ."' value='" . $stock['stock_qty'] . "'>
                                        <button style='margin-left:15px;font-size:14px;padding:5px 8px;' onclick='handleUpdate($id, " . $stock['stock_id'] . ")'>Update</button>
                                    </td>
                                    <td><button  style='font-size:14px;padding:5px 8px;' onclick='handleRemove($id, " . $stock['stock_id'] . ")'>Remove</button></td></tr>";
                                }
                            ?>

                        </table>
                    </div>
                    
                    <div class="input-box">
                        <label for="">Add new stock</label>
                        <div>
                            <select name="size" id="">
                                <option value="select">Select size</option>
                                <?php
                                    while($size = mysqli_fetch_assoc($sizeResult)) {
                                        echo "<option value='" . $size['size_id'] . "'>" . $size['size_name'] . "</option>";
                                    }
                                ?>
                            </select>
                            <select name="color" id="">
                                <option value="select">Select color</option>
                                <?php
                                    while($color = mysqli_fetch_assoc($colorResult)) {
                                        echo "<option value='" . $color['color_id'] . "'>" . $color['color_name'] . "</option>";
                                    }
                                ?>
                            </select>
                            <input type="number" name="stock" id="" placeholder="Enter stock quantity" value="0" min="0">
                        </div>
                        <small><?php echo $sizeError . " " . $colorError ?></small>
                        <div style="display: flex;align-items:center">
                            <button style="font-size: 16px;padding:10px 15px;margin-top:10px;" name="add-stock">Add stock</button>
                            <a href="#" style="color: var(--pink);display:inline-block;" onclick="openSize()">Add new size</a>
                            <a href="#" style="color: var(--pink);" onclick="openColor()">Add new color</a>
                        </div>
                        
                    </div>

                    <div class="input-box">
                        <label for="">Delete this product from the system</label>
                        <button type="submit" style="background-color:red;padding:7px 15px;font-size:16px;margin-top:8px;" name="delete-product" onclick="return confirm('Are you sure? Do you want to delete this product?')">Delete</button>
                    </div>

                    <section class="pop-up-container">
                            <div class="add-color">
                                <i class="fas fa-times"></i>
                                <h2>Add new color</h2>
                                <input type="text" name="new-color" placeholder="Enter color name">
                                <button type="submit" name="add-color">Add color</button>
                            </div>
                            <div class="add-size">
                                <i class="fas fa-times"></i>
                                <h2>Add new size</h2>
                                <input type="text" name="new-size" placeholder="Enter size name">
                                <button type="submit" name="add-size">Add size</button>
                            </div>
                    </section>


                </form>
            </div>
        </div>
        
    </main>

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
        function handleUpdate(pid, sid) {
            let form = document.querySelector(".details");
            let action = "viewproduct.php?id=" + pid + "&stock=" + sid; 
            form.setAttribute('action', action);
            form.submit();
        }
        function handleRemove(pid, sid) {
            let form = document.querySelector(".details");
            let action = "viewproduct.php?id=" + pid + "&remove=" + sid; 
            form.setAttribute('action', action);
            form.submit();
        }
    </script>

    <script src="admin.js"></script>
    
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
            return "Product name is required!";
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
    function validate_image($image) {
        if ($image['size']!=0) {
            $extension =strtolower(pathinfo($image['name'], PATHINFO_EXTENSION)) ;
            if ($extension!='jpg' && $extension!='jpeg' && $extension!='png' && $extension!='webp') {
                return "You can only upload .jpg, .jpeg, .png files only!";
            }
        }
        return "";

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
    function updateDetails($connection, $id, $name, $price, $description, $image_path, $type){
        if ($image_path == "") {
            mysqli_query($connection, "update products set product_name = '$name', price = $price, description = '$description' where product_id = $id");
        }else {
            mysqli_query($connection, "update products set product_name = '$name', price = $price, description = '$description', image_url = '$image_path' where product_id = $id");
        }

        mysqli_query($connection, "update product_category set category_id = $type where product_id = $id") ;
       

    }

    function addStock($connection, $id, $size, $color, $stock) {
        mysqli_query($connection, "insert into product_stock(product_id, size_id, color_id, stock_qty) values ($id, $size, $color, $stock)");
    }
    function checkStock($connection, $id, $size, $color) {
        $is_already_exists = mysqli_query($connection, "select * from product_stock where product_id = $id and size_id = $size and color_id = $color");
        if (mysqli_num_rows($is_already_exists) > 0) {
            return "The stock already exists!";
        }
        return "";
    }

    function updateQuantity($connection, $stock_id, $qty){
        mysqli_query($connection, "update product_stock set stock_qty = $qty where stock_id = $stock_id") ;
    }
    function deleteStock($connection, $stock_id) {
        mysqli_query($connection, "delete from product_stock where stock_id = $stock_id");
    }
    function deleteProduct($connection, $id){
        mysqli_query($connection, "delete from products where product_id = $id");
        mysqli_query($connection, "delete from product_stock where product_id = $id");
        mysqli_query($connection, "delete from product_category where product_id = $id");

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