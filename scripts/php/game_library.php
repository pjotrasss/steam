<?php
require("conn.php");
require("basic_scripts.php");

function show_user_library() {
    global $conn;

    $sql = 'SELECT games.ID as ID, LOGO_URL, TITLE, DESCRIPTION FROM games JOIN games_users ON games_users.GAME_ID=games.ID JOIN users ON users.ID=games_users.USER_ID WHERE users.ID=?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i',$_SESSION['user_data']['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows>0) {
        while($row = mysqli_fetch_array($result)) {
            echo_gamedata_link($row);
        };
    } else {
        echo "Your game library is empty";
    };
};