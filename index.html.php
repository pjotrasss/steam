<?php
    require ('scripts/php/scripts.php');

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
        <script src="scripts/js/games_filtering_ajax.js"></script>
        <title>Welcome to steam</title>
    </head>
    <body>
        <header>
            <a href="index.html.php"><img src="images/logo_steam.svg" alt="logo_steam" /></a>
            <a href="index.html.php" class="navitem">GAMES</a>
            <a href="developers_publishers.html.php?title=developers" class="navitem">DEVELOPERS</a>
            <a href="developers_publishers.html.php?title=publishers" class="navitem">PUBLISHERS</a>
            <a href="login.html.php" class="navitem">LOGIN</a>
        </header>
        <main class="row_nowrap">
            <div id="finder" class="column_nowrap_start">
                <h1>FILTERS</h1>
                <form method="post" name="gamefinder">
                    <fieldset class="basic_border column_nowrap_start">
                        <legend>SELECT TAGS</legend>
                        <?php create_inputs(0,0);?>
                    </fieldset>
                    <fieldset class="basic_border column_nowrap_start">
                        <legend>SELECT PLATFORMS</legend>
                        <?php create_inputs(1,0);?>
                    </fieldset>
                    <fieldset class="basic_border column_nowrap_start">
                        <legend>CHOOSE DEVELOPER</legend>
                        <?php create_inputs(2,1);?>
                    </fieldset>
                    <fieldset class="basic_border column_nowrap_start">
                        <legend>CHOOSE PUBLISHER</legend>
                        <?php create_inputs(3,1);?>
                    </fieldset>
                </form>
            </div>
            <div id="gamelist" class="column_nowrap">
                <h1>AVAILABLE GAMES</h1>
                <div id="all_games"><?php select_allgames();?></div>
                <div id="selected_games"></div>
            </div>
        </main>
        <footer>

        </footer>
    </body>
</html>