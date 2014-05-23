<?php

include_once 'config.php';
if ($_SESSION['logged_in'] != true) {
    header('Location: login.php');
    exit;
} else {
    if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        $id_project = trim($_REQUEST['id_project']);
        switch ($id_project) {
            case "1":
                $table_name = "models";
                break;
            default:
                break;
        }
        $table_name = "models";
        $sql = "SELECT lot FROM $table_name WHERE id_project = $id_project ORDER BY lot";
        //echo $sql;
        $result = $db->query($sql);
        $lots = array();
        while ($row = $result->fetch()) {
            $lots[$row[lot]] = $row[lot];
        }
        echo json_encode($lots);
    }
}
?>
