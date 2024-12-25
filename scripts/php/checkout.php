<?php
require ("checkout_subscripts.php");
require ("user_sessions.php");

function show_checkout_form() {
    if (hard_session_validation()) {
        choose_payment_methods();
        choose_or_add_billing_address();
        is_this_a_gift();
    } else {
        header('Location: ../../login.html.php');
    }
};



if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['checkout'])) {
    upload_order_to_db();
    redirect_to_payment();
};