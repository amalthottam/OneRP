<?php
include 'dbFunctions.php';
date_default_timezone_set("Asia/Singapore");

// connect to the database
        //do a query for the topics

        $sql = "SELECT * FROM topic";
        $result = mysqli_query($link, $sql);

?>
