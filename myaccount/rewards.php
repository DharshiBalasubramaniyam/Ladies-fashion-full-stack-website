<?php

    include('../main-header/header.php');
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        header('location:../account/login.php');exit();
    }
    $user_id = $_SESSION['user_id'];
    $rewards_result = mysqli_query($connection, "select current_point_balance from users where user_id = $user_id");
    $points = mysqli_fetch_assoc($rewards_result)['current_point_balance'];

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
    <title>My rewards - Pink Pearl</title>

</head>
<body>

    <div class="myaccount">
        <div class="header">
            <p>Welcome, <?php echo getUserName($connection); ?>!</p>
            <h1>Your Rewards</h1>
            <div class="options">
                <span><a href="rewards.php" class="active">Rewards</a></span> 
                <span><a href="orders.php">Orders</a></span> 
                <span><a href="wishlist.php">Wishlist</a></span>
                <span><a href="cart.php">Cart</a></span>
                <span><a href="review.php">Reviews</a></span>
                <span><a href="profile.php">Profile</a></span>
            </div>
        </div>

        <div class="rewards-content">
            <div class="points-wrapper">
                <p>You have earned</p>
                <h1><?php echo $points ?></h1>
                <p>POINTS</p>
            </div>
            <div class="reward-description">
                <span style="font-weight: 600;">Welcome to our rewards program!</span><br>
                Earn 1 point for every 100 LKR spent - that's 1 LKR back for every point earned! Accumulate points with each purchase and redeem them at checkout for instant discounts on your favourite fashion finds. Stay earning and saving today!
            </div>
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