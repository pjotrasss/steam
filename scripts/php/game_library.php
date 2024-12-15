<?php
require ("conn.php");
require ("basic_scripts.php");

function show_user_library() {
    global $conn;

    $user_library_sql = 'SELECT games.ID as ID, LOGO_URL, TITLE, DESCRIPTION, PRICE FROM games
                        JOIN games_users ON games_users.GAME_ID=games.ID
                        JOIN users ON users.ID=games_users.USER_ID
                        JOIN prices ON prices.ID=games.PRICE_ID
                        WHERE users.ID=?';
    $user_library_stmt = $conn->prepare($user_library_sql);
    $user_library_stmt->bind_param('i',$_SESSION['user_data']['user_id']);
    $user_library_stmt->execute();
    $user_library_result = $user_library_stmt->get_result();

    if ($user_library_result->num_rows>0) {
        while($game = mysqli_fetch_array($user_library_result)) {
            echo_gamedata_link($game);
        };
    } else {
        echo "Your game library is empty";
    };
};