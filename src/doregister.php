<?php
session_start();
include 'dbFunctions.php';
// initializing variables
$username = "";
$email    = "";
$errors = array();
date_default_timezone_set("Asia/Singapore");



// connect to the database

if(isset($_POST['username'])){
  // receive all input values from the form
  $username = mysqli_real_escape_string($link, $_POST['username']);
  $email = mysqli_real_escape_string($link, $_POST['email']);
  $password_1 = mysqli_real_escape_string($link, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($link, $_POST['password_2']);
  $allowotp = mysqli_real_escape_string($link, $_POST['otp']);
  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) {array_push($errors, "Username is required"); }
  if (empty($email)) {array_push($errors, "Email is required"); }
  else{
    $testemail = test_input($email);
    // check if e-mail address is well-formed
    if (!filter_var($testemail, FILTER_VALIDATE_EMAIL)) {
       array_push($errors,"Invalid email format");
    }
  }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
  }
    $lowerUser = strtolower($username);
    $lowerPass = strtolower($password_1);

  $uppercase = preg_match('@[A-Z]@', $password_1);
  $lowercase = preg_match('@[a-z]@', $password_1);
  $number    = preg_match('@[0-9]@', $password_1);
  $specialChars = preg_match('@[^\w]@', $password_1);
  $haveUser = preg_match('@'.$lowerUser.'@', $lowerPass);
  if(!$uppercase || !$lowercase || !$number || !$specialChars ||strlen($password_1) < 8) {
    array_push($errors, "Password should be at least 8 characters in length and should include at least one upper case letter,one lowercase, one number, and one special character.");
  } 
  if($haveUser){
   array_push($errors, "Password should not include the username");
  }

  // first check the database to make sure
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT *FROM user WHERE username = '$username' OR user_email='$email'";
  $result = mysqli_query($link, $user_check_query);
    $user = mysqli_fetch_array($result);

  if (mysqli_num_rows($result) == 1) { // if user exists
        if($user['user_email'] == $email){
            array_push($errors, "Email already exists with an account");
        }
        if ($user['username'] == $username) {
            array_push($errors, "Username already exists");
        }

  }
  
  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database
        if(isset($allowotp)){
            $_SESSION['allowotp'] = 1;
        }else{
            $_SESSION['allowotp']=0;
        }
        $_SESSION['Ausername'] = $username;
        $_SESSION['Apassword'] = $password;
        $_SESSION['Aemail'] = $email;
        $_SESSION['validate'] = 0 ;

        $rndno=rand(100000, 999999);//OTP generate
        $_SESSION['LogTime']=time();
        $_SESSION['otp']=$rndno;
        $to = $email;
        $subject = "OTP";
        $txt = "OTP: ".$rndno."";
        $headers = "From: OneRP forum";
        mail($to,$subject,$txt,$headers);
            
  	header('location: OTP.php');
  }
  else{
      $_SESSION['errors'] = $errors;

    header('location: register.php');

    }

}

function test_input($data) {
$data = trim($data);
$data = stripslashes($data);
$data = htmlspecialchars($data);
return $data;
}
  ?>
