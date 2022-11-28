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
        <title>Sign up</title>
        <link rel="stylesheet" 
                href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
        <link rel="stylesheet" href="Window.css" />
        <link rel="stylesheet" href="Elements.css" />
        <link rel="stylesheet" href="templates/Navigation.css" />
        <link rel="stylesheet" href="Signup.css" />
        <script src="templates/CloneTemplate.js"></script>
        <script src="templates/VerificationCode.js"></script>
        <script src="Elements.js"></script>
        <script src="Signup.js"></script>
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
                $email=$name=$password="";
                if(isset($_POST["user_name"]))  $name=$_POST["user_name"];
                if(isset($_POST["user_email"]))  $email=$_POST["user_email"];
                if(isset($_POST["user_password"]))  $password=$_POST["user_password"];

                $details=getUserID($email);
                /* If account already exists with this email */
                if(sizeof($details)>0)  {
                    $showError=TRUE;
                    $formErrorNo=2;
                    $cardNo=1;
                }
                /* Send verification code */
                else    {
                    $cardNo=2;
                    $code=generateVerificationCode();
                    sendEmail($email, "Verify your email", 
                            generateVerificationEmailBody($name, $code));
                    $_SESSION["user_name"]=$name;
                    $_SESSION["user_email"]=$email;
                    $_SESSION["user_password"]=$password;
                    $_SESSION["verification_code"]=$code;
                }
            }

            /* When 'Get Verification Code' button is clicked on Card 1 */
            else if(isset($_POST['verify_vc_code']))    {
                $userCode=$name=$email=$password=$code="";
                for($i=0; $i<6; $i++)
                    if(isset($_POST["vc_digit_".$i]))    $userCode.=$_POST["vc_digit_".$i];

                if(isset($_SESSION["user_name"]))   $name=$_SESSION["user_name"];
                if(isset($_SESSION["user_email"]))   $email=$_SESSION["user_email"];
                if(isset($_SESSION["user_password"]))   $password=$_SESSION["user_password"];
                if(isset($_SESSION["verification_code"]))   $code=$_SESSION["verification_code"];

                if($code==$userCode)    {
                    $cardNo=3;
                    // Add to database.
                    if(insertUser($name, $email, $password))
                    sendEmail($email, "Account created!", generateAccountCreatedEmailBody($name));

                    // End the session.
                    session_unset();
                    session_destroy();
                }
                else    {
                    $cardNo=2;
                    $showError=true;
                    $formErrorNo=4;
                }
            }

            /* When 'Go Back' link is clicked on Card 2 */
            else if(isset($_POST['back_button']))    {
                $cardNo=1;
            }
        }
        ?>


        <!-- Every element (except the background) that is below the navigation bar goes here -->
        <main class="col-12"> 
            <div class="big-logo col-6">
                
            </div>
        
            <!-- CARD 1 -->
            <div class="signup-card-1 half-page-content col-6">
                <div class="page-title">Sign up</div>
                <!-- Form to collect information, details, etc. from the user-->
                <form class="form-1" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" 
                        method="post" onsubmit="return validateSignUpCard1()" name="signUpForm1">
                    <div class="form-label">Name<span class="required">*</span></div>
                    <div class="form-error"><span class="material-symbols-outlined">error</span>
                        Name can contain only letters, digits, spaces. 
                        Maximum of 100 characters is allowed.</div>
                    <input class="col-12" type="text" name="user_name" required/>
                    <div class="form-label">Email<span class="required">*</span></div>
                    <div class="form-error"><span class="material-symbols-outlined">error</span>
                        Email can contain only letters, digits and special characters ! # $ % & * + - . _
                        . Maximum of 100 characters is allowed.</div>
                    <div class="form-error"><span class="material-symbols-outlined">error</span>
                        An account already exists with this email.</div>
                    <input class="col-12" type="email" name="user_email" required/>
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
                    <input type="submit" class="col-6 form-button" title="Get verification code"
                            value="Next" name="get_vc_code" />
                    <a class="col-12" href="Login.php">Already have an account? Go to Login
                        <span class="material-symbols-outlined">arrow_right_alt</span></a> 
                </form>
            </div>

            <!-- CARD 2 -->
            <div class="signup-card-2 half-page-content col-6">
                <div class="page-title">Sign up</div>

                <!-- Form to collect information, details, etc. from the user-->
                <form class="form-1" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" 
                        method="post" name="signUpForm2">
                    <div class="form-text">Almost done! A verification code is sent to
                            your email 
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
                    <input type="submit" class="col-6 form-button"  name="verify_vc_code"
                            title="Verify your email to create an account" value="Verify Email" />
                    <!-- No href attribute for the following 'a' tag. It'll be having UI of a
                         link, but will function as a button. -->
                    <button type="submit" name="back_button" class="col-12">
                        <a class="col-12" id="back_button_1">Go Back
                            <span class="material-symbols-outlined">keyboard_backspace</span></a>
                    </button> 
                </form>
            </div>

            <!-- CARD 3 -->
            <div class="signup-card-3 half-page-content col-6">
                <div class="page-title">Sign up</div>

                <!-- Form to collect information, details, etc. from the user-->
                <form class="form-1" action="Login.php" method="GET">
                    <div class="form-text">Your email is verified. Thank you for signing
                        with us!
                    </div>
                    <input type="submit" class="col-6 form-button" title="Continue to Login"
                            value="Continue" /> 
                </form>
            </div>
        </main>

        <script>
            showCard(<?php echo $cardNo; ?>);

        <?php 
        if($showError)  {
        ?>
            document.getElementsByClassName("form-error")[<?php echo $formErrorNo; ?>].style.display="flex";
        <?php } ?>
        </script>
    </body>
</html>