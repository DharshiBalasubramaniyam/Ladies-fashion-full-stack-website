<?php
  include '../database/dbconnection.php';
  session_start();
  session_unset();
  session_destroy();

  header('location:../shop/index.php');
?>