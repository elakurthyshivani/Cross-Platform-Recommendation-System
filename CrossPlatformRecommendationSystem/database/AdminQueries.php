<?php
function isAdminAlreadyLoggedIn($sessionID, $token) {
    /*Make a connection to the database*/
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());
    
    /*Decrypting token*/
    // $token=decryptValue($token, $conn);

    $q="SELECT adminID FROM cprsAdmin WHERE sessionID LIKE '$sessionID' AND token LIKE '$token';";
    $row=array();
    $r=mysqli_query($conn, $q);
    if(mysqli_num_rows($r)>0)
        $row=mysqli_fetch_assoc($r);

    mysqli_close($conn);
    return $row;
}

function getAdminID($email)   {
    /*Make a connection to the database*/
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());

    /*Encrypting password*/
    // $password=encryptValue($password, $conn);

    $q="SELECT adminID, password FROM cprsAdmin WHERE email LIKE '$email';";
    $row=array();
    $r=mysqli_query($conn, $q);
    if(mysqli_num_rows($r)>0) 
        $row=mysqli_fetch_assoc($r);

    mysqli_close($conn);
    return $row;
}

function updateAdminSessionIDAndToken($adminID, $sessionID, $token)  {
    /*Make a connection to the database*/
    $conn=mysqli_connect("localhost:3309", "root", "", "cprs");
    if(!$conn) die("Connection failed : ".mysqli_connect_error());
    
    /*Encrypting password*/
    // $password=encryptValue($password, $conn);

    /*Inserting the user into the database.*/
    $q="UPDATE cprsAdmin SET sessionID='$sessionID', token='$token' WHERE adminID=$adminID;";
    $result=FALSE;
    if(mysqli_query($conn, $q)) $result=TRUE;

    mysqli_close($conn);
    return $result;
}
?>