<?php
session_start();

include "database/UserQueries.php";
include "database/LanguageQueries.php";
include "database/PlatformQueries.php";
include "database/ShowQueries.php";
include "database/RatingsQueries.php";
include "database/StatusQueries.php";

$userID=-1;
/*Check if the cookie token is present.*/
if(isset($_COOKIE['cprs_token']))    {
    $arr=isUserAlreadyLoggedIn(session_id(), $_COOKIE['cprs_token']);
    //echo sizeof($arr);
    if(sizeof($arr)>0 && $arr['userID']!="") {
        $_SESSION["user_id"]=$userID=$arr['userID'];
        if($arr['isNewUser']==false)
            header("Location: Homepage.php");
    }
    else   {
        header("Location: Login.php");
    }
}else   {
    header("Location: Login.php");
}

$languages=getAllLanguages();
$platforms=getAllPlatforms();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>New User Preferences</title>
        <link rel="stylesheet" 
                href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
        <link rel="stylesheet" href="Window.css" />
        <link rel="stylesheet" href="Elements.css" />
        <link rel="stylesheet" href="templates/CustomCheckbox.css" />
        <link rel="stylesheet" href="templates/ShowsList.css" />
        <link rel="stylesheet" href="templates/Navigation.css" />
        <!-- <link rel="stylesheet" href="templates/Search.css" /> -->
        <script src="templates/CloneTemplate.js"></script>
        <script src="templates/CustomCheckbox.js"></script>
        <script src="templates/ShowsList.js"></script>
        <style>
            .full-page-content:nth-of-type(2)  {
                margin-top: 30px;
                display: none;
            }
        </style>
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
        /*When 'Get Started' is clicked*/
        $ratings=array();
        if($_SERVER["REQUEST_METHOD"]=="POST") {
            foreach($_POST as $i => $rating)  {
                if(str_contains($i, "rating_") && $rating!=null && $rating!="")
                    $ratings[intval(substr($i, 7))]=$rating;
            }
            if(sizeof($ratings)>0)  {
                addNewUserRatings($userID, $ratings);
                addNewUserStatuses($userID, array_keys($ratings));
                changeUserFromNewToExisting($userID);
                header("Location: Homepage.php");
            }
        }

        
        /*When 'Next' button is clicked*/
        $selectedLanguageIDs=array(); $selectedPlatformIDs=array();
        if($_SERVER["REQUEST_METHOD"]=="POST") {
            for($i=0; $i<sizeof($languages); $i++)  {
                if(isset($_POST[$languages[$i]["languageName"]]))  {
                    array_push($selectedLanguageIDs, $_POST[$languages[$i]["languageName"]]);
                    $languages[$i]["checked"]=true;
                }
            }
            for($i=0; $i<sizeof($platforms); $i++)  {
                if(isset($_POST[$platforms[$i]["platformName"]]))  {
                    array_push($selectedPlatformIDs, $_POST[$platforms[$i]["platformName"]]);
                    $platforms[$i]["checked"]=true;
                }
            }
            sort($selectedLanguageIDs);
            sort($selectedPlatformIDs);

            if(sizeof($selectedLanguageIDs)>0)
                addLanguagePreferences($userID, $selectedLanguageIDs);
            if(sizeof($selectedPlatformIDs)>0)
                addPlatformPreferences($userID, $selectedPlatformIDs);
        }
        ?>

        <!-- Every element (except the background) that is below the navigation bar goes here -->
        <main class="main-2 col-12">
        
            <div class="full-page-content col-12"> 
                <div class="page-title">Setting your Preferences</div>

                <!-- Form to collect information, details, etc. from the user-->
                <form class="form-1" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" 
                        method="post" name="preferences-form" 
                        onsubmit="return validateLanguagesAndPlatforms(0, 1)">
                    <!-- Language Preferences -->
                    <div class="form-label">Languages<span class="required">*</span></div>
                    <div class="label-text">Select the languages that you want shows to be
                        recommended in.
                    </div>
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
                    
                    <!-- OTT Platform Preferences -->
                    <div class="form-label">OTT Platforms<span class="required">*</span></div>
                    <div class="label-text">Select the platforms that you want shows to be
                        recommended on.
                    </div>
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

                    <input type="submit" class="col-3 form-button-2" title="Next" value="Next" />

                </form>
            </div>

            <!-- Rating a few shows -->
            <div class="full-page-content col-12"> 
                <div class="page-title">Rate a Few Shows</div>

                <!-- Form to collect information, details, etc. from the user-->
                <form class="form-1" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" 
                        method="post" name="preferences-form" 
                        onsubmit="return validateShows()">
                    <div class="form-label">Rating<span class="required">*</span></div>
                    <div class="label-text">Provide ratings for atleast one show,
                        based on which we'll provide you the personalized recommendations.
                    </div>
                    <div class="form-error"><span class="material-symbols-outlined">error</span>
                        Please provide a rating for atleast one show.</div>
                    <!-- <div class="col-12 sharing-input-button">
                        <input class="search-input col-4" type="text" name="search" />
                        <div class="search-button material-symbols-outlined"
                                title="Show Password" onclick="">
                            search</div>
                    </div> -->
                    <div class="shows-container col-12" id="currently_watching">
                    <?php
                    if($userID!="" and sizeof($selectedLanguageIDs)>0) {
                        $shows=getTopRatedShows($userID);
                    ?>
                        <script>
                            showsContainer=document.getElementsByClassName("full-page-content")[1];
                            showsContainer.style.display="block";
                            showsContainer.scrollIntoView(true);
                        </script>
                    <?php
                        for($i=0; $i<sizeof($shows); $i++)  {
                    ?>
                        <div class="show-2">
                            <?php 
                            if($shows[$i]['posterURL']!="")  {
                            ?>
                                <img class="show-poster" src="<?php echo $shows[$i]['posterURL']; ?>" />
                            <?php 
                            }
                            else    { ?>
                                <img class="show-poster" src="images/no_image.jpg" />
                            <?php } ?>
                            <div class="show-name-2" title="<?php echo $shows[$i]['title']; ?>">
                                <?php echo $shows[$i]['title']; ?>
                            </div>
                            <div class="show-user-rating">
                                <input type="number" min="0" max="10" class="input-rating" 
                                        name="rating_<?php echo $shows[$i]['showID']; ?>" />
                                <div class="label-by-10">&nbsp;/ 10</div>
                            </div>
                        </div>
                    <?php } 
                    }
                    ?>
                    </div>

                    <input type="submit" class="col-3 form-button-2" title="Get Started" 
                            value="Get Started" />
                </form>
        </main>
    </body>
</html>