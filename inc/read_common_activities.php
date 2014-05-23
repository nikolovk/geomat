<?php

include_once 'config.php';
if ($_SESSION['logged_in'] != true) {
    header('Location: login.php');
    exit;
} else {
    if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        $id_project = trim($_REQUEST['id_project']);
        $sql = "SELECT id, name FROM activities WHERE id_project = $id_project ORDER BY id";
        $result = $db->query($sql);
        $activities = array();
        while ($row = $result->fetch()) {
            $activities[$row[id]] = $row[name];
        }
        echo json_encode($activities);
    }
}
?>
