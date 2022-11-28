<?php
function addNewUserRatings($userID, $ratings)    {
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    $q="INSERT INTO Ratings (userID, contentID, rating) VALUES ";
    $i=0;
    foreach($ratings as $showID => $rating)    {
        if($i==sizeof($ratings)-1)    {
            $q=$q."($userID, $showID, $rating);";
            break;
        }
        $q=$q."($userID, $showID, $rating), ";
        $i++;
    }
    $r=mysqli_query($conn, $q);
    mysqli_close($conn);
    return $r;
}

function getRating($userID, $showID)    {
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    /* Getting the user from the database.*/
    $q="SELECT rating FROM Ratings WHERE userID=$userID AND contentID=$showID;";
    $result="";
    $r=mysqli_query($conn, $q);
    if(mysqli_num_rows($r)>0) {
        $result=mysqli_fetch_assoc($r)['rating'];
    }
    mysqli_close($conn);
    return $result;
}

function setRating($userID, $showID, $rating)    {
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    $r=false;
    if(getRating($userID, $showID)=="") {
        $q="INSERT INTO Ratings (userID, contentID, rating) VALUES ($userID, $showID, $rating);";
        $r=mysqli_query($conn, $q);
    }
    else    {
        $q="UPDATE Ratings SET rating=$rating, ratedAt=CURRENT_TIMESTAMP ".
                "WHERE userID=$userID AND contentID=$showID;";
        $r=mysqli_query($conn, $q);
    }
    mysqli_close($conn);
    return $r;
}

function deleteRating($userID, $showID) {
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    $q="DELETE FROM Ratings WHERE userID=$userID AND contentID=$showID;";
    $r=mysqli_query($conn, $q);
    
    mysqli_close($conn);
    return $r;
}

function getAllRatingsForShow($showID)  {
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    /* Getting the user from the database.*/
    $q="SELECT SUM(rating) AS S, COUNT(rating) AS C FROM Ratings WHERE contentID=$showID;";
    $result="";
    $r=mysqli_query($conn, $q);
    if(mysqli_num_rows($r)>0) {
        $result=mysqli_fetch_assoc($r);
    }
    mysqli_close($conn);
    return $result;
}

function getAllRatings()    {
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    /* Getting the user from the database.*/
    $q="SELECT * FROM Ratings ORDER BY ratedAt DESC;";
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