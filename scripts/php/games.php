<?php
require "conn.php";
require "basic_scripts.php";


//creating checkbox filters for tags and platforms
//creating radial filters for developers and publishers
function create_inputs($table, $type) {
    global $conn;

    $sql = "SELECT * FROM $table;";
    $result = $conn->query($sql);

    while ($row = mysqli_fetch_array($result)) {
        $id = $row['ID'];
        $name = htmlspecialchars($row['NAME']);

        echo "
        <div class='input_row'>
            <input type='$type' value='$id' name='$table'>&nbsp;$name
        </div>";
    };
};



function prepare_game_data($row){
    return [
        'logo_url' => htmlspecialchars($row['LOGO_URL']),
        'title' => htmlspecialchars($row['TITLE']),
        'id' => $row['ID'],
        'description' => htmlspecialchars($row['DESCRIPTION'])
    ];
};



//selecting all games from db
function select_allgames() {
    global $conn;
    $sql = "SELECT * FROM games;";
    $result = $conn->query($sql);
    while($row = mysqli_fetch_array($result)) {
        $gamedata = prepare_game_data($row);
        echo "
        <a href='game.html.php?id={$gamedata['id']}'>
            <div class='gamebox'>
                <img src='{$gamedata['logo_url']}' alt='{$gamedata['title']}' />
                <div class='gamedata_box'>
                    <p>{$gamedata['title']}</p>
                    <p>{$gamedata['description']}</p>
                </div>
            </div>
        </a>";
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

    $sql = 'SELECT DISTINCT games.*
        FROM games
        LEFT JOIN games_tags ON games.ID=games_tags.GAME_ID
        LEFT JOIN games_platforms ON games.ID=games_platforms.GAME_ID
        LEFT JOIN games_discounts ON games.ID=games_discounts.GAME_ID';

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
            echo "
            <div>
                <img src='{$gamedata['logo_url']}' alt='{$gamedata['title']}' />
                <div>
                    <a href='game..html.php?id={$gamedata['id']}'>{$gamedata['title']}</a>
                    <p>{$gamedata['description']}</p>
                </div>
            </div>";
        };
    } else {
        echo "Error: ".$conn->error;
    };
};


//functions for game.php

//finding title of game selected by user
function show_game_title() {
    global $conn;
    $sql = "SELECT TITLE from games WHERE games.ID=".$_GET['id'].";";
    $result = mysqli_fetch_array($conn->query($sql));
    echo htmlspecialchars($result['TITLE']);
};

function show_game_details() {
    global $conn;
    $sql = "SELECT * from games WHERE games.ID={$_GET['id']};";
    $result = mysqli_fetch_array($conn->query($sql));
    $gamedata = prepare_game_data($result);
    echo $gamedata['title'];
    echo $gamedata['description'];
    echo $gamedata['logo_url'];
}