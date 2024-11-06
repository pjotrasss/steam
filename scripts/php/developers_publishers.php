<?php
require "conn.php";



//function for selecting title of page (developers or publishers) depending on what is needed to be shown
function select_developers_publishers_title() {
    $title = $_GET['title'];
    return $title;
};

