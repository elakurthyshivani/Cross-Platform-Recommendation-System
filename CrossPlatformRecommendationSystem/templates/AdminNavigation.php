<!-- Coded as if this is not a separate document -->

<?php
if($_SERVER['REQUEST_METHOD']=='POST') {
    if(isset($_POST['logout_button']))  {
        setcookie("admin_cprs_token", "", time()-3600);
        session_unset();
        session_destroy();
        header("Location: AdminLogin.php");
    }
}
?>

<template id="admin_navigation_without_buttons_template">
    <nav class="col-12">
        <img class="logo" src="../images/logo.png" />
    </nav>
</template>
<template id="admin_navigation_with_buttons_template">
    <nav class="col-12">
        <img class="logo" src="../images/logo.png" />
        <div class="nav-buttons-container">
            <form method="post" name="logoutForm" 
                    action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <button type="submit" name="logout_button" value="logout">
                    <div class="nav-button material-symbols-outlined" title="Logout">
                        logout
                    </div>
                </button>
            </form>
            <form method="post" name="goToViewAllUsers" action="ViewAllUsers.php">
                <button type="submit" name="view_all_users_button" value="view_all_users">
                    <div class="nav-button material-symbols-outlined" title="View All Users">
                        group
                    </div>
                </button>
            </form>
            <form method="post" name="goToViewAllRatings" action="ViewAllRatings.php">
                <button type="submit" name="view_all_ratings_button" value="view_all_ratings">
                    <div class="nav-button material-symbols-outlined" title="View All Ratings">
                        hotel_class
                    </div>
                </button>
            </form>
        </div>
    </nav>
</template>


<!--<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" 
                href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
        <link rel="stylesheet" href="../Window.css" />
        <link rel="stylesheet" href="../Elements.css" />
        <link rel="stylesheet" href="Navigation.css" />
        <script></script>
    </head>
    <body>
    </body>
</html>-->