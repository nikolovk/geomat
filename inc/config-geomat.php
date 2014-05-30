<?php

session_start();
//$db_hostname = "localhost";
//$db_database = "geomat";
$db_username = "geomatb_stat";
$db_password = "mo75zzarella";
try {
    $db = new PDO('mysql:host=localhost;dbname=geomatb_stat', $db_username, $db_password);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
try {
    $db_second = new PDO('mysql:host=localhost;dbname=geomatb_stat', $db_username, $db_password);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
?>
