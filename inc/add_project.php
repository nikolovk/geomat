<?php

include_once 'config.php';
$data = 0;
if (($_SESSION['logged_in'] != true) || ((int) $_SESSION['user']['rights'] < 5 )) {
    exit;
} else {
    if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        $name = trim($_REQUEST['name']);
        $stmt = $db->prepare("SELECT id FROM projects WHERE name=:name");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = $db->prepare("INSERT INTO projects (name) VALUES (:name)");
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            $data = 1;
        }
    }
    echo json_encode($name);
}
