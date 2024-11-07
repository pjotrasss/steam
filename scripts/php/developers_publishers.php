<?php
require "conn.php";



//function for selecting title of page (developers or publishers) depending on what is needed to be shown
function select_developers_publishers_title() {
    $title = $_GET['title'];
    return $title;
};

function select_all($table) {
    global $conn;
    $sql = "SELECT * FROM ".normalize_tablename($table).";";
    $result = $conn->query($sql);
    while($row = mysqli_fetch_array($result)) {
        echo '<a href="developer.php?id='.$row['ID'].'" class="basic_border">'.$row['NAME'].'</a>';
    };
};