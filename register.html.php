<?php
    require('./scripts/php/user_sessions.php');
    if(soft_session_validation()) {
        header('Location: game_library.html.php');
    };
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles/login_register.css" />
        <link rel="stylesheet" href="styles/header.css" />
        <link rel="shortcut icon" href="https://store.steampowered.com/favicon.ico" />
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <title>register | steam</title>
    </head>
    <body>
        <header>
            <a href="index.html.php"><img src="images/logo_steam.svg" alt="logo_steam" /></a>
            <a class="navitem" href="index.html.php">GAMES</a>
            <a class="navitem" href="developers_publishers.html.php?title=developers">DEVELOPERS</a>
            <a class="navitem" href="developers_publishers.html.php?title=publishers">PUBLISHERS</a>
            <a class="navitem" href="login.html.php">LOGIN</a>
        </header>
        <main>
            <label for="registration_form">REGISTER</label>
            <form id="form_container" name="registration_form" method="post" action="registered.html.php">
                <div class="form_subcontainer" id="form_left">
                    <label for="username">CHOOSE USERNAME</label>
                    <input type="text" placeholder="username" name="username" required />
                    <label for="password">CHOOSE PASSWORD</label>
                    <input type="password" placeholder="password" name="password" required />
                    <label for="rpassword">REPEAT YOUR PASSWORD</label>
                    <input type="password" placeholder="repeat password" name="rpassword" required />
                    <input type="submit" value="REGISTER" name="register" />
                    <input type="checkbox" name="over13" required />
                    <label for="over13">I am 13 years of age or older and agree to the terms of service and RODO</label>
                </div>
            </form>
        </main>
    </body>
</html>