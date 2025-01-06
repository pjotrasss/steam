<?php
require ('conn.php');

function choose_billing() {
    global $conn;

    //sql below doesn't work, gotta figure out why and fix it
    $billings_sql = "SELECT CODE, STREET, CITY, NUMBER, APARTMENT, OTHER_BILLING_INFO, COUNTRY FROM billings
                    JOIN zipcodes ON billings.ZIPCODE_ID=zipcodes.ID
                    JOIN streets ON billings.STREET_ID=streets.ID
                    JOIN cities ON billings.CITY_ID=cities.ID
                    JOIN buildings ON billings.BUILDING_ID=buildings.ID
                    JOIN apartments ON billings.APARTMENT_ID=apartments.ID
                    JOIN countries ON cities.COUNTRY_ID=countries.ID
                    JOIN users_billings ON billings.ID=users_billings.BILLING_ID
                    JOIN users ON users_billings.USER_ID=users.ID
                    WHERE users.ID=?";
    $billings_stmt = $conn->prepare($billings_sql);
    $billings_stmt->bind_param("i", $_SESSION['user_id']);
    $billings_stmt->execute();
    $billings_result = $billings_stmt->get_result();

    if ($billings_result->num_rows > 0) {
        echo "<form action='payment_details.html.php' method='post'>";
            while ($billing = mysqli_fetch_array($billings_result)) {
                print_r($billing);
            };
            echo "<input type='submit' value='Continue to payment details' />";
        echo "</form>";
    } else {
        echo "<h2>No billing addresses found, you can add one by clicking the button below</h2>";
    };

    echo "<form action='add_billing.html.php' method='post'>";
        echo "<input type='submit' value='Add billing address' value='Add billing address' />";
    echo "</form>";
};