<?php
date_default_timezone_set("Asia/Singapore");

if(isset($_POST['save'])){
    $rno=$_SESSION['otp'];
    $urno=mysqli_real_escape_string($link, $_POST['otpvalue']);
    $time = time() - $_SESSION['LogTime'];
    if($time < 300 ){
    if(!strcmp($rno,$urno)){
        $_SESSION['username'] = $_SESSION['Ausername'];
        $_SESSION['password'] = $_SESSION['Apassword'] ;
        $_SESSION['email'] =$_SESSION['Aemail'] ;
        $_SESSION['total_post'] = $_SESSION['Atotal_post'] ;
        $_SESSION['date_created'] = $_SESSION['Adate_created'];
        $_SESSION['user_id'] = $_SESSION['Auser_id'] ;
        //For admin if he want to know who is register

        $_SESSION['errorPopUp'] = "<script>alert('You have logged in successfully')</script>";
        
        if(isset($_SESSION['validate'])){
            header("location: afterRegister.php");

        }else{
        header("location: index.php");
        }
        
        //For admin if he want to know who is register
        }
    else{
        $message = "<p>Invalid OTP <br> type again</p>";
    }
    }else{
        unset($_SESSION['LogTime']);
        unset($_SESSION['otp']);
        $message = "<p> Sorry, the OTP has expired. Press Resend";
    }
}
//resend OTP
if(isset($_POST['resend'])){
    if(isset($_SESSION['otp'])){
        $rno=$_SESSION['otp'];
    }else{
        $rno=rand(100000, 999999);//OTP generate
        $_SESSION['LogTime']=time();
        $_SESSION['otp'] = $rno;
    }
    $to=$_SESSION['Aemail'];
    $subject = "OTP";
    $txt = "OTP: ".$rno."";
    $headers = "From: OneRP forum";
    mail($to,$subject,$txt,$headers);
    $message="<p><b>Sucessfully resend OTP to your mail.</b></p>";
    }
?>