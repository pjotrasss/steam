<?php
require "conn.php";
require "scripts.php";



function select_allgames() {
    global $conn;
    $sql = "SELECT * FROM ".normalize_tablename("games").";";
    $result = $conn->query($sql);
    while($row = mysqli_fetch_array($result)) {
        echo '<div class="row_nowrap basic_border" style="width:95%;">
            <img src="'.$row['LOGO_URL'].'" />
            <div class="right column_nowrap">
                <a href="game.php?id='.$row['ID'].'">'.$row['TITLE'].'</a>
                <p>'.$row['DESCRIPTION'].'</p>
            </div>
        </div>';
    };
};



//selecting filtered games from db
function select_filtered_games() {

    global $conn;
    global $tables;
    $conditions = [];


    $sql = 'SELECT DISTINCT '.normalize_tablename($tables[4]).'.*
        FROM '.normalize_tablename($tables[4]).'
        LEFT JOIN '.normalize_tablename($tables[5]).' ON '.normalize_tablename($tables[4]).'.ID='.normalize_tablename($tables[5]).'.GAME_ID
        LEFT JOIN '.normalize_tablename($tables[6]).' ON '.normalize_tablename($tables[4]).'.ID='.normalize_tablename($tables[6]).'.GAME_ID
        LEFT JOIN '.normalize_tablename($tables[7]).' ON '.normalize_tablename($tables[4]).'.ID='.normalize_tablename($tables[7]).'.GAME_ID';

    if (!empty($_POST['tags']))  {
        $tags = implode(',', array_map('intval', $_POST['tags']));
        $conditions[] = 'game_tags.TAG_ID IN ('.$tags.')';
    };

    if (!empty($_POST['platforms'])) {
        $platforms = implode(',', array_map('intval', $_POST['platforms']));
        $conditions[] = 'game_platforms.PLATFORM_ID IN ('.$platforms.')';
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
                <img src='.normalize_text($row['LOGO_URL']).' alt='.normalize_text($row['TITLE']).'/>
                <div class="right column_nowrap">
                    <a href="game.php?id=' . $row['ID'] . '">' . $row['TITLE'] . '</a>
                    <p>' . $row['DESCRIPTION'] . '</p>
                </div>
            </div>';
        };
    } else {
        echo "Error: ".$conn->error;
    };
};



//creating checkbox filters for tags and platforms
//creating radial filters for developers and publishers
function create_inputs($table, $type) {
    global $conn;
    global $types;
    global $tables;

    $sql = "SELECT * FROM ".normalize_tablename($tables[$table]).";";
    $result = $conn->query($sql);

    while ($row = mysqli_fetch_array($result)) {
        echo '
        <div class="input_container row_nowrap">
            <input type='.$types[$type].' value='.$row['ID'].' name='.normalize_text($tables[$table]).'>
            <legend>'.$row['NAME'].'</legend>
        </div>';
    };
};