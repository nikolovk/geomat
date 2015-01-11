<?php

include_once 'config.php';
$removed = 0;
if (($_SESSION['logged_in'] != true) || ((int) $_SESSION['user']['rights'] < 5 )) {
    exit;
} else {
    if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        $id = trim($_REQUEST['id']);

        $stmt = $db->prepare('UPDATE projects SET archived = 1 WHERE id=:id');
        $stmt->bindParam(':id', $id);
        $result = $stmt->execute();
    }

    echo json_encode($result);
}
