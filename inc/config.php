<?php

session_start();
//$db_hostname = "localhost";
//$db_database = "geomat";
$db_username = "polivane_geomat";
$db_password = "geomat1111";
try {
    $db = new PDO('mysql:host=localhost;dbname=polivane_geomat', $db_username, $db_password);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
try {
    $db_second = new PDO('mysql:host=localhost;dbname=polivane_geomat', $db_username, $db_password);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
?>
