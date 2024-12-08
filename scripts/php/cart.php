<?php
require ("conn.php");
require ("basic_scripts.php");

function show_cart() {
    if (isset($_SESSION['cart'])) {

        $placeholders = implode(',', array_fill(0, count($_SESSION['cart']), '?'));
        $types = str_repeat('i', count($_SESSION['cart']));

        global $conn;
        $sql = "SELECT games.ID as ID, TITLE, DESCRIPTION, LOGO_URL, PRICE FROM games JOIN prices ON prices.ID=games.PRICE_ID WHERE games.ID IN ($placeholders);";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$_SESSION['cart']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while($row=mysqli_fetch_array($result)) {
            echo_gamedata_link($row);
        };
    } else {
        echo 'Your cart is empty';
    };
};