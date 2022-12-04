<?php
session_start();

include "../database/AdminQueries.php";
include "../database/UserQueries.php";

$adminID=-1;
/*Check if the cookie token is present.*/
if(isset($_COOKIE['admin_cprs_token']))    {
    $arr=isAdminAlreadyLoggedIn(session_id(), $_COOKIE['admin_cprs_token']);
    //echo sizeof($arr);
    if(sizeof($arr)>0 && $arr['adminID']!="") {
        $_SESSION["admin_id"]=$adminID=$arr['adminID'];
    }
    else   {
        header("Location: AdminLogin.php");
    }
}else   {
    header("Location: AdminLogin.php");
}

if($_SERVER["REQUEST_METHOD"]=="POST") {
    if(isset($_POST['delete']))  
        deleteUser($_POST['delete']);
}

$users=getAllUsers();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>View All Users</title>
        <link rel="stylesheet" 
                href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
        <link rel="stylesheet" href="../Window.css" />
        <link rel="stylesheet" href="../Elements.css" />
        <link rel="stylesheet" href="../templates/Navigation.css" />
        <link rel="stylesheet" href="../templates/AdminTable.css" />
        <script src="../templates/CloneTemplate.js"></script>
    </head>
    <body>
        <!-- Navigation Bar, Background -->
        <?php
        include "../templates/AdminNavigation.php";
        include "../templates/Background.php";
        ?>
        <script>
            show("admin_navigation_with_buttons_template");
            show("background_template");
        </script>

        <!-- Every element (except the background) that is below the navigation bar goes here -->
        <main class="main-2 col-12"> 
            <div class="full-page-content col-12">
                <div class="page-title">View All Users</div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <table class="col-12">
                    <tr>
                        <!-- <th>User ID</th> -->
                        <th>User ID</th>
                        <th>Email</th>
                        <th>Registration Date</th>
                        <th>Count of Ratings</th>
                        <th></th>
                    </tr>
                    <?php
                    foreach($users as $i => $user)  {
                    ?>
                        <tr>
                            <td class="user-id"><?php echo $user["userID"]; ?></td>
                            <td class="email"><?php echo $user["email"]; ?></td>
                            <td class="registration-date"><?php echo $user["registrationDate"]; ?></td>
                            <td class="count-of-ratings"><?php echo $user["countOfRatings"]; ?></td>
                            <td class="delete-link">
                                <button type="submit" name="delete" 
                                        value="<?php echo $rating["userID"]; ?>">
                                    <a>Delete</a>
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </form>
            </div>
        </main>
    </body>
</html>