<?php
  
  session_start();
  include '../database/dbconnection.php';
  

  if(isset($_POST['login'])){
    $email=mysqli_real_escape_string($connection,$_POST['email']);
    $password=mysqli_real_escape_string($connection,$_POST['password']);
   
    $select = mysqli_query($connection, "SELECT * FROM users WHERE email='$email'") or die('query failed');
   

    if (mysqli_num_rows($select) > 0) {
        $row = mysqli_fetch_assoc($select);
        $stored_hashed_password = $row['password'];

        if (password_verify($password, $stored_hashed_password)) {
            if($row['user_type'] == 'admin'){
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['isLogged'] = true;
               
                header('location:../admin/dashboard.php');
       
                echo '<script>alert("You logged in as an ADMIN!")</script>';
            }
            elseif($row['user_type'] == 'customer'){
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['isLogged'] = true;
                header('Location: ../shop/index.php');
       
       
            } else {
                $message = 'Incorrect email or password!!';
            }
    } else {
        $message = 'Incorrect email or password!!';
    }
}
  }


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - pink pearl</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">


</head>
<body>

    <section class="container">


        <form action="" class="form" method="post" novalidate>
            <h2>Login</h2>

            <div class="input">
                <input type="email" name="email" id="email" class="input" placeholder="Enter your Email" required>
                <span><?php if(isset($message )) echo $message ;  ?></span>
            </div>

            <div class="input">
                <input type="password" name="password" id="password" class="input" placeholder="Enter the password" required>
            </div>

            <input type="submit" name="login" value="Login" class="btn">
            
            <p class="login">Don't have an account? <a href="register.php">Register here</a></p>

        </form>
    </section>

</body>
</html>