<?php
    require ('scripts/php/games.php');
    require ('scripts/php/user_sessions.php');
    $id = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles/game.css" />
        <link rel="stylesheet" href="styles/header.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
        <title><?php show_game_title($id);?></title>
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
            <div id="game_details">
                <div id="left">
                    <?php show_game_info($id);?>    
                </div>
                <div id="right">    
                    <?php show_game_details($id);?>
                </div>
            </div>
            <div id="reviews">
                <?php show_reviews($id);?>
            </div>
        </main>
        <footer>
            
        </footer>
    </body>
</html>