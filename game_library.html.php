<?php
require ('./scripts/php/game_library.php');
require ('./scripts/php/user_sessions');
?>
<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="styles/index.css" />
        <link rel="stylesheet" href="styles/header.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
        <link rel="shortcut icon" href="https://store.steampowered.com/favicon.ico" />
        <title>Game library | steam</title>
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
            <div id="games">
                <h1>YOUR GAMES</h1>
                <div name="all_games"></div>
                <div name="selected_games"></div>
            </div>
        </main>
        <footer>

        </footer>
    </body>
</html>