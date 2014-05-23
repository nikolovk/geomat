<?php

include_once 'config.php';
$data = 0;
if ($_SESSION['logged_in'] != true) {
    exit;
} else {
    if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        $id = trim($_REQUEST['id']);
        $count = trim($_REQUEST['count']);
        $time = trim($_REQUEST['time']);
        $user_id = $_SESSION['user']['id'];
        $stmt = $db->prepare("UPDATE work SET count=:count, time=:time WHERE id=:id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':count', $count);
        $stmt->bindParam(':time', $time);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $data = 1;
        }
    }
    echo json_encode($data);
}
