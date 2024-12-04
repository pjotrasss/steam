<?php
require ('conn.php');
require ('basic_scripts.php');


//creating checkbox filters for tags and platforms
//creating radial filters for developers and publishers
function create_inputs($table, $type) {
    global $conn;

    $sql = "SELECT * FROM $table;";
    $result = $conn->query($sql);

    while ($row = mysqli_fetch_array($result)) {
        $id = $row['ID'];
        $name = htmlspecialchars($row['NAME']);

        echo "<div class='input_row'>";
        echo    "<input type='$type' value='$id' name='$table'>&nbsp;$name";
        echo "</div>";
    };
};



//selecting all games from db
function select_allgames() {
    global $conn;

    $sql = "SELECT * FROM games;";
    $result = $conn->query($sql);
    while($row = mysqli_fetch_array($result)) {
        $gamedata = prepare_game_data($row);
        echo "<a href='game.html.php?id={$gamedata['id']}'>";
        echo    "<div class='gamebox'>";
        echo        "<img src='{$gamedata['logo_url']}' alt='{$gamedata['title']}' />";
        echo        "<div class='gamedata_box'>";
        echo            "<p>{$gamedata['title']}</p>";
        echo            "<p>{$gamedata['description']}</p>";
        echo        "</div>";
        echo    "</div>";
        echo "</a>";
    };
};



//selecting filtered games from db
function select_filtered_games() {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    global $conn;

    $conditions = [];
    $params = [];
    $types = '';

    $sql = 'SELECT DISTINCT games.* FROM games
        LEFT JOIN games_tags ON games.ID=games_tags.GAME_ID
        LEFT JOIN games_platforms ON games.ID=games_platforms.GAME_ID
        LEFT JOIN games_discounts ON games.ID=games_discounts.GAME_ID;';

    $tags = $data['tags'] ?? [];
    if (!empty($tags))  {
        $placeholders = implode(',', array_fill(0, count($tags), '?'));
        $conditions[] = "games_tags.TAG_ID IN ($placeholders)";
        $types .= str_repeat('i', count($tags));
        $params = array_merge($params, $tags);
    };

    $platforms = $data['platforms'] ?? [];
    if (!empty($platforms)) {
        $placeholders = implode(',', array_fill(0, count($platforms), '?'));
        $conditions[] = "games_platforms.PLATFORM_ID IN ($placeholders)";
        $types .= str_repeat('i', count($platforms));
        $params = array_merge($params, $platforms);
    };

    $developer = $data['developers'] ?? [];
    if (!empty($developer)) {
        $conditions[] = 'games.DEVELOPER_ID = ?';
        $types .= 'i';
        $params[] = $developer[0];
    };

    $publisher = $data['publishers'] ?? [];
    if (!empty($publisher)) {
        $conditions[] = 'games.PUBLISHER_ID = ?';
        $types .= 'i';
        $params[] = $publisher[0];
    };


    if(!empty($conditions)) {
        $sql .= ' WHERE '.implode(' AND ', $conditions);
    };

    $stmt = $conn->prepare($sql);
    if(!empty($params)){
        $stmt->bind_param($types, ...$params);
    };
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
};