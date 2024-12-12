<?php
require ("game_subscripts.php");


//finding title of game selected by user
function show_game_title($game_id) {
    global $conn;
    $title_sql = "SELECT TITLE from games WHERE games.ID={$game_id};";
    $title_result = mysqli_fetch_array($conn->query($title_sql));
    echo htmlspecialchars($title_result['TITLE']);
};



function show_game($game_id) {
    echo "<h1>";
        show_game_title($game_id);
    echo "</h1>";

    echo "<div id='game_details'>";
    echo    "<div id='left'>";
        show_game_logo_description($game_id);
    echo    "</div>";

    echo    "<div id='right'>";
        show_game_details($game_id);
        show_game_reviews_info($game_id);
        show_game_tags($game_id);
        if(soft_session_validation()) {
            check_logged_user_owns_game($game_id);
        } else {
            echo "<form method='post' action='scripts/php/add_to_cart.php'>";
            echo    "<input type='hidden' value='{$game_id}' name='game_id' />";
            echo    "<input type='submit' value='Add to cart' name='buygame' />";
            echo "</form>";
        };
        echo    "</div>";
    echo "</div>";

    echo "<div id='reviews'>";
        show_game_reviews($game_id);
    echo "</div>";
};