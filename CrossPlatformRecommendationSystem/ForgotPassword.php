<?php
session_start();

// Include statements
include "database/UserQueries.php";


/*Check if the cookie token is present.*/
if(isset($_COOKIE['cprs_token']))    {
    $arr=isUserAlreadyLoggedIn(session_id(), $_COOKIE['cprs_token']);
    if(sizeof($arr)!=0) {
        $_SESSION["user_id"]=$arr['userID'];
        if($arr['isNewUser'])
            header("Location: NewUserPreferences.php");
        else
            header("Location: Homepage.php");
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Forgot your Password</title>
        <link rel="stylesheet" 
                href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
        <link rel="stylesheet" href="Window.css" />
        <link rel="stylesheet" href="Elements.css" />
        <link rel="stylesheet" href="templates/Navigation.css" />
        <link rel="stylesheet" href="ForgotPassword.css" />
        <script src="templates/CloneTemplate.js"></script>
        <script src="templates/VerificationCode.js"></script>
        <script src="Elements.js"></script>
        <script src="ForgotPassword.js"></script>
    </head>
    <body>
        <!-- To send emails to the user. -->
        <?php
        include "templates/SendEmail.php";
        include "templates/VerificationCode.php";
        include "templates/EmailTemplates.php";
        ?>
        <!-- Navigation Bar, Background -->
        <?php
        include "templates/Navigation.php";
        include "templates/Background.php";
        ?>
        <script>
            show("navigation_without_buttons_template");
            show("background_template");
        </script>
    
        <?php
        $cardNo=1;
        $showError=false;
        if($_SERVER["REQUEST_METHOD"]=="POST") {
            /* When 'Get Verification Code' button is clicked on Card 1 */
            if(isset($_POST['get_vc_code']))    {
                $email=$name="";
                $userID=null;
                if(isset($_POST["user_email"]))  $email=$_POST["user_email"];
                
                $details=accountExists($email);
                if(sizeof($details)>0)  {
                    $cardNo=2;
                    $name=$_SESSION['user_name']=$details['name'];
                    $userID=$_SESSION['user_id']=$details['userID'];
                    $_SESSION['user_email']=$email;
                    $code=generateVerificationCode();
                    sendEmail($email, "Verify your email", 
                            generateVerificationEmailBody2($name, $code));
                    $_SESSION['verification_code']=$code;
                }
                else
                    $showError=true;
            }

            /* When 'Verify Email' button is clicked on Card 2 */
            else if(isset($_POST['verify_vc_code']))    {
                $userCode=$name=$email="";
                $userID=null;
                for($i=0; $i<6; $i++)
                    if(isset($_POST["vc_digit_".$i]))    $userCode.=$_POST["vc_digit_".$i];

                if(isset($_SESSION["user_name"]))   $name=$_SESSION["user_name"];
                if(isset($_SESSION["user_email"]))   $email=$_SESSION["user_email"];
                if(isset($_SESSION["user_id"]))   $userID=$_SESSION["user_id"];
                if(isset($_SESSION["verification_code"]))   $code=$_SESSION["verification_code"];

                if($code==$userCode)
                    $cardNo=3;
                else    {
                    $cardNo=2;
                    $showError=true;
                }
            }
            
            /* When 'Go Back' link is clicked on Card 2 */
            else if(isset($_POST['back_button']))    {
                $cardNo=1;
            }

            /* When 'Reset Your Password' button is clicked on Card 2 */
            else if(isset($_POST['reset_password']))    {
                $userCode=$name=$email=$password="";
                $userID=null;
                
                if(isset($_SESSION["user_name"]))   $name=$_SESSION["user_name"];
                if(isset($_SESSION["user_email"]))   $email=$_SESSION["user_email"];
                if(isset($_SESSION["user_id"]))   $userID=$_SESSION["user_id"];

                if(isset($_POST["user_password"]))  $password=$_POST["user_password"];
                
                if(updatePassword($userID, $password))
                    sendEmail($email, "Password has been reset!", 
                            generateResetPasswordEmailBody($name));

                // End the session.
                session_unset();
                session_destroy();

                header("Location: Login.php");
            }
        }
        ?>


        <!-- Every element (except the background) that is below the navigation bar goes here -->
        <main class="col-12"> 
            <div class="big-logo col-6">
                
            </div>
        
            <div class="fp-card-1 half-page-content col-6">
                <div class="page-title">Forgot Password</div>

                <!-- Form to collect information, details, etc. from the user-->
                <form class="form-1" name="fpForm1"
                        action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" 
                        method="post" onsubmit="return validateForgotPasswordCard1() ">
                    <div class="form-label">Registered Email<span class="required">*</span></div>
                    <div class="form-error"><span class="material-symbols-outlined">error</span>
                        No account exists with this email.</div>
                    <input class="col-12" type="email" name="user_email" />
                    <input type="submit" class="col-6 form-button" title="Get verification code"
                            value="Get Verification Code" name="get_vc_code" />
                    <a class="col-12" href="Login.php">Back to Login?
                        <span class="material-symbols-outlined">arrow_right_alt</span></a> 
                </form>
            </div>

            <div class="fp-card-2 half-page-content col-6">
                <div class="page-title">Forgot Password</div>

                <!-- Form to collect information, details, etc. from the user-->
                <form class="form-1" method="post" name="fpForm2"
                        action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-text">To reset your password, please enter the verification
                            code sent to your email 
                            <?php 
                                echo substr($email, 0, 3)."****@".
                                substr($email, strpos($email, "@")+1); 
                            ?>.
                    </div>
                    <div class="form-label">Verification Code<span class="required">*</span></div>
                    <div class="form-error"><span class="material-symbols-outlined">error</span>
                        Wrong 6-digit verification code.</div>
                    <div class="col-12 verification-code-input-container">
                    <?php
                    for($i=0; $i<6; $i++)   {
                    ?>
                        <input class="vc-digit" type="text" name="vc_digit_<?php echo $i; ?>" 
                                maxlength="1" onkeyup="goToNextVCDigitElement(this)" />
                    <?php } ?>
                    </div>
                    <input type="submit" class="col-6 form-button" name="verify_vc_code"
                            title="Reset your password" value="Verify Email" />
                    <!-- No href attribute for the following 'a' tag. It'll be having UI of a
                         link, but will function as a button. -->
                    <button type="submit" name="back_button" class="col-12">
                        <a class="col-12" id="back_button_1">Go Back
                            <span class="material-symbols-outlined">keyboard_backspace</span></a> 
                    </button>
                </form>
            </div>

            <div class="fp-card-3 half-page-content col-6">
                <div class="page-title">Forgot Password</div>

                <!-- Form to collect information, details, etc. from the user-->
                <form class="form-1" name="fpForm3"
                        action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" 
                        method="post" onsubmit="return validateForgotPasswordCard3() ">
                    <div class="form-text">Thank you for verifiying your email. Please
                        set a new password.
                    </div>
                    <div class="form-label">Password<span class="required">*</span></div>
                    <div class="form-error"><span class="material-symbols-outlined">error</span>
                        Password can contain only letters, digits, @, $, _. 
                        Minimum of 5 to a maximum of 30 characters is allowed.</div>
                    <div class="col-12 sharing-input-button">
                        <input class="col-12" type="password" name="user_password" required/>
                        <div class="password-visible material-symbols-outlined"
                                title="Show Password" onclick="togglePasswordVisibility(this)">
                                visibility</div>
                    </div>
                    <input type="submit" class="col-6 form-button" title="Reset your password"
                            value="Reset Your Password" name="reset_password" /> 
                </form>
            </div>
        </main>

        <script>
            showCard(<?php echo $cardNo; ?>);

        <?php 
        if($showError)  {
        ?>
            document.getElementsByClassName("form-error")[<?php echo $cardNo-1; ?>].style.display="flex";
        <?php } ?>
        </script>
    </body>
</html>