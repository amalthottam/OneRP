
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">  
<title>Home</title>
    <link href="./CSSfiles/headerCss.css" rel="stylesheet" type="text/css">
    <link href="./CSSfiles/loginform.css" rel="stylesheet" type="text/css">
    <link href="./CSSfiles/sidebar.css" rel="stylesheet" type="text/css">
    <link href="./CSSfiles/ForTable.css" rel="stylesheet" type="text/css">

</head>

    <body>
        <?php include './header.php'; ?>
         <?php include './doOTP.php'; ?>
        <br>
        <div class="w3-row">
            <?php if(isset($_SESSION['validate'])){ ?>
            <h2>Validate account OTP</h2>
            An email is sent to your email account with validation one-time password
            <?php }else{ ?>
            <h2>OTP</h2>
            <?php }
            if(isset($message)) { echo $message; } ?>
            <form class="w3-container" method="post" action="">
            <p><input class="w3-input w3-border w3-round" type="password" placeholder="OTP" name="otpvalue" maxlength="10"></p>
            
            <p class="w3-center"><button class="w3-btn w3-green w3-round" style="width:100%;height:40px" name="save">Submit</button></p>
            <p class="w3-center"><button class="w3-btn w3-green w3-round" style="width:100%;height:40px" name="resend">Resend</button></p>
            </form>
            <br>

            
            
        </div>
    </body>
</html>
       
