<?php
require ('conn.php');



function choose_or_add($table_name, $input_type, $input_name, $input_placeholder, $column_name, $choose_or_add, $required) {
    switch ($choose_or_add) {
        case 'add_other':
            echo "<$input_type name='$input_name' placeholder='$input_placeholder' $required></$input_type>";
            break;
        case 'add':
            echo "<input type='$input_type' name='$input_name' placeholder='$input_placeholder' $required/>";
            break;

        case 'choose':
            global $conn;

            $select_sql = "SELECT * FROM $table_name";
            $select_result = $conn->query($select_sql);

            echo "<select id='$table_name' name='$input_name' $required>";
                while ($row = mysqli_fetch_array($select_result)) {
                    echo "<option value='{$row['ID']}'>{$row[$column_name]}</option>";
                };
            echo "</select>";
            break;

        case 'choose&add':
            global $conn;

            $select_sql = "SELECT * FROM $table_name";
            $select_result = $conn->query($select_sql);

            echo "<input type='$input_type' name='$input_name' placeholder='$input_placeholder' list='$table_name' $required/>";
            echo "<datalist id='$table_name'>";
                while ($row = mysqli_fetch_array($select_result)) {
                    echo "<option value='{$row[$column_name]}'>";
                };
            echo "</datalist>";
            break;
        default:
            echo 'error';
    };
};



function add_or_not($table_name, $column_name, $value_to_insert, $city_or_other) { 
    global $conn;

    switch ($city_or_other) {
        case 'city':
            $insert_sql = "INSERT INTO $table_name ($column_name) VALUES (?, ?) ON DUPLICATE KEY UPDATE ID=ID;";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param('is', $_SESSION['billing']['country_id'], $value_to_insert);

            $insert_stmt->execute(); 
            $last_id = $insert_stmt->insert_id;
            
            if($last_id==0) {
                $select_id_sql = "SELECT ID FROM cities WHERE CITY = ? AND COUNTRY_ID = ?";
                $select_id_stmt = $conn->prepare($select_id_sql);
                $select_id_stmt->bind_param('si', $value_to_insert, $_SESSION['billing']['country_id']);
                $select_id_stmt->execute();

                $select_id_result = $select_id_stmt->get_result();
                $last_id = mysqli_fetch_array($select_id_result)['ID'];
            };

            return $last_id;

        case 'other':
            $insert_sql = "INSERT INTO $table_name ($column_name) VALUES (?) ON DUPLICATE KEY UPDATE ID=ID;";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param('s',$value_to_insert);

            $insert_stmt->execute();
            $last_id = $insert_stmt->insert_id;

            if($last_id==0) {
                $select_id_sql = "SELECT ID FROM $table_name WHERE $column_name = ?";
                $select_id_stmt = $conn->prepare($select_id_sql);
                $select_id_stmt->bind_param('s', $value_to_insert);
                $select_id_stmt->execute();

                $select_id_result = $select_id_stmt->get_result();
                $last_id = mysqli_fetch_array($select_id_result)['ID'];
            };

            return $last_id;
    };
};



if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['add_billing'])) {
    session_start();
    
    $_SESSION['billing'] = [];

    $_SESSION['billing']['country_id'] = $_POST['country'];
    $_SESSION['billing']['city_id'] = add_or_not('cities', 'country_id, city', $_POST['city'], 'city');
    $_SESSION['billing']['zipcode_id'] = add_or_not('zipcodes', 'code', $_POST['zipcode'], 'other');
    $_SESSION['billing']['street_id'] = add_or_not('streets', 'street', $_POST['street'], 'other');
    $_SESSION['billing']['building_number_id'] = add_or_not('buildings', 'number', $_POST['building_number'], 'other');

    if(!empty($_POST['apartment_number'])) {
        $_SESSION['billing']['apartment_number_id'] = add_or_not('apartments', 'apartment', $_POST['apartment_number'], 'other');
    } else {
        $_SESSION['billing']['apartment_number_id'] = null;
    };

    $_SESSION['billing']['other_billing_info'] = $_POST['other_billing_info'];

    $insert_billing_sql = "INSERT INTO billings (ZIPCODE_ID, STREET_ID, CITY_ID, BUILDING_ID, APARTMENT_ID, OTHER_BILLING_INFO) VALUES (?, ?, ?, ?, ?, ?);";
    $insert_billing_stmt = $conn->prepare($insert_billing_sql);
    $insert_billing_stmt->bind_param('iiiiis', $_SESSION['billing']['zipcode_id'], $_SESSION['billing']['street_id'], $_SESSION['billing']['city_id'], $_SESSION['billing']['building_number_id'], $_SESSION['billing']['apartment_number_id'], $_SESSION['billing']['other_billing_info']);
    $insert_billing_stmt->execute();

    $added_billing_id = $insert_billing_stmt->insert_id;

    $insert_users_billings_sql = "INSERT INTO users_billings (USER_ID, BILLING_ID) VALUES (?, ?);";
    $insert_users_billings_stmt = $conn->prepare($insert_users_billings_sql);
    $insert_users_billings_stmt->bind_param('ii', $_SESSION['user_data']['user_id'], $added_billing_id);
    $insert_users_billings_stmt->execute();

    print_r($_SESSION['user_data']);
};