<?php
require("conn.php");
global $conn;

$sql = "SELECT USER_ID, PASSWORD FROM passwords;";
$result = $conn->query($sql);
while($row=mysqli_fetch_array($result)){
    $hashed_password = password_hash($row['PASSWORD'], PASSWORD_BCRYPT);
    $id = $row['USER_ID'];
    $sql = "UPDATE passwords SET PASSWORD='$hashed_password' WHERE USER_ID=$id";
    //$conn->query($sql);
    echo "Updated password for user with ID $id";
};