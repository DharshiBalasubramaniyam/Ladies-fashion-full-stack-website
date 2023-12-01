<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pink-Pearl</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="index.css">




</head>
<body>

<section class="home" id="home">

<div class="swiper home-slider">
  <div class="swiper-wrapper">


  <div class="swiper-slide slide" style="background: rgba(0,0,0,0.5)url(images/officewear.webp);background-repeat:no-repeat; background-size:cover;background-blend-mode: darken;">
    <div class="content">
      <span>OFFICE WEAR</span>
      <h3>Work in Style</h3>
      <a href="" class="button">Shop Now</a>
    </div>
  </div>

  <div class="swiper-slide slide" style="background: rgba(0,0,0,0.5)url(images/casual\ wear.webp);background-repeat:no-repeat; background-size:cover;background-blend-mode: darken; ">
    <div class="content">
      <span>CASUAL WEAR</span>
      <h3>Comfy | Unique | Stylish</h3>
      <a href="" class="button">Shop Now</a>
    </div>
  </div>

  <div class="swiper-slide slide" style="background: rgba(0,0,0,0.5)url(images/partywear.jpeg); background-repeat:no-repeat; background-size:cover;background-blend-mode: darken;">
    <div class="content">
      <span>PARTY WEAR</span>
      <h3>Unleash your style and shine at every event</h3>
      <a href="" class="button">Shop Now</a>
    </div>
  </div>


  <div class="swiper-slide slide" style="background: rgba(0,0,0,0.5)url(images/footwear.jpg); background-repeat:no-repeat; background-size:cover;background-blend-mode: darken;">
    <div class="content">
      <span>FOOT WEAR</span>
      <h3>Elevate your look</h3>
      <a href="" class="button">Shop Now</a>
    </div>
  </div>
  
  <div class="swiper-slide slide" style="background: rgba(0,0,0,0.5)url(images/accessories.jpg); background-repeat:no-repeat; background-size:cover;background-blend-mode: darken;">
    <div class="content">
      <span>ACCESSORIES</span>
      <h3>Discover a world of trendy accessories</h3>
      <a href="" class="button">Shop Now</a>
    </div>
  </div>

  <div class="swiper-slide slide" style="background: rgba(0,0,0,0.5)url(images/beauty\ products.webp); background-repeat:no-repeat; background-size:cover;background-blend-mode: darken;">
    <div class="content">
      <span>BEAUTY PRODUCTS</span>
      <h3>Enhance your natural beauty</h3>
      <a href="" class="button">Shop Now</a>
    </div>
  </div>

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
    <a href="" class="button">Shop Now</a>
  </div>
  </div>

  <div class="banner">
  <img src="images/blouse.webp" alt="">
  <div class="b-content">
  <h3>Our Tops</h3>
    <a href="" class="button">Shop Now</a>
  </div>
  </div>

  <div class="banner">
  <img src="images/lehenga.jpg" alt="" class="img">
  <div class="b-content">
  <h3>Our Lehengas</h3>
    <a href="" class="button">Shop Now</a>
  </div>
  </div>

  <div class="banner">
    <img src="images/makeup.avif" alt="">
  <div class="b-content">
  <h3>Our Makeup Products</h3>
    <a href="" class="button">Shop Now</a>
  </div>
  </div>

  <div class="banner">
    <img src="images/jewels.jpeg" alt="">
  <div class="b-content">
  <h3>Our jewelleries</h3>
    <a href="" class="button">Shop Now</a>
  </div>
  </div>

  <div class="banner">
    <img src="images/sandals.webp" alt="">
  <div class="b-content">
    <h3>Our Sandals</h3>
    <a href="" class="button">Shop Now</a>
  </div>
  </div>

</section>


<?php
include 'config.php';

$all_messages = mysqli_query($conn,"SELECT * from reviews inner join users on (reviews.user_id=users.user_id) LIMIT 3") or die(mysqli_error($conn));
?>

<section id="review">
        <div class="review_heading">
            <span>Comments</span>
            <h1>Clients Says</h1>
        </div>

        <div class="review_box_container">
        <?php
while($row= mysqli_fetch_assoc($all_messages)){

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
                    <i class="fa-solid fa-star" style="color: #ffe3e8;"></i>
                    <i class="fa-solid fa-star" style="color: #ffe3e8;"></i>
                    <i class="fa-solid fa-star" style="color: #ffe3e8;"></i>
                    <i class="fa-solid fa-star" style="color: #ffe3e8;"></i>
                    <i class="fa-solid fa-star" style="color: #ffe3e8;"></i>


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
        delay: 9500,
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