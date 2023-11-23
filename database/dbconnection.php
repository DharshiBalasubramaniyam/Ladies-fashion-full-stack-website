<?php
    $server = "localhost";
    $user = "root";
    $password = "";
    $database = "pink_pearl";
    $connection = "";

    try {
        $connection = mysqli_connect($server, 
                                    $user, 
                                    $password, $database);
    }catch(mysqli_sql_exception) {
        echo "could not connect";
    }

?>