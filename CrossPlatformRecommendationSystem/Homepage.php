<?php
session_start();

include "database/UserQueries.php";
include "database/ShowQueries.php";
include "database/PlatformQueries.php";
include "database/StatusQueries.php";

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

$currentlyWatchingShows=getCurrentlyWatching($userID);
$wishlistShows=getWishlist($userID);
$recommendations=getRecommendations($userID);
if(sizeof($recommendations)<=0)   {
    $recommendations=getTopRatedShows($userID, 30);
}
$platformPreferences=getPlatformPreferences($userID);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Homepage</title>
        <link rel="stylesheet" 
                href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
        <link rel="stylesheet" href="Window.css" />
        <link rel="stylesheet" href="Elements.css" />
        <link rel="stylesheet" href="templates/Navigation.css" />
        <link rel="stylesheet" href="templates/ShowsList.css" />
        <script src="templates/CloneTemplate.js"></script>
        <script src="templates/Navigation.js"></script>
        <script src="Search.js"></script>
        <style> /*Needed for vertical scrolling*/
            .body, main   {
                overflow: hidden;
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
            show("navigation_with_buttons_template");
            show("background_template");
        </script>
    

        <!-- Every element (except the background) that is below the navigation bar goes here -->
        <main class="main-2 col-12">
            <div class="full-page-content col-12">
                <form class="form-1" name="search" action="ShowInformation.php" method="get">
                    <!-- Currently Watching -->
                    <?php
                    if(sizeof($currentlyWatchingShows)>0)   {
                    ?>
                    <div class="page-title">Currently Watching</div>
                    <div class="shows-container-2" id="currently_watching">
                    <?php
                    for($i=0; $i<sizeof($currentlyWatchingShows); $i++)  {
                    ?>
                        <div class="show-2" title="<?php echo $currentlyWatchingShows[$i]['title']; ?>"
                                onclick="goToShow(<?php echo '\''.$currentlyWatchingShows[$i]['showID'].'\''; ?>)">
                        <?php 
                        if($currentlyWatchingShows[$i]['posterURL']!="")  {
                        ?>
                            <img class="show-poster" 
                                    src="<?php echo $currentlyWatchingShows[$i]['posterURL']; ?>" />
                        <?php 
                        }
                        else    { ?>
                            <img class="show-poster" src="images/no_image.jpg" />
                        <?php } ?>
                            <div class="show-name-2">
                                <?php echo $currentlyWatchingShows[$i]['title']; ?>
                            </div>
                        </div>
                    <?php } ?>
                    </div>
                    <?php
                    }
                    ?>

                    <!-- You Might Like -->
                    <div class="page-title you-might-like-title">
                        You Might Like
                        <span class="material-symbols-outlined shows-info"
                                title="Personalized recommendations are updated every hour!">
                            info
                        </span>
                    </div>
                    <div class="shows-container-2" id="currently_watching">
                    <?php
                    for($i=0; $i<sizeof($recommendations); $i++)  {
                    ?>
                        <div class="show-2" title="<?php echo $recommendations[$i]['title']; ?>"
                                onclick="goToShow(<?php echo '\''.$recommendations[$i]['showID'].'\''; ?>)">
                        <?php 
                        if($recommendations[$i]['posterURL']!="")  {
                        ?>
                            <img class="show-poster" 
                                    src="<?php echo $recommendations[$i]['posterURL']; ?>" />
                        <?php 
                        }
                        else    { ?>
                            <img class="show-poster" src="images/no_image.jpg" />
                        <?php } ?>
                            <div class="show-name-2">
                                <?php echo $recommendations[$i]['title']; ?>
                            </div>
                        </div>
                    <?php } ?>
                    </div>
                    
                    <!-- On Platform, you Might Like -->
                    <?php
                    foreach($platformPreferences as $platformID => $platformName)   {
                        $platform=array();
                        for($i=0; $i <sizeof($recommendations); $i++)    {
                            if(str_contains($recommendations[$i]["platformIDs"], "$platformID"))
                                array_push($platform, $recommendations[$i]);
                        }
                    ?>
                    <div class="page-title">On <?php echo $platformName; ?>, You Mike Like</div>
                    <div class="shows-container-2" id="currently_watching">
                    <?php
                        for($i=0; $i<sizeof($platform); $i++)  {
                    ?>
                        <div class="show-2" title="<?php echo $platform[$i]['title']; ?>"
                                onclick="goToShow(<?php echo '\''.$platform[$i]['showID'].'\''; ?>)">
                        <?php 
                            if($platform[$i]['posterURL']!="")  {
                        ?>
                            <img class="show-poster" 
                                    src="<?php echo $platform[$i]['posterURL']; ?>" />
                        <?php 
                        }
                            else    { ?>
                            <img class="show-poster" src="images/no_image.jpg" />
                        <?php } ?>
                            <div class="show-name-2">
                                <?php echo $platform[$i]['title']; ?>
                            </div>
                        </div>
                    <?php } # Inner for ?>
                    </div>
                    <?php } # Foreach ?> 

                    <!-- Wishlist -->
                    <?php
                    if(sizeof($wishlistShows)>0)   {
                    ?>
                    <div class="page-title">Wishlist</div>
                    <div class="shows-container-2" id="currently_watching">
                    <?php
                    for($i=0; $i<sizeof($wishlistShows); $i++)  {
                    ?>
                        <div class="show-2" title="<?php echo $wishlistShows[$i]['title']; ?>"
                            onclick="goToShow(<?php echo '\''.$wishlistShows[$i]['showID'].'\''; ?>)">
                        <?php 
                        if($wishlistShows[$i]['posterURL']!="")  {
                        ?>
                            <img class="show-poster" 
                                    src="<?php echo $wishlistShows[$i]['posterURL']; ?>" />
                        <?php 
                        }
                        else    { ?>
                            <img class="show-poster" src="images/no_image.jpg" />
                        <?php } ?>
                            <div class="show-name-2" 
                                    title="<?php echo $wishlistShows[$i]['title']; ?>">
                                <?php echo $wishlistShows[$i]['title']; ?>
                            </div>
                        </div>
                    <?php } ?>
                    </div>
                    <?php } ?>
                    
                    <input type="hidden" name="show_id" value="" />
                </form>
            </div>
        </main>
    </body>
</html>