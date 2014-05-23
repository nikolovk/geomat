<?php

include_once 'config.php';
$data = 0;
if (($_SESSION['logged_in'] != true) || ((int) $_SESSION['user']['rights'] < 5 )) {
    exit;
} else {
    if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        $id = trim($_REQUEST['id']);
        $model = trim($_REQUEST['model']);
        $stage = trim($_REQUEST['stage']);
        $term = trim($_REQUEST['term']);
        $stmt = $db->prepare("UPDATE models SET model=:model, stage=:stage,term=:term WHERE id=:id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':model', $model);
        $stmt->bindParam(':stage', $stage);
        $stmt->bindParam(':term', strtotime($term));
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $data = date("d-M-Y", strtotime($term));
        }
    }
    echo json_encode($data);
}
