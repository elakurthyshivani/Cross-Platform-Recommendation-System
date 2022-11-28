<?php
session_start();

include "database/UserQueries.php";
include "database/LanguageQueries.php";
include "database/PlatformQueries.php";

include "templates/SendEmail.php";
include "templates/EmailTemplates.php";

$userID=-1;
/*Check if the cookie token is present.*/
if(isset($_COOKIE['cprs_token']))    {
    $arr=isUserAlreadyLoggedIn(session_id(), $_COOKIE['cprs_token']);
    //echo sizeof($arr);
    if(sizeof($arr)>0 && $arr['userID']!="") {
        $_SESSION["user_id"]=$userID=$arr['userID'];
        if($arr['isNewUser'])
            header("Location: NewUserPreferences.php");
    }
    else   {
        header("Location: Login.php");
    }
}else   {
    header("Location: Login.php");
}

$languages=getAllLanguages();
$platforms=getAllPlatforms();

if($_SERVER["REQUEST_METHOD"]=="POST")  {
    /* If Save button is clicked */
    if(isset($_POST['save_user']))  {
        $photoPresent=false;
        if(isset($_FILES['choose_photo']))  {
            move_uploaded_file($_FILES['choose_photo']['tmp_name'], "user_uploads/$userID.png");
            $photoPresent=true;
        }

        $name=$email=$password="";
        if(isset($_POST["user_name"]))  $name=$_POST["user_name"];
        if(isset($_POST["user_email"]))  $email=$_POST["user_email"];
        if(isset($_POST["user_password"]))  $password=$_POST["user_password"];

        updateUserDetails($userID, $name, $email, $password, $photoPresent);

        $selectedLanguageIDs=array(); $selectedPlatformIDs=array();
        for($i=0; $i<sizeof($languages); $i++)  {
            if(isset($_POST[$languages[$i]["languageName"]]))
                array_push($selectedLanguageIDs, $_POST[$languages[$i]["languageName"]]);
        }
        for($i=0; $i<sizeof($platforms); $i++)  {
            if(isset($_POST[$platforms[$i]["platformName"]]))
                array_push($selectedPlatformIDs, $_POST[$platforms[$i]["platformName"]]);
        }
        sort($selectedLanguageIDs);
        sort($selectedPlatformIDs);

        if(sizeof($selectedLanguageIDs)>0)
            addLanguagePreferences($userID, $selectedLanguageIDs);
        if(sizeof($selectedPlatformIDs)>0)
            addPlatformPreferences($userID, $selectedPlatformIDs);
    }
}

$userDetails=getUserDetails($userID);

$passwordMismatch=false;
if($_SERVER["REQUEST_METHOD"]=="POST")  {
    /* If Delete button is clicked */
    if(isset($_POST['delete_user']))  {   
        $password="";
        if(isset($_POST["confirm_password"]))  $password=$_POST["confirm_password"];

        /* If passwords match. */
        if($password==$userDetails['password']) {
            deleteUser($userID);
            sendEmail($userDetails['email'], "Your account is deleted", 
                    generateAccountDeletedEmailBody($userDetails['name']));
                
            session_unset();
            session_destroy();

            header("Location: Signup.php");
        }
        /* If passwords does not match. */
        else    {
            $passwordMismatch=true;
        }
    }
}

$languagePreferences=getLanguagePreferences($userID);
foreach($languages as $i => $language)  {
    $index=$language['languageID'];
    if(array_key_exists($index, $languagePreferences))
        $languages[$i]['checked']=true;
}

$platformPreferences=getPlatformPreferences($userID);
foreach($platforms as $i => $platform)  {
    $index=$platform['platformID'];
    if(array_key_exists($index, $platformPreferences))
        $platforms[$i]['checked']=true;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Profile</title>
        <link rel="stylesheet" 
                href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
        <link rel="stylesheet" href="Window.css" />
        <link rel="stylesheet" href="Elements.css" />
        <link rel="stylesheet" href="templates/Navigation.css" />
        <link rel="stylesheet" href="Profile.css" />
        <link rel="stylesheet" href="templates/CustomCheckbox.css" />
        <script src="templates/CloneTemplate.js"></script>
        <script src="Elements.js"></script>
        <script src="templates/Navigation.js"></script>
        <script src="templates/CustomCheckbox.js"></script>
        <script src="Profile.js"></script>
        <style>
            .page-title {
                padding-bottom: 30px;
            }
        </style>
    </head>
    <body onresize="resizeProfilePhoto()">
        <!-- Navigation Bar, Background -->
        <?php
        include "templates/Navigation.php";
        include "templates/Background.php";
        ?>
        <script>
            show("navigation_with_buttons_template");
            show("background_template");
        </script>

        <!-- Every element (except the background) that is below the navigation bar goes here -->
        <main class="main-2 col-12">
        
            <div class="full-page-content col-12">
                <div class="page-title">Profile</div>

                <form class="form-2 profile-form" name="profileForm" method="post"
                        enctype= "multipart/form-data" onsubmit="return validateProfileForm()"
                        action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>'>
                        
                    <!-- Profile Photo, Change Photo, Delete my Account -->
                    <div class="left col-3">
                        <div class="profile-photo">
                            &nbsp;
                        </div>
                        <a class="col-12" onclick="choosePhoto(this)">Change Photo</a>
                        <input type="file" name="choose_photo" 
                                accept="image/png, image/jpeg, image/jpg" onchange="displayNewPhoto()" />
                        <div class="form-error"><span class="material-symbols-outlined">error</span>
                                Please upload a jpeg, jpg, or png image file.</div>

                        <a class="delete-account col-12" onclick="openDeleteMyAccount()">
                                Delete my Account</a> 
                    </div>

                    <!-- Name, Email, Password, Language Preferences, Platform Preferences, Save -->
                    <div class="right col-9">
                        <!-- Name -->
                        <div class="form-label">Name<span class="required">*</span></div>
                        <div class="form-error"><span class="material-symbols-outlined">error</span>
                            Name can contain only letters, digits, spaces. 
                            Maximum of 100 characters is allowed.</div>
                        <input class="col-12" type="text" name="user_name" 
                                value="<?php echo $userDetails['name']; ?>"
                                required/>

                        <!-- Email -->
                        <div class="form-label">Email<span class="required">*</span></div>
                        <div class="form-error"><span class="material-symbols-outlined">error</span>
                            Email can contain only letters, digits and special characters ! # $ % & * + - . _
                            . Maximum of 100 characters is allowed.</div>
                        <input class="col-12" type="email" name="user_email" 
                                value="<?php echo $userDetails['email']; ?>"
                                required/>

                        <!-- Password -->
                        <div class="form-label">Password<span class="required">*</span></div>
                        <div class="form-error"><span class="material-symbols-outlined">error</span>
                            Password can contain only letters, digits, @, $, _. 
                            Minimum of 5 to a maximum of 30 characters is allowed.</div>
                        <div class="col-12 sharing-input-button">
                            <input class="col-12" type="password" name="user_password" 
                                value="<?php echo $userDetails['password']; ?>"
                                required/>
                            <div class="password-visible material-symbols-outlined"
                                    title="Show Password" onclick="togglePasswordVisibility(this)">
                                    visibility</div>
                        </div>

                        <!-- Language Preferences -->
                        <div class="form-label">Language Preferences<span class="required">*</span></div>
                        <div class="form-error"><span class="material-symbols-outlined">error</span>
                            Please select atleast one language.</div>
                        <div class="checkboxes-holder col-12">
                        <?php
                        for($i=0; $i<sizeof($languages); $i++)  {
                        ?>
                            <div class="checkbox-pair" onclick="toggleLanguage(this)">
                                <input type="checkbox" name="<?php echo $languages[$i]['languageName']; ?>" 
                                        value="<?php echo $languages[$i]['languageID']; ?>"
                                        class="checkbox-language" />
                                <?php echo $languages[$i]['languageName']; ?>
                            </div>
                            <?php 
                            if(array_key_exists("checked", $languages[$i]) && 
                                    $languages[$i]["checked"])  {
                            ?>
                                <script>
                                    document.getElementsByClassName("checkbox-pair")[<?php echo $i; ?>].click();
                                </script>
                            <?php } ?>
                        <?php } ?>
                        </div>

                        <!-- Platform Preferences -->
                        <div class="form-label">OTT Platforms<span class="required">*</span></div>
                        <div class="form-error"><span class="material-symbols-outlined">error</span>
                            Please select atleast one platform.</div>
                        <div class="checkboxes-holder col-12">
                        <?php
                        for($i=0; $i<4; $i++)  {
                        ?>
                            <div class="platform-logo" onclick="togglePlatform(this)"
                                    title="<?php echo $platforms[$i]['platformName']; ?>">
                                <input type="checkbox" name="<?php echo $platforms[$i]['platformName']; ?>" 
                                        value="<?php echo $platforms[$i]['platformID']; ?>"
                                        class="checkbox-platform" />
                                <img src='<?php echo "images/".$platforms[$i]['platformID'].".png"; ?>' />
                            </div>
                            <?php 
                            if(array_key_exists("checked", $platforms[$i]) && 
                                    $platforms[$i]["checked"])  {
                            ?>
                                <script>
                                    document.getElementsByClassName("platform-logo")[<?php echo $i; ?>].click();
                                </script>
                            <?php } ?>
                        <?php } ?>
                        </div>

                        <input type="hidden" name="button_name" />

                        <input type="submit" class="col-3 form-button-2" title="Save" name="save_user" 
                                value="Save" />

                    </div>
                </form>
            </div>

            <!-- Delete Account -->
            <div class="delete-account-container col-3">
                    <form class="delete-form col-12" name="deleteForm" method="post"
                            onsubmit="return validateDeleteForm()"
                            action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>'>
                        <span class="close col-12 material-symbols-outlined"
                                    onclick="closeDeleteMyAccount()">close</span>
                        <div class="form-label">Password<span class="required">*</span></div>
                        <div class="form-error"><span class="material-symbols-outlined">error</span>
                            Invalid password.</div>
                        <input class="col-12" type="password" name="confirm_password" 
                                required/>
                        <input type="submit" class="form-button-2" title="Delete my account" 
                                name="delete_user" value="Delete Account" onclick="submitClicked('delete')" />
                    </form>
                </div>
        </main>

        <script>
            resizeProfilePhoto();

        <?php 
        if($userDetails['photo'])   { 
        ?>
            updateProfilePhoto("<?php echo "user_uploads/$userID.png"; ?>");
        <?php }
        else    { ?>
            updateProfilePhoto("images/blank_profile_photo.png");
        <?php } ?>

            adjustPositionForDeleteContainer();

        <?php
        if($passwordMismatch==true) {
        ?>
            openDeleteMyAccount();
            document.getElementsByClassName("form-error")[6].style.display="flex";
        <?php } ?>
        </script>
    </body>
</html>