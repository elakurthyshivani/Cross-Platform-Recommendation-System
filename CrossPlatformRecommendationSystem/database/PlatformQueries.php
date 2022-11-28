<?php
function getAllPlatforms()    {
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    /* Getting the user from the database.*/
    $q="SELECT * FROM Platforms;";
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

function addPlatformPreferences($userID, $selectedPlatformIDs)    {
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    /* Getting the user from the database.*/
    $q="DELETE FROM PlatformPreferences WHERE userID=$userID;";
    $r=mysqli_query($conn, $q);
    $q="INSERT INTO PlatformPreferences VALUES ";
    for($i=0; $i<sizeof($selectedPlatformIDs); $i++)    {
        if($i==sizeof($selectedPlatformIDs)-1)    {
            $q=$q."($userID, ".$selectedPlatformIDs[$i].");";
            break;
        }
        $q=$q."($userID, $selectedPlatformIDs[$i]), ";
    }
    $r=mysqli_query($conn, $q);
    mysqli_close($conn);
    return $r;
}

function getPlatformPreferences($userID)    {
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    /* Getting the user from the database.*/
    $q="SELECT P.platformID, P.platformName FROM platformpreferences PP, platforms P ".
            "WHERE userID=$userID AND PP.platformID=P.platformID;";
    $results=array();
    $r=mysqli_query($conn, $q);
    if(mysqli_num_rows($r)>0) {
        $i=0;
        while($i<mysqli_num_rows($r))    {
            $row=mysqli_fetch_assoc($r);
            $results[$row['platformID']]=$row['platformName'];
            $i+=1;
        }
    }
    mysqli_close($conn);
    return $results;
}
?>