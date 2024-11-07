<?php
require "conn.php";
require "basic_scripts.php";


//functions for index.php

//selecting all games from db
function select_allgames() {
    global $conn;
    $sql = "SELECT * FROM games;";
    $result = $conn->query($sql);
    while($row = mysqli_fetch_array($result)) {
        echo '<div class="row_nowrap basic_border" style="width:95%;">
            <img src='.add_quote($row['LOGO_URL']).' />
            <div class="right column_nowrap">
                <a href="game.php?id='.$row['ID'].'">'.$row['TITLE'].'</a>
                <p>'.$row['DESCRIPTION'].'</p>
            </div>
        </div>';
    };
};



//creating checkbox filters for tags and platforms
//creating radial filters for developers and publishers
function create_inputs($table, $type) {
    global $conn;
    global $types;
    global $tables;

    $sql = "SELECT * FROM $table;";
    $result = $conn->query($sql);

    while ($row = mysqli_fetch_array($result)) {
        echo "
        <div class='input_container row_nowrap'>
            <input type='$type' value='".$row['ID']."' name='$table'>
            <legend>".$row['NAME']."</legend>
        </div>";
    };
};



//selecting filtered games from db
function select_filtered_games() {

    global $conn;
    global $tables;
    $conditions = [];


    $sql = 'SELECT DISTINCT games.*
        FROM games
        LEFT JOIN games_tags ON games.ID=games_tags.GAME_ID
        LEFT JOIN games_platforms ON games.ID=games_platforms.GAME_ID
        LEFT JOIN games_discounts ON games.ID=games_discounts.GAME_ID';

    if (!empty($_POST['tags']))  {
        $tags = implode(',', array_map('intval', $_POST['tags']));
        $conditions[] = 'games_tags.TAG_ID IN ('.$tags.')';
    };

    if (!empty($_POST['platforms'])) {
        $platforms = implode(',', array_map('intval', $_POST['platforms']));
        $conditions[] = 'games_platforms.PLATFORM_ID IN ('.$platforms.')';
    };

    if (!empty($_POST['developers'])) {
        $developer = intval($_POST['developers'][0]);
        $conditions[] = 'games.DEVELOPER_ID IN ('.$developer.')';
    };

    if (!empty($_POST['publishers'])) {
        $publisher = intval($_POST['publishers'][0]);
        $conditions[] = 'games.PUBLISHER_ID IN ('.$publisher.')';
    };


    if(!empty($conditions)) {
        $sql .= ' WHERE '.implode(' AND ', $conditions);
    };


    $result = $conn->query($sql);

    if ($result) {
        while ($row = mysqli_fetch_array($result)) {
            echo '
            <div class="row_nowrap basic_border" style="width:95%;">
                <img src='.add_quote($row['LOGO_URL']).' alt='.add_quote($row['TITLE']).' />
                <div class="right column_nowrap">
                    <a href="game.php?id='.$row['ID'].'">'.$row['TITLE'].'</a>
                    <p>'.$row['DESCRIPTION'].'</p>
                </div>
            </div>';
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
    echo $result['TITLE'];
};


//finding details of game selected by user
function show_game() {
    global $conn;
    $sql = "SELECT * FROM games WHERE games.ID=".$_GET['id'].";";
    $result = $conn->query($sql);
    while($row = mysqli_fetch_array($result)) {
        echo '<h1>'.$row['TITLE'].'</h1>';
        echo '<p class="basic_border">'.$row['DESCRIPTION'].'</p>';
    }
};