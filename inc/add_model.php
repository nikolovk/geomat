<?php

include_once 'config.php';
$data = 0;
if (($_SESSION['logged_in'] != true) || ((int) $_SESSION['user']['rights'] < 5 )) {
    exit;
} else {
    if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        $model = trim($_REQUEST['model']);
        $stage = trim($_REQUEST['stage']);
        $note = trim($_REQUEST['note']);
        $term = trim($_REQUEST['term']);
        $id_project = trim($_REQUEST['id_project']);
        $lot = trim($_REQUEST['lot']);
        $stmt = $db->prepare("INSERT INTO models (lot, model, term, id_project, note, stage) 
            VALUES (:lot,:model,:term,:id_project,:note,:stage)");
        $stmt->bindParam(':model', $model);
        $stmt->bindParam(':stage', $stage);
        $stmt->bindParam(':lot', $lot);
        $stmt->bindParam(':id_project', $id_project);
        $stmt->bindParam(':note', $note);
        $stmt->bindParam(':term', strtotime($term));
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $data = 1;
        }
    }
    echo json_encode($data);
}
