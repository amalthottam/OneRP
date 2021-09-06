<?php
session_start();

include './dbFunctions.php';


    if( isset($_POST['neverOTP'])){
        $query = "UPDATE user set allow_otp= '1' WHERE username = " . $_SESSION['username'] ." ";
  	mysqli_query($link, $query);
        $_SESSION['allowotp'] = 1;
        header("location: account.php");

        
    }else if(!isset($_POST['alwaysOTP'])){
        $query = "UPDATE user set allow_otp= '0' WHERE username = " . $_SESSION['username'] ." ";
  	mysqli_query($link, $query);
        $_SESSION['allowotp'] = 0;
        header("location: account.php");
        
    }




?>

