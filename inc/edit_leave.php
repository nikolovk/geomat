<?php

include_once 'config.php';
$data = 0;
if (($_SESSION['logged_in'] != true) || ((int) $_SESSION['user']['rights'] < 10 )) {
    exit;
} else {
    if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        $id = trim($_REQUEST['id']);
        $id_user = trim($_REQUEST['id_user']);
        $type = trim($_REQUEST['type']);
        $from = trim($_REQUEST['from']);
        $to = trim($_REQUEST['to']);
        $days = trim($_REQUEST['days']);
        $stmt = $db->prepare("UPDATE `leave` SET `id_user`=:id_user,`type`=:type,`from`=:from,`to`=:to,
            `days`=:days WHERE id=:id");
        $stmt->bindParam(':id', $id);
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
