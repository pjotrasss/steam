<?php 
    require ('scripts/php/developers_publishers.php');
    global $title;
    $title = select_developers_publishers_title();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles/developers_publishers.css" />
        <link rel="stylesheet" href="styles/header.css" />
        <title><?php echo $title;?></title>
    </head>
    <body>
        <header>
            <a href="index.html.php"><img src="images/logo_steam.svg" alt="logo_steam" /></a>
            <a href="index.html.php" class="navitem">GAMES</a>
            <a href="developers_publishers.html.php?title=developers" class="navitem">DEVELOPERS</a>
            <a href="developers_publishers.html.php?title=publishers" class="navitem">PUBLISHERS</a>
            <a href="login.html.php" class="navitem">LOGIN</a>
        </header>
        <main class="column_nowrap">
            <?php select_all($title);?>
        </main>
        <footer>
            
        </footer>
    </body>
</html>