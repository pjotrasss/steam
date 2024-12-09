<?php
    require_once ('./scripts/php/user_sessions.php');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles/login_register.css" />
        <link rel="stylesheet" href="styles/header.css" />
        <link rel="shortcut icon" href="https://store.steampowered.com/favicon.ico" />
        <title>sign in | steam</title>
    </head>
    <body>
        <header>
        <a href="index.html.php"><img src="images/logo_steam.svg" alt="logo_steam" /></a>
            <a class="navitem" href="index.html.php">GAMES</a>
            <a class="navitem" href="developers_publishers.html.php?title=developers">DEVELOPERS</a>
            <a class="navitem" href="developers_publishers.html.php?title=publishers">PUBLISHERS</a>
            <?php login_profile();?>
        </header>
        <main>
            <label for="login_form">SIGN IN</label>
            <form name="login_form" method="post" action="logged.html.php">
                <div class="form_subcontainer" id="form_left">
                    <label for="username">SIGN IN WITH ACCOUNT NAME</label>
                    <input type="text" placeholder="username" name="username" required />
                    <label for="password">PASSWORD</label>
                    <input type="password" placeholder="password" name="password" required />
                    <input type="submit" value="SIGN IN" name="signin" />
                </div>
                <div class="form_subcontainer" id="loginform_right">
                    <img src="images/logowanie.svg" alt="qr code">
                </div>
            </form>
            <p>Don't have an account yet? Register it <a href="register.html.php">here</a>!</p>
        </main>
        <footer>
            
        </footer>
    </body>
</html>