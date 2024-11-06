<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="styles/index.css" />
        <link rel="stylesheet" href="styles/header.css" />
        <?php require ('scripts/scripts.php');?>
        <title>Welcome to steam</title>
    </head>
    <body>
        <header>
            <a href="index.php"><img src="images/logo_steam.svg" alt="logo_steam" /></a>
            <a href="index.php" class="navitem">GAMES</a>
            <a href="developers.php" class="navitem">DEVELOPERS</a>
            <a href="publishers.php" class="navitem">PUBLISHERS</a>
            <a href="login.php" class="navitem">LOGIN</a>
        </header>
        <main>
            <div id="games">
                <h1>DOSTĘPNE GRY</h1>
                <?php select_allgames();?>
            </div>
        </main>
        <footer>

        </footer>
    </body>
</html>
