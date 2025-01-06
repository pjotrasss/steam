<?php
    require_once ('scripts/php/user_sessions.php');
    require ('scripts/php/add_billing.php');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles/header.css" />
        <link rel="stylesheet" href="styles/add_billing.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
        <link rel="shortcut icon" href="https://store.steampowered.com/favicon.ico" />
        <title>Add billing address | steam</title>
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
            <h1>Add billing address</h1>
            <form name="add_billing" action="scripts/php/add_billing.php" method="post">
                <?php
                    choose_or_add("countries", 'none', "country", "Country", "COUNTRY", "choose", "required");
                    choose_or_add("cities", 'text', "city", "City", "CITY", "choose&add", "required");
                    choose_or_add("zipcodes", 'text', "zipcode", "Zipcode", "CODE", "choose&add", "required");
                    choose_or_add("streets", "text", "street", "Street", "STREET", "choose&add", "required");
                    choose_or_add("buildings", "text", "building_number", "Building number", "NUMBER", "add", "required");
                    choose_or_add("apartments", "text", "apartment_number", "Apartment number", "APARTMENT", "add", "");
                    choose_or_add("none", 'textarea', 'other_billing_info', 'Other billing information', 'none', 'add_other', "");
                ?>
                <input type="submit" value="Continue" name="add_billing"/>
            </form>
        </main>
        <footer>
            
        </footer>
    </body>
</html>