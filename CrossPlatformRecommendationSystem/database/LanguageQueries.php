<?php
function getLanguage($languageID)    {
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    /* Getting the user from the database.*/
    $q="SELECT languageName FROM Languages WHERE languageID=$languageID;";
    $result="";
    $r=mysqli_query($conn, $q);
    if(mysqli_num_rows($r)>0) {
        $result=mysqli_fetch_assoc($r)['languageName'];
    }
    mysqli_close($conn);
    return $result;
}

function getAllLanguages()    {
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    /* Getting the user from the database.*/
    $q="SELECT * FROM Languages ORDER BY languageName ASC;";
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

function addLanguagePreferences($userID, $selectedLanguageIDs)    {
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    /* Getting the user from the database.*/
    $q="DELETE FROM LanguagePreferences WHERE userID=$userID;";
    $r=mysqli_query($conn, $q);
    $q="INSERT INTO LanguagePreferences VALUES ";
    for($i=0; $i<sizeof($selectedLanguageIDs); $i++)    {
        if($i==sizeof($selectedLanguageIDs)-1)    {
            $q=$q."($userID, ".$selectedLanguageIDs[$i].");";
            break;
        }
        $q=$q."($userID, $selectedLanguageIDs[$i]), ";
    }
    $r=mysqli_query($conn, $q);
    mysqli_close($conn);
    return $r;
}

function getLanguagePreferences($userID)  {
    /* Make a connection to the database */
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    /* Getting the user from the database.*/
    $q="SELECT LP.languageID FROM LanguagePreferences AS LP, Languages AS L ".
            "WHERE userID=$userID AND L.languageID=LP.languageID ORDER BY languageName ASC;";
    $results=array();
    $r=mysqli_query($conn, $q);
    if(mysqli_num_rows($r)>0) {
        $i=0;
        while($i<mysqli_num_rows($r))    {
            $results[mysqli_fetch_assoc($r)['languageID']]=true;
            $i+=1;
        }
    }
    mysqli_close($conn);
    return $results;
}
?>