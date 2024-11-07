<?php
require ('conn.php');



function echo_error($msg) {
    echo $msg;
}



function show_game_title() {
    global $conn;
    $sql = "SELECT TITLE from games WHERE games.ID=".$_GET['id'].";";
    $result = mysqli_fetch_array($conn->query($sql));
    echo $result['TITLE'];
};



function show_game() {
    global $conn;
    $sql = "SELECT * FROM games WHERE games.ID=".$_GET['id'].";";
    $result = $conn->query($sql);
    while($row = mysqli_fetch_array($result)) {
        echo '<h1>'.$row['TITLE'].'</h1>';
        echo '<p class="basic_border">'.$row['DESCRIPTION'].'</p>';
    }
};