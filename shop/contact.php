<?php

include '../database/dbconnection.php';


  if(isset($_POST['submit'])){
    $name = mysqli_real_escape_string($connection,$_POST['name']);
    $email=mysqli_real_escape_string($connection,$_POST['email']);
    $message=mysqli_real_escape_string($connection,$_POST['message']);

  
      $success=mysqli_query($connection,"insert into messages (name,email,message) VALUES ('$name','$email','$message')") or die ('query failed'. mysqli_error($conn));
      if($success){
        $_SESSION['message'] = "Message sent successfully!";
        // header('location: index.php');
        echo "<script>window.location.href = 'index.php'</script>";
      }
      else{
        $failed_message='Message failed!!!!';
      }
   
  }

 


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../index.css">
    <link rel="stylesheet" href="./main-header//header.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<section class="container">


<form action="" class="form" method="post" id="contact">
    <h2>Contact Us</h2>

    <div class="input">
    <span style="color: green;"><?php if(isset($successful_message )) echo  $successful_message ;  ?></span>
    <span><?php if(isset($failed_message )) echo  $failed_message ;  ?></span>
    </div>

    <div class="input">
    <input type="text" name="name" id="name" placeholder="Your name" required>
    </div>


    <div class="input">
    <input type="email" name="email" id="email"  placeholder="Your Email" required>
    </div>

    <div class="input">
    <textarea name="message" id="message" cols="30" rows="10" placeholder="Your message here!!!!"></textarea>
    </div>

    <input type="submit" name="submit" value="Send Message" class="btn">

</section>
</body>
</html>