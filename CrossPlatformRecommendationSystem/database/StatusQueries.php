<?php
define("NOT_YET_STARTED", 0);
define("CURRENTLY_WATCHING", 1);
define("FINISHED", 2);
define("DROPPED", 3);
define("WISHLIST", 4);

define("STATUSES", array("Not Yet Started", "Currently Watching", 
        "Finished", "Dropped", "Wishlist"));

function getStatus($userID, $showID)    {
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    /* Getting the user from the database.*/
    $q="SELECT status FROM Status WHERE userID=$userID AND contentID=$showID;";
    $result=NOT_YET_STARTED;
    $r=mysqli_query($conn, $q);
    if(mysqli_num_rows($r)>0) {
        $result=mysqli_fetch_assoc($r)['status'];
    }
    mysqli_close($conn);
    return $result;
}

function addNewUserStatuses($userID, $showIDs)  {
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    $q="INSERT INTO Status (userID, contentID, status) VALUES ";
    for($i=0; $i<sizeof($showIDs); $i++)    {
        $showID=$showIDs[$i];
        if($i==sizeof($showIDs)-1)    {
            $q=$q."($userID, $showID, ".FINISHED.");";
            break;
        }
        $q=$q."($userID, $showID, ".FINISHED."), ";
    }
    $r=mysqli_query($conn, $q);
    mysqli_close($conn);
    return $r;
}

function setStatus($userID, $showID, $status)    {
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    $r=false;
    if(getStatus($userID, $showID)==NOT_YET_STARTED) {
        $q="INSERT INTO Status (userID, contentID, status) VALUES ($userID, $showID, $status);";
        $r=mysqli_query($conn, $q);
    }
    else    {
        $q="UPDATE Status SET status=$status WHERE userID=$userID AND contentID=$showID;";
        $r=mysqli_query($conn, $q);
    }
    
    mysqli_close($conn);
    return $r;
}

function deleteStatus($userID, $showID) {
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    $q="DELETE FROM Status WHERE userID=$userID AND contentID=$showID;";
    $r=mysqli_query($conn, $q);
    
    mysqli_close($conn);
    return $r;
}
?>