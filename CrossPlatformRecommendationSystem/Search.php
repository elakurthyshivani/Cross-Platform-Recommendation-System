<?php
session_start();

include "database/UserQueries.php";
include "database/ShowQueries.php";

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

$show_name="";
if($_SERVER["REQUEST_METHOD"]=="GET")
    if(isset($_GET["show_name"]))  $show_name=$_GET["show_name"];

$shows=array();
if($show_name!="")  {
    $shows=search($show_name);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>
            <?php
            if($show_name!=null and sizeof($shows)>0)
                echo "Search - ".$show_name; 
            else
                echo "404 - Show not present";
            ?>
        </title>
        <link rel="stylesheet" 
                href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
        <link rel="stylesheet" href="Window.css" />
        <link rel="stylesheet" href="Elements.css" />
        <link rel="stylesheet" href="templates/CustomCheckbox.css" />
        <link rel="stylesheet" href="templates/ShowsList.css" />
        <link rel="stylesheet" href="templates/Navigation.css" />
        <script src="templates/CloneTemplate.js"></script>
        <script src="Search.js"></script>
        <script src="templates/Navigation.js"></script>
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
            <?php
            if($show_name!=null and sizeof($shows)>0)   {
            ?>
                <form class="form-1" name="search" action="ShowInformation.php" method="get">
                    <div class="page-title">Results for "<?php echo $show_name; ?>"</div> 
                    <div class="form-text">Click on a show for more information. You can also provide
                        a rating or set a status for that show.
                    </div>
                    <div class="shows-container col-12" id="currently_watching">
                    <?php
                    for($i=0; $i<sizeof($shows); $i++)  {
                    ?>
                        <div class="show-2" title="<?php echo $shows[$i]['title']; ?>"
                                onclick="goToShow(<?php echo '\''.$shows[$i]['showID'].'\''; ?>)">
                        <?php 
                        if($shows[$i]['posterURL']!="")  {
                        ?>
                            <img class="show-poster" src="<?php echo $shows[$i]['posterURL']; ?>" />
                        <?php 
                        }
                        else    { ?>
                            <img class="show-poster" src="images/no_image.jpg" />
                        <?php } ?>
                            <div class="show-name-2"><?php echo $shows[$i]['title']; ?></div>
                        </div>
                    <?php } ?>
                        <input type="hidden" name="show_id" value="" />
                    </div>
                </form>
            <?php 
            }
            else    {
            ?>
                <div class="error-404 col-12">
                    <img src="images/404.jpg" />
                </div>
            <?php } ?>
            </div>
        </main>
    </body>
</html>