<?php

function search($keyword)   {
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());
    
    $keyword="%".str_replace(" ", "%", $keyword)."%";

    /* Getting the user from the database.*/
    $q="SELECT showID, title, posterURL FROM Shows WHERE title LIKE '$keyword';";
    $results=array();
    $r=mysqli_query($conn, $q);
    if(mysqli_num_rows($r)>0) {
        $i=0;
        while($i<mysqli_num_rows($r))    {
            $results[$i]=mysqli_fetch_assoc($r);
            $i+=1;
        }
    }
    mysqli_close($conn);
    return $results;
}

function getShowDetails($showID)    {
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    /* Getting the user from the database.*/
    $q="SELECT * FROM Shows WHERE showID=$showID;";
    $results=array();
    $r=mysqli_query($conn, $q);
    if(mysqli_num_rows($r)>0) {
        $results=mysqli_fetch_assoc($r);
    }
    mysqli_close($conn);
    return $results;
}

function getTopRatedShows($userID, $count=100)  {
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    /* Getting the user from the database.*/
    $q="SELECT DISTINCT S.title, S.showID, S.posterURL, S.platformIDs FROM Shows S, PlatformPreferences P ".
        "WHERE S.languageID IN (SELECT languageID FROM languagepreferences WHERE userID=$userID) AND ".
        "LOCATE(P.platformID, S.platformIDs)>0 ORDER BY average_rating_from_imdb DESC, ".
        "count_of_ratings_imdb DESC LIMIT $count";
    $results=array();
    $r=mysqli_query($conn, $q);
    if(mysqli_num_rows($r)>0) {
        $i=0;
        while($i<mysqli_num_rows($r))    {
            $results[$i]=mysqli_fetch_assoc($r);
            $i+=1;
        }
    }
    mysqli_close($conn);
    return $results;
}

function getCurrentlyWatching($userID)  { 
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    /* Getting the user from the database.*/
    $q="SELECT showID, title, posterURL FROM Shows WHERE showID IN ".
            "(SELECT contentID FROM Status WHERE status=".CURRENTLY_WATCHING." AND userID=$userID);";
    $results=array();
    $r=mysqli_query($conn, $q);
    if(mysqli_num_rows($r)>0) {
        $i=0;
        while($i<mysqli_num_rows($r))    {
            $results[$i]=mysqli_fetch_assoc($r);
            $i+=1;
        }
    }
    mysqli_close($conn);
    return $results;
}

function getWishlist($userID)  { 
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    /* Getting the user from the database.*/
    $q="SELECT showID, title, posterURL FROM Shows WHERE showID IN ".
            "(SELECT contentID FROM Status WHERE status=".WISHLIST." AND userID=$userID);";
    $results=array();
    $r=mysqli_query($conn, $q);
    if(mysqli_num_rows($r)>0) {
        $i=0;
        while($i<mysqli_num_rows($r))    {
            $results[$i]=mysqli_fetch_assoc($r);
            $i+=1;
        }
    }
    mysqli_close($conn);
    return $results;
}

function getRecommendations($userID)    {
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    /* Getting the user from the database.*/
    $q="SELECT showID, title, posterURL, platformIDs FROM PersonalizedRecommendations, ".
            "Shows WHERE userID=$userID AND showID=contentID ORDER BY RAND();";
    $results=array();
    $r=mysqli_query($conn, $q);
    if(mysqli_num_rows($r)>0) {
        $i=0;
        while($i<mysqli_num_rows($r))    {
            $results[$i]=mysqli_fetch_assoc($r);
            $i+=1;
        }
    }
    mysqli_close($conn);
    return $results;
}

function getSimilarContent($showID)    {
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    /* Getting the user from the database.*/
    $q="SELECT showID, title, posterURL, platformIDs FROM SimilarContent, ".
            "Shows WHERE contentID=$showID AND showID=similarContentID ORDER BY RAND();";
    $results=array();
    $r=mysqli_query($conn, $q);
    if(mysqli_num_rows($r)>0) {
        $i=0;
        while($i<mysqli_num_rows($r))    {
            $results[$i]=mysqli_fetch_assoc($r);
            $i+=1;
        }
    }
    mysqli_close($conn);
    return $results;
}
?>