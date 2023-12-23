<?php

    include('../main-header/header.php');
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        header('location:../account/login.php');exit();
    }

    if (isset($_POST['add-review'])) {
        $no_of_stars = 0;
        if (isset($_POST['star1'])) $no_of_stars++;
        if (isset($_POST['star2'])) $no_of_stars++;
        if (isset($_POST['star3'])) $no_of_stars++;
        if (isset($_POST['star4'])) $no_of_stars++;
        if (isset($_POST['star5'])) $no_of_stars++;

        if (isset($_POST['review']) && !empty($_POST['review']) && $no_of_stars!=0) {
            $review = $_POST['review'];
            $user_id = $_SESSION['user_id'];

            mysqli_query($connection, "insert into reviews(user_id, comment, num_of_stars) values($user_id, '$review', $no_of_stars)");

            header('location:review.php');
        }
    }
    if (isset($_POST['cancel'])) {
        header('location:review.php');
    }

    
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
    <title>Leave a review - Pink Pearl</title>
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

        <!-- <div class="content"> -->
            <form action="" method="post">
                <h2 style="padding:5px 0;border-bottom:1px solid rgb(186, 186, 186);margin-bottom:10px;">Write a review</h2>                
                <div class="input-box" style="margin: 25px 0;width:100%;;display:flex;gap:20px;justify-content:center">
                    <label for="star1"><i class='fa-solid fa-star star'></i></label><input type="checkbox" name="star1" id="star1">
                    <label for="star2"><i class='fa-solid fa-star star'></i></label><input type="checkbox" name="star2" id="star2">
                    <label for="star3"><i class='fa-solid fa-star star'></i></label><input type="checkbox" name="star3" id="star3">
                    <label for="star4"><i class='fa-solid fa-star star'></i></label><input type="checkbox" name="star4" id="star4">
                    <label for="star5"><i class='fa-solid fa-star star'></i></label><input type="checkbox" name="star5" id="star5">
                </div>
                <div class="input-box">
                    <textarea name="review" cols="30" rows="10" placeholder="Share your thoughts about your experience"></textarea>
                </div>
                <div class="input-box" style="margin: 25px 0;width:100%;;display:flex;gap:20px;justify-content:center">
                    <button type="submit" name="cancel" style="background-color: transparent;border:2px solid var(--pink);color:var(--pink)">Cancel</button>
                    <button type="submit" name="add-review">Submit</button>

                </div>
            <!-- </form> -->
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