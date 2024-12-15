<?php
require ("cart_subscripts.php");
require_once ("user_sessions.php");

function show_cart() {
    if (hard_session_validation()) {
        if (!empty($_SESSION['cart']['id']) && !empty($_SESSION['cart']['games'])) {
            show_cart_from_cookie();
            merge_cookie_cart_with_db_cart();
            count_cart_value($_SESSION['cart']['games']);
        }elseif (!empty($_SESSION['cart']['games']) && empty($_SESSION['cart']['id'])) {
            show_cart_from_cookie();
            insert_cookie_cart_to_db();
            count_cart_value($_SESSION['cart']['games']);
        } else {
            echo 'Your cart is empty';
        };
    } else {
        header('Location: ../../login.html.php');
    };
};