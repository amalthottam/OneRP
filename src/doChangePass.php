<?php
session_start();
$changeError = array();
include 'dbFunctions.php';
date_default_timezone_set("Asia/Singapore");

 
if(isset($_SESSION['email'])){
    $email = mysqli_real_escape_string($link, $_SESSION['email']);
    
    $newPassword1 = mysqli_real_escape_string($link, $_POST['NewPassword1']);
    $newPassword2 = mysqli_real_escape_string($link, $_POST['NewPassword2']);
    $_SESSION['avoidarray'] = array("<", ">", "/", "/");
    $newPassword1 = str_replace($_SESSION['avoidarray'], " ", $newPassword1);
    $newPassword2 = str_replace($_SESSION['avoidarray'], " ", $newPassword2);
    
    if ($password_1 != $password_2) {array_push($changeError, "The two passwords do not match");}
    

    $uppercase = preg_match('@[A-Z]@', $newPassword1);
    $lowercase = preg_match('@[a-z]@', $newPassword1);
    $number    = preg_match('@[0-9]@', $newPassword1);
    $specialChars = preg_match('@[^\w]@', $newPassword1);
    if(!$uppercase || !$lowercase || !$number ||strlen($newPassword1) < 8) {
      array_push($changeError, "Password should be at least 8 characters in length and should include at least one upper case letter,one lowercase, one number, and one special character.");
    } 
    
    $newPassword1 = md5($newPassword1);
    $newPassword2 = md5($newPassword2);
    $user_check_query = "SELECT username FROM user WHERE user_email='$email'";
    $result = mysqli_query($link, $user_check_query);
    if(mysqli_num_rows($result) == 1){
        $row = mysqli_fetch_array($result);
        $lowerUser = strtolower($row['username']);
        $lowerPass = strtolower($newPassword1);
        $haveUser = preg_match('@'.$lowerUser.'@', $lowerPass);
        if($haveUser){
             array_push($changeError, "Password should not include the username");
        }
    }
    
    if (count($changeError) == 0 && mysqli_num_rows($result) == 1) {
        $query = "UPDATE user set user_password='$newPassword1' WHERE user_email='$email'";
  	mysqli_query($link, $query);
        $_SESSION['errorPopUp'] = "<script>alert('Password has been changed. Please log in.')</script>";
        header('location: index.php');

    }else{
      $_SESSION['changeError'] = $changeError;
        header('location: changePass.php?forget='. $_SESSION['forgetotp'] );

    }
}
?>