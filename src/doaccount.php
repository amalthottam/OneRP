<?php
session_start();
$changeError = array();
include './dbFunctions.php';
date_default_timezone_set("Asia/Singapore");

if(isset($_POST['currentPassword'])){
    $currentPass = mysqli_real_escape_string($link, $_POST['currentPassword']);
    $newPassword1 = mysqli_real_escape_string($link, $_POST['NewPassword1']);
    $newPassword2 = mysqli_real_escape_string($link, $_POST['NewPassword2']);
    $avoidarray = array("<", ">");
    $newPassword1 = str_replace($avoidarray, " ", $newPassword1);
    $newPassword2 = str_replace($avoidarray, " ", $newPassword2);

    $username = $_SESSION['username'];
    if ($password_1 != $password_2) {array_push($changeError, "The two passwords do not match");}
    

    $uppercase = preg_match('@[A-Z]@', $newPassword1);
    $lowercase = preg_match('@[a-z]@', $newPassword1);
    $number    = preg_match('@[0-9]@', $newPassword1);
    $specialChars = preg_match('@[^\w]@', $newPassword1);
    if(!$uppercase || !$lowercase || !$number ||strlen($newPassword1) < 8) {
      array_push($changeError, "Password should be at least 8 characters in length and should include at least one upper case letter,one lowercase, one number, and one special character.");
    } 


    $user_check_query = "SELECT user_password FROM user WHERE username='$username'";
    $result = mysqli_query($link, $user_check_query);
    $row = mysqli_fetch_array($result);
    if(mysqli_num_rows($result) == 1){
        $lowerUser = strtolower($_SESSION['username']);
        $lowerPass = strtolower($newPassword1);
        $haveUser = preg_match('@'.$lowerUser.'@', $lowerPass);
        if($haveUser){
             array_push($changeError, "Password should not include the username");
        }
    }
    $currentPass = md5($currentPass);
    $newPassword1 = md5($newPassword1);
    $newPassword2 = md5($newPassword2);
    if ($row['user_password'] != $currentPass) {
	array_push($changeError, "The current password invalid");
    }
    if ($currentPass == $newPassword1){
	array_push($changeError, "Please type a new password");
    }
    
    
    if (count($changeError) == 0) {

        $query = "UPDATE user set user_password='$newPassword1' WHERE username='$username'";
  	mysqli_query($link, $query);
        
        $_SESSION['changepass'] = "<a> Password has been changed</a><br>";
        header('location: account.php');

    }else{
      $_SESSION['changeError'] = $changeError;

        header('location: account.php');

    }
    
}
?>