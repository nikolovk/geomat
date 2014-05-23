<?php

include_once 'config.php';
include_once '../class/register.class.php';
if ($_SESSION['logged_in'] != true) {
    exit;
} else {
    if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        $id_model = trim($_REQUEST['id_model']);
        $from = strtotime(trim($_REQUEST['from']));
        $to = strtotime("+1 day",strtotime(trim($_REQUEST['to'])));

        $stmt = $db->prepare("SELECT SUM(work.count) as count, users.name as name
            FROM work
            JOIN users ON users.id = work.id_user
            WHERE work.id_model = :id_model AND work.end >= :from AND work.end < :to
            GROUP BY name
            ORDER BY name");
        $stmt->bindParam(':id_model', $id_model);
        $stmt->bindParam(':from', $from);
        $stmt->bindParam(':to', $to);
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            $data[$row['name']] = $row['count'];
        }
    }
    echo json_encode($data);
}
