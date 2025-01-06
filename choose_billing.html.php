<?php
    require_once ('scripts/php/user_sessions.php');
    require ('scripts/php/choose_billing.php');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles/header.css" />
        <link rel="stylesheet" href="styles/choose_billing.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
        <link rel="shortcut icon" href="https://store.steampowered.com/favicon.ico" />
        <title>Billing address | steam</title>
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
            <h1>Choose or add your billing address</h1>
            <?php choose_billing();?>
        </main>
        <footer>
            
        </footer>
    </body>
</html>