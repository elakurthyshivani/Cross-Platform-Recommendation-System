<!-- Coded as if this is not a separate document -->

<?php
if($_SERVER['REQUEST_METHOD']=='POST') {
    if(isset($_POST['logout_button']))  {
        setcookie("cprs_token", "", time()-3600);
        session_unset();
        session_destroy();
        header("Location: Login.php");
    }
}
?>

<template id="navigation_without_buttons_template">
    <nav class="col-12">
        <img class="logo" src="images/logo.png" />
    </nav>
</template>
<template id="navigation_with_buttons_template">
    <nav class="col-12">
        <form method="post" name="goToHomeForm" action="Homepage.php">
            <button type="submit" name="home_button" class="home-button">
                <img class="logo" src="images/logo.png" />
            </button>
        </form>
        <div class="nav-buttons-container">
            <form method="get" name="searchForm" class="search-form" action="Search.php"
                    onsubmit="return toggleSearchBar(this)">
                <input type="text" class="search-bar" name="show_name" />
                <button type="submit" name="search_button" class="search-button">
                    <div class="nav-button material-symbols-outlined" title="Search a Show">
                        search
                    </div>
                </button>
            </form>
            <form method="post" name="goToProfileForm" action="Profile.php">
                <button type="submit" name="profile_button" value="profile">
                    <div class="nav-button material-symbols-outlined" title="My Profile">
                        manage_accounts
                    </div>
                </button>
            </form>
            <form method="post" name="logoutForm" 
                    action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <button type="submit" name="logout_button" value="logout">
                    <div class="nav-button material-symbols-outlined" title="Logout">
                        logout
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