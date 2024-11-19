<?php
    require ('scripts/php/games.php');
    require ('scripts/php/user_sessions.php');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles/game.css" />
        <link rel="stylesheet" href="styles/header.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
        <title><?php show_game_title();?></title>
    </head>
    <body>
        <header>
            <div class="navitem"><a href="index.html.php"><img src="images/logo_steam.svg" alt="logo_steam" /></a></div>
            <div class="navitem"><p><a href="index.html.php">GAMES</a></p></div>
            <div class="navitem"><p><a href="developers_publishers.html.php?title=developers">DEVELOPERS</a></p></div>
            <div class="navitem"><p><a href="developers_publishers.html.php?title=publishers">PUBLISHERS</a></p></div>
            <?php login_profile();?>
        </header>
        <main class="column_nowrap">
            <div id="game" class="column_nowrap">
                <?php show_game();?>
            </div>
        </main>
        <footer>
            
        </footer>
    </body>
</html>