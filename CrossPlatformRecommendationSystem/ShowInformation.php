<?php
session_start();

include "database/UserQueries.php";
include "database/ShowQueries.php";
include "database/LanguageQueries.php";
include "database/RatingsQueries.php";
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

$showID=null;
if($_SERVER["REQUEST_METHOD"]=="GET")
    if(isset($_GET["show_id"]))  {
        $showID=intval($_GET["show_id"]);
        $_SESSION['show_id']=$showID;
    }

if($_SERVER["REQUEST_METHOD"]=="POST")  {
    $showID=null;
    if(isset($_SESSION["show_id"])) {
        $showID=intval($_SESSION["show_id"]);
        if(isset($_POST["rating"]))  {
            if($_POST["rating"]!=0)    {
                $rating=floatval($_POST["rating"]);
                setRating(intval($userID), $showID, $rating);
            }
            else    
                deleteRating(intval($userID), $showID);
        }
        if(isset($_POST["status"]))    {
            $status=intval($_POST["status"]);
            if($status!=NOT_YET_STARTED)
                setStatus(intval($userID), $showID, $status);
            else
                deleteStatus(intval($userID), $showID);
        }
    }
}

$show_details=array();
$similar_content=array();
if($showID!=null)  {
    $show_details=getShowDetails($showID);
    if(sizeof($show_details)>0) {
        $show_details['user_rating']=getRating($userID, $showID);
        $show_details['user_status']=getStatus($userID, $showID);
        $x=getAllRatingsForShow($showID);
        $show_details['sum_user_rated_ratings']=$x['S'];
        $show_details['count_user_rated_ratings']=$x['C'];

        $similar_content=getSimilarContent($showID);
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>
        <?php
        if($showID!=null and sizeof($show_details)>0)
            echo $show_details['title']; 
        else
            echo "404 - Show not present";
        ?>
        </title>
        <link rel="stylesheet" 
                href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
        <link rel="stylesheet" href="Window.css" />
        <link rel="stylesheet" href="Elements.css" />
        <link rel="stylesheet" href="templates/ShowsList.css" />
        <link rel="stylesheet" href="ShowInformation.css" />
        <link rel="stylesheet" href="templates/Navigation.css" />
        <script src="templates/CloneTemplate.js"></script>
        <script src="ShowInformation.js"></script>
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
            if($showID!=null && sizeof($show_details)>0)   {
            ?>
                <form class="form-1 show-form" name="searchForm" method="post"
                        onsubmit="return validateRatingAndStatus()"
                        action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?show_id=$showID"; ?>'>
                    
                        <div class="show-image-container">
                        <?php 
                        if($show_details['posterURL']!="")  {
                        ?>
                            <img class="curr-show-poster" src="<?php echo $show_details['posterURL']; ?>" />
                        <?php 
                        }
                        else    { ?>
                            <img class="curr-show-poster" src="images/no_image.jpg" />
                        <?php } ?>
                        </div>
                        <div class="show-details-container">
                            <!-- Row 1 -->
                            <div class="details-row col-12">
                                <div class="page-title col-10" title="<?php echo $show_details['title']; ?>">
                                    <?php echo $show_details['title']; ?>
                                </div>
                                <div class="platform-logos col-2">
                                    <?php 
                                    $platforms=explode(", ", $show_details['platformIDs']);
                                    for($i=0; $i<sizeof($platforms); $i++)
                                        echo "<img class='platform-logo' src='images/".$platforms[$i].
                                                ".png' />";
                                    ?>
                                </div>
                            </div>

                            <!-- Row 2 -->
                            <div class="details-row col-12">
                                <?php
                                $language=getLanguage($show_details['languageID']);
                                ?>
                                <span><?php echo strtoupper($show_details['contentType']); ?></span>&nbsp;&nbsp;&#183;&nbsp;&nbsp;
                                <span><?php 
                                if($show_details['releaseYear']!="")    {
                                    echo $show_details['releaseYear']; 
                                ?></span>&nbsp;&nbsp;&#183;&nbsp;&nbsp;
                                <span><?php }
                                if($show_details['contentRating']!="Not Rated") {
                                    echo $show_details['contentRating']; 
                                ?></span>&nbsp;&nbsp;&#183;&nbsp;&nbsp;
                                <span>
                                <?php }
                                    echo strtoupper($language); 
                                ?>
                                </span>
                            </div>

                            <!-- Row 3 -->
                            <div class="user-show-input details-row col-12">
                                <div class="set col-4">
                                    <div class="form-label col-5">Average<br />Rating</div>
                                    <div class="label-values col-7">
                                        <?php 
                                        $sumRatings=$show_details['average_rating_from_imdb']*
                                                $show_details['count_of_ratings_imdb']+$show_details['sum_user_rated_ratings'];
                                        $countRatings=$show_details['count_of_ratings_imdb']+$show_details['count_user_rated_ratings'];
                                        if($countRatings==0)
                                            echo "xx";
                                        else
                                            printf("%1.2f", $sumRatings/$countRatings); 
                                        ?>
                                        &nbsp;/&nbsp;10</div>
                                </div>
                                <div class="set col-4">
                                    <div class="form-label col-5">Your<br/>Rating</div>
                                    <input type="number" min="0" max="10" class="input-rating" 
                                        name="rating" step="0.01"
                                        value="<?php printf("%1.2f", $show_details['user_rating']); ?>"/>
                                    <div class="label-values saved-rating">
                                    <?php 
                                        if($show_details['user_rating']==null ||
                                                $show_details['user_rating']==0)
                                            echo "xx";
                                        else
                                            printf("%1.2f", $show_details['user_rating']); 
                                    ?>
                                    </div>
                                    &nbsp;/&nbsp;10
                                </div>
                                <div class="set col-4">
                                    <div class="form-label col-5">Status</div>
                                    <select class="input-status" name="status"
                                        onclick="showHideRating()">
                                    <?php
                                    for($i=0; $i<5; $i++)   {
                                    ?>
                                        <option value="<?php echo $i; ?>"
                                        <?php
                                        if($i==$show_details['user_status'])
                                            echo "selected='selected'";
                                        ?>
                                        >
                                            <?php echo STATUSES[$i]; ?>
                                        </option>
                                    <?php } ?>
                                    </select>
                                    <div class="label-values col-7 saved-status">
                                    <?php 
                                        if(!array_key_exists('user_status', $show_details))
                                            echo "Not Yet Started";
                                        else
                                            echo(STATUSES[$show_details['user_status']]); 
                                    ?>
                                    </div>
                                </div>
                                <div class="user-show-buttons">
                                    <span class="material-symbols-outlined user-show-button" 
                                            title="Edit your rating or status"
                                            onclick="toggleEditVsSave('Edit')">edit</span>
                                    <button type="submit">
                                        <span class="material-symbols-outlined user-show-button" 
                                            title="Save your rating or status"
                                            onclick="">save</span>
                                    </button>
                                    <span class="material-symbols-outlined user-show-button" 
                                            title="Do not save your changes"
                                            onclick="toggleEditVsSave('Close')">close</span>
                                </div>
                            </div>

                            <!-- Row 4 -->
                            <div class="details-row col-12">
                                <div class="form-label col-3">Genres</div>
                                <div class="label-values col-9"><?php echo $show_details['genres']; ?></div>
                            </div>

                            <!-- Row 5 -->
                            <div class="details-row col-12">
                                <div class="form-label col-3">Actors</div>
                                <div class="label-values col-9"><?php echo $show_details['actors']; ?></div>
                            </div>

                            <!-- Row 6 -->
                            <div class="details-row col-12">
                                <div class="form-label col-3">Directors</div>
                                <div class="label-values col-9"><?php echo $show_details['directors']; ?></div>
                            </div>

                            <!-- Row 7 -->
                            <div class="details-row col-12">
                                <div class="form-label col-3">Description</div>
                                <div class="label-values col-9"><?php echo $show_details['summary']; ?></div>
                            </div>

                            <!-- Similar Content -->
                        <?php
                        if(sizeof($similar_content)>0)   {
                        ?>
                            <div class="form-label col-12">Similar Content</div>
                            <div class="shows-container-2" id="currently_watching">
                            <?php
                            for($i=0; $i<sizeof($similar_content); $i++)  {
                            ?>
                                <div class="show-2" title="<?php echo $similar_content[$i]['title']; ?>"
                                        onclick="goToShow(<?php echo '\''.$similar_content[$i]['showID'].'\''; ?>)">
                                <?php 
                                if($similar_content[$i]['posterURL']!="")  {
                                ?>
                                    <img class="show-poster" 
                                            src="<?php echo $similar_content[$i]['posterURL']; ?>" />
                                <?php 
                                }
                                else    { ?>
                                    <img class="show-poster" src="images/no_image.jpg" />
                                <?php } ?>
                                    <div class="show-name-2">
                                        <?php echo $similar_content[$i]['title']; ?>
                                    </div>
                                </div>
                            <?php } ?>
                            </div>
                        <?php } ?>
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

        <script>
            toggleEditVsSave("Close");
            showHideRating();
        </script>
    </body>
</html>