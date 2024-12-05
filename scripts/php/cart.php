<?php
require ('conn.php');


function show_cart() {
    if (isset($_SESSION['cart'])) {
        session_start();
        print_r($_SESSION['cart']);
    } else {
        echo 'Your cart is empty';
    }
}