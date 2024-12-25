<?php
require ("conn.php");



function choose_payment_methods() {
    global $conn;

    $payment_methods_sql = "SELECT * FROM payment_methods;";
    $payment_methods_result = $conn->query($payment_methods_sql);

    echo "<label for='payment_method'>Choose your payment method:</label>";
    echo "<select name='payment_method'>";
        while ($payment_method = mysqli_fetch_array($payment_methods_result)) {
            echo "<option value='{$payment_method['ID']}'>{$payment_method['NAME']}</option>";
        };
    echo "</select>";
};



function choose_or_add_billing_address() {
    global $conn;

    $billing_addresses_sql = "SELECT CODE, STREET, NUMBER, APARTMENT, CITY, COUNTRY FROM billings
                            JOIN users_billings ON users_billings.BILLING_ID=billings.ID
                            JOIN users ON users.ID=users_billings.USER_ID
                            JOIN zipcodes ON zipcodes.ID=billings.ZIPCODE_ID
                            JOIN streets ON streets.ID=billings.STREET_ID
                            JOIN buildings ON buildings.ID=billings.BUILDING_ID
                            JOIN apartments ON apartments.ID=billings.APARTMENT_ID
                            JOIN cities ON cities.ID=billings.CITY_ID
                            JOIN countries ON countries.ID=cities.COUNTRY_ID
                            WHERE users.ID=?;";
    $billing_addresses_stmt = $conn->prepare($billing_addresses_sql);
    $billing_addresses_stmt->bind_param('i', $_SESSION['user_data']['user_id']);
    $billing_addresses_stmt->execute();
    $billing_addresses_result = $billing_addresses_stmt->get_result();

    if ($billing_addresses_result->num_rows > 0) {
        echo "<label for='billing_address'>Choose your billing address:</label>";
        echo "<fieldset name='billing_address'>";
            while ($billing_address = mysqli_fetch_array($billing_addresses_result)) {
                echo htmlspecialchars($billing_address['ID']);
            };
        echo "</fieldset>";
    } else {
        echo "<p>You don't have any billing addresses. Please add one by clicking button below.</p>";
    };

    echo "<form name='add_billing_address' action='add_billing_address.html.php' method='post'>";
        echo "<input type='submit' value='ADD BILLING ADDRESS' name='add_billing_address' required />";
    echo "</form>";
};



function is_this_a_gift() {
    echo "<label for='gift'>Is this a gift?</label>";
    echo "<input type='checkbox' name='gift' />";
};



function upload_order_to_db() {
    global $conn;

    
};



function redirect_to_payment() {
    global $conn;

    
};