<?php

include_once 'config.php';
$data = 0;
if (($_SESSION['logged_in'] != true) || ((int) $_SESSION['user']['rights'] < 10 )) {
    exit;
} else {
    if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        $id_user = trim($_REQUEST['id']);
        $type = trim($_REQUEST['type']);
        $from = trim($_REQUEST['from']);
        $to = trim($_REQUEST['to']);
        $days = trim($_REQUEST['days']);
        $stmt = $db->prepare("INSERT INTO `leave`(`id_user`, `type`, `from`, `to`, `days`)
            VALUES (:id_user,:type,:from,:to,:days)");
        $stmt->bindParam(':id_user', $id_user);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':from', strtotime($from));
        $stmt->bindParam(':to', strtotime($to));
        $stmt->bindParam(':days', $days);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $data = 1;
        }
    }
    echo json_encode($data);
}
