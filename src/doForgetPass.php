<?php 
session_start();
$msg = '';
date_default_timezone_set("Asia/Singapore");

if(isset($_POST['forgotEmail'])){
    include "dbFunctions.php";
    $forgotPass = mysqli_real_escape_string($link,$_POST['forgotEmail']);
    $forgotPass = str_replace($_SESSION['avoidarray'], " ", $forgotPass);

    $querycheck = "SELECT username FROM user WHERE user_email = '$forgotPass'";
    $resultCheck = mysqli_query($link, $querycheck) or die(mysqli_error($link));

    if(mysqli_num_rows($resultCheck) == 1){
        $user = mysqli_fetch_array($resultCheck);
        $username = $user['username'];
        $rndno=rand(100000, 999999);//OTP generate
        $_SESSION['forgetotp']=$rndno;
        $_SESSION['forgetEmail']=$forgotPass;
        $to = $forgotPass;
        $subject = "You Forgot your password";
        // Message
        $message = '
        <html>
        <head>
          <title>press link to change password</title>
        </head>
        <body>
          <p>Change your password</p>
          <h5>
          Hello ' . $username . '
          <br> please press this <a href="https://onerp.site/changePass.php?forget=' . $rndno. '">link </a> to change your password
              <br> This link wll expire in 30 mins.
          <h5>
        </body>
        </html>
        ';

        // To send HTML mail, the Content-type header must be set
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        if(mail($to,$subject,$message,$headers)){
            $_SESSION['msg'] = "<a style='color:red'>An email has been sent. Please check you email</a>";
            
        }else{
            $_SESSION['msg'] = "<a style='color:red'> The link has not been send. Pls try again</a>";
            
        }
        header("location: forgetpassword.php");
    }else{
        $_SESSION['msg'] = "<a style='color:red'>This email does not belong to an account</a>";
        header("location: forgetpassword.php");

    }
}
?>