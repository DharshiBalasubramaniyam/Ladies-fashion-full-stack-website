<?php

    include('../main-header/header.php');
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        header('location:../account/login.php');exit();
    }

    $user_id = $_SESSION['user_id'];
    $reviews_result = mysqli_query($connection, "select * from reviews r inner join users on (r.user_id=users.user_id) where r.user_id = $user_id");

    if(isset($_POST['msg'])){
        unset($_SESSION['message']);
    }

    // display messages
    if(isset($_SESSION['message'])){
            echo '
                <form class="message-wrapper" action="wishlist.php" method="post">
                    <div class="message">
                        <span>' . $_SESSION['message'] . '</span>
                        <button class="fas fa-times" name="msg"></button>
                    </div>
                </form>
            
            ';
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../shop/style.css">
    <title>My reviews - Pink Pearl</title>
</head>
<body>

    <div class="myaccount">
        <div class="header">
            <p>Welcome, <?php echo getUserName($connection); ?>!</p>
            <h1>Your Reviews</h1>
            <div class="options">
                <span><a href="orders.php">Orders</a></span> 
                <span><a href="wishlist.php">Wishlist</a></span>
                <span><a href="cart.php">Cart</a></span>
                <span><a href="review.php"  class="active">Reviews</a></span>
                <span><a href="profile.php">Profile</a></span>
            </div>
        </div>

        <div class="content">
            <?php
                if (mysqli_num_rows($reviews_result)>0) {
                    echo "<a href = 'addreview.php'><button>+ Leave a review</button></a>";

                    echo "<div class='review_box_container' style='justify-content:left;padding:0;margin-top:25px;'>";
                    while ($row = mysqli_fetch_assoc($reviews_result)){
            ?>

                        <div class="review_box">
                            <div class="box_top">
                                <div class="profile">
                                    <div class="profile_img">
                                        <i class="fa-solid fa-user"></i>
                                    </div>

                                    <div class="name_user">
                                        <strong><?php echo $row['username']; ?></strong>
                                        <span><?php echo $row['email']; ?></span>
                                    </div>
                                </div>       

                                <div class="reviews">
                                    <?php
                                        for ($i=0; $i < $row['num_of_stars']; $i++) {
                                            echo "<i class='fa-solid fa-star' style='color: var(--pink);'></i>";
                                        }
                                        for ($i=0; $i < 5-$row['num_of_stars']; $i++) {
                                            echo "<i class='fa-solid fa-star' style='color: var(--lightpink);'></i>";
                                        }

                                    ?>
                                    
                                </div>
                            </div>

                            <div class="comments">
                                <p><?php echo $row['comment']; ?></p>
                            </div>

                            <div class="date">
                                <span><?php echo $row['date']; ?></span>
                            </div>

                        </div>
            
            <?php
                    }
                    echo "</div>";
                }else {
                    echo "<div class='empty-order-history'>
                            <h3 style='margin-bottom:25px;'>You haven't written any reviews!</h3>
                            <a href = 'addreview.php'><button>Leave a review</button></a>
                        </div>";
                }
            ?>
        </div>
        
    </div>   

    <?php include('../shop/footer.php') ?>

    <script src="myaccount.js"></script>

</body>
</html>

<?php
    function getUserName($connection) {

        $query = 'select username from users where user_id = ' . $_SESSION['user_id'];
        $result = mysqli_query($connection, $query);

        $name = mysqli_fetch_assoc($result)['username'];

        return $name;

    }
?>