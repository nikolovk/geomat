<?php

include_once 'config.php';
$data = 0;
if ($_SESSION['logged_in'] != true) {
    exit;
} else {
    if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        $id = trim($_REQUEST['id']);
        $note = trim($_REQUEST['note']);
        $stmt = $db->prepare("UPDATE models SET note=:note WHERE id=:id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':note', $note);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $data = 1;
        }
    }
    echo json_encode($data);
}
