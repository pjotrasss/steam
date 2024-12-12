<?php
require ("conn.php");



//function for selecting title of page (developers or publishers) depending on what is needed to be shown
function select_developers_publishers_title() {
    $title = htmlspecialchars($_GET['title']);
    return $title;
};

function select_all($table) {
    global $conn;

    $developers_publishers_sql = "SELECT * FROM $table;";
    $developers_publishers_result = $conn->query($developers_publishers_sql);

    while($developer_publisher = mysqli_fetch_array($developers_publishers_result)) {
        $name = htmlspecialchars($developer_publisher['NAME']);
        echo "<a href='developer.php?id={$developer_publisher['ID']}'>$name</a>";
    };
};