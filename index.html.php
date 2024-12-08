<?php
    require ('scripts/php/index.php');
    require ('scripts/php/user_sessions.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_POST = json_decode(file_get_contents('php://input'), true);
        select_filtered_games();
        exit;
    };
?>
<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="styles/index.css" />
        <link rel="stylesheet" href="styles/header.css" />
        <link rel="stylesheet" href="styles/games_display.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
        <link rel="shortcut icon" href="https://store.steampowered.com/favicon.ico" />
        <script src="scripts/js/games_filtering_ajax.js"></script>
        <title>Welcome to steam</title>
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
            <div id="filters">
                <h1>FILTERS</h1>
                <form method="post" name="gamefinder">
                    <input type="reset" value="Reset filters">
                    <fieldset>
                        <legend>SELECT TAGS</legend>
                        <?php create_inputs("tags", "checkbox");?>
                    </fieldset>
                    <fieldset>
                        <legend>SELECT PLATFORMS</legend>
                        <?php create_inputs("platforms", "checkbox");?>
                    </fieldset>
                    <fieldset>
                        <legend>CHOOSE DEVELOPER</legend>
                        <?php create_inputs("developers","radio");?>
                    </fieldset>
                    <fieldset>
                        <legend>CHOOSE PUBLISHER</legend>
                        <?php create_inputs("publishers","radio");?>
                    </fieldset>
                </form>
            </div>
            <div id="games">
                <h1>AVAILABLE GAMES</h1>
                <div name="all_games"><?php select_allgames();?></div>
                <div name="selected_games"></div>
            </div>
        </main>
        <footer>

        </footer>
    </body>
</html>