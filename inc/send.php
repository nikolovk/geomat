<?php

include_once 'config.php';
$sended = 0;
if (($_SESSION['logged_in'] != true) || ((int) $_SESSION['user']['rights'] < 9 )) {
    exit;
} else {
    if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        $send = $_REQUEST['send'];
        $now = time();
        $stmt = $db->prepare('UPDATE models SET send=:now WHERE id = :id');
        $stmt->bindParam(':now', $now);
        foreach ($send as $id) {
            $sended = 1;
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        }
    }
    echo json_encode($sended);
}
