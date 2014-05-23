<?php

include_once 'config.php';
$data = 0;
if (($_SESSION['logged_in'] != true) || ((int) $_SESSION['user']['rights'] < 10 )) {
    exit;
} else {
    if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        $id = trim($_REQUEST['id']);
        $stmt = $db->prepare("DELETE FROM `leave` WHERE id=:id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $data = 1;
        }
    }
    echo json_encode($data);
}
