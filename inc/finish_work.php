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
        $stmt = $db->prepare("UPDATE work SET count=:count, time=:time, end=:end WHERE id=:id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':count', $count);
        $stmt->bindParam(':time', $time);
        $stmt->bindParam(':end', time());
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $data = date("d-M-Y H:i ", time());
            $st = $db->prepare("SELECT activities.stage as stage, work.id_model
                FROM work
                JOIN activities ON work.id_activiti = activities.id
                WHERE work.id = :id");
            $st->bindParam(':id', $id);
            $st->execute();
            while ($row = $st->fetch()) {
                $stage = $row['stage'];
                $id_model = $row['id_model'];
                if ($stage<100) {
                    $db->query("UPDATE models SET stage = $stage + 1 WHERE id = $id_model");
                }               
            }
            
        }
    }
    echo json_encode($data);
}
