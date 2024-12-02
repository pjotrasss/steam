<?php
require ('conn.php');
require ('basic_scripts.php');


global $conn;
$username = $_SESSION['username'];


$sql = 'SELECT * FROM games JOIN games_users ON games_users.GAME_ID=games.ID JOIN users ON users.ID=games_users.USER_ID WHERE users.EMAIL=?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('s',$username);
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    while ($row = mysqli_fetch_array($result)) {
        $gamedata = prepare_game_data($row);
        echo "<div>";
        echo    "<img src='{$gamedata['logo_url']}' alt='{$gamedata['title']}' />";
        echo    "<div>";
        echo        "<a href='game..html.php?id={$gamedata['id']}'>{$gamedata['title']}</a>";
        echo        "<p>{$gamedata['description']}</p>";
        echo    "</div>";
        echo "</div>";
    };
} else {
    echo "Error: ".$conn->error;
};