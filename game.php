<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles/game.css" />
        <link rel="stylesheet" href="styles/header.css" />
        <?php require ('scripts/scripts.php');?>
        <title><?php show_game_title();?></title>
    </head>
    <body>
        <header>
            <a href="index.php" class="navitem"><img src="images/logo_steam.svg" alt="logo_steam" /></a>
            <a href="index.php" class="navitem">GAMES</a>
            <a href="developers.php" class="navitem">DEVELOPERS</a>
            <a href="publishers.php" class="navitem">PUBLISHERS</a>
            <a href="login.php" class="navitem">LOGIN</a>
        </header>
        <main>
            <div id="game">
                <?php show_game();?>
            </div>
        </main>
        <footer>
            
        </footer>
    </body>
</html>