<?php
include '../database/dbconnection.php';

$all_messages = mysqli_query($connection,"SELECT * from reviews inner join users on (reviews.user_id=users.user_id)") or die(mysqli_error($conn));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews pink-pearl</title>
    <link rel="stylesheet" href="../index.css">
    <link rel="stylesheet" href="./main-header//header.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
</head>
<body>

<?php include('../main-header/header.php') ?>
    <section id="review" style="padding-top: 15vh;">
        <div class="review_heading">
            <h1>Reviews</h1>
            <h2>Customers Says</h2>
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


    </section>

    <?php
      include 'footer.php';
   ?>
</body>
</html>