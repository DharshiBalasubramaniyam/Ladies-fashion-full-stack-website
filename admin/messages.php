<?php

    session_start();
    if (!isset($_SESSION['admin_id'])) {
        header('location:../account/login.php');exit();
    }
    include('../database/dbconnection.php');

    $orderby = 'newest';

    if (isset($_GET['orderby'])) {
        $orderby = $_GET['orderby'];
    }

    if ($orderby == 'newest') 
        $messagesResult = mysqli_query($connection, "select * from messages order by date desc");
    else if ($orderby == 'oldest') 
        $messagesResult = mysqli_query($connection, "select * from messages order by date asc");

    if (isset($_GET['del_msg'])) {
        $id = $_GET['del_msg'];
        mysqli_query($connection, "delete from messages where message_id = $id");
        echo "<script>alert('message deleted successfully!');window.location.href = 'messages.php';</script>";
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - pink pearl</title>
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
                    <a href="categories.php"><i class="fa fa-cubes" aria-hidden="true"></i>
                    <span>View Categories</span></a>
                </li>
                <li>
                    <a href="customers.php"><i class='bx bxs-group'></i>
                    <span>View customers</span></a>
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
                    <a href="messages.php" class="active"><i class='bx bx-message-detail'></i>
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

            <h1>Customer Messages</h1>

            <div class="top-header">
                <a href="messages.php?orderby=newest"><button style="font-size: 16px;padding:5px 12px" class="<?php if($orderby=='oldest') echo "unactive"; else echo "";?>">Newest</button></a>
                <a href="messages.php?orderby=oldest"><button style="font-size: 16px;padding:5px 12px" class="<?php if($orderby=='newest') echo "unactive"; else echo "";?>">Oldest</button></a>
            </div>

            <div class="messages-wrapper">

                <?php while($msg = mysqli_fetch_assoc($messagesResult)) { ?>
                    <div class="message-box">
                        <div class="box_top">
                            <div class="profile">
                                <span class="profile_img">
                                        <i class="fa-solid fa-user"></i>
                                </span>

                                <span class="name_user">
                                    <span style="font-weight: 600;text-transform:uppercase;"><?php echo $msg['name'] ?></span>
                                    <span><?php echo $msg['email'] ?></span>
                                </span>
                            </div>
                            <a href="messages.php?del_msg=<?php echo $msg['message_id'] ?>"><button type='submit' name="delete_msg" class="delete"><i class="fa fa-trash-o" aria-hidden="true"></i>Delete</button></a>
                        </div>

                        <div class="comments">
                            <?php echo $msg['message'] ?>
                        </div>

                        <div class="date">
                            <?php echo $msg['date'] ?>
                        </div>
                    </div>
                <?php } ?>
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