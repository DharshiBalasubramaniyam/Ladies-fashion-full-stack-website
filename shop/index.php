<?php

  include('../main-header/header.php');
  if(isset($_POST['msg'])){
    unset($_SESSION['message']);
  }

  // display messages
  if(isset($_SESSION['message'])){
        echo '
            <form class="message-wrapper" action="index.php" method="post">
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
    <title>Home - Pink-Pearl</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../index.css">
    <link rel="stylesheet" href="./main-header//header.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php  ?>

    <section class="home" id="home">
        <div class="swiper home-slider">
          <div class="swiper-wrapper">

                <?php 
                    $main_category_res = mysqli_query($connection, "SELECT * FROM main_category")
                    or die('Category query failed');
                    $main_categories = mysqli_fetch_all($main_category_res, MYSQLI_ASSOC);
                    
                    foreach ($main_categories as $main) {
                      $image = $main['image_url'];$name = $main['category_name'];$description = $main['description'];$id = $main['main_category_id'];
                      echo "<div class='swiper-slide slide' style='background: rgba(0,0,0,0.5)url(../uploadedImages/$image);background-repeat:no-repeat; background-size:cover;background-blend-mode: darken;'>
                              <div class='content'>
                                <span style='text-transform:uppercase;'>$name</span>
                                <h3>$description</h3>
                                <a href='../fashion/fashion.php?main=$id' class='button'>Shop Now</a>
                              </div>
                            </div>";
                    }
                ?>
          </div>

          <div class="swiper-button-next"></div>
          <div class="swiper-button-prev"></div>

        </div>

    </section>


    <section class="banner-container">


      <div class="banner">
        <img src="images/frocks.webp" alt="">
          <div class="b-content">
          <h3>Our Frocks</h3>
          <a href="../fashion/fashion.php?sub=32" class="button">Shop Now</a>
        </div>
      </div>

      <div class="banner">
        <img src="images/blouse.webp" alt="">
        <div class="b-content">
        <h3>Our Tops</h3>
          <a href="../fashion/fashion.php?sub=28" class="button">Shop Now</a>
        </div>
      </div>

      <div class="banner">
        <img src="images/lehenga.jpg" alt="" class="img">
        <div class="b-content">
        <h3>Our Lehengas</h3>
          <a href="../fashion/fashion.php?sub=37" class="button">Shop Now</a>
        </div>
      </div>

      <div class="banner">
          <img src="images/makeup.jpg" alt="">
          <div class="b-content">
            <h3>Our Makeup Products</h3>
            <a href="../fashion/fashion.php?sub=49" class="button">Shop Now</a>
          </div>
      </div>

      <div class="banner">
          <img src="images/jewels.jpeg" alt="">
          <div class="b-content">
          <h3>Our jewelleries</h3>
            <a href="../fashion/fashion.php?sub=45" class="button">Shop Now</a>
          </div>
      </div>

      <div class="banner">
        <img src="images/sandals.webp" alt="">
        <div class="b-content">
          <h3>Our Sandals</h3>
          <a href="../fashion/fashion.php?sub=41" class="button">Shop Now</a>
        </div>
      </div>

    </section>


    <?php
        include '../database/dbconnection.php';

        $all_messages = mysqli_query($connection,"SELECT * from reviews inner join users on (reviews.user_id=users.user_id) LIMIT 3") or die(mysqli_error($conn));
    ?>

    <section id="review">
        <div class="review_heading">
            <h1>Top Reviews</h1>
            <h2>Customers Says</h2>
        </div>

        <div class="review_box_container">
        <?php
            while($row= mysqli_fetch_assoc($all_messages)){

        ?> 
            <div class="review_box">
                <div class="box_top">
                    <div class="profile">
                        <span class="profile_img">
                          <i class="fa-solid fa-user"></i>
                        </span>

                        <span class="name_user">
                            <span style="font-weight: 600;"><?php echo $row['username']; ?></span>
                            <span><?php echo $row['email']; ?></span>
                        </span>
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
                    <?php echo $row['comment']; ?>
                </div>

                <div class="date">
                  <?php echo $row['date']; ?>
                </div>


                
            </div>
          <?php
            }
          ?>
        </div>
    
        <div class="load-more">
          <a href="review.php">Load More Reviews</a>
        </div>   

    </section>

 
    <?php
    include 'contact.php';
    ?>


    <?php
    include 'about.php';
    ?>

  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <script>
    var swiper = new Swiper(".home-slider", {
      spaceBetween: 30,
      centeredSlides: true,
      loop:true,
      autoplay: {
        delay: 4000,
        disableOnInteraction: false,
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
    });
  </script>

</body>
</html>