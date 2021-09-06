<?php
session_start();
include "dbFunctions.php";
date_default_timezone_set("Asia/Singapore");

if(isset($_POST['username'])){
    $enteredUsername = mysqli_real_escape_string($link, $_POST['username']);
    $enteredPassword = mysqli_real_escape_string($link, $_POST['password']);
    $enteredUsername = str_replace($_SESSION['avoidarray'], " ", $enteredUsername);
    $enteredPassword = str_replace($_SESSION['avoidarray'], " ", $enteredPassword);

    $msg = "";
    $errormsg="";

    $querycheck = "SELECT *FROM user WHERE username = '$enteredUsername' "
            . "AND user_password = md5('$enteredPassword')";
    $resultCheck = mysqli_query($link, $querycheck) or die(mysqli_error($link));

    if(mysqli_num_rows($resultCheck) == 1){
        $row = mysqli_fetch_array($resultCheck);
        $id = $row['role_id'];
        $allowotp = $row['allow_otp'];
        $_SESSION['allowotp'] = $allowotp;

        if($id == 2 || $id == 1 || $allowotp == 1){
            $rndno=rand(100000, 999999);//OTP generate
            $_SESSION['LogTime']=time();
            $_SESSION['otp']=$rndno;
            $message = urlencode("otp number.".$rndno);
            $_SESSION['msg']=$message;
            $to = $row['user_email'];
            $subject = "OTP";
            $txt = "OTP: ".$rndno."";
            $headers = "From: OneRP forum";
            mail($to,$subject,$txt,$headers);
            $_SESSION['otp']=$rndno;
            $_SESSION['Ausername'] = $enteredUsername;
            $_SESSION['Apassword'] = md5('$enteredPassword');
            $_SESSION['Aemail'] = $row['user_email'];
            $_SESSION['Atotal_post'] = $row['total_post'];
            $_SESSION['Adate_created'] = $row['date_created'];
            $_SESSION['Auser_id'] = $row['user_id'];
            session_regenerate_id(true);
            header("location: OTP.php");
            
        }else{
            $_SESSION['username'] = $enteredUsername;
            $_SESSION['password'] = md5('$enteredPassword');
            $_SESSION['email'] = $row['user_email'];
            $_SESSION['total_post'] = $row['total_post'];
            $_SESSION['date_created'] = $row['date_created'];
            $_SESSION['user_id'] = $row['user_id'];
            session_regenerate_id(true);
            header("location: index.php");
        }
        
    } else{
        $_SESSION['errormsg'] = "<p style='text-align:center; color:red'>Sorry, you must enter a valid username and password to log in</p>";
        $_SESSION['errorPopUp'] = "<script>alert('Sorry, you must enter a valid username and password to log in')</script>";


            header("location: index.php");

    }

}
