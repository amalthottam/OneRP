<?php
    session_start();
    date_default_timezone_set("Asia/Singapore");
    $username = $_SESSION['Ausername'];
    $password = $_SESSION['Apassword'];
    $email = $_SESSION['Aemail'];    
    $allowotp = $_SESSION['allowotp'];
    $otp = $_SESSION['otp'];

    
    $query = "INSERT INTO user (username, user_email, user_password, allow_otp, role_id)
                      VALUES('$username', '$email', '$password', '$allowotp', '0')";
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>trying</title>
    <link href="CSSfiles/headerCss.css" rel="stylesheet" type="text/css">
    <link href="CSSfiles/loginform.css" rel="stylesheet" type="text/css">
    <style>
        .registered{
            text-align: center;
        }
        
    </style>

</head>

<?php include './header.php'; ?>

    <section class="registered">
        <?php
        if(mysqli_query($link, $query)){
            $_SESSION['username'] = $username;

            $user_check_query = "SELECT user_id, date_created FROM user WHERE username='$username' LIMIT 1";
            $result = mysqli_query($link, $user_check_query);
            $row = mysqli_fetch_array($result);
            $_SESSION['password'] = $password;
            $_SESSION['email'] = $email;
            $_SESSION['date_created'] = $row['date_created'];
            $_SESSION['user_id'] = $row['user_id'];
            $to=$email;
            $subject = "OTP";
            $txt = "Your account have successfully registered.";
            $txt .= "Username:" . $username . "";
                
            $headers = "From: OneRP forum";
            mail($to,$subject,$txt,$headers);
        ?>
        <a style="font-weight: bold">Congrats You Have Successfully Registered</a><br>
        Go back to home page<br>
        <a class="pointer" href = "index.php">Home Page</a>
        <?php }else{?>  
            <a style="font-weight: bold">Sorry Your account has not been registered</a><br>
            Please Try again<br>
            <a class="pointer" href = "register.php">Register page</a>
        <?php } ?>
    </section>
</body>
</html>

