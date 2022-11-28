<?php
session_start();

include "database/UserQueries.php";


/*Check if the cookie token is present.*/
if(isset($_COOKIE['cprs_token']))    {
    $arr=isUserAlreadyLoggedIn(session_id(), $_COOKIE['cprs_token']);
    //echo sizeof($arr);
    if(sizeof($arr)>0 && $arr['userID']!="") {
        $_SESSION["user_id"]=$arr['userID'];
        if($arr['isNewUser'])
            header("Location: NewUserPreferences.php");
        else
            header("Location: Homepage.php");
    }
    /*
    else   {
        // Get random token, set it to cookie. Should be above <html> tag.
        setcookie('cprs_token', generateRandomToken());
    }*/
}else   {
    // Get random token, set it to cookie. Should be above <html> tag.
    setcookie('cprs_token', generateRandomToken());
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Login</title>
        <link rel="stylesheet" 
                href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
        <link rel="stylesheet" href="Window.css" />
        <link rel="stylesheet" href="Elements.css" />
        <link rel="stylesheet" href="templates/Navigation.css" />
        <link rel="stylesheet" href="Login.css" />
        <script src="templates/CloneTemplate.js"></script>
        <script src="templates/Navigation.js"></script>
        <script src="Elements.js"></script>
        <script src="Login.js"></script>
    </head>
    <body>
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
        $showError=false;
        $formErrorNo=-1;
        if($_SERVER["REQUEST_METHOD"]=="POST") {
            if(isset($_POST['login']))  {
                $email=$password="";
                $userID=-1;

                if(isset($_POST["user_email"]))  $email=$_POST["user_email"];
                if(isset($_POST["user_password"]))  $password=$_POST["user_password"];

                $details=getUserID($email);      
                /*If an account is associated with this email*/
                if(sizeof($details)>0)  {
                    /* If password matches */
                    if($details['password']==$password) {
                        $userID=$details['userID'];
                        updateSessionIDAndToken($userID, session_id(), $_COOKIE["cprs_token"]);
                        if($details['isNewUser'])
                            header("Location: NewUserPreferences.php");
                        else
                            header("Location: Homepage.php");
                    }

                    /* If invalid password is entered */
                    else    {
                        $showError=true;
                        $formErrorNo=1;
                    }
                }  

                /* If no account exists */
                else    {
                    $showError=true;
                    $formErrorNo=0;
                }
            }
        }
        ?>

        <!-- Every element (except the background) that is below the navigation bar goes here -->
        <main class="col-12"> 
            <div class="big-logo col-6">
                
            </div>
        
            <div class="half-page-content col-6">
                <div class="page-title">Login</div>

                <!-- Form to collect information, details, etc. from the user-->
                <form class="form-1" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" 
                        method="post" onsubmit="return validateLogin()" name="loginForm">
                    <div class="form-label">Email<span class="required">*</span></div>
                    <div class="form-error"><span class="material-symbols-outlined">error</span>
                        No account exist with this email.</div>
                    <input class="col-12" type="email" name="user_email" />
                    <div class="form-label">Password<span class="required">*</span></div>
                    <div class="form-error"><span class="material-symbols-outlined">error</span>
                        Incorrect password.</div>
                    <div class="col-12 sharing-input-button">
                        <input class="col-12" type="password" name="user_password" />
                        <div class="password-visible material-symbols-outlined"
                                title="Show Password" onclick="togglePasswordVisibility(this)">
                            visibility</div>
                    </div>
                    <input type="submit" class="col-6 form-button" title="Login" value="Login"
                            name="login" />
                    <a class="col-12" href="Signup.php">Don't have an account? Create one
                        <span class="material-symbols-outlined">arrow_right_alt</span></a> 
                    <a class="col-12" href="ForgotPassword.php">Forgot your password?</a>
                </form>
            </div>
        </main>

        <script>
            <?php 
            if($showError) { ?>
                showErrorMessage(<?php echo $formErrorNo; ?>);
            <?php } ?>
        </script>
    </body>
</html>