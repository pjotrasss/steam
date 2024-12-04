<?php
require "conn.php";



//function for selecting title of page (developers or publishers) depending on what is needed to be shown
function select_developers_publishers_title() {
    $title = htmlspecialchars($_GET['title']);
    return $title;
};

function select_all($table) {
    global $conn;
    $sql = "SELECT * FROM $table;";
    $result = $conn->query($sql);
    while($row = mysqli_fetch_array($result)) {
        $id = $row['ID'];
        $name = htmlspecialchars($row['NAME']);
        echo "<a href='developer.php?id=$id' class='basic_border'>$name</a>";
    };    
};

$conn->close();