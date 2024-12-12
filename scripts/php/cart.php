<?php
require ("cart_subscripts.php");
require_once ("user_sessions.php");

function show_cart() {
    if (hard_session_validation()) {
        if (!empty($_SESSION['cart']['id'])) {
            cart_in_db();
        } elseif (!empty($_SESSION['cart']['games'])) {
            cart_on_site_not_db();
        } else {
            echo 'Your cart is empty';
        };
    } else {
        header('Location: ../../login.html.php');
    };
};