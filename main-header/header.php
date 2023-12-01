<?php

    session_start();
    include('../database/dbconnection.php');

    $no_of_wishlist_items = 0;
    $no_of_cart_items = 0;

    if (isset($_SESSION['isLogged']) && $_SESSION['isLogged']) {
        $no_of_wishlist_items = getNoOfWishlistItems($connection, $_SESSION['user_id']);
        $no_of_cart_items = getNoOfCartItems($connection, $_SESSION['user_id']);
    }

    if (isset($_POST['search'])) {
        $search_word = $_POST['search-word'];
        
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/c732ec9339.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../index.css">
    <link rel="stylesheet" href="../../Ladies-fashion-full-stack-website//main-header//header.css">
</head>
<body>

    <header>
        <div class="top-header">
            <div class="logo"><a href="index.php"><img src="../uploadedImages/logo.png" alt="Pink-pearl-logo"></a></div>
            <form class="search laptop-setup " action="header.php" method='post'>
                <input type="text" placeholder="Search entire fashion here" name='search-word'><button type="submit" name="search"><i class="fa fa-search" aria-hidden="true"></i></button>
            </form>
            <div class="top-header-right">
                <?php

                    if (isset($_SESSION['isLogged']) && $_SESSION['isLogged']) {
                        echo "<span class='user laptop-setup'>" . 
                                 "<a href='../account/logout.php'>sign out</a><a href='../myaccount/orders.php'><i class='fa fa-user-circle-o' aria-hidden='true'></i></a>" .
                             "</span>";
                    }
                    else {
                        echo "<span class='laptop-setup'><a href='../account/login.php'>Sign in  </a> / <a href='../account/register.php'>Sign up</a></span>";
                        
                    }

                ?>
                <span class="mobile-setup"><i class="fa fa-search" aria-hidden="true"  onclick="handleSearch()"></i></span>
                <span><a href="../myaccount/wishlist.php"><i class="fa fa-heart" aria-hidden="true"></i></a><span><?php echo $no_of_wishlist_items; ?></span></span>
                <span><a href="../myaccount/cart.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a><span><?php echo $no_of_cart_items; ?></span></span>
                <span class="menu mobile-setup"><i class="fa fa-bars toggle-menu" aria-hidden="true" onclick="handleNav()"></i></span>
            </div>
        </div>

        <div class="bottom-header">
            <ul>
                <?php
                    if (!isset($_SESSION['isLogged']) || !$_SESSION['isLogged']) {
                        echo "<li class='mobile-setup'><a href='../account/login.php'>Sign in</a></li>";
                    }
                ?>
                
                <li><a href="./fashion/newin.php">New in</a> </li>
                <li><a href="./fashion/officewear.php">Office wear</a> </li>
                <li><a href="./fashion/casualwear.php">Casual wear</a> </li>
                <li><a href="./fashion/partywear.php">Party wear</a> </li>
                <li><a href="./fashion/footwear.php">Footwear</a> </li>
                <li><a href="./fashion/accessories.php">Accessories</a> </li>
                <li><a href="./fashion/beauty.php">Beauty</a> </li>
                <?php
                    if (isset($_SESSION['isLogged']) && $_SESSION['isLogged']) {
                        echo "<li class='mobile-setup'><a href='../myaccount/orders.php'>My Account</a> </li>" . 
                             "<li class='mobile-setup'><a href='../account/logout.php'>Sign out</a> </li>";
                    }
                ?>
            </ul>
        </div>
        <div class="search-body mobile-setup">
            <i class="fa fa-arrow-left" aria-hidden="true" onclick="handleSearch()"></i>
            <form class="search" action="header.php" method='post'>
                <input type="text" placeholder="Search entire fashion here" name='search-word'><button  type="submit" name="search"><i class="fa fa-search" aria-hidden="true"></i></button>
            </form>
        </div>
    </header>

    <script>
        const nav = document.querySelector(".bottom-header");
        const search = document.querySelector(".search-body");


        function handleNav() {
            if (nav.classList.contains("open")) {
                nav.classList.remove("open");
                document.querySelector(".menu").innerHTML = `<i class="fa fa-bars" aria-hidden="true" onclick="handleNav()"></i>`;

            }else {
                nav.classList.add("open");
                document.querySelector(".menu").innerHTML = `<i class="fa fa-times" aria-hidden="true" onclick="handleNav()"></i>`;
            }
        }

        function handleSearch(){
            if (search.classList.contains("open")) {
                search.classList.remove("open");

            }else {
                search.classList.add("open");
            }
        }
        </script>
    
</body>
</html>


<?php
    function getNoOfWishlistItems($connection, $id) {
        $sql = "select * from wishlist where user_id=" . $id ;
        $result = mysqli_query($connection, $sql);

        $noOfItems = mysqli_num_rows($result);

        return $noOfItems;
    }
    
    function getNoOfCartItems($connection, $id) {
        $sql = "select sum(quantity) as sum from shopping_cart where user_id=" . $id ;

        $result = mysqli_query($connection, $sql);

        $noOfItems = mysqli_fetch_assoc($result)['sum'];

        if ($noOfItems>0) return $noOfItems;
        return 0;

    }

?>