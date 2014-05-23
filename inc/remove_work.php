<?php

include_once 'config.php';
$removed = 0;
if (($_SESSION['logged_in'] != true) || ((int) $_SESSION['user']['rights'] < 5 )) {
    exit;
} else {
    if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        $id = trim($_REQUEST['id']);
        $stmt = $db->prepare('DELETE FROM work WHERE id=:id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $removed = 1;
        }
    }
    echo json_encode($removed);
}
