<?php
require_once('config.php');


function connect_to_db() {
    $con = mysqli_connect(DB_HOST,DB_USER,DB_PW,DB_NAME);

    // Check connection
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    } else {
        echo "Connected to database!";
    }
}

