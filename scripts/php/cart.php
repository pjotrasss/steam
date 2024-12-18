<?php
require ("cart_subscripts.php");
require_once ("user_sessions.php");

function show_cart() {
    if (hard_session_validation()) {

        if (!empty($_SESSION['cart']['id']) && !empty($_SESSION['cart']['games'])) {

            show_cart_from_cookie();
            count_cart_value($_SESSION['cart']['games']);
            insert_to_db_cart();

        } elseif (!empty($_SESSION['cart']['games']) && empty($_SESSION['cart']['id'])) {

            create_new_cart();
            insert_to_db_cart();
            show_cart_from_cookie();
            count_cart_value($_SESSION['cart']['games']);

        } else {
            echo 'Your cart is empty';
        };
    } else {
        header('Location: ../../login.html.php');
    };
};

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['remove_from_cart'])) {
    remove_from_cart();
};