<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Opening Page</title>
        <link rel="stylesheet" 
                href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
        <link rel="stylesheet" href="Window.css" />
        <link rel="stylesheet" href="Elements.css" />
        <link rel="stylesheet" href="templates/Navigation.css" />
        <link rel="stylesheet" href="Signup.css" />
        <script src="templates/CloneTemplate.js"></script>
        <style>
        p 
        {
            text-align: center;
            font-size: 32px;
        }
        </style>
    </head>
    <body>
        <!-- Navigation Bar, Background -->
        <?php
        include "templates/Navigation.php";
        include "templates/Background.php";
        ?>
        <script>
            show("navigation_without_buttons_template");
            show("background_template");
        </script>

        <!-- Every element (except the background) that is below the navigation bar goes here -->
        <main class="main col-12"> 
            <div class="big-logo col-6"></div>
            <div class="half-page-content col-6">
                <div class="page-title">Welcome!</div>
                <form class="form-1" action="Login.php" method="post">
                    <div>
                    <div class="form-label">
                    <p><br>You can’t decide between thousands of movies available for streaming?<br><br>
                    Wasting half an hour and still cannot decide what to watch.<br><br>
                    Then you have landed on the right page.<br><br></p>
                    </div>
                    <input type="submit" class="col-6 form-button" title="Login" value="Lets Get Started" />
                    </div>
                </form>
            </div>
        </main>
    </body>
</html>