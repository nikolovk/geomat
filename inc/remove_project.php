<?php

include_once 'config.php';
$removed = 0;
if (($_SESSION['logged_in'] != true) || ((int) $_SESSION['user']['rights'] < 5 )) {
    exit;
} else {
    if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        $id = trim($_REQUEST['id']);
        $sql = "SELECT projects.id FROM projects 
           LEFT JOIN models ON models.id_project = projects.id
           LEFT JOIN activities ON activities.id_project = projects.id
           LEFT JOIN work ON work.id_project = projects.id
            WHERE models.id_project = :id OR activities.id_project = :id OR work.id_project = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id',$id);
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = $db->prepare('DELETE FROM projects WHERE id=:id');
            $stmt->bindParam(':id',$id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $removed = 1;
            }
        }
    }
    echo json_encode($removed);
}
