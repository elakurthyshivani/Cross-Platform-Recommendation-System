<?php
session_start();
// session_id();

// Include statements
include "../database/UserQueries.php";
include "../database/AdminQueries.php";


/*Check if the cookie token is present.*/
if(isset($_COOKIE['admin_cprs_token']))    {
    $arr=isAdminAlreadyLoggedIn(session_id(), $_COOKIE['admin_cprs_token']);
    //echo sizeof($arr);
    if(sizeof($arr)>0 && $arr['adminID']!="") {
        $_SESSION["admin_id"]=$arr['adminID'];
        header("Location: ViewAllRatings.php");
    }
}else   {
    // Get random token, set it to cookie. Should be above <html> tag.
    setcookie('admin_cprs_token', generateRandomToken());
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Admin Login</title>
        <link rel="stylesheet" 
                href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
        <link rel="stylesheet" href="../Window.css" />
        <link rel="stylesheet" href="../Elements.css" />
        <link rel="stylesheet" href="../templates/Navigation.css" />
        <link rel="stylesheet" href="../Login.css" />
        <script src="../templates/CloneTemplate.js"></script>
        <script src="../Elements.js"></script>
        <script src="AdminLogin.js"></script>
        <style> 
        </style>
    </head>
    <body>
        <!-- Navigation Bar, Background -->
        <?php
        include "../templates/AdminNavigation.php";
        include "../templates/Background.php";
        ?>
        <script>
            show("admin_navigation_without_buttons_template");
            show("background_template");
        </script>
    
        <?php 
        $showError=false;
        $formErrorNo=-1;
        if($_SERVER["REQUEST_METHOD"]=="POST") {
            if(isset($_POST['login']))  {
                $email=$password="";
                if(isset($_POST["admin_email"]))  $email=$_POST["admin_email"];
                if(isset($_POST["admin_password"]))  $password=$_POST["admin_password"];

                $details=getAdminID($email);
                /*If an account is associated with this email*/
                if(sizeof($details)>0)  {
                    /* If password matches */
                    if($details['password']==$password) {
                        $adminID=$details['adminID'];
                        // Update in the database, admin's session_id and token.
                        updateAdminSessionIDAndToken($adminID, session_id(), 
                                $_COOKIE["admin_cprs_token"]);
                        header("Location: ViewAllRatings.php");
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
                <div class="page-title">Admin Login</div>

                <!-- Form to collect information, details, etc. from the user-->
                <form class="form-1" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" 
                        method="post" onsubmit="return validateLogin()" name="loginForm">
                    <div class="form-label">Email<span class="required">*</span></div>
                    <div class="form-error"><span class="material-symbols-outlined">error</span>
                        No account exist with this email.</div>
                    <input class="col-12" type="email" name="admin_email" required />
                    <div class="form-label">Password<span class="required">*</span></div>
                    <div class="form-error"><span class="material-symbols-outlined">error</span>
                        Incorrect password.</div>
                    <div class="col-12 sharing-input-button">
                        <input class="col-12" type="password" name="admin_password" required />
                        <div class="password-visible material-symbols-outlined"
                                title="Show Password" onclick="togglePasswordVisibility(this)">
                            visibility</div>
                    </div>
                    <input type="submit" class="col-6 form-button" title="Login" 
                            value="Login" name="login" />
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